<?php
session_start();
header('Content-Type: application/json; charset=utf8mb4');

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'tersedia' => false,
        'pesan'    => 'Sesi tidak valid. Silakan login kembali.'
    ]);
    exit();
}

require_once '../config/koneksi.php';

// parameter request ajax
$field = trim($_GET['field'] ?? '');
$nilai = trim($_GET['nilai'] ?? '');

if (empty($field) || empty($nilai)) {
    echo json_encode([
        'tersedia' => false,
        'pesan'    => 'Data yang diperiksa tidak boleh kosong.'
    ]);
    exit();
}

try {
    // whitelist nama kolom
    if ($field === 'nip') {
        $sql   = "SELECT id FROM pegawai WHERE nip = :nilai LIMIT 1";
        $label = "NIP";
    } elseif ($field === 'email') {
        $sql   = "SELECT id FROM pegawai WHERE email = :nilai LIMIT 1";
        $label = "Email";
    } else {
        http_response_code(400);
        echo json_encode([
            'tersedia' => false,
            'pesan'    => 'Kolom pemeriksaan tidak dikenali.'
        ]);
        exit();
    }

    // pdo prepared statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nilai' => $nilai]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'tersedia' => false,
            'pesan'    => "{$label} \"{$nilai}\" sudah terdaftar! Gunakan yang lain."
        ]);
    } else {
        echo json_encode([
            'tersedia' => true,
            'pesan'    => "{$label} tersedia dan dapat digunakan."
        ]);
    }

} catch (PDOException $e) {
    error_log("Error AJAX cek ketersediaan: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'tersedia' => false,
        'pesan'    => 'Gagal memeriksa data ke server.'
    ]);
}