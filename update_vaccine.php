<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['vaccine_id']);
    $vaccine_name = $_POST['vaccine_name'];
    $description = $_POST['description'];
    $supplier_id = $_POST['supplier_id'];
    $quantity = $_POST['quantity'];
    $total_doses = $_POST['total_doses'];
    $recommended_ages = $_POST['recommended_ages'];
    $expiry_date = $_POST['expiry_date'];
    $date_received = $_POST['date_received'];
    $lot_number = $_POST['lot_number'];

    $sql = "UPDATE vaccines 
            SET vaccine_name=?, description=?, supplier_id=?, quantity=?, 
                total_doses=?, recommended_ages=?, expiry_date=?, 
                date_received=?, lot_number=?
            WHERE vaccine_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiissssi", $vaccine_name, $description, $supplier_id, $quantity, 
                                     $total_doses, $recommended_ages, $expiry_date, 
                                     $date_received, $lot_number, $id);
    if($stmt->execute()){
        echo "<script>alert('Vaccine updated successfully!');window.location='vaccines.php';</script>";
    } else {
        echo "<script>alert('Error updating vaccine.');window.location='vaccines.php';</script>";
    }
}
?>


