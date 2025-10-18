<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM barangay WHERE barangay_id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: barangay.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

