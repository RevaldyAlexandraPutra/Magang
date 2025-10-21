<?php
include '../../config/db.php';

$id = $_GET['id'] ?? 0;
$r = $conn->query("SELECT * FROM resep WHERE id='$id'")->fetch_assoc();

if ($r) {
  $obat = $conn->query("SELECT * FROM obat WHERE nama='".$r['nama_obat']."'")->fetch_assoc();

  if ($obat) {
    $new_stok = $obat['stok'] - $r['jumlah'];
    if ($new_stok < 0) $new_stok = 0;

    $conn->query("UPDATE obat SET stok='$new_stok' WHERE id='".$obat['id']."'");
    $conn->query("UPDATE resep SET status='selesai' WHERE id='$id'");

    echo "<script>alert('Resep diproses & stok dikurangi!');window.location='farmasi.php';</script>";
  } else {
    echo "<script>alert('Obat tidak ditemukan di data stok!');window.location='farmasi.php';</script>";
  }
} else {
  echo "<script>alert('Resep tidak ditemukan!');window.location='farmasi.php';</script>";
}
?>
