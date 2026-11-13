<?php
session_start();
require_once '../config/koneksi.php';

// kirim lewat post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/login.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// validasi dari server
if (empty($username) || empty($password)) {
    header("Location: ../pages/login.php?error=1");
    exit();
}

try {
    // cari akun pakai pdo prepare
    $sql  = "SELECT id, username, password, nama_lengkap FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // verif password dengan hash
    if ($user && password_verify($password, $user['password'])) {
        // simpan info ke session
        $_SESSION['user_id']      = $user['id'];
        $_SESSION['username']     = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['login_time']   = time();

        // arahin ke halaman utama
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        // login gagal, ga cocok
        header("Location: ../pages/login.php?error=1");
        exit();
    }

} catch (PDOException $e) {
    // Log error teknis untuk admin dan stop proses secara aman
    error_log("Kesalahan saat proses login: " . $e->getMessage());
    die("Terjadi kesalahan sistem saat memproses login. Silakan coba beberapa saat lagi.");
}