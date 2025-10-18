<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

if (isset($_GET['id'])) {
  $child_id = (int)$_GET['id'];
  
  $sql = "SELECT c.*, b.name as barangay_name, f.family_no, f.family_head 
          FROM children c
          JOIN barangay b ON c.barangay_id = b.barangay_id
          JOIN family_number f ON c.family_id = f.family_id
          WHERE c.child_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $child_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $child = $result->fetch_assoc();
  
  if (!$child) {
    echo "Child not found.";
    exit();
  }
} else {
  echo "Invalid child ID.";
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Child Record - <?= htmlspecialchars($child['full_name']); ?></title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .header { text-align: center; margin-bottom: 30px; }
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table td { padding: 8px; border: 1px solid #ddd; }
    .label { font-weight: bold; background: #f5f5f5; width: 30%; }
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
  <div class="header">
    <h2>Child Record</h2>
    <p>Papan Health Center</p>
  </div>
  
  <table class="info-table">
    <tr>
      <td class="label">Child ID:</td>
      <td><?= htmlspecialchars($child['child_id']); ?></td>
    </tr>
    <tr>
      <td class="label">Full Name:</td>
      <td><?= htmlspecialchars($child['full_name']); ?></td>
    </tr>
    <tr>
      <td class="label">Date of Birth:</td>
      <td><?= htmlspecialchars($child['date_of_birth']); ?></td>
    </tr>
    <tr>
      <td class="label">Place of Birth:</td>
      <td><?= htmlspecialchars($child['place_of_birth']); ?></td>
    </tr>
    <tr>
      <td class="label">Sex:</td>
      <td><?= htmlspecialchars($child['sex']); ?></td>
    </tr>
    <tr>
      <td class="label">Mother's Name:</td>
      <td><?= htmlspecialchars($child['mother_name']); ?></td>
    </tr>
    <tr>
      <td class="label">Father's Name:</td>
      <td><?= htmlspecialchars($child['father_name']); ?></td>
    </tr>
    <tr>
      <td class="label">Birth Height:</td>
      <td><?= htmlspecialchars($child['birth_height']); ?> cm</td>
    </tr>
    <tr>
      <td class="label">Birth Weight:</td>
      <td><?= htmlspecialchars($child['birth_weight']); ?> kg</td>
    </tr>
    <tr>
      <td class="label">Barangay:</td>
      <td><?= htmlspecialchars($child['barangay_name']); ?></td>
    </tr>
    <tr>
      <td class="label">Family Number:</td>
      <td><?= htmlspecialchars($child['family_no']); ?></td>
    </tr>
    <tr>
      <td class="label">Family Head:</td>
      <td><?= htmlspecialchars($child['family_head']); ?></td>
    </tr>
    <tr>
      <td class="label">Date Created:</td>
      <td><?= htmlspecialchars($child['date_created']); ?></td>
    </tr>
  </table>
  
  <div class="no-print" style="margin-top: 20px; text-align: center;">
    <button onclick="window.print()">Print</button>
    <button onclick="window.close()">Close</button>
  </div>
  
  <script>
    window.onload = function() {
      window.print();
    };
  </script>
</body>
</html>