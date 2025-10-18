<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $patient_id = (int)$_GET['id'];
  
  // Update patient to active status
  $sql = "UPDATE patients SET archived = 0 WHERE patient_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $patient_id);
  
  if ($stmt->execute()) {
    header("Location: archived_patients.php");
    exit();
  } else {
    echo "Error restoring patient: " . $conn->error;
  }
} else {
  header("Location: archived_patients.php");
  exit();
}
?>