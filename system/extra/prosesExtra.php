<?php
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
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
} else if (isset($_POST['flag']) && $_POST['flag'] === 'delete') {
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
            "pesan" => "Failed to delete Extra: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flag'] && $_POST['flag'] === 'update') {
        $extraId = $_POST['extraId'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $query = "UPDATE extra 
                SET name = ?, 
                    price = ?
                WHERE extraId = ? ";
        $result = query($query,[$name,$price,$extraId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Extra updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update Extra: " . mysqli_error($db)
            ]);
        }
}
