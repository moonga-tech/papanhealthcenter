<?php
include 'db_connect.php';

if (isset($_GET['record_id'])) {
    $record_id = $_GET['record_id'];

    $sql = "DELETE FROM medical_records WHERE record_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $record_id);

    if ($stmt->execute()) {
        echo "<script>alert('Medical record deleted successfully!'); window.location='medical_records.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>

