<?php
require 'config.php';

// Jika ada aksi "pulihkan" data pasien
if (isset($_GET['restore'])) {
    $id = (int) $_GET['restore'];
    $stmt = $pdo->prepare("UPDATE pasien SET status_data = 'aktif' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('✅ Data pasien berhasil dipulihkan!');window.location='arsip.php';</script>";
    exit;
}

// Ambil semua data pasien yang dihapus (soft delete)
$stmt = $pdo->query("
  SELECT p.*, m.nama_klinik 
  FROM pasien p
  LEFT JOIN master_klinik m ON p.klinik_id = m.id
  WHERE p.status_data = 'hapus'
  ORDER BY p.id DESC
");

$data = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Arsip Data Pasien (Terhapus)</title>
  <style>
    body { font-family: Arial; background:#f9f9f9; margin:20px; }
    h1 { color:#333; }
    table { border-collapse: collapse; width:100%; background:#fff; margin-top:15px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding:8px; border:1px solid #ddd; text-align:left; font-size:14px; }
    th { background:#f4f4f4; }
    img { border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    a { text-decoration:none; color:#0073aa; }
    a:hover { text-decoration:underline; }
    .btn { padding:5px 8px; border-radius:4px; font-size:13px; }
    .btn-restore { background:#28a745; color:#fff; }
    .btn-back { background:#6c757d; color:#fff; }
  </style>
</head>
<body>
  <h1>🗃️ Arsip Data Pasien (Soft Delete)</h1>
  <p><a href="list.php" class="btn btn-back">⬅️ Kembali ke Daftar Aktif</a></p>

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
        <th>No HP</th>
        <th>Klinik</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="10" style="text-align:center;">Tidak ada data pasien dalam arsip.</td></tr>
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
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td><?= htmlspecialchars($row['tempat_lahir']) . ' / ' . htmlspecialchars($row['tanggal_lahir']) ?></td>
          <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
          <td><?= htmlspecialchars($row['no_hp']) ?></td>
          <td><?= htmlspecialchars($row['nama_klinik']) ?></td>
          <td>
            <a href="?restore=<?= $row['id'] ?>" class="btn btn-restore" onclick="return confirm('Pulihkan data pasien ini?')">Pulihkan</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
