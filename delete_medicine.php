<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM medicines WHERE medicine_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: medicine.php?success=deleted");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
}
?>

