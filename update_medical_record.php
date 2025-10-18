<?php

include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $record_id = $_POST['record_id'];
    $date = $_POST['date'];
    $systolic_bp = $_POST['systolic_bp'];
    $diastolic_bp = $_POST['diastolic_bp'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $pulse = $_POST['pulse'];
    $assessment = $_POST['assessment'];
    $plan = $_POST['plan'];

  
    $sql = "UPDATE medical_records 
            SET date = ?, 
                systolic_bp = ?, 
                diastolic_bp = ?, 
                height = ?, 
                weight = ?, 
                pulse = ?, 
                assessment = ?, 
                plan = ?
            WHERE record_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "siiddissi", 
        $date, 
        $systolic_bp, 
        $diastolic_bp, 
        $height, 
        $weight, 
        $pulse, 
        $assessment, 
        $plan, 
        $record_id
    );

    if ($stmt->execute()) {
      
        header("Location: medical_records.php?success=1");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

