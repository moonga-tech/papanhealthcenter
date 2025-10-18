<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("No immunization ID provided.");
}

$id = intval($_GET['id']);

$query = $conn->prepare("
    SELECT ci.immunization_id, c.full_name, v.vaccine_name, v.lot_number,
           ci.dose_number, ci.date_given, ci.vaccinator, ci.place_given, ci.remarks
    FROM child_immunizations ci
    JOIN children c ON ci.child_id = c.child_id
    JOIN vaccines v ON ci.vaccine_id = v.vaccine_id
    WHERE ci.immunization_id = ?
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
    <title>Immunization Record - <?= htmlspecialchars($record['full_name']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .receipt { border: 2px solid #333; padding: 20px; border-radius: 10px; width: 650px; margin: auto; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; vertical-align: top; }
        .label { font-weight: bold; width: 200px; }
        .print-btn, .back-btn { 
            color: #fff; 
            padding: 8px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin: 5px; 
        }
        .print-btn { background: #27ae60; }
        .print-btn:hover { background: #219150; }
        .back-btn { background: #7f8c8d; text-decoration: none; display: inline-block; }
        .back-btn:hover { background: #636e72; }
        .center { text-align: center; margin-top: 20px; }
        @media print {
            .print-btn, .back-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Child Immunization Record</h2>
        <table>
            <tr><td class="label">Child:</td><td><?= htmlspecialchars($record['full_name']) ?></td></tr>
            <tr><td class="label">Vaccine:</td><td><?= htmlspecialchars($record['vaccine_name']) ?></td></tr>
            <tr><td class="label">Lot Number:</td><td><?= !empty($record['lot_number']) ? htmlspecialchars($record['lot_number']) : "N/A" ?></td></tr>
            <tr><td class="label">Dose Number:</td><td><?= htmlspecialchars($record['dose_number']) ?></td></tr>
            <tr><td class="label">Date Given:</td><td><?= htmlspecialchars($record['date_given']) ?></td></tr>
            <tr><td class="label">Vaccinator:</td><td><?= htmlspecialchars($record['vaccinator']) ?></td></tr>
            <tr><td class="label">Place Given:</td><td><?= htmlspecialchars($record['place_given']) ?></td></tr>
            <tr><td class="label">Remarks:</td><td><?= htmlspecialchars($record['remarks']) ?></td></tr>
        </table>
        <div class="center">
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
            <button class="print-btn" onclick="window.print()">🖨 Print</button>
        </div>
    </div>
</body>
</html>

