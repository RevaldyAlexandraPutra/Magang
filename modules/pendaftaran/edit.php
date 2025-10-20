<?php
require 'config.php';

if (!isset($_GET['id'])) die("ID tidak ditemukan!");
$id = (int) $_GET['id'];

// Ambil data pasien
$stmt = $pdo->prepare("
  SELECT p.*, mk.nama_klinik 
  FROM pasien p
  LEFT JOIN master_klinik mk ON p.klinik_id = mk.id
  WHERE p.id = ?
");
$stmt->execute([$id]);
$pasien = $stmt->fetch();
if (!$pasien) die("Data pasien tidak ditemukan!");

// Ambil data master klinik
$klinik = $pdo->query("SELECT * FROM master_klinik ORDER BY nama_klinik")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    if (!preg_match('/^[0-9]{16}$/', $nik)) {
        echo "<script>alert('❌ NIK harus terdiri dari tepat 16 digit angka!');history.back();</script>";
        exit;
    }

    $foto_json = $pasien['foto']; // default pakai foto lama

    // Jika user centang hapus foto
    if (!empty($_POST['hapus_foto'])) {
        $foto_json = null;
    }
    // Jika upload baru
    elseif (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($file['type'], $allowed) && $file['size'] <= 2 * 1024 * 1024) {
            $imgData = file_get_contents($file['tmp_name']);
            // Simpan sebagai JSON
            $foto_json = json_encode([
                "type" => $file['type'],
                "data" => base64_encode($imgData)
            ]);
        } else {
            echo "<script>alert('❌ Format atau ukuran file tidak valid!');</script>";
        }
    }

    // Simpan perubahan ke database
    $stmt = $pdo->prepare("UPDATE pasien SET 
        nik=?, nama=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?, 
        alamat=?, no_hp=?, klinik_id=?, foto=? 
        WHERE id=?");

    $stmt->execute([
        $_POST['nik'], $_POST['nama'], $_POST['tempat_lahir'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'],
        $_POST['alamat'], $_POST['no_hp'], $_POST['klinik_id'], $foto_json, $id
    ]);

    echo "<script>alert('✅ Data pasien berhasil diperbarui!');window.location='detail.php?id=$id';</script>";
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Data Pasien</title>
  <style>
    body { font-family: Arial; max-width: 800px; margin: 30px auto; background: #fafafa; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 5px; }
    button { margin-top: 15px; padding: 10px 20px; background: #0073aa; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #005f87; }
    img { border-radius: 8px; margin-top: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
  </style>
</head>
<body>
  <h2>✏️ Edit Data Pasien</h2>
  <form method="post" enctype="multipart/form-data">
    <label>NIK</label>
    <input type="text" name="nik" value="<?= htmlspecialchars($pasien['nik']) ?>" required pattern="\d{16}" maxlength="16">

    <label>Nama Lengkap</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($pasien['nama']) ?>" required>

    <label>Tempat Lahir</label>
    <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($pasien['tempat_lahir']) ?>">

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($pasien['tanggal_lahir']) ?>">

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin">
      <option value="Laki-laki" <?= $pasien['jenis_kelamin']=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
      <option value="Perempuan" <?= $pasien['jenis_kelamin']=='Perempuan'?'selected':'' ?>>Perempuan</option>
    </select>

    <label>Alamat</label>
    <textarea name="alamat"><?= htmlspecialchars($pasien['alamat']) ?></textarea>

    <label>No. HP</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($pasien['no_hp']) ?>">

    <label>Klinik Tujuan</label>
    <select name="klinik_id">
      <?php foreach ($klinik as $k): ?>
        <option value="<?= $k['id'] ?>" <?= $pasien['klinik_id']==$k['id']?'selected':'' ?>><?= $k['nama_klinik'] ?></option>
      <?php endforeach; ?>
    </select>

    <label>Foto Pasien (opsional)</label>
    <input type="file" name="foto" accept="image/*">

    <?php 
    if (!empty($pasien['foto'])) {
        $fotoData = json_decode($pasien['foto'], true);
        if ($fotoData && isset($fotoData['data'])) {
            echo '<p>Foto saat ini:</p>';
            echo '<img src="data:' . htmlspecialchars($fotoData['type']) . ';base64,' . $fotoData['data'] . '" width="120"><br>';
            echo '<label><input type="checkbox" name="hapus_foto" value="1"> Hapus foto ini</label>';
        }
    }
    ?>

    <button type="submit">💾 Simpan Perubahan</button>
  </form>
</body>
</html>
