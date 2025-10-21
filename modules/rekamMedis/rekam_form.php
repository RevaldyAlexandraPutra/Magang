<?php
// rekam_form.php
require_once __DIR__ . '/includes/db.php';

// Ambil daftar pasien untuk dropdown
$patients = $pdo->query("SELECT id, nama, phone FROM patients ORDER BY nama")->fetchAll();

// Ambil daftar dokter (sederhana)
$doctors = $pdo->query("SELECT id, nama FROM doctors ORDER BY nama")->fetchAll();

// Jika ada pasien terpilih, ambil data riwayat
$selected_patient = null;
$history = [];
if (isset($_GET['patient_id']) && is_numeric($_GET['patient_id'])) {
    $pid = (int)$_GET['patient_id'];

    // Data pasien
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->execute([$pid]);
    $selected_patient = $stmt->fetch();

    // Riwayat rekam medis
    $stmt2 = $pdo->prepare("
        SELECT r.*, d.nama AS doctor_name
        FROM rekam_medis r
        LEFT JOIN doctors d ON r.doctor_id = d.id
        WHERE r.patient_id = ?
        ORDER BY r.tanggal_pemeriksaan DESC
    ");
    $stmt2->execute([$pid]);
    $history = $stmt2->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekam Medis - Dokter</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .container { max-width: 1000px; margin: auto; }
    fieldset { margin-bottom: 1rem; padding:1rem; }
    table { width:100%; border-collapse: collapse; margin-top: 0.5rem; }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 8px; text-align: left; }
    .small { font-size: 0.9rem; color: #555; }
  </style>
</head>
<body>
<div class="container">
  <h1>Modul Rekam Medis (Dokter)</h1>

  <form method="GET" action="">
    <label>Pilih Pasien:
      <select name="patient_id" onchange="this.form.submit()">
        <option value="">-- Pilih Pasien --</option>
        <?php foreach($patients as $p): ?>
          <option value="<?=htmlspecialchars($p['id'])?>" <?= (isset($pid) && $pid == $p['id']) ? 'selected' : '' ?>>
            <?=htmlspecialchars($p['nama'])?> (<?=htmlspecialchars($p['phone'])?>)
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  </form>

  <?php if($selected_patient): ?>
    <h2>Pasien: <?=htmlspecialchars($selected_patient['nama'])?> <span class="small">(ID: <?=$selected_patient['id']?>)</span></h2>

    <!-- Form input rekam medis -->
    <fieldset>
      <legend>Tambah Pemeriksaan Baru</legend>
      <form method="POST" action="save_rekam.php">
        <input type="hidden" name="patient_id" value="<?=htmlspecialchars($selected_patient['id'])?>">
        <label>Dokter:
          <select name="doctor_id" required>
            <option value="">-- Pilih Dokter --</option>
            <?php foreach($doctors as $d): ?>
              <option value="<?=htmlspecialchars($d['id'])?>"><?=htmlspecialchars($d['nama'])?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <br><br>

        <label>Keluhan:<br>
          <textarea name="keluhan" rows="3" cols="80" required></textarea>
        </label><br><br>

        <label>Diagnosa:<br>
          <textarea name="diagnosa" rows="3" cols="80" required></textarea>
        </label><br><br>

        <label>Catatan / Pemeriksaan Lainnya:<br>
          <textarea name="notes" rows="3" cols="80"></textarea>
        </label><br><br>

        <label>Tanggal Kontrol Berikutnya:
          <input type="date" name="next_control">
        </label>

        <label>Biaya Pelayanan (Rp):
          <input type="number" step="0.01" name="biaya_service" value="0">
        </label><br><br>

        <fieldset>
          <legend>Resep Obat (jika ada)</legend>
          <!-- sederhananya kita tampilkan 5 baris input obat -->
          <?php for($i=1;$i<=5;$i++): ?>
            <div>
              <input type="text" name="obat_name[]" placeholder="Nama obat">
              <input type="number" name="obat_qty[]" placeholder="Qty" min="1" style="width:70px;">
              <input type="text" name="obat_satuan[]" placeholder="Satuan" style="width:90px;">
              <input type="number" step="0.01" name="obat_harga[]" placeholder="Harga satuan" style="width:120px;">
            </div>
          <?php endfor; ?>
        </fieldset>

        <br>
        <button type="submit">Simpan Pemeriksaan</button>
      </form>
    </fieldset>

    <!-- Tampilkan riwayat medis -->
    <h3>Riwayat Pemeriksaan</h3>
    <?php if(empty($history)): ?>
      <p>Tidak ada riwayat pemeriksaan.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th><th>Dokter</th><th>Diagnosa</th><th>Resep</th><th>Kontrol</th><th>Biaya</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($history as $h): ?>
          <?php
            // Ambil resep untuk rekam ini
            $stmt = $pdo->prepare("SELECT id FROM prescriptions WHERE rekam_id = ?");
            $stmt->execute([$h['id']]);
            $presc = $stmt->fetch();
            $presc_id = $presc ? $presc['id'] : null;
            $items = [];
            if ($presc_id) {
                $stmt2 = $pdo->prepare("SELECT nama_obat, qty FROM prescription_items WHERE prescription_id = ?");
                $stmt2->execute([$presc_id]);
                $items = $stmt2->fetchAll();
            }
          ?>
          <tr>
            <td><?=htmlspecialchars($h['tanggal_pemeriksaan'])?></td>
            <td><?=htmlspecialchars($h['doctor_name'])?></td>
            <td><?=nl2br(htmlspecialchars($h['diagnosa']))?></td>
            <td>
              <?php if($items): ?>
                <ul>
                <?php foreach($items as $it): ?>
                  <li><?=htmlspecialchars($it['nama_obat'])?> (<?=htmlspecialchars($it['qty'])?>)</li>
                <?php endforeach; ?>
                </ul>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td><?= $h['next_control'] ? htmlspecialchars($h['next_control']) : '-' ?></td>
            <td><?=number_format($h['biaya_service'], 2, ',', '.')?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  <?php else: ?>
    <p>Pilih pasien terlebih dahulu untuk menambah pemeriksaan dan melihat riwayat.</p>
  <?php endif; ?>

</div>
</body>
</html>
