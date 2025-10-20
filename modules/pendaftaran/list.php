<?php
require 'config.php';

// Ambil semua data pasien aktif + join master klinik
$stmt = $pdo->query("
  SELECT p.*, 
         m.nama_klinik AS klinik
  FROM pasien p
  LEFT JOIN master_klinik m ON p.klinik_id = m.id
  WHERE p.status_data = 'aktif' OR p.status_data IS NULL
  ORDER BY p.created_at DESC
");
$data = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Daftar Pasien</title>
  <style>
    body { font-family: Arial; background:#fafafa; margin:20px; }
    table { border-collapse: collapse; width:100%; background:#fff; }
    th, td { padding:8px; border:1px solid #ddd; text-align:left; font-size:14px; }
    th { background:#f4f4f4; }
    img { border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    a { text-decoration:none; color:#0073aa; }
    a:hover { text-decoration:underline; }
    .actions a { margin-right:8px; }
  </style>
</head>
<body>
  <h1>📋 Data Pasien Klinik</h1>
  <p>
    <a href="index.php">➕ Tambah Pasien Baru</a> |
    <a href="arsip.php">🗃️ Lihat Arsip</a>
  </p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Foto</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>TTL</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>No. HP</th>
        <th>Klinik</th>
        <th>Tgl Daftar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="11" style="text-align:center;">Belum ada data pasien.</td></tr>
      <?php else: ?>
        <?php foreach ($data as $i => $row): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td>
            <?php 
            if (!empty($row['foto'])) {
                $fotoObj = json_decode($row['foto'], true);
                if ($fotoObj && isset($fotoObj['data'])) {
                    echo '<img src="data:' . htmlspecialchars($fotoObj['type']) . ';base64,' . $fotoObj['data'] . '" width="60">';
                } else {
                    echo '<i>-</i>';
                }
            } else {
                echo '<i>-</i>';
            }
            ?>
          </td>
          <td><?= htmlspecialchars($row['nik']) ?></td>
          <td><a href="detail.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></a></td>
          <td><?= htmlspecialchars($row['tempat_lahir']) . ' / ' . htmlspecialchars($row['tanggal_lahir']) ?></td>
          <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
          <td><?= htmlspecialchars($row['no_hp']) ?></td>
          <td><?= htmlspecialchars($row['klinik']) ?></td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
          <td class="actions">
            <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a>
            <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus data ini? Data akan masuk ke arsip.')">🗑️ Hapus</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
