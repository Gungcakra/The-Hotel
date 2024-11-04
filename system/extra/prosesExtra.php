<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagExtra is set
if (isset($_POST['flagExtra']) && $_POST['flagExtra'] === 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $query = "INSERT INTO extra (name, price) VALUES (?, ?)";

    $result = query($query, [$name, $price]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Extra added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Extra."
        ]);
    }
} else if (isset($_POST['flagExtra']) && $_POST['flagExtra'] === 'delete') {
    $extraId = $_POST['extraId'];

    $query = "DELETE FROM extra WHERE extraId = ?";
    $result = query($query, [$extraId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Extra deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Extra: "
        ]);
    }
} else if ($_POST['flagExtra'] && $_POST['flagExtra'] === 'update') {
        $extraId = $_POST['extraId'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $query = "UPDATE extra 
                SET name = ?, 
                    price = ?
                WHERE extraId = ? ";
        $result = query($query,[$name, $price, $extraId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Extra updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update Extra: "
            ]);
        }
}
