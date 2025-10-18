<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$result = $conn->query("SELECT * FROM barangay WHERE archived = 1 ORDER BY date_created DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Barangays</title>
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
    <h2>Archived Barangays</h2>
    <a href="barangay.php" class="btn btn-back">← Back to Active Barangays</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Barangay Name</th>
          <th>Date Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['barangay_id']); ?></td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['date_created']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_barangay.php?id=<?= $row['barangay_id']; ?>" onclick="return confirm('Restore this barangay?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" style="text-align:center;">No archived barangays found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>