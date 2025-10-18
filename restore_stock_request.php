<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $request_id = (int)$_GET['id'];
  
  $sql = "UPDATE stock_request SET archived = 0 WHERE request_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $request_id);
  
  if ($stmt->execute()) {
    header("Location: archived_stock_requests.php");
    exit();
  } else {
    echo "Error restoring request: " . $conn->error;
  }
} else {
  header("Location: archived_stock_requests.php");
  exit();
}
?>