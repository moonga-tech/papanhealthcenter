<?php
include 'db_connect.php';
$id = $_GET['id'];

// Get medicine data before deleting to restore stock
$get_data = $conn->query("SELECT medicine_id, quantity_given FROM medicine_given WHERE give_id=$id");
$data = $get_data->fetch_assoc();

$sql = "DELETE FROM medicine_given WHERE give_id=$id";
if ($conn->query($sql)) {
    // Restore stock
    $conn->query("UPDATE medicines SET stock = stock + {$data['quantity_given']} WHERE medicine_id = {$data['medicine_id']}");
    header("Location: medicine_given.php");
} else {
    echo "Error: " . $conn->error;
}
?>