<?php
include 'db_connect.php';

$result = $conn->query("SELECT * FROM barangay WHERE archived = 0 ORDER BY date_created DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Management</title>
    <link rel="stylesheet" href="assets/common_styles.css">
    <style>
        body { font-family: Arial, sans-serif;  margin:0; padding:20px; }
        .container { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); max-width:2000px; margin:auto; }
        h2 { text-align:center; color:#007bff; }
        .btn-add { background:#28a745; color:#fff; padding:8px 15px; border:none; border-radius:6px; cursor:pointer; margin-bottom:15px; }
        .btn-add:hover { background:#218838; }
        .btn-edit { background:#007bff; color:#fff; padding:5px 10px; border:none; border-radius:5px; cursor:pointer; }
        .btn-edit:hover { background:#0056b3; }
        .btn-delete { background:#dc3545; color:#fff; padding:5px 10px; border:none; border-radius:5px; cursor:pointer; text-decoration:none; }
        .btn-delete:hover { background:#b02a37; }
        .styled-table { width:100%; border-collapse:collapse; margin-top:15px; text-align:center; border-radius:10px; overflow:hidden; }
        .styled-table thead tr { background:#007bff; color:#fff; }
        .styled-table th, .styled-table td { padding:10px; border-bottom:1px solid #ddd; }
        .styled-table tbody tr:hover { background:#f1f1f1; }

        .modal { display:none; position:fixed; z-index:100; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); overflow:auto; }
        .modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:12px; width:400px; animation:slideDown 0.4s ease; }
        @keyframes slideDown { from{transform:translateY(-50px);opacity:0;} to{transform:translateY(0);opacity:1;} }
        .close { float:right; font-size:22px; color:#dc3545; cursor:pointer; }
        .close:hover { color:#a71d2a; }
        form label { display:block; margin-top:10px; font-weight:bold; font-size:14px; }
        form input { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px; }
        .btn-back {
  display: inline-block;
  background: #6c757d;
  color: #fff;
  padding: 8px 15px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 14px;
  transition: background 0.3s ease;
  margin-bottom: 15px;
}
.btn-back:hover {
  background: #565e64;
}

    </style>
</head>
<body>
<div class="container">
    <h2>Barangay Management</h2>
     <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    <a href="archived_barangays.php" class="btn btn-edit">View Archived Barangays</a>
    <button class="btn btn-add" onclick="document.getElementById('addModal').style.display='block'">+ Add Barangay</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Barangay Name</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['barangay_id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['date_created'] ?></td>
                <td>
                    <button class="btn btn-edit" onclick="openEditModal(<?= $row['barangay_id'] ?>, '<?= $row['name'] ?>')">Edit</button>
                    <a href="archive_barangay.php?id=<?= $row['barangay_id'] ?>" class="btn btn-archive" onclick="return confirm('Archive this barangay?')">Archive</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
    <h3>Add Barangay</h3>
    <form action="add_barangay.php" method="POST">
        <label>Barangay Name</label>
        <input type="text" name="name" required>
        <br><br>
        <button type="submit" class="btn btn-add">Save</button>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
    <h3>Edit Barangay</h3>
    <form action="update_barangay.php" method="POST">
        <input type="hidden" id="edit_id" name="barangay_id">
        <label>Barangay Name</label>
        <input type="text" id="edit_name" name="name" required>
        <br><br>
        <button type="submit" class="btn btn-edit">Update</button>
    </form>
  </div>
</div>

<script>
function openEditModal(id, name) {
    document.getElementById('editModal').style.display='block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
}
</script>
</body>
</html>

