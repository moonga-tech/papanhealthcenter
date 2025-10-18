<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $family_id = (int)$_GET['id'];
  
  $sql = "UPDATE family_number SET archived = 1 WHERE family_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $family_id);
  
  if ($stmt->execute()) {
    header("Location: family_number.php");
    exit();
  } else {
    echo "Error archiving family: " . $conn->error;
  }
} else {
  header("Location: family_number.php");
  exit();
}
?>