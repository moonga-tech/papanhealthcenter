<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT v.vaccine_id, v.vaccine_name, v.description, v.quantity, v.total_doses, 
               v.recommended_ages, v.expiry_date, v.date_received, v.lot_number, s.supplier_name,
               DATEDIFF(v.expiry_date, CURDATE()) as days_expired
        FROM vaccines v 
        JOIN vaccine_suppliers s ON v.supplier_id = s.supplier_id 
        WHERE v.archived = 0 AND v.expiry_date < CURDATE()
        ORDER BY v.expiry_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Expired Vaccines</title>
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
    <h2>Expired Vaccines</h2>
    <a href="vaccines.php" class="btn btn-back">← Back to Vaccines</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Vaccine Name</th>
          <th>Description</th>
          <th>Supplier</th>
          <th>Quantity</th>
          <th>Expiry Date</th>
          <th>Days Expired</th>
          <th>Lot Number</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="expired-row">
              <td><?= htmlspecialchars($row['vaccine_id']); ?></td>
              <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['supplier_name']); ?></td>
              <td><?= htmlspecialchars($row['quantity']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td><?= abs($row['days_expired']); ?> days ago</td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">No expired vaccines found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>