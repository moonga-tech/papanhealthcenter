<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$query = "SELECT sr.request_id, sr.quantity, sr.request_date, m.medicine_name 
          FROM stock_request sr 
          JOIN medicines m ON sr.medicine_id = m.medicine_id 
          WHERE sr.archived = 1
          ORDER BY sr.request_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Stock Requests</title>
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
    <h2>Archived Stock Requests</h2>
    <a href="view_stock_requests.php" class="btn btn-back">← Back to Active Requests</a>
    
    <table>
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Medicine Name</th>
          <th>Quantity</th>
          <th>Request Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['request_id']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['quantity']); ?></td>
              <td><?= htmlspecialchars($row['request_date']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_stock_request.php?id=<?= $row['request_id']; ?>" onclick="return confirm('Are you sure you want to restore this request?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" style="text-align:center;">No archived requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>