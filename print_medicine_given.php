<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$id = intval($_GET['id']);

// Fetch medicine given details
$sql = "SELECT mg.give_id, p.full_name AS patient_name, m.medicine_name, mg.quantity_given, mg.date_given
        FROM medicine_given mg
        JOIN patients p ON mg.patient_id = p.patient_id
        JOIN medicines m ON mg.medicine_id = m.medicine_id
        WHERE mg.give_id = $id";
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
  <title>Print Medicine Record</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .receipt {
      border: 2px solid #333;
      padding: 20px;
      max-width: 500px;
      margin: auto;
      border-radius: 10px;
    }
    h2 { text-align: center; color: #007bff; }
    .info { margin: 15px 0; }
    .info strong { display: inline-block; width: 120px; }
    .btn-container {
      text-align: center;
      margin-top: 20px;
    }
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
    <h2>Medicine Given Receipt</h2>
    <div class="info"><strong>ID:</strong> <?= $row['give_id']; ?></div>
    <div class="info"><strong>Patient:</strong> <?= $row['patient_name']; ?></div>
    <div class="info"><strong>Medicine:</strong> <?= $row['medicine_name']; ?></div>
    <div class="info"><strong>Quantity:</strong> <?= $row['quantity_given']; ?></div>
    <div class="info"><strong>Date Given:</strong> <?= $row['date_given']; ?></div>
  </div>

  <div class="btn-container">
    <button class="btn-print" onclick="window.print()">🖨 Print</button>
    <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
