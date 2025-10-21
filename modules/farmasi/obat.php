<?php include '../../config/db.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Obat</title>
  <style>
    body { font-family: Arial; background:#f6f9fc; }
    table { width:80%; margin:30px auto; border-collapse:collapse; background:white; }
    th, td { border:1px solid #ddd; padding:10px; text-align:center; }
    th { background:#2b6777; color:white; }
    a { text-decoration:none; color:#2b6777; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">💊 Data Obat</h2>

  <table>
    <tr>
      <th>ID</th>
      <th>Nama Obat</th>
      <th>Jenis</th>
      <th>Satuan</th>
      <th>Stok</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM obat ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['jenis']}</td>
        <td>{$row['satuan']}</td>
        <td>{$row['stok']}</td>
        <td>Rp ".number_format($row['harga'])."</td>
        <td><a href='stok_update.php?id={$row['id']}'>Update</a></td>
      </tr>";
    }
    ?>
  </table>

  <div style="text-align:center; margin-top:20px;">
    <a href="index.php">⬅ Kembali</a>
  </div>
</body>
</html>
