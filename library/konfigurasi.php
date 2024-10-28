<?php
$db = mysqli_connect("localhost","root","","thehoteldb");

// LAMBDA FUNCTION FOR CONCATING CONSTANT
$constant = function (string $name) {
    return constant($name) ?? '';
};

function query($query){
    global $db;

    $result = mysqli_query($db, $query);
    $rows = [];

    while($row = mysqli_fetch_assoc($result)){
        $rows [] = $row;
    }
    return $rows;
}


define('BASE_URL_HTML', '/thehotel');
define('BASE_URL_PHP', $_SERVER['DOCUMENT_ROOT'] . '/thehotel');


function checkUserSession($db) {
    session_start();

    // Cek apakah pengguna sudah login dan memiliki token CSRF
    if (!isset($_SESSION['idUser']) || !isset($_SESSION['csrf_token'])) {
        session_destroy(); // Hapus sesi jika tidak ada
        header("Location: /thehotel"); // Redirect ke halaman /thehotel
        exit();
    }

    // Cek apakah idUser ada di database
    $query = "SELECT * FROM user WHERE idUser = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['idUser']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy(); 
        header("Location: /thehotel");
        exit();
    }
}

