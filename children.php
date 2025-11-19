<?php
include 'db_connect.php';

// Fetch all children with Barangay & Family
$sql = "SELECT c.*, b.name, f.family_no, f.family_head 
        FROM children c
        JOIN barangay b ON c.barangay_id = b.barangay_id
        JOIN family_number f ON c.family_id = f.family_id
        WHERE c.archived = 0
        ORDER BY c.date_created DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Children Records</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    @media print {
      body { background: none; }
      .container { box-shadow: none; padding: 0; }
      .btn, .modal { display: none !important; }
      h2 { text-align: center; margin-bottom: 20px; }
      
      table { 
        width: 100% !important;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 10pt;
      }
      th, td { 
        border: 1px solid #000;
        padding: 6px;
        font-size: 9pt;
        text-align: left;
      }
      th { 
        background-color: #f0f0f0 !important;
        font-weight: bold;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      /* Hide action column */
      th:last-child, td:last-child { display: none; }
      
      /* Page breaks */
      tr { page-break-inside: avoid; }
      thead { display: table-header-group; }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Children Records</h2>
  <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
  <a href="archived_children.php" class="btn btn-edit">View Archived Children</a>
  <button class="btn btn-add" onclick="openAddModal()">+ Add Child</button>
  <button class="btn btn-edit" onclick="window.print()">Print All</button>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Date of Birth</th>
        <th>Place of Birth</th>
        <th>Sex</th>
        <th>Mother</th>
        <th>Father</th>
        <th>Birth Height</th>
        <th>Birth Weight</th>
        <th>Barangay</th>
        <th>Family No</th>
        <th>Family Head</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['child_id']; ?></td>
        <td><?= $row['full_name']; ?></td>
        <td><?= $row['date_of_birth']; ?></td>
        <td><?= $row['place_of_birth']; ?></td>
        <td><?= $row['sex']; ?></td>
        <td><?= $row['mother_name']; ?></td>
        <td><?= $row['father_name']; ?></td>
        <td><?= $row['birth_height']; ?> cm</td>
        <td><?= $row['birth_weight']; ?> kg</td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['family_no']; ?></td>
        <td><?= $row['family_head']; ?></td>
        <td>
          <button class="btn btn-edit" onclick="openEditModal(<?= $row['child_id']; ?>)">Edit</button>
          <a href="archive_child.php?id=<?= $row['child_id']; ?>" class="btn btn-archive" onclick="return confirm('Archive this child?')">Archive</a>
          <button class="btn btn-edit" onclick="printChild(<?= $row['child_id']; ?>)">Print</button>
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
    <h2>Add Child</h2>
    <form action="add_child.php" method="POST">
      <label>Full Name:</label>
      <input type="text" name="full_name" required>

      <label>Date of Birth:</label>
      <input type="date" name="date_of_birth" required>

      <label>Place of Birth:</label>
      <input type="text" name="place_of_birth" required>

      <label>Sex:</label>
      <select name="sex" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label>Mother's Name:</label>
      <input type="text" name="mother_name" required>

      <label>Father's Name:</label>
      <input type="text" name="father_name" required>

      <label>Birth Height (cm):</label>
      <input type="number" step="0.01" name="birth_height" required>

      <label>Birth Weight (kg):</label>
      <input type="number" step="0.01" name="birth_weight" required>

      <label>Barangay:</label>
      <select name="barangay_id" required>
        <?php
        $barangays = $conn->query("SELECT * FROM barangay");
        while($b = $barangays->fetch_assoc()){
          echo "<option value='{$b['barangay_id']}'>{$b['name']}</option>";
        }
        ?>
      </select>

      <label>Family:</label>
      <select name="family_id" required>
        <?php
        $families = $conn->query("SELECT * FROM family_number");
        while($f = $families->fetch_assoc()){
          echo "<option value='{$f['family_id']}'>
                  {$f['family_no']} - {$f['family_head']}
                </option>";
        }
        ?>
      </select>

      <button type="submit" class="btn btn-add">Save</button>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content" id="editFormContainer"></div>
</div>

<script>
function openAddModal(){ document.getElementById('addModal').style.display='block'; }
function closeAddModal(){ document.getElementById('addModal').style.display='none'; }
function openEditModal(id){
  fetch('fetch_child.php?id='+id)
  .then(res=>res.text())
  .then(data=>{
    document.getElementById('editFormContainer').innerHTML=data;
    document.getElementById('editModal').style.display='block';
  });
}

function printChild(id){
  window.open('print_child.php?id='+id, '_blank');
}
</script>
</body>
</html>

