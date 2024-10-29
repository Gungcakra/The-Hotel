<?php
$db = mysqli_connect("localhost","root","","thehoteldb");

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

