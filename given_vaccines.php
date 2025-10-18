<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$sql = "SELECT ci.immunization_id, c.full_name, v.vaccine_name, v.lot_number, 
               ci.dose_number, ci.date_given, ci.vaccinator, v.quantity
        FROM child_immunizations ci
        JOIN children c ON ci.child_id = c.child_id
        JOIN vaccines v ON ci.vaccine_id = v.vaccine_id
        WHERE ci.archived = 0
        ORDER BY ci.date_given DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Given Vaccines</title>
  <link rel="stylesheet" href="assets/common_styles.css">
</head>
<body>
  <div class="container">
    <h2>Given Vaccines</h2>
    <a href="vaccines.php" class="btn btn-back">← Back to Vaccines</a>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Child Name</th>
          <th>Vaccine</th>
          <th>Lot Number</th>
          <th>Dose Number</th>
          <th>Date Given</th>
          <th>Vaccinator</th>
          <th>Remaining Stock</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['immunization_id']); ?></td>
              <td><?= htmlspecialchars($row['full_name']); ?></td>
              <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
              <td><?= htmlspecialchars($row['dose_number']); ?></td>
              <td><?= htmlspecialchars($row['date_given']); ?></td>
              <td><?= htmlspecialchars($row['vaccinator']); ?></td>
              <td><?= htmlspecialchars($row['quantity']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">No vaccines given yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>