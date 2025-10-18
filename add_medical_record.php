<?php
include 'db_connect.php'; // connect sa imong database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $systolic_bp = $_POST['systolic_bp'];
    $diastolic_bp = $_POST['diastolic_bp'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $pulse = $_POST['pulse'];
    $assessment = $_POST['assessment'];
    $plan = $_POST['plan'];

    // Insert query
    $sql = "INSERT INTO medical_records 
            (patient_id, date, systolic_bp, diastolic_bp, height, weight, pulse, assessment, plan) 
            VALUES 
            ('$patient_id', '$date', '$systolic_bp', '$diastolic_bp', '$height', '$weight', '$pulse', '$assessment', '$plan')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Medical record saved successfully!');
                window.location.href='medical_records.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>

