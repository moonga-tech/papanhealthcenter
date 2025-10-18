<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT f.family_id, f.family_no, f.family_head, f.date_created, b.name AS barangay_name
        FROM family_number f
        JOIN barangay b ON f.barangay_id = b.barangay_id
        WHERE f.archived = 1
        ORDER BY f.date_created DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Family Numbers</title>
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
    <h2>Archived Family Numbers</h2>
    <a href="family_number.php" class="btn btn-back">← Back to Active Families</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Family No</th>
          <th>Family Head</th>
          <th>Barangay</th>
          <th>Date Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['family_id']); ?></td>
              <td><?= htmlspecialchars($row['family_no']); ?></td>
              <td><?= htmlspecialchars($row['family_head']); ?></td>
              <td><?= htmlspecialchars($row['barangay_name']); ?></td>
              <td><?= htmlspecialchars($row['date_created']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_family.php?id=<?= $row['family_id']; ?>" onclick="return confirm('Are you sure you want to restore this family?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" style="text-align:center;">No archived families found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>