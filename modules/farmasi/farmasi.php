<?php
include '../../config/db.php';
$resep = $conn->query("SELECT * FROM resep WHERE status='menunggu'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Farmasi / Apotek</title>
  <style>
    body { font-family: Arial; background:#f6f9fc; margin:0; }
    h1 { text-align:center; color:#2b6777; margin-top:30px; }
    table { width:90%; margin:20px auto; border-collapse:collapse; background:white; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    th, td { padding:10px; border:1px solid #ddd; text-align:center; }
    th { background:#2b6777; color:white; }
    a.btn { background:#2b6777; color:white; padding:6px 10px; border-radius:5px; text-decoration:none; }
    a.btn:hover { background:#1e4c57; }
    .top-bar { width:90%; margin:20px auto; display:flex; justify-content:space-between; }
    .btn-add { background:#52ab98; color:white; padding:8px 15px; border-radius:5px; text-decoration:none; }
    .btn-add:hover { background:#3d8574; }
  </style>
</head>
<body>

<h1>💊 Modul Farmasi / Apotek</h1>

<div class="top-bar">
  <a href="obat.php" class="btn-add">📦 Kelola Data Obat</a>
  <a href="../../index.php" class="btn">⬅ Kembali</a>
</div>

<h2 style="text-align:center;">📄 Daftar Resep dari Dokter</h2>

<table>
  <tr>
    <th>ID Resep</th>
    <th>Nama Pasien</th>
    <th>Nama Obat</th>
    <th>Jumlah</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>
  <?php while ($r = $resep->fetch_assoc()): ?>
  <tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['nama_pasien']) ?></td>
    <td><?= htmlspecialchars($r['nama_obat']) ?></td>
    <td><?= $r['jumlah'] ?></td>
    <td><?= ucfirst($r['status']) ?></td>
    <td>
      <a href="proses_resep.php?id=<?= $r['id'] ?>" class="btn">✅ Proses</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
