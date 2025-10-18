<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $prenatal_id = (int)$_GET['id'];
  
  $sql = "UPDATE prenatal_records SET archived = 1 WHERE prenatal_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $prenatal_id);
  
  if ($stmt->execute()) {
    header("Location: prenatal_records.php");
    exit();
  } else {
    echo "Error archiving prenatal record: " . $conn->error;
  }
} else {
  header("Location: prenatal_records.php");
  exit();
}
?>