<?php
include '../../config/db.php';
$result = $conn->query("SELECT * FROM obat ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Obat</title>
  <style>
    body { font-family: Arial; background:#f7f9fc; margin:0; }
    h1 { text-align:center; margin-top:30px; color:#2b6777; }
    table { width:90%; margin:20px auto; border-collapse:collapse; background:white; }
    th, td { padding:10px; border:1px solid #ddd; text-align:center; }
    th { background:#2b6777; color:white; }
    a.btn { background:#2b6777; color:white; padding:5px 8px; border-radius:5px; text-decoration:none; }
    a.btn:hover { background:#1e4c57; }
    .top-bar { width:90%; margin:20px auto; display:flex; justify-content:space-between; }
    .btn-add { background:#52ab98; color:white; padding:8px 15px; border-radius:5px; text-decoration:none; }
  </style>
</head>
<body>

<h1>📦 Manajemen Data Obat</h1>

<div class="top-bar">
  <a href="obat_form.php" class="btn-add">➕ Tambah Obat</a>
  <a href="farmasi.php" class="btn">⬅ Kembali</a>
</div>

<table>
  <tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Jenis</th>
    <th>Satuan</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['jenis']) ?></td>
    <td><?= htmlspecialchars($row['satuan']) ?></td>
    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
    <td><?= $row['stok'] ?></td>
    <td>
      <a href="obat_form.php?id=<?= $row['id'] ?>" class="btn">✏️ Edit</a>
      <a href="stok_update.php?id=<?= $row['id'] ?>" class="btn">📦 Stok</a>
    <a href="obat_hapus.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Hapus data ini?')">🗑️ Hapus</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
