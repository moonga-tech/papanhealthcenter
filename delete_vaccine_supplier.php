<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $conn->prepare("DELETE FROM vaccine_suppliers WHERE supplier_id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}
header("Location: vaccine_suppliers.php");
exit;
?>

