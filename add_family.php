<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $family_no = $_POST['family_no'];
    $family_head = $_POST['family_head'];
    $barangay_id = $_POST['barangay_id'];

    $sql = "INSERT INTO family_number (family_no, family_head, barangay_id, date_created) 
            VALUES ('$family_no', '$family_head', '$barangay_id', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Family added successfully!'); window.location='family_number.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>

