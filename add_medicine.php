<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_name = mysqli_real_escape_string($conn, $_POST['medicine_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = intval($_POST['stock']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);

    $sql = "INSERT INTO medicines (medicine_name, description, stock, unit, expiry_date, date_added) 
            VALUES ('$medicine_name', '$description', '$stock', '$unit', '$expiry_date', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Medicine added successfully!'); window.location.href='medicine.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

