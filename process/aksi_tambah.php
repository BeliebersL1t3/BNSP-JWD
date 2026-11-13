<?php
session_start();

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php?pesan=belum_login");
    exit();
}

// kirim pakai post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/pegawai.php");
    exit();
}

require_once '../config/koneksi.php';

// bersihkan input
$nip           = trim($_POST['nip'] ?? '');
$nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');
$email         = trim($_POST['email'] ?? '');
$no_hp         = trim($_POST['no_hp'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$jabatan       = trim($_POST['jabatan'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');

// validasi dari server
if (empty($nip) || empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($jenis_kelamin) || empty($jabatan) || empty($alamat)) {
    header("Location: ../pages/tambah.php?error=empty");
    exit();
}

// Validasi email dari server
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/tambah.php?error=invalid_email");
    exit();
}

try {
    // cek nip dan email pernah daftar
    $checkSql  = "SELECT id FROM pegawai WHERE nip = :nip OR email = :email LIMIT 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':nip' => $nip, ':email' => $email]);

    if ($checkStmt->rowCount() > 0) {
        header("Location: ../pages/tambah.php?error=duplicate");
        exit();
    }

    // masuk data baru pakai PDO prepare statement
    $insertSql = "INSERT INTO pegawai (nip, nama_lengkap, email, no_hp, jenis_kelamin, jabatan, alamat) 
                  VALUES (:nip, :nama_lengkap, :email, :no_hp, :jenis_kelamin, :jabatan, :alamat)";
    
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([
        ':nip'           => $nip,
        ':nama_lengkap'  => $nama_lengkap,
        ':email'         => $email,
        ':no_hp'         => $no_hp,
        ':jenis_kelamin' => $jenis_kelamin,
        ':jabatan'       => $jabatan,
        ':alamat'        => $alamat
    ]);

    // sukses, balik ke hal pegawai
    header("Location: ../pages/pegawai.php?pesan=tambah_sukses");
    exit();

} catch (PDOException $e) {
    error_log("Gagal menambahkan pegawai baru: " . $e->getMessage());
    header("Location: ../pages/tambah.php?error=system");
    exit();
}