<?php
include_once "konfigurasi.php";
function logOut(){

session_destroy();

header('location: ' . BASE_URL_HTML);
}