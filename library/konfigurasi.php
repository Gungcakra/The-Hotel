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

