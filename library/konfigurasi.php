<?php

$config = [
    'localhost' => [
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'thehoteldb',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => '',
    ],
    'thehotel.cakra-portfolio.my.id' => [
        'DB_HOST' => 'localhost', // Hostname baru
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

// Koneksi database menggunakan PDO
try {
    $dsn = "mysql:host={$dbConfig['DB_HOST']};port=3306;dbname={$dbConfig['DB_NAME']};charset=utf8mb4";

    $db = new PDO($dsn, $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD']);
    // Set mode error PDO untuk menampilkan exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// LAMBDA FUNCTION FOR CONCATING CONSTANT
$constant = function (string $name) {
    return constant($name) ?? '';
};

function query($query, $params = []) {
    global $db;

    // Prepare the statement
    $stmt = $db->prepare($query);

    // Execute the query with parameters if any are provided
    $stmt->execute($params);

    // Determine if the query is SELECT or not
    $queryType = strtoupper(explode(' ', trim($query))[0]);
    if ($queryType === 'SELECT') {
        // Fetch results for SELECT queries
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // For INSERT, UPDATE, DELETE queries, return affected rows
        return $stmt->rowCount(); // Returns the number of affected rows
    }
}

// Cek host untuk menentukan BASE_URL
// Cek host untuk menentukan BASE_URL
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('BASE_URL_HTML', dirname(__FILE__));;
    define('BASE_URL_PHP', dirname(__FILE__)); // Mengarah ke folder root proyek saat di localhost
} else {
    define('BASE_URL_HTML', ''); // Untuk hosting, tidak perlu prefiks
    define('BASE_URL_PHP', dirname(__DIR__)); // Mengarah ke folder root proyek saat di hosting
}



function checkUserSession($db) {
    // session_start();
    var_dump($_SESSION['userId']);
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
