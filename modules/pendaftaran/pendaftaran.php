<?php
require 'config.php';

// Ambil data master dari tabel master_klinik
$kliniks = $pdo->query("SELECT * FROM master_klinik ORDER BY nama_klinik ASC")->fetchAll();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik            = $_POST['nik'];
    $nama           = $_POST['nama'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $alamat         = $_POST['alamat'];
    $no_hp          = $_POST['no_hp'];
    $klinik_id      = $_POST['klinik'];

    // === FOTO OPSIONAL (disimpan dalam format JSON base64) ===
    $foto_json = null;

    if (
        isset($_FILES['foto']) &&
        is_uploaded_file($_FILES['foto']['tmp_name']) &&
        $_FILES['foto']['error'] === UPLOAD_ERR_OK
    ) {
        $file = $_FILES['foto'];
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

        if (in_array($file['type'], $allowed) && $file['size'] <= 2 * 1024 * 1024) {
            $imgData = file_get_contents($file['tmp_name']);
            $foto_json = json_encode([
                'type' => $file['type'],
                'data' => base64_encode($imgData)
            ]);
        } else {
            echo "<script>alert('❌ Format atau ukuran file tidak valid!');</script>";
        }
    }

    // Simpan ke database
    $stmt = $pdo->prepare("
        INSERT INTO pasien 
        (nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, klinik_id, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nik, $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin,
        $alamat, $no_hp, $klinik_id, $foto_json
    ]);

    echo "<script>alert('✅ Data pasien berhasil disimpan!');window.location='list.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pendaftaran Pasien</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; background: #f7f8fa; padding: 20px; border-radius: 10px; }
    h2 { text-align: center; color: #333; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select, textarea {
      width: 100%; padding: 8px; box-sizing: border-box; margin-top: 4px;
      border: 1px solid #ccc; border-radius: 5px;
    }
    .row { display: flex; gap: 10px; }
    .col { flex: 1; }
    button {
      margin-top: 15px; padding: 10px 20px;
      background: #2e86de; color: white; border: none;
      border-radius: 5px; cursor: pointer;
    }
    button:hover { background: #1e5fac; }
  </style>
</head>

<body>
  <h2>🩺 Form Pendaftaran Pasien</h2>

  <form action="" method="POST" enctype="multipart/form-data">
    <label for="nik">NIK:</label>
    <input type="text" id="nik" name="nik" pattern="^[0-9]{16}$" maxlength="16" minlength="16" required
      oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)" />

    <label for="nama">Nama Lengkap:</label>
    <input type="text" id="nama" name="nama" required />

    <div class="row">
      <div class="col">
        <label for="tempat_lahir">Tempat Lahir:</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" required />
      </div>
      <div class="col">
        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required />
      </div>
    </div>

    <label for="jenis_kelamin">Jenis Kelamin:</label>
    <select id="jenis_kelamin" name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="Laki-laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>

    <label for="alamat">Alamat:</label>
    <textarea id="alamat" name="alamat" rows="2" required></textarea>

    <label for="no_hp">Nomor HP:</label>
    <input type="text" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx" pattern="^08[0-9]{8,11}$" required
      oninput="this.value=this.value.replace(/[^0-9]/g,'')" />

    <label for="klinik">Klinik Tujuan:</label>
    <select id="klinik" name="klinik" required>
      <option value="">-- Pilih Klinik --</option>
      <?php foreach ($kliniks as $k): ?>
        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_klinik']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="foto">Upload Foto (Opsional):</label>
    <input type="file" id="foto" name="foto" accept="image/*" />

    <button type="submit">💾 Simpan Data</button>
  </form>
</body>
</html>
