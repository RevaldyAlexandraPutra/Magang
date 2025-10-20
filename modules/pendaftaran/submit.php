<?php
require 'config.php'; // koneksi ke database

function clean($v) {
    return trim($v);
}

// ========== Ambil data dari form ========== //
$nik            = clean($_POST['nik']);
$nama           = clean($_POST['nama']);
$tempat_lahir   = clean($_POST['tempat_lahir']);
$tanggal_lahir  = $_POST['tanggal_lahir'] ?? null;
$jenis_kelamin  = $_POST['jenis_kelamin'] ?? 'Laki-laki';
$alamat         = clean($_POST['alamat']);
$no_hp          = clean($_POST['no_hp']);
$klinik_id      = $_POST['klinik_id'] ?? null;

// Validasi NIK
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    die('❌ NIK harus terdiri dari tepat 16 digit angka!');
}

// ========== Upload Foto langsung ke DB (disimpan dalam JSON Base64) ========== //
$foto_json = null;
if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['foto'];
    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!in_array($file['type'], $allowed)) {
        die('❌ Tipe file tidak diperbolehkan. Gunakan JPG atau PNG.');
    }

    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        die('❌ Ukuran file terlalu besar (maks 2MB).');
    }

    $imgData = file_get_contents($file['tmp_name']);
    $foto_json = json_encode([
        'type' => $file['type'],
        'data' => base64_encode($imgData)
    ]);
}

// ========== Simpan ke Database ========== //
try {
    $sql = "INSERT INTO pasien (
        nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, klinik_id, foto
    ) VALUES (
        :nik, :nama, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :alamat, :no_hp, :klinik_id, :foto
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nik'           => $nik,
        ':nama'          => $nama,
        ':tempat_lahir'  => $tempat_lahir,
        ':tanggal_lahir' => $tanggal_lahir,
        ':jenis_kelamin' => $jenis_kelamin,
        ':alamat'        => $alamat,
        ':no_hp'         => $no_hp,
        ':klinik_id'     => $klinik_id,
        ':foto'          => $foto_json
    ]);

    header("Location: list.php");
    exit;
} catch (PDOException $e) {
    die("❌ Terjadi kesalahan: " . $e->getMessage());
}
?>
