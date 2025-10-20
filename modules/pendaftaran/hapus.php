<?php
require 'config.php';

if (!isset($_GET['id'])) {
  die("ID tidak ditemukan!");
}

$id = (int) $_GET['id'];

// Pastikan kolom status_data ada di tabel pasien
// ALTER TABLE pasien ADD COLUMN status_data ENUM('aktif','hapus') DEFAULT 'aktif';

// Ubah status_data menjadi 'hapus' tanpa menghapus data fisik
$stmt = $pdo->prepare("UPDATE pasien SET status_data = 'hapus' WHERE id = ?");
$stmt->execute([$id]);

echo "<script>
  alert('✅ Data pasien berhasil disembunyikan dari daftar (soft delete)!');
  window.location='list.php';
</script>";
?>
