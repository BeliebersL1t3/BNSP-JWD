<?php
session_start();

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

// notifberhasil kaga
$pesanSukses = '';
$pesanError = '';

if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'tambah_sukses') {
        $pesanSukses = 'Data pegawai baru berhasil ditambahkan!';
    } elseif ($_GET['pesan'] == 'edit_sukses') {
        $pesanSukses = 'Data pegawai berhasil diperbarui!';
    } elseif ($_GET['pesan'] == 'hapus_sukses') {
        $pesanSukses = 'Data pegawai berhasil dihapus dari sistem.';
    } elseif ($_GET['pesan'] == 'error') {
        $pesanError = 'Terjadi kesalahan saat memproses data. Silakan coba lagi.';
    }
}

try {
    // ambil data urutan terbaru
    $stmt = $pdo->query("SELECT * FROM pegawai ORDER BY id DESC");
    $daftarPegawai = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Gagal mengambil data pegawai: " . $e->getMessage());
    die("Gagal memuat data pegawai.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai - Aplikasi Data Pegawai</title>
    <!-- Library CSS Lokal -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- CSS Kustom -->
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

    <!-- pegawais -->
    <main class="container my-5">
        
        <!-- notif -->
        <?php if (!empty($pesanSukses)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesanSukses); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($pesanError)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesanError); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- header + tambah -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h3 class="fw-bold mb-1">Daftar Data Pegawai</h3>
                <p class="text-muted mb-0 small">Kelola informasi seluruh pegawai aktif di perusahaan.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="tambah.php" class="btn btn-primary">
                    + Tambah Pegawai
                </a>
            </div>
        </div>

        <!-- kartu data -->
        <div class="card shadow-sm p-4">
            <!-- search (jquery) -->
            <div class="row mb-3">
                <div class="col-md-5 ms-auto">
                    <div class="input-group">
                        <span class="input-group-text bg-white">🔍</span>
                        <input type="text" id="searchPegawai" class="form-control" placeholder="Cari nama, NIP, atau jabatan...">
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabelPegawai">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">NIP</th>
                            <th width="20%">Nama Lengkap</th>
                            <th width="15%">Jabatan</th>
                            <th width="12%">Jenis Kelamin</th>
                            <th width="13%">No. HP</th>
                            <th width="23%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($daftarPegawai) > 0): ?>
                            <?php $nomor = 1; ?>
                            <?php foreach ($daftarPegawai as $p): ?>
                                <tr>
                                    <td><?= $nomor++; ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($p['nip']); ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                                    <td><?= htmlspecialchars($p['jabatan']); ?></td>
                                    <td>
                                        <?php if ($p['jenis_kelamin'] == 'Laki-laki'): ?>
                                            <span class="badge bg-info-subtle text-info border">Laki-laki</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border">Perempuan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['no_hp']); ?></td>
                                    <td class="text-center">
                                        <!-- detail -->
                                        <a href="detail.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-info text-white me-1" title="Lihat Detail">
                                            Detail
                                        </a>
                                        <!-- Edit -->
                                        <a href="edit.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-warning text-dark me-1" title="Ubah Data">
                                            Edit
                                        </a>
                                        <!-- hapus -->
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus Data"
                                                onclick="konfirmasiHapus('<?= htmlspecialchars(addslashes($p['nama_lengkap'])); ?>', '../process/aksi_hapus.php?id=<?= $p['id']; ?>')">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data pegawai yang tersimpan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- footer -->
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