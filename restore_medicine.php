<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $medicine_id = (int)$_GET['id'];
  
  $sql = "UPDATE medicines SET archived = 0 WHERE medicine_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $medicine_id);
  
  if ($stmt->execute()) {
    header("Location: archived_medicines.php");
    exit();
  } else {
    echo "Error restoring medicine: " . $conn->error;
  }
} else {
  header("Location: archived_medicines.php");
  exit();
}
?>