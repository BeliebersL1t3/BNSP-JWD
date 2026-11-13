<?php
session_start();

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

// ambil dan validasi id yang mau dihapus
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: ../pages/pegawai.php?pesan=error");
    exit();
}

try {
    // hapus pakai pdo prepared statement
    $stmt = $pdo->prepare("DELETE FROM pegawai WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // cek terhapus beneran
    if ($stmt->rowCount() > 0) {
        // Berhasil terhapus: Alihkan ke daftar pegawai dengan notifikasi sukses
        header("Location: ../pages/pegawai.php?pesan=hapus_sukses");
        exit();
    } else {
        header("Location: ../pages/pegawai.php?pesan=error");
        exit();
    }

} catch (PDOException $e) {
    error_log("Gagal menghapus data pegawai: " . $e->getMessage());
    header("Location: ../pages/pegawai.php?pesan=error");
    exit();
}