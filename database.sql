-- buat database
CREATE DATABASE IF NOT EXISTS `db_kepegawaian` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `db_kepegawaian`;

-- tabel user
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- tabel pegawai
CREATE TABLE IF NOT EXISTS `pegawai` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nip` VARCHAR(20) NOT NULL UNIQUE,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `no_hp` VARCHAR(20) NOT NULL,
    `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') NOT NULL,
    `jabatan` VARCHAR(50) NOT NULL,
    `alamat` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- akun admin
INSERT INTO `users` (`username`, `password`, `nama_lengkap`) VALUES
('admin', '$2y$10$tM2a6Y0f9q9P1oXkR1Hl5eZ0sT9s0uD1mU8qM4xQ7.7b7Bq7gqQy.', 'Administrator BNSP')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- data dummy user
INSERT INTO `pegawai` (`nip`, `nama_lengkap`, `email`, `no_hp`, `jenis_kelamin`, `jabatan`, `alamat`) VALUES
('PEG-001', 'Ahmad Pratama', 'ahmad.pratama@example.com', '081234567890', 'Laki-laki', 'Manager IT', 'Jl. Merdeka No. 10, Jakarta'),
('PEG-002', 'Siti Rahmawati', 'siti.rahmawati@example.com', '081298765432', 'Perempuan', 'Staff HRD', 'Jl. Sudirman No. 45, Bandung'),
('PEG-003', 'Budi Santoso', 'budi.santoso@example.com', '081377889900', 'Laki-laki', 'Web Developer', 'Jl. Diponegoro No. 12, Surabaya')
ON DUPLICATE KEY UPDATE `nip`=`nip`;