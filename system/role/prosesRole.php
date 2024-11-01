<?php
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
    $roleName = $_POST['roleName'];

    $query = "INSERT INTO employeeroles (roleName) VALUES (?)";

    $result = query($query, [$roleName]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Role added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Role."
        ]);
    }
} else if (isset($_POST['flag']) && $_POST['flag'] === 'delete') {
    $roleId = $_POST['roleId'];

    $query = "DELETE FROM employeeroles WHERE roleId = ?";
    $result = query($query, [$roleId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Role deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Role: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flag'] && $_POST['flag'] === 'update') {
        $roleId = $_POST['roleId'];
        $roleName = $_POST['roleName'];

        $query = "UPDATE employeeroles 
                SET roleName = ?
                WHERE roleId = ? ";
        $result = query($query,[$roleName,$roleId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Role updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update Role: " . mysqli_error($db)
            ]);
        }
}
