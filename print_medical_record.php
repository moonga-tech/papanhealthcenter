<?php
include 'db_connect.php';

if (!isset($_GET['record_id'])) {
    die("No record ID provided.");
}

$id = intval($_GET['record_id']);

$query = $conn->prepare("
    SELECT mr.*, p.full_name
    FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.patient_id
    WHERE mr.record_id = ?
");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    die("Record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Record - <?= htmlspecialchars($record['full_name']) ?></title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; }
    .receipt { border: 2px solid #333; padding: 20px; border-radius: 10px; width: 700px; margin: auto; }
    h2 { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 8px; vertical-align: top; }
    .label { font-weight: bold; width: 200px; }
    .btn { 
      background: #007bff; 
      color: white; 
      padding: 8px 15px; 
      border: none; 
      border-radius: 5px; 
      cursor: pointer; 
      margin: 10px 5px; 
      text-decoration: none;
      display: inline-block;
    }
    .btn:hover { background: #0056b3; }
    @media print {
      .btn { display: none; }
    }
    .center { text-align: center; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="receipt">
    <h2>Patient Medical Record</h2>
    <table>
      <tr><td class="label">Patient:</td><td><?= htmlspecialchars($record['full_name']) ?></td></tr>
      <tr><td class="label">Date:</td><td><?= htmlspecialchars($record['date']) ?></td></tr>
      <tr><td class="label">Blood Pressure:</td><td><?= htmlspecialchars($record['systolic_bp']) ?>/<?= htmlspecialchars($record['diastolic_bp']) ?></td></tr>
      <tr><td class="label">Height:</td><td><?= htmlspecialchars($record['height']) ?> cm</td></tr>
      <tr><td class="label">Weight:</td><td><?= htmlspecialchars($record['weight']) ?> kg</td></tr>
      <tr><td class="label">Pulse:</td><td><?= htmlspecialchars($record['pulse']) ?></td></tr>
      <tr><td class="label">Assessment:</td><td><?= htmlspecialchars($record['assessment']) ?></td></tr>
      <tr><td class="label">Plan:</td><td><?= htmlspecialchars($record['plan']) ?></td></tr>
    </table>
    <div class="center">
      <button class="btn" onclick="window.print()">🖨 Print</button>
      <a href="medical_records.php" class="btn">⬅ Back</a>
    </div>
  </div>
</body>
</html>

