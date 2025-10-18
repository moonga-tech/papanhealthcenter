<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = 'SELECT * FROM medicines WHERE archived = 1 ORDER BY date_added DESC';
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Medicines</title>
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
    <h2>Archived Medicines</h2>
    <a href="medicine.php" class="btn btn-back">← Back to Active Medicines</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Medicine Name</th>
          <th>Description</th>
          <th>Stock</th>
          <th>Expiry Date</th>
          <th>Date Added</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['medicine_id']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['stock']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td><?= htmlspecialchars($row['date_added']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_medicine.php?id=<?= $row['medicine_id']; ?>" onclick="return confirm('Are you sure you want to restore this medicine?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;">No archived medicines found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>