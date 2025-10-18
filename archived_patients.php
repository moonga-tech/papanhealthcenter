<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

// Fetch archived patients with barangay name
$sql = "SELECT p.patient_id, p.full_name, p.age, p.gender, b.name AS barangay_name, p.date_created 
        FROM patients p
        JOIN barangay b ON p.barangay_id = b.barangay_id
        WHERE p.archived = 1
        ORDER BY p.full_name";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Patients</title>
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
    <h2>Archived Patients</h2>
    <a href="patients.php" class="btn btn-back">← Back to Active Patients</a>
    
    <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Barangay</th>
        <th>Date Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['patient_id']); ?></td>
            <td><?= htmlspecialchars($row['full_name']); ?></td>
            <td><?= htmlspecialchars($row['age']); ?></td>
            <td><?= htmlspecialchars($row['gender']); ?></td>
            <td><?= htmlspecialchars($row['barangay_name']); ?></td>
            <td><?= htmlspecialchars($row['date_created']); ?></td>
            <td>
              <a class="btn btn-restore" href="restore_patient.php?id=<?= $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to restore this patient?')">Restore</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No archived patients found.</td></tr>
      <?php endif; ?>
    </tbody>
    </table>
  </div>
</body>
</html>