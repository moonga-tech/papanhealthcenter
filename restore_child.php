<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $child_id = (int)$_GET['id'];
  
  $sql = "UPDATE children SET archived = 0 WHERE child_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $child_id);
  
  if ($stmt->execute()) {
    header("Location: archived_children.php");
    exit();
  } else {
    echo "Error restoring child: " . $conn->error;
  }
} else {
  header("Location: archived_children.php");
  exit();
}
?>