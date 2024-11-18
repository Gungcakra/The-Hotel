<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once ".././library/konfigurasi.php";

checkUserSession($db);

session_destroy();

header('location: ' . BASE_URL_HTML);