<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_id = intval($_POST['medicine_id']);
    $medicine_name = mysqli_real_escape_string($conn, $_POST['medicine_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = intval($_POST['stock']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    $sql = "UPDATE medicines 
            SET medicine_name='$medicine_name', description='$description', stock='$stock', expiry_date='$expiry_date'
            WHERE medicine_id=$medicine_id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Medicine updated successfully!'); window.location.href='medicine.php';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

