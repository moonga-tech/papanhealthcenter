<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $child_id = $_GET['id'];
    $sql = "DELETE FROM children WHERE child_id='$child_id'";
    if ($conn->query($sql)) {
        header("Location: children.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

