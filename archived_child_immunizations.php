<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$immunizations = $conn->query("
    SELECT ci.immunization_id, c.full_name, v.vaccine_name, v.lot_number, 
           ci.dose_number, ci.date_given, ci.vaccinator, ci.place_given, ci.remarks
    FROM child_immunizations ci
    JOIN children c ON ci.child_id = c.child_id
    JOIN vaccines v ON ci.vaccine_id = v.vaccine_id
    WHERE ci.archived = 1
    ORDER BY ci.date_given DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Archived Child Immunizations</title>
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
    <h2>Archived Child Immunizations</h2>
    <a href="child_immunizations.php" class="btn btn-back">← Back to Active Records</a>
    
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Child</th>
          <th>Vaccine</th>
          <th>Lot No.</th>
          <th>Dose</th>
          <th>Date Given</th>
          <th>Vaccinator</th>
          <th>Place</th>
          <th>Remarks</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($immunizations->num_rows > 0): ?>
          <?php while($row = $immunizations->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['immunization_id']); ?></td>
              <td><?= htmlspecialchars($row['full_name']); ?></td>
              <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
              <td><?= htmlspecialchars($row['dose_number']); ?></td>
              <td><?= htmlspecialchars($row['date_given']); ?></td>
              <td><?= htmlspecialchars($row['vaccinator']); ?></td>
              <td><?= htmlspecialchars($row['place_given']); ?></td>
              <td><?= htmlspecialchars($row['remarks']); ?></td>
              <td>
                <a class="btn btn-restore" href="restore_immunization.php?id=<?= $row['immunization_id']; ?>" onclick="return confirm('Restore this record?')">Restore</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="9" style="text-align:center;">No archived immunization records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>