<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $immunization_id = (int)$_GET['id'];
  
  $sql = "UPDATE child_immunizations SET archived = 0 WHERE immunization_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $immunization_id);
  
  if ($stmt->execute()) {
    header("Location: archived_child_immunizations.php");
    exit();
  } else {
    echo "Error restoring immunization: " . $conn->error;
  }
} else {
  header("Location: archived_child_immunizations.php");
  exit();
}
?>