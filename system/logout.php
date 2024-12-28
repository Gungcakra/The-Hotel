<?php
include_once ".././library/konfigurasi.php";

session_start();
session_unset();
session_destroy();

header("Location: https://thehotel.cakra-portfolio.my.id/");
exit;
