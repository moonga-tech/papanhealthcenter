<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $family_id = (int)$_GET['id'];
  
  $sql = "UPDATE family_number SET archived = 0 WHERE family_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $family_id);
  
  if ($stmt->execute()) {
    header("Location: archived_family_numbers.php");
    exit();
  } else {
    echo "Error restoring family: " . $conn->error;
  }
} else {
  header("Location: archived_family_numbers.php");
  exit();
}
?>