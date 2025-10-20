<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT medicine_id, medicine_name, description, stock, expiry_date, date_added,
               DATEDIFF(expiry_date, CURDATE()) as days_expired
        FROM medicines 
        WHERE archived = 0 AND expiry_date < CURDATE()
        ORDER BY expiry_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Expired Medicines</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .expired-row {
      background-color: #ffebee !important;
      color: #c62828;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Expired Medicines</h2>
    <a href="medicine.php" class="btn btn-back">← Back to Medicines</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Medicine Name</th>
          <th>Description</th>
          <th>Stock</th>
          <th>Expiry Date</th>
          <th>Days Expired</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="expired-row">
              <td><?= htmlspecialchars($row['medicine_id']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['stock']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td><?= abs($row['days_expired']); ?> days ago</td>
              <td><?= htmlspecialchars($row['date_added']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;">No expired medicines found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>