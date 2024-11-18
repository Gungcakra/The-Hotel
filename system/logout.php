<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../library/konfigurasi.php";
checkUserSession($db);

if (isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
    // Hapus sesi
    session_destroy();
    // Cek BASE_URL_HTML
    error_log("Redirecting to: " . BASE_URL_HTML);
    header("Location: " . BASE_URL_HTML);
    exit();
} else {
    // Token tidak valid, redirect
    header("Location: " . BASE_URL_HTML);
    exit();
}