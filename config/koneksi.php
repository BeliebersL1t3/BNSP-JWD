<?php
$host     = 'localhost';
$dbname   = 'db_kepegawaian';
$username = 'root';
$password = '';

try {
    // data source
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

// keamanan
$options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

// start pdo
$pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
// log erorr
    error_log("Koneksi Database Gagal: " . $e->getMessage());

// error message
    die("Maaf, terjadi gangguan saat menghubungkan ke basis data. Pastikan service MySQL pada Laragon sudah aktif.");
}