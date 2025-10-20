<?php
require 'config.php';

if (!isset($_GET['id'])) die("ID tidak ditemukan!");
$id = (int) $_GET['id'];

// Ambil data pasien + join nama klinik
$stmt = $pdo->prepare("
  SELECT p.*, mk.nama_klinik AS nama_klinik
  FROM pasien p
  LEFT JOIN master_klinik mk ON p.klinik_id = mk.id
  WHERE p.id = ?
");
$stmt->execute([$id]);
$pasien = $stmt->fetch();

if (!$pasien) die("Data pasien tidak ditemukan!");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Data Pasien</title>
  <style>
    body { font-family: Arial, sans-serif; background: #fafafa; max-width: 800px; margin: 30px auto; }
    h2 { color: #333; }
    table { border-collapse: collapse; width: 100%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ddd; padding: 10px; vertical-align: top; font-size: 14px; }
    th { width: 30%; background: #f4f4f4; text-align: left; }
    img { max-width: 150px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.15); }
    a { text-decoration: none; color: #0073aa; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <h2>🧾 Detail Data Pasien</h2>

  <table>
    <tr><th>NIK</th><td><?= htmlspecialchars($pasien['nik']) ?></td></tr>
    <tr><th>Nama</th><td><?= htmlspecialchars($pasien['nama']) ?></td></tr>
    <tr><th>Tempat, Tanggal Lahir</th><td><?= htmlspecialchars($pasien['tempat_lahir']) ?>, <?= htmlspecialchars($pasien['tanggal_lahir']) ?></td></tr>
    <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($pasien['jenis_kelamin']) ?></td></tr>
    <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($pasien['alamat'])) ?></td></tr>
    <tr><th>No. HP</th><td><?= htmlspecialchars($pasien['no_hp']) ?></td></tr>
    <tr><th>Klinik</th><td><?= htmlspecialchars($pasien['nama_klinik'] ?? '-') ?></td></tr>
    <tr>
      <th>Foto</th>
      <td>
        <?php 
        if (!empty($pasien['foto'])) {
          $fotoData = json_decode($pasien['foto'], true);
          if ($fotoData && isset($fotoData['data'])) {
            echo '<img src="data:' . htmlspecialchars($fotoData['type']) . ';base64,' . $fotoData['data'] . '" alt="Foto Pasien">';
          } else {
            echo '<i>Data foto tidak valid</i>';
          }
        } else {
          echo '<i>Tidak ada foto</i>';
        }
        ?>
      </td>
    </tr>
    <tr><th>Status Data</th><td><?= htmlspecialchars($pasien['status_data'] ?? 'aktif') ?></td></tr>
    <tr><th>Tanggal Pendaftaran</th><td><?= htmlspecialchars($pasien['created_at']) ?></td></tr>
  </table>

  <br>
  <a href="edit.php?id=<?= $pasien['id'] ?>">✏️ Edit Data</a> |
  <a href="hapus.php?id=<?= $pasien['id'] ?>" onclick="return confirm('Data pasien akan disembunyikan dari daftar. Lanjutkan?')">🗑️ Sembunyikan</a> |
  <a href="list.php">⬅️ Kembali ke Daftar</a>
</body>
</html>
