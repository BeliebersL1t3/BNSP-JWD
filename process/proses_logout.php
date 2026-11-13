<?php
session_start();

// hapus data dari array:session
$_SESSION = [];

// hapus session cookie kalau ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// hapus sesi di server
session_destroy();

// balik ke login
header("Location: ../pages/login.php?pesan=logout");
exit();