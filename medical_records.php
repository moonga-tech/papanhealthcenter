<?php
include("db_connect.php"); // database connection

// Fetch active medical records
$query = "SELECT mr.*, pi.full_name 
          FROM medical_records mr 
          JOIN patients pi ON mr.patient_id = pi.patient_id 
          WHERE mr.archived = 0
          ORDER BY mr.date DESC";
$result = mysqli_query($conn, $query);

// Fetch patients for dropdown
$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Records</title>
  <link rel="stylesheet" href="assets/common_styles.css">
</head>
<body>

<body>
  <div class="container">
  <h2>Medical Records</h2>
  <div>
    <a href="dashboard.php" class="btn btn-back">⬅ Back to Dashboard</a>
    <a href="archived_medical_records.php" class="btn btn-edit">View Archived Records</a>
    <button class="btn btn-add" id="openModal">+ Add Medical Record</button>
  </div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Date</th>
        <th>BP</th>
        <th>Height (cm)</th>
        <th>Weight (kg)</th>
        <th>Pulse</th>
        <th>Assessment</th>
        <th>Plan</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= $row['record_id'] ?></td>
          <td><?= $row['full_name'] ?></td>
          <td><?= $row['date'] ?></td>
          <td><?= $row['systolic_bp'] ?>/<?= $row['diastolic_bp'] ?></td>
          <td><?= $row['height'] ?></td>
          <td><?= $row['weight'] ?></td>
          <td><?= $row['pulse'] ?></td>
          <td><?= $row['assessment'] ?></td>
          <td><?= $row['plan'] ?></td>
       <td class="action-btns">
  <a href="edit_form_medical.php?record_id=<?= $row['record_id'] ?>" class="btn btn-edit">Edit</a>
  <a href="archive_medical_record.php?record_id=<?= $row['record_id'] ?>" 
     class="btn btn-archive"
     onclick="return confirm('Are you sure you want to archive this record?');">
     Archive
  </a>
  <a href="print_medical_record.php?record_id=<?= $row['record_id'] ?>" target="_blank" class="btn btn-edit">🖨 Print</a>
</td>

        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal" id="myModal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>➕ Add Medical Record</h3>
    <form action="add_medical_record.php" method="POST">
      <label>Patient:</label>
      <select name="patient_id" required>
        <option value="">Select Patient</option>
        <?php while($p = mysqli_fetch_assoc($patients)) { ?>
          <option value="<?= $p['patient_id'] ?>"><?= $p['full_name'] ?></option>
        <?php } ?>
      </select>

      <label>Date:</label>
      <input type="date" name="date" required>

      <label>Blood Pressure:</label>
      <div style="display:flex; gap:5px;">
        <input type="number" name="systolic_bp" placeholder="Systolic">
        <input type="number" name="diastolic_bp" placeholder="Diastolic">
      </div>

      <label>Height (cm):</label>
      <input type="number" step="0.01" name="height">

      <label>Weight (kg):</label>
      <input type="number" step="0.01" name="weight">

      <label>Pulse:</label>
      <input type="number" name="pulse">

      <label>Assessment:</label>
      <input type="text" name="assessment">

      <label>Plan:</label>
      <input type="text" name="plan">

      <button type="submit" class="btn btn-add">💾 Save</button>
    </form>
  </div>
</div>

<script>
  var modal = document.getElementById("myModal");
  var btn = document.getElementById("openModal");
  var span = document.getElementsByClassName("close")[0];

  btn.onclick = function() { modal.style.display = "flex"; }
  span.onclick = function() { modal.style.display = "none"; }
  window.onclick = function(event) {
    if (event.target == modal) { modal.style.display = "none"; }
  }
</script>

</body>
</html>
