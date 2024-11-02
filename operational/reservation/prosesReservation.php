<?php
session_start();

require_once "../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flag is set
if (isset($_POST['flag']) && $_POST['flag'] === 'add') {
    $userId = $_POST['userId'];
    $guestId = $_POST['guestId'];
    $roomId = $_POST['roomId'];
    $extraId = $_POST['extraId'];
    $adult = $_POST['adult'];
    $child = $_POST['child'];
    $rentang = $_POST['rentang'];
    $totalPrice = $_POST['totalPrice'];
    list($checkInDate, $checkOutDate) = explode(" - ", $rentang);

    $checkInDate = date("Y-m-d", strtotime($checkInDate));
    $checkOutDate = date("Y-m-d", strtotime($checkOutDate));
    $query = "INSERT INTO reservations (guestId, roomId, extraId, adult, child, checkInDate, checkOutDate, userInputId, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $result = query($query, [$guestId, $roomId, $extraId, $adult, $child, $checkInDate, $checkOutDate, $userId, $totalPrice]);

    if ($result > 0) {
        $queryRoom = query("UPDATE rooms SET status = ? WHERE roomId = ?",["Booked", $roomId]);
        echo json_encode([
            "status" => true,
            "pesan" => "Reservation added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Reservation."
        ]);
    }
} 
// else if (isset($_POST['flag']) && $_POST['flag'] === 'delete') {
//     $employeeId = $_POST['employeeId'];

//     $query = "DELETE FROM employees WHERE employeeId = ?";
//     $result = query($query, [$employeeId]);

//     if ($result > 0) {
//         echo json_encode([
//             "status" => true,
//             "pesan" => "Employee deleted successfully!"
//         ]);
//     } else {
//         echo json_encode([
//             "status" => false,
//             "pesan" => "Failed to delete Employee: " . mysqli_error($db)
//         ]);
//     }
// } else if ($_POST['flag'] && $_POST['flag'] === 'update') {
//     $employeeId = $_POST['employeeId'];
//     $name = $_POST['name'];
//     $roleId = $_POST['roleId'];
//     $phoneNumber = $_POST['phoneNumber'];
//     $email = $_POST['email'];
//     $address = $_POST['address'];
    
//     $checkQuery = "SELECT COUNT(*) as count FROM employees WHERE email = ? AND employeeId != ?";
//     $checkResult = query($checkQuery, [$email, $employeeId]);
    
//     if ($checkResult[0]['count'] > 0) {
//         echo json_encode([
//             "status" => false,
//             "pesan" => "The email is already used by another employee."
//         ]);
//         exit;
//     }
//     $query = "UPDATE employees 
//               SET name = ?, 
//                   roleId = ?, 
//                   phoneNumber = ?, 
//                   email = ?, 
//                   address = ? 
//               WHERE employeeId = ?";
//     $result = query($query, [$name, $roleId, $phoneNumber, $email, $address, $employeeId]);
    
//     if ($result) {
//         echo json_encode([
//             "status" => true,
//             "pesan" => "Employee updated successfully!"
//         ]);
//     } else {
//         echo json_encode([
//             "status" => false,
//             "pesan" => "Failed to update employee: " . mysqli_error($db)
//         ]);
//     }
    
// }
