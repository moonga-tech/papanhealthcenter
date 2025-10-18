<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $give_id = (int)$_GET['id'];
  
  $sql = "UPDATE medicine_given SET archived = 0 WHERE give_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $give_id);
  
  if ($stmt->execute()) {
    header("Location: archived_medicine_given.php");
    exit();
  } else {
    echo "Error restoring record: " . $conn->error;
  }
} else {
  header("Location: archived_medicine_given.php");
  exit();
}
?>