<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['supplier_name'];
  $contact = $_POST['contact_person'];
  $phone = $_POST['phone_number'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("INSERT INTO vaccine_suppliers (supplier_name, contact_person, phone_number, address) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $name, $contact, $phone, $address);
  $stmt->execute();
}
header("Location: vaccine_suppliers.php");
exit;
?>

