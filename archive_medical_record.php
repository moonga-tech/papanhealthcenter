<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['record_id'])) {
  $record_id = (int)$_GET['record_id'];
  
  $sql = "UPDATE medical_records SET archived = 1 WHERE record_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $record_id);
  
  if ($stmt->execute()) {
    header("Location: medical_records.php");
    exit();
  } else {
    echo "Error archiving medical record: " . $conn->error;
  }
} else {
  header("Location: medical_records.php");
  exit();
}
?>