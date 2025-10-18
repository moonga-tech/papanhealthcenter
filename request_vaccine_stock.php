<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vaccine_id = $_POST['vaccine_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO vaccine_stock_requests (vaccine_id, quantity, request_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $vaccine_id, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Vaccine stock request submitted successfully!'); window.location.href='vaccines.php';</script>";
    } else {
        echo "<script>alert('Error submitting request.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>

