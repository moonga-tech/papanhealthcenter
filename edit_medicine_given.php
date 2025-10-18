<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['give_id'];
    $patient_id = $_POST['patient_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity_given'];

    // Get old quantity to restore stock
    $old_data = $conn->query("SELECT medicine_id, quantity_given FROM medicine_given WHERE give_id='$id'");
    $old = $old_data->fetch_assoc();
    
    // Restore old stock
    $conn->query("UPDATE medicines SET stock = stock + {$old['quantity_given']} WHERE medicine_id = '{$old['medicine_id']}'";
    
    // Check new stock availability
    $check_stock = $conn->query("SELECT stock FROM medicines WHERE medicine_id = '$medicine_id'");
    $current_stock = $check_stock->fetch_assoc()['stock'];
    
    if ($current_stock >= $quantity) {
        // Update record
        $sql = "UPDATE medicine_given 
                SET patient_id='$patient_id', medicine_id='$medicine_id', quantity_given='$quantity' 
                WHERE give_id='$id'";
        if ($conn->query($sql)) {
            // Deduct new stock
            $conn->query("UPDATE medicines SET stock = stock - $quantity WHERE medicine_id = '$medicine_id'");
            header("Location: medicine_given.php");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Insufficient stock! Available: $current_stock'); window.location='medicine_given.php';</script>";
    }
}
?>