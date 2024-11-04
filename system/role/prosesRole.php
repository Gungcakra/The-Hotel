<?php
session_start();
require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagRole is set
if (isset($_POST['flagRole']) && $_POST['flagRole'] === 'add') {
    $roleName = $_POST['roleName'];
    $salary = $_POST['salary'];

    $query = "INSERT INTO employeeroles (roleName,salary) VALUES (?,?)";

    $result = query($query, [$roleName,$salary]);

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
} else if (isset($_POST['flagRole']) && $_POST['flagRole'] === 'delete') {
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
            "pesan" => "Failed to delete Role: " 
        ]);
    }
} else if ($_POST['flagRole'] && $_POST['flagRole'] === 'update') {
        $roleId = $_POST['roleId'];
        $roleName = $_POST['roleName'];
        $salary = $_POST['salary'];

        $query = "UPDATE employeeroles 
                SET roleName = ?,
                      salary = ? 
                WHERE roleId = ? ";
        $result = query($query,[$roleName,$salary,$roleId]);
        if ($result) {
            echo json_encode([
                "status" => true,
                "pesan" => "Role updated successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to update Role: " 
            ]);
        }
}
