<?php
// db.php
// Koneksi database menggunakan PDO. Ubah parameter sesuai konfigurasi MySQL kamu.

$DB_HOST = '127.0.0.1';
$DB_NAME = 'sim_klinik';
$DB_USER = 'root';
$DB_PASS = ''; // isi password jika ada

try {
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // tampilkan error SQL (development)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Jangan tampilkan error detail ke user di production
    die("Koneksi database gagal: " . $e->getMessage());
}
