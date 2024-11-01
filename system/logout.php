<?php
require_once "../library/konfigurasi.php";
checkUserSession($db);

if (isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    // Hapus sesi
    session_destroy();
    header("Location: /thehotel");
    exit();
} else {
    // Token tidak valid, redirect
    header("Location: /thehotel");
    exit();
}
