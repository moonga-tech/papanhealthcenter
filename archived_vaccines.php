<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT v.vaccine_id, v.vaccine_name, v.description, v.quantity, v.total_doses, v.recommended_ages, v.expiry_date, v.date_received, v.lot_number, s.supplier_name FROM vaccines v JOIN vaccine_suppliers s ON v.supplier_id = s.supplier_id WHERE v.archived = 1 ORDER BY v.date_received DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Vaccines</title>
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
    <h2>Archived Vaccines</h2>
    <a href="vaccines.php" class="btn btn-back">← Back to Active Vaccines</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Vaccine Name</th>
          <th>Description</th>
          <th>Supplier</th>
          <th>Quantity</th>
          <th>Total Doses</th>
          <th>Recommended Ages</th>
          <th>Expiry Date</th>
          <th>Date Received</th>
          <th>Lot Number</th>
          <th>Actions</th>
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
              <td><?= htmlspecialchars($row['quantity']); ?></td>
              <td><?= htmlspecialchars($row['total_doses']); ?></td>
              <td><?= htmlspecialchars($row['recommended_ages']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td><?= htmlspecialchars($row['date_received']); ?></td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_vaccine.php?id=<?= $row['vaccine_id']; ?>" onclick="return confirm('Are you sure you want to restore this vaccine?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="11" style="text-align:center;">No archived vaccines found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>