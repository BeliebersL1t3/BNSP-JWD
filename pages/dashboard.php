<?php
session_start();

// tolak kalau lom login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

try {
    // ambill data dari database pake pdo
    // Total Seluruh Pegawai
    $stmtTotal = $pdo->query("SELECT COUNT(*) AS total FROM pegawai");
    $totalPegawai = $stmtTotal->fetch()['total'];

    // semua pegawai cowo
    $stmtPria = $pdo->query("SELECT COUNT(*) AS total FROM pegawai WHERE jenis_kelamin = 'Laki-laki'");
    $totalPria = $stmtPria->fetch()['total'];

    // pegawai cewe
    $stmtWanita = $pdo->query("SELECT COUNT(*) AS total FROM pegawai WHERE jenis_kelamin = 'Perempuan'");
    $totalWanita = $stmtWanita->fetch()['total'];

    // jabatan
    $stmtJabatan = $pdo->query("SELECT COUNT(DISTINCT jabatan) AS total FROM pegawai");
    $totalJabatan = $stmtJabatan->fetch()['total'];

} catch (PDOException $e) {
    error_log("Gagal memuat statistik dashboard: " . $e->getMessage());
    die("Gagal memuat data statistik dashboard.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Data Pegawai</title>
    <!-- Library CSS lokal -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- CSS  -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="dashboard.php">
                <img src="../assets/img/logo.svg" alt="Logo" width="30" height="30" class="me-2">
                SIM Pegawai
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pegawai.php">Data Pegawai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah.php">Tambah Pegawai</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3 small">Halo, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></strong></span>
                    <a href="../process/proses_logout.php" class="btn btn-outline-light btn-sm px-3">Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- dashboard -->
    <main class="container my-5">
        <!-- banner -->
        <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border-start border-primary border-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h3 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h3>
                    <p class="text-muted mb-0">Sistem Informasi Manajemen Pencatatan Data Kepegawaian (BNSP JWD SKK-32-10/2024)</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="tambah.php" class="btn btn-primary me-2">
                        + Tambah Pegawai Baru
                    </a>
                    <a href="pegawai.php" class="btn btn-outline-secondary">
                        Lihat Semua Data
                    </a>
                </div>
            </div>
        </div>

        <!-- statistik -->
        <div class="row g-4 mb-5">
            <!-- Total Pegawai -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">TOTAL PEGAWAI</span>
                            <h2 class="fw-bold text-primary mb-0 mt-2"><?= $totalPegawai; ?></h2>
                        </div>
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle fs-3">
                            👥
                        </div>
                    </div>
                </div>
            </div>

            <!-- pegawai cowo -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">LAKI-LAKI</span>
                            <h2 class="fw-bold text-info mb-0 mt-2"><?= $totalPria; ?></h2>
                        </div>
                        <div class="bg-info-subtle text-info p-3 rounded-circle fs-3">
                            👨
                        </div>
                    </div>
                </div>
            </div>

            <!-- pegawai cewe -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">PEREMPUAN</span>
                            <h2 class="fw-bold text-warning mb-0 mt-2"><?= $totalWanita; ?></h2>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle fs-3">
                            👩
                        </div>
                    </div>
                </div>
            </div>

            <!-- jabatan -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">DIVISI / JABATAN</span>
                            <h2 class="fw-bold text-success mb-0 mt-2"><?= $totalJabatan; ?></h2>
                        </div>
                        <div class="bg-success-subtle text-success p-3 rounded-circle fs-3">
                            🏢
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            &copy; 2026 Aplikasi Kepegawaian - Uji Kompetensi BNSP Junior Web Developer
        </div>
    </footer>

    <!-- Library JS lokal -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js"></script>
</body>
</html>