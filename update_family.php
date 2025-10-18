
<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $family_id = $_POST['family_id'];
    $family_no = $_POST['family_no'];
    $family_head = $_POST['family_head'];
    $barangay_id = $_POST['barangay_id'];

    $sql = "UPDATE family_number 
            SET family_no='$family_no', family_head='$family_head', barangay_id='$barangay_id' 
            WHERE family_id=$family_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Family updated successfully!'); window.location='family_number.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>
