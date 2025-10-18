<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $medicine_id = (int)$_GET['id'];
  
  $sql = "UPDATE medicines SET archived = 1 WHERE medicine_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $medicine_id);
  
  if ($stmt->execute()) {
    header("Location: medicine.php");
    exit();
  } else {
    echo "Error archiving medicine: " . $conn->error;
  }
} else {
  header("Location: medicine.php");
  exit();
}
?>