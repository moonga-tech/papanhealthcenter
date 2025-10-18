<?php
include 'db_connect.php';

// Fetch all records
$sql = "SELECT mg.give_id, p.full_name AS patient_name, m.medicine_name, mg.quantity_given, mg.date_given
        FROM medicine_given mg
        JOIN patients p ON mg.patient_id = p.patient_id
        JOIN medicines m ON mg.medicine_id = m.medicine_id
        WHERE mg.archived = 0
        ORDER BY mg.date_given DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medicine Given Records</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .btn-save {
      background: #28a745;
      color: white;
    }
    .btn-save:hover {
      background: #218838;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <div class="container">
      <h2>Medicine Given Records</h2>
      <a href="medicine.php" class="btn btn-back">⬅ Back to Medicine</a>
      <a href="archived_medicine_given.php" class="btn btn-edit">View Archived Records</a>
      <button class="btn btn-add" onclick="openAddModal()">+ Give Medicine</button>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Medicine</th>
            <th>Quantity</th>
            <th>Date Given</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['give_id']; ?></td>
            <td><?= $row['patient_name']; ?></td>
            <td><?= $row['medicine_name']; ?></td>
            <td><?= $row['quantity_given']; ?></td>
            <td><?= $row['date_given']; ?></td>
            <td>
  <button class="btn btn-edit" onclick="openEditModal(<?= $row['give_id']; ?>)">Edit</button>
  <a href="archive_medicine_given.php?id=<?= $row['give_id']; ?>" 
     class="btn btn-archive" 
     onclick="return confirm('Are you sure to archive this record?')">Archive</a>
  <a href="print_medicine_given.php?id=<?= $row['give_id']; ?>" 
     target="_blank" 
     class="btn btn-save">Print</a>
</td>

          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
  </div>

  <!-- Add Modal -->
  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeAddModal()">&times;</span>
      <h2>Give Medicine</h2>
      <form action="add_medicine_given.php" method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
          <?php
          $patients = $conn->query("SELECT * FROM patients");
          while ($p = $patients->fetch_assoc()) {
            echo "<option value='{$p['patient_id']}'>{$p['full_name']}</option>";
          }
          ?>
        </select><br>

        <label>Medicine:</label>
        <select name="medicine_id" required>
          <?php
          $meds = $conn->query("SELECT *, DATEDIFF(expiry_date, CURDATE()) as days_left FROM medicines ORDER BY stock ASC");
          while ($m = $meds->fetch_assoc()) {
            $stock_warning = $m['stock'] <= 5 ? ' ⚠️ LOW STOCK' : '';
            $expiry_warning = '';
            $style = '';
            
            if ($m['expiry_date'] && $m['days_left'] <= 3 && $m['days_left'] >= 0) {
                $days_text = $m['days_left'] == 0 ? 'TODAY' : $m['days_left'] . ' days';
                $expiry_warning = ' 🚨 EXPIRES IN ' . strtoupper($days_text);
                $style = 'color: red; font-weight: bold; background: #ffe6e6;';
            } else if ($m['stock'] <= 5) {
                $style = 'color: red; font-weight: bold;';
            }
            
            echo "<option value='{$m['medicine_id']}' style='$style'>{$m['medicine_name']} (Stock: {$m['stock']})$stock_warning$expiry_warning</option>";
          }
          ?>
        </select><br>

        <label>Quantity:</label>
        <input type="number" name="quantity_given" required><br>

        <button type="submit" class="btn-save">Save</button>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content" id="editFormContainer">
      <!-- Content from fetch_medicine_given.php via AJAX -->
    </div>
  </div>

<script>
function openAddModal(){ document.getElementById('addModal').style.display='block'; }
function closeAddModal(){ document.getElementById('addModal').style.display='none'; }
function openEditModal(id){
  fetch('fetch_medicine_given.php?id='+id)
    .then(res=>res.text())
    .then(data=>{
      document.getElementById('editFormContainer').innerHTML=data;
      document.getElementById('editModal').style.display='block';
    });
}
</script>
</body>
</html>
