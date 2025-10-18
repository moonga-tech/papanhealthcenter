<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['barangay_id'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE barangay SET name=? WHERE barangay_id=?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        header("Location: barangay.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

