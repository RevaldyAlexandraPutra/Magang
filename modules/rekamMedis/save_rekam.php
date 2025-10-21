<?php
// save_rekam.php
require_once __DIR__ . '/includes/db.php';

// Simple helper untuk safe POST
function post($key, $default = null) {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

// Ambil data wajib
$patient_id = (int) post('patient_id');
$doctor_id = (int) post('doctor_id');
$keluhan = trim(post('keluhan',''));
$diagnosa = trim(post('diagnosa',''));
$notes = trim(post('notes',''));
$next_control = post('next_control') ? trim(post('next_control')) : null;
$biaya_service = (float) post('biaya_service', 0);

// Validasi sederhana
if (!$patient_id || !$doctor_id || !$keluhan || !$diagnosa) {
    die("Data tidak lengkap. Pastikan pasien, dokter, keluhan dan diagnosa diisi.");
}

try {
    $pdo->beginTransaction();

    // 1) Insert rekam_medis
    $stmt = $pdo->prepare("INSERT INTO rekam_medis (patient_id, doctor_id, keluhan, diagnosa, notes, next_control, biaya_service) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$patient_id, $doctor_id, $keluhan, $diagnosa, $notes, $next_control, $biaya_service]);
    $rekam_id = $pdo->lastInsertId();

    // 2) Jika ada obat -> buat prescription dan items
    $obat_names = post('obat_name', []);
    $obat_qtys = post('obat_qty', []);
    $obat_satuan = post('obat_satuan', []);
    $obat_harga = post('obat_harga', []);

    $hasPrescription = false;
    // cek setidaknya satu obat diisi nama
    foreach($obat_names as $name) {
        if (trim($name) !== '') { $hasPrescription = true; break; }
    }

    if ($hasPrescription) {
        // Insert ke prescriptions
        $stmt = $pdo->prepare("INSERT INTO prescriptions (rekam_id) VALUES (?)");
        $stmt->execute([$rekam_id]);
        $presc_id = $pdo->lastInsertId();

        // Insert items
        $stmtItem = $pdo->prepare("INSERT INTO prescription_items (prescription_id, nama_obat, qty, satuan, harga) VALUES (?, ?, ?, ?, ?)");
        $totalObat = 0;
        $n = count($obat_names);
        for ($i=0;$i<$n;$i++) {
            $name = trim($obat_names[$i]);
            if ($name === '') continue;
            $qty = (int) ($obat_qtys[$i] ?? 1);
            $satuan = trim($obat_satuan[$i] ?? '');
            $harga = (float) ($obat_harga[$i] ?? 0);
            $stmtItem->execute([$presc_id, $name, $qty, $satuan, $harga]);
            $totalObat += ($qty * $harga);
        }

        // Buat entry antrian apotek
        $stmtQueue = $pdo->prepare("INSERT INTO pharmacy_queue (prescription_id, status) VALUES (?, 'pending')");
        $stmtQueue->execute([$presc_id]);
    } else {
        $totalObat = 0;
    }

    // 3) Buat record pembayaran (biaya service + obat)
    $totalBayar = round($biaya_service + $totalObat, 2);
    $stmtPay = $pdo->prepare("INSERT INTO payments (rekam_id, total, status) VALUES (?, ?, 'unpaid')");
    $stmtPay->execute([$rekam_id, $totalBayar]);

    // 4) Buat notification untuk jadwal kontrol jika ada
    if ($next_control) {
        $payload = json_encode([
            'message' => "Pengingat kontrol pada tanggal $next_control",
            'rekam_id' => $rekam_id
        ]);
        $stmtNotif = $pdo->prepare("INSERT INTO notifications (patient_id, type, payload, send_at) VALUES (?, 'control_reminder', ?, ?)");
        // send_at bisa diisi sesuai logika scheduler (mis. 1 hari sebelum)
        $send_at = date('Y-m-d', strtotime($next_control . ' -1 day')) . ' 09:00:00';
        $stmtNotif->execute([$patient_id, $payload, $send_at]);
    }

    // 5) Buat notification invoice (opsional)
    $payloadInv = json_encode([
        'message' => "Invoice untuk pemeriksaan, total Rp " . number_format($totalBayar,2,',','.'),
        'rekam_id' => $rekam_id
    ]);
    $stmt = $pdo->prepare("INSERT INTO notifications (patient_id, type, payload) VALUES (?, 'invoice', ?)");
    $stmt->execute([$patient_id, $payloadInv]);

    $pdo->commit();

    // Redirect kembali ke form dengan pesan sukses
    header("Location: rekam_form.php?patient_id={$patient_id}&saved=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Simpan pemeriksaan gagal: " . $e->getMessage());
}
