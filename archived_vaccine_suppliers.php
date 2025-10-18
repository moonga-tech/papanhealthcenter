<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$result = $conn->query("SELECT * FROM vaccine_suppliers WHERE archived = 1 ORDER BY date_created DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Vaccine Suppliers</title>
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
    <h2>Archived Vaccine Suppliers</h2>
    <a href="vaccine_suppliers.php" class="btn btn-back">← Back to Active Suppliers</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Supplier Name</th>
          <th>Contact Person</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Date Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['supplier_id']); ?></td>
              <td><?= htmlspecialchars($row['supplier_name']); ?></td>
              <td><?= htmlspecialchars($row['contact_person']); ?></td>
              <td><?= htmlspecialchars($row['phone_number']); ?></td>
              <td><?= htmlspecialchars($row['address']); ?></td>
              <td><?= htmlspecialchars($row['date_created']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_vaccine_supplier.php?id=<?= $row['supplier_id']; ?>" onclick="return confirm('Restore this supplier?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;">No archived suppliers found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>