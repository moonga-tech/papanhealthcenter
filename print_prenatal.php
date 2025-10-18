<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$id = intval($_GET['id']);

// Fetch prenatal record
$sql = "SELECT pr.*, p.full_name 
        FROM prenatal_records pr
        JOIN patients p ON pr.patient_id = p.patient_id
        WHERE pr.prenatal_id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Record not found.");
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print Prenatal Record</title>
<style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .receipt {
        border: 2px solid #333;
        padding: 20px;
        max-width: 700px;
        margin: auto;
        border-radius: 10px;
    }
    h2 { text-align: center; color: #007bff; }
    .info { margin: 8px 0; }
    .info strong { display: inline-block; width: 180px; }
    .btn-container { text-align: center; margin-top: 20px; }
    .btn-print, .btn-back {
        padding: 8px 15px;
        margin: 5px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: #fff;
        font-size: 14px;
    }
    .btn-print { background: #007bff; }
    .btn-print:hover { background: #0056b3; }
    .btn-back { background: #6c757d; text-decoration: none; }
    .btn-back:hover { background: #545b62; }

    /* Hide buttons when printing */
    @media print {
        .btn-container { display: none; }
    }
</style>
</head>
<body>
<div class="receipt">
    <h2>Prenatal Record</h2>
    <div class="info"><strong>ID:</strong> <?= $row['prenatal_id']; ?></div>
    <div class="info"><strong>Patient:</strong> <?= $row['full_name']; ?></div>
    <div class="info"><strong>Visit Date:</strong> <?= $row['visit_date']; ?></div>
    <div class="info"><strong>LMP:</strong> <?= $row['lmp']; ?></div>
    <div class="info"><strong>EDD:</strong> <?= $row['edd']; ?></div>
    <div class="info"><strong>Gestational Age:</strong> <?= $row['gestational_age']; ?></div>
    <div class="info"><strong>Blood Pressure:</strong> <?= $row['blood_pressure']; ?></div>
    <div class="info"><strong>Weight:</strong> <?= $row['weight']; ?> kg</div>
    <div class="info"><strong>Height:</strong> <?= $row['height']; ?> cm</div>
    <div class="info"><strong>Fetal Heart Rate:</strong> <?= $row['fetal_heart_rate']; ?></div>
    <div class="info"><strong>Fundal Height:</strong> <?= $row['fundal_height']; ?></div>
    <div class="info"><strong>Complaints:</strong> <?= nl2br($row['complaints']); ?></div>
    <div class="info"><strong>Diagnosis:</strong> <?= nl2br($row['diagnosis']); ?></div>
    <div class="info"><strong>Treatment:</strong> <?= nl2br($row['treatment']); ?></div>
    <div class="info"><strong>Next Visit:</strong> <?= $row['next_visit']; ?></div>
</div>

<div class="btn-container">
    <button class="btn-print" onclick="window.print()">🖨 Print</button>
    <a href="prenatal_records.php" class="btn-back">⬅ Back</a>
</div>
</body>
</html>

