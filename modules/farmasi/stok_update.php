<?php
include '../../config/db.php';

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $stok = $_POST['stok'];

  $update = $conn->query("UPDATE obat SET stok = '$stok' WHERE id = '$id'");
  if ($update) {
    echo "<script>alert('Stok berhasil diperbarui');window.location='obat.php';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui stok');</script>";
  }
}

// pastikan id dikirim dari URL
$id = $_GET['id'] ?? null;
$data = null;

if ($id) {
  $result = $conn->query("SELECT * FROM obat WHERE id='$id'");
  if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Stok</title>
  <style>
    body { font-family: Arial; background:#f6f9fc; }
    form { width:400px; margin:50px auto; background:white; padding:20px; border-radius:10px; }
    label { font-weight:bold; }
    input { width:100%; padding:8px; margin:5px 0; }
    button { background:#2b6777; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; }
    button:hover { background:#1e4c57; }
    a { display:block; text-align:center; margin-top:10px; text-decoration:none; color:#2b6777; }
  </style>
</head>
<body>

  <?php if ($data): ?>
  <form method="post">
    <h2 style="text-align:center;">📦 Update Stok Obat</h2>
    <label>Nama Obat</label>
    <input type="text" value="<?= htmlspecialchars($data['nama']) ?>" readonly>
    <label>Stok Baru</label>
    <input type="number" name="stok" value="<?= htmlspecialchars($data['stok']) ?>" required>
    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
    <button type="submit" name="update">Simpan Perubahan</button>
    <a href="obat.php">⬅ Kembali</a>
  </form>
  <?php else: ?>
    <div style="text-align:center; margin-top:50px;">
      <h2>❌ Data tidak ditemukan</h2>
      <a href="obat.php">⬅ Kembali</a>
    </div>
  <?php endif; ?>

</body>
</html>
