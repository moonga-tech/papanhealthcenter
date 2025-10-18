<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['supplier_id'];
  $name = $_POST['supplier_name'];
  $contact = $_POST['contact_person'];
  $phone = $_POST['phone_number'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("UPDATE vaccine_suppliers SET supplier_name=?, contact_person=?, phone_number=?, address=? WHERE supplier_id=?");
  $stmt->bind_param("ssssi", $name, $contact, $phone, $address, $id);
  $stmt->execute();
}
header("Location: vaccine_suppliers.php");
exit;
?>

