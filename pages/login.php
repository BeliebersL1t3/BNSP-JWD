<?php
session_start();

// loggedin, ga bisa ke page login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// cek pesan error
$pesanError = '';
$pesanSukses = '';

if (isset($_GET['error'])) {
    $pesanError = 'Username atau password salah! Silakan coba lagi.';
} elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'logout') {
    $pesanSukses = 'Anda telah berhasil keluar dari sistem.';
} elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login') {
    $pesanError = 'Silakan login terlebih dahulu untuk mengakses aplikasi.';
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Data Pegawai</title>
    <!-- Library CSS Pre-existing Lokal -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="d-flex align-items-center justify-content-center py-5">

    <div class="container" style="max-width: 420px;">
        <div class="card p-4 shadow-sm">
            <div class="text-center mb-4">
                <!-- Elemen Multimedia: Logo Aplikasi -->
                <img src="../assets/img/logo.svg" alt="Logo Kepegawaian" width="64" height="64" class="mb-3">
                <h4 class="fw-bold mb-1">Aplikasi Pegawai</h4>
                <p class="text-muted small">Sistem Pencatatan Data Pegawai - BNSP JWD</p>
            </div>

            <!-- Notifikasi Pesan Sukses / Error -->
            <?php if (!empty($pesanError)): ?>
                <div class="alert alert-danger py-2 small" role="alert">
                    <?= htmlspecialchars($pesanError); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($pesanSukses)): ?>
                <div class="alert alert-success py-2 small" role="alert">
                    <?= htmlspecialchars($pesanSukses); ?>
                </div>
            <?php endif; ?>

            <!-- Form Login (Mengarah ke process/proses_login.php) -->
            <form action="../process/proses_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">Default: <strong>admin</strong> | <strong>admin123</strong></small>
            </div>
        </div>
    </div>

    <!-- Library JS Pre-existing Lokal -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>