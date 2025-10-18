<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT mg.give_id, p.full_name AS patient_name, m.medicine_name, mg.quantity_given, mg.date_given
        FROM medicine_given mg
        JOIN patients p ON mg.patient_id = p.patient_id
        JOIN medicines m ON mg.medicine_id = m.medicine_id
        WHERE mg.archived = 1
        ORDER BY mg.date_given DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Medicine Given Records</title>
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
    <h2>Archived Medicine Given Records</h2>
    <a href="medicine_given.php" class="btn btn-back">← Back to Active Records</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient</th>
          <th>Medicine</th>
          <th>Quantity</th>
          <th>Date Given</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['give_id']); ?></td>
              <td><?= htmlspecialchars($row['patient_name']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['quantity_given']); ?></td>
              <td><?= htmlspecialchars($row['date_given']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_medicine_given.php?id=<?= $row['give_id']; ?>" onclick="return confirm('Are you sure you want to restore this record?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" style="text-align:center;">No archived records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>