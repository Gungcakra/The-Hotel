<?php

// Konfigurasi database untuk lingkungan DEVELOPMENT dan PRODUCTION
$config = [
    'localhost' => [
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'thehoteldb',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => '',
    ],
    'thehotel.cakra-portfolio.my.id' => [
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'u686303384_thehoteldb',
        'DB_USERNAME' => 'u686303384_thehotel',
        'DB_PASSWORD' => '#G[B/zL#S>x2b#',
    ],
];

// Fungsi untuk mendapatkan konfigurasi berdasarkan host
function getDatabaseConfig($config) {
    $host = $_SERVER['HTTP_HOST'];
    if (isset($config[$host])) {
        return $config[$host];
    }
    // Default ke konfigurasi localhost jika host tidak ditemukan
    return $config['localhost'];
}

// Ambil konfigurasi yang sesuai
$dbConfig = getDatabaseConfig($config);

// Lakukan koneksi database
$db = mysqli_connect($dbConfig['DB_HOST'], $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD'], $dbConfig['DB_NAME']);

// Periksa koneksi
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}






// LAMBDA FUNCTION FOR CONCATING CONSTANT
$constant = function (string $name) {
    return constant($name) ?? '';
};

function query($query, $params = []) {
    global $db;

    // Prepare the statement
    $stmt = mysqli_prepare($db, $query);

    // Bind parameters if any are provided
    if (!empty($params)) {
        // Dynamically bind the parameters
        $types = str_repeat("s", count($params)); // Assumes all parameters are strings; adjust if needed
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Determine if the query is SELECT or not
    $queryType = strtoupper(explode(' ', trim($query))[0]);
    if ($queryType === 'SELECT') {
        // Fetch results for SELECT queries
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    } else {
        // For INSERT, UPDATE, DELETE queries, return affected rows
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows; // Returns the number of affected rows
    }
}




define('BASE_URL_HTML', '/thehotel');
define('BASE_URL_PHP', $_SERVER['DOCUMENT_ROOT'] . '/thehotel');


function checkUserSession($db) {
    session_start();

    // Cek apakah pengguna sudah login dan memiliki token CSRF
    if (!isset($_SESSION['userId']) || !isset($_SESSION['csrf_token'])) {
        session_destroy(); // Hapus sesi jika tidak ada
        header("Location: /thehotel"); // Redirect ke halaman /thehotel
        exit();
    }

    // Cek apakah userId ada di database
    $query = "SELECT * FROM user WHERE userId = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['userId']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy(); 
        header("Location: /thehotel");
        exit();
    }
}

function encryptUrl($url) {
    return base64_encode($url);
}

function decryptUrl($encryptedUrl) {
    return base64_decode($encryptedUrl);
}


