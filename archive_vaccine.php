<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $vaccine_id = (int)$_GET['id'];
  
  $sql = "UPDATE vaccines SET archived = 1 WHERE vaccine_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $vaccine_id);
  
  if ($stmt->execute()) {
    header("Location: vaccines.php");
    exit();
  } else {
    echo "Error archiving vaccine: " . $conn->error;
  }
} else {
  header("Location: vaccines.php");
  exit();
}
?>