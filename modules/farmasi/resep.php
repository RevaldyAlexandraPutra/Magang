<?php include '../../config/db.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Resep Pasien</title>
  <style>
    body { font-family: Arial; background:#f6f9fc; }
    table { width:80%; margin:30px auto; border-collapse:collapse; background:white; }
    th, td { border:1px solid #ddd; padding:10px; text-align:center; }
    th { background:#2b6777; color:white; }
    a { text-decoration:none; color:#2b6777; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">📜 Daftar Resep Pasien</h2>

  <table>
    <tr>
      <th>ID Resep</th>
      <th>Nama Pasien</th>
      <th>Nama Obat</th>
      <th>Jumlah</th>
      <th>Aksi</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM resep ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['nama_pasien']}</td>
        <td>{$row['obat']}</td>
        <td>{$row['jumlah']}</td>
        <td><a href='proses_resep.php?id={$row['id']}'>Proses</a></td>
      </tr>";
    }
    ?>
  </table>

  <div style="text-align:center; margin-top:20px;">
    <a href="index.php">⬅ Kembali</a>
  </div>
</body>
</html>
