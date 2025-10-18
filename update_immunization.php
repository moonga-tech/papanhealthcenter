<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $immunization_id = $_POST['immunization_id'];
    $child_id   = $_POST['child_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $dose_number = $_POST['dose_number'];
    $date_given = $_POST['date_given'];
    $lot_number = $_POST['lot_number']; // gikan gihapon sa readonly field
    $vaccinator = $_POST['vaccinator'];
    $place_given = $_POST['place_given'];
    $remarks    = $_POST['remarks'];

    $stmt = $conn->prepare("UPDATE child_immunizations 
        SET child_id=?, vaccine_id=?, dose_number=?, date_given=?, lot_number=?, vaccinator=?, place_given=?, remarks=? 
        WHERE immunization_id=?");
    $stmt->bind_param("iiisssssi", $child_id, $vaccine_id, $dose_number, $date_given, $lot_number, $vaccinator, $place_given, $remarks, $immunization_id);

    if ($stmt->execute()) {
        header("Location: child_immunizations.php?updated=1");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

