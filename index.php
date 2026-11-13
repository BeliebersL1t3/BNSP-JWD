<?php
session_start();
// loggedin, ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}

// nologged, ke halaman login
header("Location: pages/login.php");
exit();