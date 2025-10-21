<?php
include '../../config/db.php';

$id = $_GET['id'] ?? '';
$data = $conn->query("SELECT * FROM obat WHERE id='$id'")->fetch_assoc();

if (isset($_POST['update'])) {
  $stok = $_POST['stok'];
  $conn->query("UPDATE obat SET stok='$stok' WHERE id='$id'");
  echo "<script>alert('Stok berhasil diperbarui!');window.location='obat.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Stok</title>
</head>
<body>
  <form method="post" style="width:400px;margin:auto;background:#fff;padding:20px;">
    <h3>Update Stok: <?= $data['nama'] ?></h3>
    <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
    <button type="submit" name="update">Simpan</button>
    <a href="obat.php">⬅ Kembali</a>
  </form>
</body>
</html>
