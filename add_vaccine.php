<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vaccine_name   = $_POST['vaccine_name'];
    $description    = $_POST['description'];
    $supplier_id    = $_POST['supplier_id'];
    $quantity       = $_POST['quantity'];
    $unit           = $_POST['unit'];
    $total_doses    = $_POST['total_doses'];
    $recommended_ages = $_POST['recommended_ages'];
    $expiry_date    = $_POST['expiry_date'];
    $date_received  = $_POST['date_received'];
    $lot_number     = $_POST['lot_number'];

    $sql = "INSERT INTO vaccines (vaccine_name, description, supplier_id, quantity, unit, total_doses, recommended_ages, expiry_date, date_received, lot_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiisissss", $vaccine_name, $description, $supplier_id, $quantity, $unit, $total_doses, $recommended_ages, $expiry_date, $date_received, $lot_number);

    if ($stmt->execute()) {
        echo "<script>alert('Vaccine added successfully!'); window.location='vaccines.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

