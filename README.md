# Aplikasi Pencatatan Data Pegawai
**Sertifikasi Kompetensi BNSP - Junior Web Developer (JWD)**
**Skema:** SKK-32-10/2024

Aplikasi berbasis web untuk pencatatan dan pengelolaan data pegawai (CRUD: *Create, Read, Update, Delete*) yang dibangun dengan arsitektur terstruktur (*Separation of Concerns*), memenuhi standar 6 unit kompetensi BNSP JWD.

## Teknologi & Library
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Library CSS:** Bootstrap 5 (Lokal / Pre-existing)
- **Library JS:** jQuery 3.7.1 (Lokal / AJAX & Interaktivitas)
- **Backend:** PHP 8 (Pemrograman Terstruktur)
- **Database:** MySQL via PHP Data Objects (PDO) dengan Prepared Statements

## Struktur Proyek
```text
BNSP_PROJECT/
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   └── style.css
│   ├── js/
│   │   ├── bootstrap.bundle.min.js
│   │   ├── jquery-3.7.1.min.js
│   │   └── app.js
│   └── img/
├── config/
│   └── koneksi.php
├── pages/
│   ├── login.php
│   ├── dashboard.php
│   ├── pegawai.php
│   ├── tambah.php
│   ├── edit.php
│   └── detail.php
├── process/
│   ├── proses_login.php
│   ├── proses_logout.php
│   ├── aksi_tambah.php
│   ├── aksi_edit.php
│   └── aksi_hapus.php
├── database.sql
├── .gitignore
├── README.md
└── index.php
```

