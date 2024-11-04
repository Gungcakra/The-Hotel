<?php
session_start();
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagGuest is set
if (isset($_POST['flagGuest']) && $_POST['flagGuest'] === 'add') {
    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $query = "INSERT INTO guests (name, phoneNumber, email, address) VALUES (?, ?, ?, ?)";

    $result = query($query, [$name, $phoneNumber, $email, $address]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Guest added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Guest."
        ]);
    }
} else if (isset($_POST['flagGuest']) && $_POST['flagGuest'] === 'delete') {
    $guestId = $_POST['guestId'];

    $query = "DELETE FROM guests WHERE guestId = ?";
    $result = query($query, [$guestId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Guest deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Guest: "
        ]);
    }
} else if ($_POST['flagGuest'] && $_POST['flagGuest'] === 'update') {
    $guestId = $_POST['guestId'];
    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    $query = "UPDATE guests 
              SET name = ?,
                  phoneNumber = ?, 
                  email = ?, 
                  address = ? 
              WHERE guestId = ?";

    $result = query($query, [$name, $phoneNumber, $email, $address, $guestId]);
    
    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Guest updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update Guest: "
        ]);
    }
    
}
