<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$query = "SELECT mr.*, pi.full_name 
          FROM medical_records mr 
          JOIN patients pi ON mr.patient_id = pi.patient_id 
          WHERE mr.archived = 1
          ORDER BY mr.date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Medical Records</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .btn-restore {
      background: #28a745;
      color: #fff;
    }
    .btn-restore:hover {
      background: #218838;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Archived Medical Records</h2>
    <a href="medical_records.php" class="btn btn-back">← Back to Active Records</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient</th>
          <th>Date</th>
          <th>BP</th>
          <th>Height (cm)</th>
          <th>Weight (kg)</th>
          <th>Pulse</th>
          <th>Assessment</th>
          <th>Plan</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['record_id']); ?></td>
              <td><?= htmlspecialchars($row['full_name']); ?></td>
              <td><?= htmlspecialchars($row['date']); ?></td>
              <td><?= htmlspecialchars($row['systolic_bp']); ?>/<?= htmlspecialchars($row['diastolic_bp']); ?></td>
              <td><?= htmlspecialchars($row['height']); ?></td>
              <td><?= htmlspecialchars($row['weight']); ?></td>
              <td><?= htmlspecialchars($row['pulse']); ?></td>
              <td><?= htmlspecialchars($row['assessment']); ?></td>
              <td><?= htmlspecialchars($row['plan']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_medical_record.php?record_id=<?= $row['record_id']; ?>" onclick="return confirm('Restore this record?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="10" style="text-align:center;">No archived medical records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>