<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT pr.*, p.full_name 
        FROM prenatal_records pr
        JOIN patients p ON pr.patient_id = p.patient_id
        WHERE pr.archived = 1
        ORDER BY pr.visit_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Prenatal Records</title>
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
    <h2>Archived Prenatal Records</h2>
    <a href="prenatal_records.php" class="btn btn-back">← Back to Active Records</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient</th>
          <th>Visit Date</th>
          <th>LMP</th>
          <th>EDD</th>
          <th>Gestational Age</th>
          <th>Blood Pressure</th>
          <th>Weight</th>
          <th>Height</th>
          <th>FHR</th>
          <th>Fundal Height</th>
          <th>Complaints</th>
          <th>Diagnosis</th>
          <th>Treatment</th>
          <th>Next Visit</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['prenatal_id']); ?></td>
              <td><?= htmlspecialchars($row['full_name']); ?></td>
              <td><?= htmlspecialchars($row['visit_date']); ?></td>
              <td><?= htmlspecialchars($row['lmp']); ?></td>
              <td><?= htmlspecialchars($row['edd']); ?></td>
              <td><?= htmlspecialchars($row['gestational_age']); ?></td>
              <td><?= htmlspecialchars($row['blood_pressure']); ?></td>
              <td><?= htmlspecialchars($row['weight']); ?></td>
              <td><?= htmlspecialchars($row['height']); ?></td>
              <td><?= htmlspecialchars($row['fetal_heart_rate']); ?></td>
              <td><?= htmlspecialchars($row['fundal_height']); ?></td>
              <td><?= htmlspecialchars($row['complaints']); ?></td>
              <td><?= htmlspecialchars($row['diagnosis']); ?></td>
              <td><?= htmlspecialchars($row['treatment']); ?></td>
              <td><?= htmlspecialchars($row['next_visit']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_prenatal.php?id=<?= $row['prenatal_id']; ?>" onclick="return confirm('Restore this record?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="16" style="text-align:center;">No archived prenatal records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>