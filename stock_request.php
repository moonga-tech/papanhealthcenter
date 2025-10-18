<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];

    if (!empty($medicine_id) && !empty($quantity)) {
        $stmt = $conn->prepare("INSERT INTO stock_request (medicine_id, quantity) VALUES (?, ?)");
        $stmt->bind_param("ii", $medicine_id, $quantity);

        if ($stmt->execute()) {
            echo "<script>alert('Stock request submitted successfully!'); window.location.href='view_stock_requests.php';</script>";
        } else {
            echo "<script>alert('Error submitting stock request.'); window.location.href='medicine.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please select medicine and enter quantity.'); window.location.href='medicine.php';</script>";
    }
}
$conn->close();
?>

