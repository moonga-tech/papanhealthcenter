<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO barangay (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        header("Location: barangay.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

