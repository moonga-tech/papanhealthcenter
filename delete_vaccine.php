<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("DELETE FROM vaccines WHERE vaccine_id=$id");
}
header("Location: vaccines.php");
exit;
?>

