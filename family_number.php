<?php
include 'db_connect.php';

// Fetch all active families with barangay name
$sql = "SELECT f.family_id, f.family_no, f.family_head, f.date_created, b.name AS barangay_name
        FROM family_number f
        JOIN barangay b ON f.barangay_id = b.barangay_id
        WHERE f.archived = 0
        ORDER BY f.date_created DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Family Numbers</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    /* 🌐 Global Style */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    .container {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 2000px;
      margin: auto;
    }

    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 20px;
    }

    /* 🎨 Buttons */
    .btn-add,
    .btn-edit,
    .btn-delete,
    .btn-save,
    .btn-back {
      padding: 8px 15px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      color: #fff;
      font-size: 14px;
      transition: 0.3s ease;
    }

    .btn-add {
      background: #28a745;
      margin-bottom: 15px;
    }
    .btn-add:hover { background: #218838; }

    .btn-edit {
      background: #007bff;
    }
    .btn-edit:hover { background: #0056b3; }

    .btn-delete {
      background: #dc3545;
      text-decoration: none;
      padding: 8px 12px;
      display: inline-block;
    }
    .btn-delete:hover { background: #b02a37; }

    .btn-save {
      background: #17a2b8;
      margin-top: 10px;
      width: 100%;
    }
    .btn-save:hover { background: #117a8b; }

    .btn-back {
      background: #6c757d;
      margin-bottom: 15px;
    }
    .btn-back:hover { background: #565e64; }

    /* 📊 Table */
    .styled-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 15px;
      text-align: center;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
    }

    .styled-table thead tr {
      background: #007bff;
      color: #fff;
      font-weight: bold;
    }

    .styled-table th,
    .styled-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
    }

    .styled-table tbody tr:hover {
      background: #f1f1f1;
    }

    /* 🪟 Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.6);
      overflow: auto;
    }

    .modal-content {
      background: #fff;
      margin: 4% auto;
      padding: 25px;
      border-radius: 12px;
      width: 400px;
      animation: slideDown 0.4s ease;
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    @keyframes slideDown {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .close {
      float: right;
      font-size: 22px;
      font-weight: bold;
      color: #dc3545;
      cursor: pointer;
    }
    .close:hover { color: #a71d2a; }

    /* 📝 Form */
    form label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
      font-size: 14px;
    }

    form input,
    form select {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
      transition: 0.3s;
    }

    form input:focus,
    form select:focus {
      border-color: #007bff;
      box-shadow: 0 0 4px rgba(0,123,255,0.3);
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Family Numbers</h2>
  <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
  <a href="archived_family_numbers.php" class="btn btn-edit">View Archived Families</a>
  <button class="btn btn-add" onclick="openAddModal()">+ Add Family</button>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Family No</th>
        <th>Family Head</th>
        <th>Barangay</th>
        <th>Date Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php while($row=$result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['family_id']; ?></td>
        <td><?= $row['family_no']; ?></td>
        <td><?= $row['family_head']; ?></td>
        <td><?= $row['barangay_name']; ?></td>
        <td><?= $row['date_created']; ?></td>
        <td>
          <button class="btn btn-edit" onclick="openEditModal(<?= $row['family_id']; ?>)">Edit</button>
          <a href="archive_family.php?id=<?= $row['family_id']; ?>" class="btn btn-archive" onclick="return confirm('Are you sure to archive this record?')">Archive</a>
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
    <h2>Add Family</h2>
    <form action="add_family.php" method="POST">
      <label>Family No:</label>
      <input type="text" name="family_no" required>

      <label>Family Head:</label>
      <input type="text" name="family_head" required>

      <label>Barangay:</label>
      <select name="barangay_id" required>
        <?php
        $barangays=$conn->query("SELECT * FROM barangay");
        while($b=$barangays->fetch_assoc()){
          echo "<option value='{$b['barangay_id']}'>{$b['name']}</option>";
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
function openAddModal(){document.getElementById('addModal').style.display='block';}
function closeAddModal(){document.getElementById('addModal').style.display='none';}
function openEditModal(id){
  fetch('fetch_family.php?id='+id)
  .then(res=>res.text())
  .then(data=>{
    document.getElementById('editFormContainer').innerHTML=data;
    document.getElementById('editModal').style.display='block';
  });
}
</script>
</body>
</html>

