<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT c.*, b.name, f.family_no, f.family_head 
        FROM children c
        JOIN barangay b ON c.barangay_id = b.barangay_id
        JOIN family_number f ON c.family_id = f.family_id
        WHERE c.archived = 1
        ORDER BY c.date_created DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Children</title>
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
    <h2>Archived Children Records</h2>
    <a href="children.php" class="btn btn-back">← Back to Active Children</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Date of Birth</th>
          <th>Place of Birth</th>
          <th>Sex</th>
          <th>Mother</th>
          <th>Father</th>
          <th>Birth Height</th>
          <th>Birth Weight</th>
          <th>Barangay</th>
          <th>Family No</th>
          <th>Family Head</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['child_id']); ?></td>
              <td><?= htmlspecialchars($row['full_name']); ?></td>
              <td><?= htmlspecialchars($row['date_of_birth']); ?></td>
              <td><?= htmlspecialchars($row['place_of_birth']); ?></td>
              <td><?= htmlspecialchars($row['sex']); ?></td>
              <td><?= htmlspecialchars($row['mother_name']); ?></td>
              <td><?= htmlspecialchars($row['father_name']); ?></td>
              <td><?= htmlspecialchars($row['birth_height']); ?> cm</td>
              <td><?= htmlspecialchars($row['birth_weight']); ?> kg</td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['family_no']); ?></td>
              <td><?= htmlspecialchars($row['family_head']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_child.php?id=<?= $row['child_id']; ?>" onclick="return confirm('Restore this child?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="13" style="text-align:center;">No archived children found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>