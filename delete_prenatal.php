<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $prenatal_id = $_GET['id'];

    $sql = "DELETE FROM prenatal_records WHERE prenatal_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prenatal_id);

    if ($stmt->execute()) {
        header("Location: prenatal_records.php?success=Prenatal record deleted successfully");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>

