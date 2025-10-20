<?php
// config.php
// --------------------
// File ini digunakan untuk membuat koneksi ke database MySQL
// menggunakan PDO agar lebih aman dan fleksibel.

// Konfigurasi koneksi database
$host = '127.0.0.1';     // Server database (biasanya localhost)
$db   = 'sim_klinik_db'; // Nama database baru
$user = 'root';          // Username default XAMPP
$pass = '';              // Password default XAMPP biasanya kosong
$charset = 'utf8mb4';    // Charset yang umum digunakan

// DSN (Data Source Name) untuk PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opsi tambahan untuk PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mode error: exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil query berupa array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Gunakan prepared statement asli
];

// Membuat koneksi
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Koneksi ke database berhasil"; // Bisa diaktifkan untuk debugging
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}
?>
