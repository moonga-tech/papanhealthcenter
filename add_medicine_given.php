<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity_given'];

    // Check current stock
    $check_stock = "SELECT stock FROM medicines WHERE medicine_id = '$medicine_id'";
    $stock_result = $conn->query($check_stock);
    $current_stock = $stock_result->fetch_assoc()['stock'];

    if ($current_stock >= $quantity) {
        // Insert medicine given record
        $sql = "INSERT INTO medicine_given (patient_id, medicine_id, quantity_given) 
                VALUES ('$patient_id','$medicine_id','$quantity')";
        
        if ($conn->query($sql)) {
            // Update medicine stock
            $update_stock = "UPDATE medicines SET stock = stock - $quantity WHERE medicine_id = '$medicine_id'";
            $conn->query($update_stock);
            header("Location: medicine_given.php");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Insufficient stock! Available: $current_stock'); window.location='medicine_given.php';</script>";
    }
}
?>

