<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $visit_date = $_POST['visit_date'];
    $lmp = $_POST['lmp'];
    $edd = $_POST['edd'];
    $gestational_age = $_POST['gestational_age'];
    $blood_pressure = $_POST['blood_pressure'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $fetal_heart_rate = $_POST['fetal_heart_rate'];
    $fundal_height = $_POST['fundal_height'];
    $complaints = $_POST['complaints'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $next_visit = $_POST['next_visit'];

    $sql = "INSERT INTO prenatal_records 
            (patient_id, visit_date, lmp, edd, gestational_age, blood_pressure, weight, height, fetal_heart_rate, fundal_height, complaints, diagnosis, treatment, next_visit) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssddsssss",
        $patient_id, $visit_date, $lmp, $edd, $gestational_age, $blood_pressure,
        $weight, $height, $fetal_heart_rate, $fundal_height, $complaints,
        $diagnosis, $treatment, $next_visit
    );

    if ($stmt->execute()) {
        header("Location: prenatal_records.php?success=Prenatal record added successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

