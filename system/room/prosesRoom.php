<?php
session_start();
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
    $roomNumber = $_POST['roomNumber'];
    $roomTypeId = $_POST['roomTypeId'];

    $query = "INSERT INTO rooms (roomNumber, roomTypeId, status) VALUES (?, ?, ?)";

    $result = query($query, [$roomNumber, $roomTypeId, 'Available']);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add room."
        ]);
    }
} else if (isset($_POST['flag']) && $_POST['flag'] === 'delete') {
    $roomId = $_POST['roomId'];

    $query = "DELETE FROM rooms WHERE roomId = ?";
    $result = query($query, [$roomId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete room: "
        ]);
    }
} else if ($_POST['flag'] && $_POST['flag'] === 'update') {
        $roomId = $_POST['roomId'];
        $roomNumber = $_POST['roomNumber'];
        $roomTypeId = $_POST['roomTypeId'];
        $status = $_POST['status'];

        $query = "UPDATE rooms 
                SET roomNumber = ?, 
                    roomTypeId = ?,
                    status = ? 
                WHERE roomId = ? ";
        $result = query($query,[$roomNumber,$roomTypeId,$status,$roomId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Room updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update room: "
            ]);
        }
}
