<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM child_immunizations WHERE immunization_id=$id");
}
header("Location: child_immunizations.php");
?>

