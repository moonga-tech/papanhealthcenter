<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT medicine_id, medicine_name, description, stock, expiry_date, date_added,
               CASE 
                 WHEN stock <= 5 THEN 'Low Stock'
                 WHEN stock <= 20 THEN 'Medium Stock'
                 ELSE 'Good Stock'
               END as stock_status,
               CASE 
                 WHEN expiry_date < CURDATE() THEN 'Expired'
                 WHEN DATEDIFF(expiry_date, CURDATE()) <= 30 THEN 'Expiring Soon'
                 ELSE 'Good'
               END as expiry_status
        FROM medicines 
        WHERE archived = 0
        ORDER BY medicine_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medicine Inventory</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .low-stock { background-color: #ffebee; color: #c62828; }
    .medium-stock { background-color: #fff3e0; color: #ef6c00; }
    .good-stock { background-color: #e8f5e8; color: #2e7d32; }
    .expired { background-color: #ffcdd2; color: #d32f2f; }
    .expiring-soon { background-color: #ffe0b2; color: #f57c00; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Medicine Inventory</h2>
    <a href="medicine.php" class="btn btn-back">← Back to Medicines</a>
    <button class="btn btn-edit" onclick="window.print()">Print Inventory</button>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Medicine Name</th>
          <th>Description</th>
          <th>Stock</th>
          <th>Stock Status</th>
          <th>Expiry Date</th>
          <th>Expiry Status</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['medicine_id']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock']); ?>
              </td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['expiry_status'])); ?>">
                <?= htmlspecialchars($row['expiry_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['date_added']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">No medicines in inventory.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>