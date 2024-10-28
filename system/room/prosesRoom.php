<?php
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
    $roomNumber = $_POST['roomNumber'];
    $roomTypeId = $_POST['roomTypeId'];

    $query = "INSERT INTO rooms (roomNumber, roomTypeId, status) VALUES ('$roomNumber', '$roomTypeId', 'available')";

    if (mysqli_query($db, $query)) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add room: " . mysqli_error($db)
        ]);
    }
} else if (isset($_POST['flag']) && $_POST['flag'] === 'delete') {
    $roomId = $_POST['roomId'];

    $query = "DELETE FROM rooms WHERE roomId = $roomId";

    if (mysqli_query($db, $query)) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete room: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flag'] && $_POST['flag'] === 'update') {
    $roomId = $_POST['roomId'];
    $roomNumber = $_POST['roomNumber'];
    $roomTypeId = $_POST['roomTypeId'];
    $status = $_POST['status'];
    
    $query = "UPDATE rooms SET roomNumber = $roomNumber, roomTypeId = $roomTypeId , status = '$status' WHERE roomId = $roomId ";
    
    if (mysqli_query($db, $query)) {
        echo json_encode([
            "status" => true,
            "pesan" => "Room updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update room: " . mysqli_error($db)
        ]);
    }
    

}
