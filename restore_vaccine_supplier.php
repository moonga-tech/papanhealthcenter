<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $supplier_id = (int)$_GET['id'];
  
  $sql = "UPDATE vaccine_suppliers SET archived = 0 WHERE supplier_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $supplier_id);
  
  if ($stmt->execute()) {
    header("Location: archived_vaccine_suppliers.php");
    exit();
  } else {
    echo "Error restoring supplier: " . $conn->error;
  }
} else {
  header("Location: archived_vaccine_suppliers.php");
  exit();
}
?>