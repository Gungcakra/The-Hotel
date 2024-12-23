<?php
session_start();
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagRoomType is set
if (isset($_POST['flagRoomType']) && $_POST['flagRoomType'] === 'add') {
    $roomTypeId = $_POST['roomTypeId'];
    $typeName = $_POST['typeName'];
    $price = $_POST['price'];

    $query = "INSERT INTO roomtypes (typeName, price) VALUES (?, ?)";

    $result = query($query, [$typeName, $price]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room Type added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Room Type."
        ]);
    }
} else if (isset($_POST['flagRoomType']) && $_POST['flagRoomType'] === 'delete') {
    $roomTypeId = $_POST['roomTypeId'];

    $query = "DELETE FROM roomtypes WHERE roomTypeId = ?";
    $result = query($query, [$roomTypeId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room Type deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Room Type: "
        ]);
    }
} else if ($_POST['flagRoomType'] && $_POST['flagRoomType'] === 'update') {
        $roomTypeId = $_POST['roomTypeId'];
        $typeName = $_POST['typeName'];
        $price = $_POST['price'];

        $query = "UPDATE roomtypes 
                SET typeName = ?, 
                    price = ?
                WHERE roomTypeId = ? ";
        $result = query($query,[$typeName,$price,$roomTypeId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Room Type updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update Room Type: "
            ]);
        }
}
