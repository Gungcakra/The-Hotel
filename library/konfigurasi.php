<?php

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
        'DB_PASSWORD' => '#Thehotel12',
    ],
];

// Fungsi untuk mendapatkan konfigurasi berdasarkan host
function getDatabaseConfig($config) {
    $host = $_SERVER['HTTP_HOST'];
    return $config[$host] ?? $config['localhost'];
}

// Ambil konfigurasi yang sesuai
$dbConfig = getDatabaseConfig($config);

// Koneksi database menggunakan mysqli
$db = mysqli_connect($dbConfig['DB_HOST'], $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD'], $dbConfig['DB_NAME']);

// Cek koneksi
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
    
    if ($params) {
        // Bind parameters if they exist
        $types = str_repeat('s', count($params)); // Assuming all parameters are strings
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Determine if the query is SELECT or not
    $queryType = strtoupper(explode(' ', trim($query))[0]);
    if ($queryType === 'SELECT') {
        // Fetch results for SELECT queries
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        // For INSERT, UPDATE, DELETE queries, return affected rows
        return mysqli_affected_rows($db); // Returns the number of affected rows
    }
}

// Cek host untuk menentukan BASE_URL
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('BASE_URL_HTML', '/thehotel');
    define('BASE_URL_PHP', dirname(__DIR__)); // Mengarah ke folder root proyek saat di localhost
} else {
    define('BASE_URL_HTML', ''); // Untuk hosting, tidak perlu prefiks
    define('BASE_URL_PHP', dirname(__DIR__)); // Mengarah ke folder root proyek saat di hosting
}

function checkUserSession() {
    // Cek apakah pengguna sudah login dan memiliki token CSRF
    if (!isset($_SESSION['userId']) || !isset($_SESSION['csrf_token'])) {
        session_destroy(); // Hapus sesi jika tidak ada
        header("Location: /thehotel"); // Redirect ke halaman /thehotel
        exit();
    }

    global $db;
    // Cek apakah userId ada di database
    $query = "SELECT * FROM user WHERE userId = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['userId']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

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
