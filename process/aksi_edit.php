<?php
session_start();

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php?pesan=belum_login");
    exit();
}

// kirim dengan post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/pegawai.php");
    exit();
}

require_once '../config/koneksi.php';

// bersihkah input
$id            = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nip           = trim($_POST['nip'] ?? '');
$nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');
$email         = trim($_POST['email'] ?? '');
$no_hp         = trim($_POST['no_hp'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$jabatan       = trim($_POST['jabatan'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');

// validasi id dan data di server
if (!$id || empty($nip) || empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($jenis_kelamin) || empty($jabatan) || empty($alamat)) {
    header("Location: ../pages/edit.php?id={$id}&error=empty");
    exit();
}

// Validasi format email di server
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/edit.php?id={$id}&error=invalid_email");
    exit();
}

try {
    // cek id pegawai bentrok dengna id lain
    $checkSql  = "SELECT id FROM pegawai WHERE (nip = :nip OR email = :email) AND id != :id LIMIT 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([
        ':nip'   => $nip,
        ':email' => $email,
        ':id'    => $id
    ]);

    if ($checkStmt->rowCount() > 0) {
        header("Location: ../pages/edit.php?id={$id}&error=duplicate");
        exit();
    }

    // eksekusi update dengan pdo prepared statement
    $updateSql = "UPDATE pegawai SET 
                    nip           = :nip,
                    nama_lengkap  = :nama_lengkap,
                    email         = :email,
                    no_hp         = :no_hp,
                    jenis_kelamin = :jenis_kelamin,
                    jabatan       = :jabatan,
                    alamat        = :alamat
                  WHERE id = :id";
    
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        ':nip'           => $nip,
        ':nama_lengkap'  => $nama_lengkap,
        ':email'         => $email,
        ':no_hp'         => $no_hp,
        ':jenis_kelamin' => $jenis_kelamin,
        ':jabatan'       => $jabatan,
        ':alamat'        => $alamat,
        ':id'            => $id
    ]);

    // berhasil, balik ke hal pegawai
    header("Location: ../pages/pegawai.php?pesan=edit_sukses");
    exit();

} catch (PDOException $e) {
    error_log("Gagal memperbarui data pegawai: " . $e->getMessage());
    header("Location: ../pages/edit.php?id={$id}&error=system");
    exit();
}