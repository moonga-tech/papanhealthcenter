<?php
include("db_connect.php");

$id = $_GET['id'];
$sql = "DELETE FROM vaccine_suppliers WHERE supplier_id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: supplier.php");
} else {
    echo "Error deleting: " . mysqli_error($conn);
}
?>

