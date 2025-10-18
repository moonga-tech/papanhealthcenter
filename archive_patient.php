<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $patient_id = (int)$_GET['id'];
  
  // Update patient to archived status
  $sql = "UPDATE patients SET archived = 1 WHERE patient_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $patient_id);
  
  if ($stmt->execute()) {
    header("Location: patients.php");
    exit();
  } else {
    echo "Error archiving patient: " . $conn->error;
  }
} else {
  header("Location: patients.php");
  exit();
}
?>