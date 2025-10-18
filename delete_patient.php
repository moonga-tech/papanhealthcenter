<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';


if (isset($_GET['id'])) {
  $id = intval($_GET['id']);


  $check = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
  $check->bind_param("i", $id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
 
    $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
     
      header("Location: patients.php?message=deleted");
      exit();
    } else {
      echo "Error deleting record: " . $conn->error;
    }
  } else {
  
    header("Location: patients.php?error=notfound");
    exit();
  }
} else {

  header("Location: patients.php");
  exit();
}
?>

