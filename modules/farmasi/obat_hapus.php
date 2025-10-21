<?php
include '../../config/db.php';

// Pastikan ada parameter id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Jalankan query hapus
    $query = "DELETE FROM obat WHERE id = '$id'";
    $result = $conn->query($query);

    if ($result) {
        echo "<script>
                alert('Data obat berhasil dihapus!');
                window.location='obat.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data obat: " . $conn->error . "');
                window.location='obat.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan!');
            window.location='obat.php';
          </script>";
}
?>
