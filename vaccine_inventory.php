<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT v.vaccine_id, v.vaccine_name, v.description, v.quantity, v.total_doses, 
               v.recommended_ages, v.expiry_date, v.date_received, v.lot_number, s.supplier_name,
               CASE 
                 WHEN v.quantity <= 5 THEN 'Low Stock'
                 WHEN v.quantity <= 20 THEN 'Medium Stock'
                 ELSE 'Good Stock'
               END as stock_status,
               CASE 
                 WHEN v.expiry_date < CURDATE() THEN 'Expired'
                 WHEN DATEDIFF(v.expiry_date, CURDATE()) <= 30 THEN 'Expiring Soon'
                 ELSE 'Good'
               END as expiry_status
        FROM vaccines v 
        JOIN vaccine_suppliers s ON v.supplier_id = s.supplier_id 
        WHERE v.archived = 0
        ORDER BY v.vaccine_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccine Inventory</title>
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
    <h2>Vaccine Inventory</h2>
    <a href="vaccines.php" class="btn btn-back">← Back to Vaccines</a>
    <button class="btn btn-edit" onclick="window.print()">Print Inventory</button>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Vaccine Name</th>
          <th>Description</th>
          <th>Supplier</th>
          <th>Quantity</th>
          <th>Stock Status</th>
          <th>Total Doses</th>
          <th>Expiry Date</th>
          <th>Expiry Status</th>
          <th>Lot Number</th>
          <th>Date Received</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['vaccine_id']); ?></td>
              <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['supplier_name']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['quantity']); ?>
              </td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['total_doses']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['expiry_status'])); ?>">
                <?= htmlspecialchars($row['expiry_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
              <td><?= htmlspecialchars($row['date_received']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="11" style="text-align:center;">No vaccines in inventory.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>