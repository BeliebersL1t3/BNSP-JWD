<?php
/**
 * Logika Backend: Pemrosesan Penghapusan Data Pegawai (Delete Operation)
 * Menggunakan PDO Prepared Statement
 * Skema Sertifikasi BNSP Junior Web Developer
 */
session_start();

// 1. Session Guard: Tolak jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

// 2. Ambil dan validasi parameter ID yang akan dihapus
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: ../pages/pegawai.php?pesan=error");
    exit();
}

try {
    // 3. Eksekusi penghapusan data dengan PDO Prepared Statement
    $stmt = $pdo->prepare("DELETE FROM pegawai WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // 4. Periksa apakah baris data benar-benar ditemukan dan terhapus
    if ($stmt->rowCount() > 0) {
        // Berhasil terhapus: Alihkan ke daftar pegawai dengan notifikasi sukses
        header("Location: ../pages/pegawai.php?pesan=hapus_sukses");
        exit();
    } else {
        // ID tidak ditemukan di tabel
        header("Location: ../pages/pegawai.php?pesan=error");
        exit();
    }

} catch (PDOException $e) {
    error_log("Gagal menghapus data pegawai: " . $e->getMessage());
    header("Location: ../pages/pegawai.php?pesan=error");
    exit();
}