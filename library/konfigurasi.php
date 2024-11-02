<?php
if ($_SERVER['HTTP_HOST'] === 'localhost'){
    
    $db = mysqli_connect("localhost","root","","thehoteldb");
}else if($_SERVER['HTTP_HOST'] === 'thehotel.cakra-portfolio.my.id'){
    $db = mysqli_connect("localhost","u686303384_thehotel","#Thehotel12","u686303384_thehoteldb");

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



if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('BASE_URL_HTML', '/thehotel');
    define('BASE_URL_PHP', dirname(__DIR__)); // Mengarah ke folder root proyek saat di localhost
} else {
    define('BASE_URL_HTML', ''); // Untuk hosting, tidak perlu prefiks
    define('BASE_URL_PHP', dirname(__DIR__)); // Mengarah ke folder root proyek saat di hosting
}


function checkUserSession($db) {

    // Cek apakah pengguna sudah login dan memiliki token CSRF
    if (!isset($_SESSION['userId']) || !isset($_SESSION['csrf_token'])) {
        session_destroy(); // Hapus sesi jika tidak ada
        header("Location: " . dirname(__DIR__)); // Redirect ke halaman /thehotel
        exit();
    }

    // Cek apakah userId ada di database
    $query = "SELECT * FROM user WHERE userId = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['userId']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_destroy(); 
        header("Location: " . dirname(__DIR__));
        exit();
    }
}

function encryptUrl($url) {
    return base64_encode($url);
}

function decryptUrl($encryptedUrl) {
    return base64_decode($encryptedUrl);
}


