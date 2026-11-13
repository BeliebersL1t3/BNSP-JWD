<?php
session_start();

// galogin tolak
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}

require_once '../config/koneksi.php';

// ambil id dan validasi
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: pegawai.php");
    exit();
}

try {
    // take data pegawai
    $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $pegawai = $stmt->fetch();

    if (!$pegawai) {
        header("Location: pegawai.php?pesan=error");
        exit();
    }
} catch (PDOException $e) {
    error_log("Gagal mengambil data pegawai untuk edit: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data pegawai.");
}

// catch error kalau ada
$pesanError = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'empty') {
        $pesanError = 'Semua kolom formulir wajib diisi!';
    } elseif ($_GET['error'] == 'duplicate') {
        $pesanError = 'NIP atau Email tersebut sudah digunakan oleh pegawai lain!';
    } elseif ($_GET['error'] == 'invalid_email') {
        $pesanError = 'Format alamat email tidak valid!';
    } else {
        $pesanError = 'Terjadi kesalahan sistem saat memperbarui data.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai - <?= htmlspecialchars($pegawai['nama_lengkap']); ?></title>
    <!-- Library CSS Lokal -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- Navbar  -->
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

    <!-- edit -->
    <main class="container my-5" style="max-width: 800px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Ubah Data Pegawai</h3>
                <p class="text-muted small mb-0">Perbarui rincian informasi pegawai terdaftar.</p>
            </div>
            <a href="pegawai.php" class="btn btn-outline-secondary">
                &larr; Batal & Kembali
            </a>
        </div>

        <!-- Alert error dari server -->
        <?php if (!empty($pesanError)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesanError); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- form edit pegawai -->
        <div class="card shadow-sm p-4">
            <form action="../process/aksi_edit.php" method="POST" id="formPegawai" novalidate>
                
                <!-- Hidden Input untuk mengirim Primary Key ID Pegawai -->
                <input type="hidden" name="id" value="<?= $pegawai['id']; ?>">

                <div class="row g-3">
                    
                    <!-- NIP -->
                    <div class="col-md-6">
                        <label for="nip" class="form-label fw-semibold small">Nomor Induk Pegawai (NIP) *</label>
                        <input type="text" name="nip" id="nip" class="form-control" value="<?= htmlspecialchars($pegawai['nip']); ?>" required>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label fw-semibold small">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= htmlspecialchars($pegawai['nama_lengkap']); ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold small">Alamat Email *</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($pegawai['email']); ?>" required>
                    </div>

                    <!-- Nomor HP -->
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label fw-semibold small">Nomor WhatsApp / HP *</label>
                        <input type="tel" name="no_hp" id="no_hp" class="form-control" value="<?= htmlspecialchars($pegawai['no_hp']); ?>" required>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label fw-semibold small">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" <?= ($pegawai['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($pegawai['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Jabatan -->
                    <div class="col-md-6">
                        <label for="jabatan" class="form-label fw-semibold small">Jabatan / Posisi *</label>
                        <select name="jabatan" id="jabatan" class="form-select" required>
                            <?php
                            $pilihanJabatan = ['Staff IT', 'Web Developer', 'Staff HRD', 'Manager IT', 'Finance Officer', 'Marketing Specialist'];
                            foreach ($pilihanJabatan as $jab):
                            ?>
                                <option value="<?= $jab; ?>" <?= ($pegawai['jabatan'] == $jab) ? 'selected' : ''; ?>><?= $jab; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="col-12">
                        <label for="alamat" class="form-label fw-semibold small">Alamat Tinggal Lengkap *</label>
                        <textarea name="alamat" id="alamat" rows="3" class="form-control" required><?= htmlspecialchars($pegawai['alamat']); ?></textarea>
                    </div>

                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="pegawai.php" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">Simpan Perubahan</button>
                </div>
            </form>
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