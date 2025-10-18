<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $child_id   = $_POST['child_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $dose_number = $_POST['dose_number'];
    $date_given = $_POST['date_given'];
    $lot_number = $_POST['lot_number']; // gikan sa readonly field
    $vaccinator = $_POST['vaccinator'];
    $place_given = $_POST['place_given'];
    $remarks    = $_POST['remarks'];

    $stmt = $conn->prepare("INSERT INTO child_immunizations 
        (child_id, vaccine_id, dose_number, date_given, lot_number, vaccinator, place_given, remarks) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisssss", $child_id, $vaccine_id, $dose_number, $date_given, $lot_number, $vaccinator, $place_given, $remarks);

    if ($stmt->execute()) {
        header("Location: child_immunizations.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

