<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $family_id = $_GET['id'];

    $sql = "DELETE FROM family_number WHERE family_id=$family_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Family deleted successfully!'); window.location='family_number.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
$conn->close();
?>

