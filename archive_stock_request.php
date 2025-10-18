<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $request_id = (int)$_GET['id'];
  
  $sql = "UPDATE stock_request SET archived = 1 WHERE request_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $request_id);
  
  if ($stmt->execute()) {
    header("Location: view_stock_requests.php");
    exit();
  } else {
    echo "Error archiving request: " . $conn->error;
  }
} else {
  header("Location: view_stock_requests.php");
  exit();
}
?>