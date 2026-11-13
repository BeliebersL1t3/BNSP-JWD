<?php
session_start();

// ga login tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

// ambil dan validasi id dari url
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: pegawai.php");
    exit();
}

try {
    // ambil data spesifik pegawai pakai pdo prepared statement
    $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $pegawai = $stmt->fetch();

    // gada di database, balik ke hal pegawai
    if (!$pegawai) {
        header("Location: pegawai.php?pesan=error");
        exit();
    }

} catch (PDOException $e) {
    error_log("Gagal memuat detail pegawai: " . $e->getMessage());
    die("Gagal memuat data detail pegawai.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pegawai - <?= htmlspecialchars($pegawai['nama_lengkap']); ?></title>
    <!-- Library CSS Pre-existing Lokal -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Navbar -->
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="pegawai.php">Data Pegawai</a>
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

    <!-- detail profil -->
    <main class="container my-5" style="max-width: 850px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Detail Informasi Pegawai</h3>
                <p class="text-muted small mb-0">Informasi rekam data lengkap pegawai dalam basis data.</p>
            </div>
            <a href="pegawai.php" class="btn btn-outline-secondary">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <div class="card shadow-sm p-4">
            <div class="row align-items-center">
                
                <!-- Kolom Kiri: profil avatar -->
                <div class="col-md-4 text-center border-end py-3">
                    <img src="../assets/img/avatar-default.svg" alt="Avatar Pegawai" width="120" height="120" class="rounded-circle shadow-sm mb-3">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($pegawai['nama_lengkap']); ?></h5>
                    <span class="badge bg-primary px-3 py-2 fs-6 mb-2"><?= htmlspecialchars($pegawai['jabatan']); ?></span>
                    <p class="text-muted small mb-0">NIP: <strong><?= htmlspecialchars($pegawai['nip']); ?></strong></p>
                </div>

                <!-- Kolom Kanan: detail pegawai -->
                <div class="col-md-8 ps-md-4 py-3">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="35%" class="text-muted small">Jenis Kelamin</th>
                                <td width="65%">: 
                                    <?php if ($pegawai['jenis_kelamin'] == 'Laki-laki'): ?>
                                        <span class="badge bg-info-subtle text-info border">Laki-laki</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border">Perempuan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted small">Alamat Email</th>
                                <td>: <a href="mailto:<?= htmlspecialchars($pegawai['email']); ?>" class="text-decoration-none"><?= htmlspecialchars($pegawai['email']); ?></a></td>
                            </tr>
                            <tr>
                                <th class="text-muted small">Nomor WhatsApp / HP</th>
                                <td>: <?= htmlspecialchars($pegawai['no_hp']); ?></td>
                            </tr>
                            <tr>
                                <th class="text-muted small">Alamat Domisili</th>
                                <td>: <?= nl2br(htmlspecialchars($pegawai['alamat'])); ?></td>
                            </tr>
                            <tr>
                                <th class="text-muted small">Tanggal Terdaftar</th>
                                <td>: <?= date('d F Y, H:i', strtotime($pegawai['created_at'])); ?> WIB</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- aksi langsung dari hal detail -->
                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <a href="edit.php?id=<?= $pegawai['id']; ?>" class="btn btn-warning text-dark px-4">
                            Ubah Data Pegawai
                        </a>
                        <button type="button" 
                                class="btn btn-danger px-4"
                                onclick="konfirmasiHapus('<?= htmlspecialchars(addslashes($pegawai['nama_lengkap'])); ?>', '../process/aksi_hapus.php?id=<?= $pegawai['id']; ?>')">
                            Hapus Pegawai Ini
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            &copy; 2026 Aplikasi Kepegawaian - Uji Kompetensi BNSP Junior Web Developer
        </div>
    </footer>

    <!-- Library JS Lokal -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js"></script>
</body>
</html>