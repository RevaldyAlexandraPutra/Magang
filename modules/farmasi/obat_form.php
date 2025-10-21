<?php
include '../../config/db.php';

$id = $_GET['id'] ?? null;
$data = $id ? $conn->query("SELECT * FROM obat WHERE id='$id'")->fetch_assoc() : null;

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $jenis = $_POST['jenis'];
  $satuan = $_POST['satuan'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  if ($id) {
    $conn->query("UPDATE obat SET nama='$nama', jenis='$jenis', satuan='$satuan', harga='$harga', stok='$stok' WHERE id='$id'");
  } else {
    $conn->query("INSERT INTO obat (nama, jenis, satuan, harga, stok) VALUES ('$nama','$jenis','$satuan','$harga','$stok')");
  }
  echo "<script>alert('Data obat disimpan!');window.location='obat.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Obat</title>
  <style>
    body { font-family: Arial; background:#f6f9fc; }
    form { width:400px; margin:50px auto; background:white; padding:20px; border-radius:10px; }
    input { width:100%; padding:8px; margin:5px 0; }
    button { background:#2b6777; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; }
  </style>
</head>
<body>
  <form method="post">
    <h2 style="text-align:center;"><?= $id ? "Edit" : "Tambah" ?> Obat</h2>
    <input type="text" name="nama" placeholder="Nama Obat" value="<?= $data['nama'] ?? '' ?>" required>
    <input type="text" name="jenis" placeholder="Jenis" value="<?= $data['jenis'] ?? '' ?>">
    <input type="text" name="satuan" placeholder="Satuan" value="<?= $data['satuan'] ?? '' ?>">
    <input type="number" name="harga" placeholder="Harga" value="<?= $data['harga'] ?? '' ?>">
    <input type="number" name="stok" placeholder="Stok" value="<?= $data['stok'] ?? '' ?>">
    <button type="submit" name="simpan">Simpan</button>
    <a href="obat.php">⬅ Kembali</a>
  </form>
</body>
</html>
