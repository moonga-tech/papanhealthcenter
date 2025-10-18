<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $barangay_id = (int)$_GET['id'];
  
  $sql = "UPDATE barangay SET archived = 1 WHERE barangay_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $barangay_id);
  
  if ($stmt->execute()) {
    header("Location: barangay.php");
    exit();
  } else {
    echo "Error archiving barangay: " . $conn->error;
  }
} else {
  header("Location: barangay.php");
  exit();
}
?>