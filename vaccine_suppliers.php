<?php
include 'db_connect.php';

$result = $conn->query("SELECT * FROM vaccine_suppliers WHERE archived = 0 ORDER BY date_created DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccine Suppliers</title>
  <link rel="stylesheet" href="assets/common_styles.css">
</head>
<body>
  <div class="container">
    <h2>Vaccine Suppliers Management</h2>

    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    <a href="archived_vaccine_suppliers.php" class="btn btn-edit">View Archived Suppliers</a>
    <button class="btn btn-add" onclick="openAddModal()">+ Add Supplier</button>

    <table style="table-layout: fixed; width: 100%;">
      <thead>
        <tr>
          <th style="width: 8%;">ID</th>
          <th style="width: 20%;">Supplier Name</th>
          <th style="width: 18%;">Contact Person</th>
          <th style="width: 15%;">Phone</th>
          <th style="width: 20%;">Address</th>
          <th style="width: 12%;">Date Created</th>
          <th style="width: 10%;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['supplier_id']; ?></td>
          <td><?= $row['supplier_name']; ?></td>
          <td><?= $row['contact_person']; ?></td>
          <td><?= $row['phone_number']; ?></td>
          <td><?= $row['address']; ?></td>
          <td><?= $row['date_created']; ?></td>
          <td>
            <button class="btn btn-edit" onclick="openEditModal(
              '<?= $row['supplier_id']; ?>',
              '<?= $row['supplier_name']; ?>',
              '<?= $row['contact_person']; ?>',
              '<?= $row['phone_number']; ?>',
              '<?= $row['address']; ?>'
            )">Edit</button>
            <a href="archive_vaccine_supplier.php?id=<?= $row['supplier_id']; ?>" class="btn btn-archive" onclick="return confirm('Archive this supplier?')">Archive</a>
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
      <h3>Add Vaccine Supplier</h3>
      <form action="insert_vaccine_supplier.php" method="POST">
        <input type="text" name="supplier_name" placeholder="Supplier Name" required>
        <input type="text" name="contact_person" placeholder="Contact Person">
        <input type="text" name="phone_number" placeholder="Phone Number">
        <textarea name="address" placeholder="Address"></textarea>
        <button type="submit" class="btn btn-add">Save</button>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModal()">&times;</span>
      <h3>Edit Vaccine Supplier</h3>
      <form action="update_vaccine_supplier.php" method="POST">
        <input type="hidden" id="edit_id" name="supplier_id">
        <input type="text" id="edit_supplier_name" name="supplier_name" required>
        <input type="text" id="edit_contact_person" name="contact_person">
        <input type="text" id="edit_phone_number" name="phone_number">
        <textarea id="edit_address" name="address"></textarea>
        <button type="submit" class="btn btn-edit">Update</button>
      </form>
    </div>
  </div>

  <script>
    function openAddModal() {
      document.getElementById('addModal').style.display = 'block';
    }
    function closeAddModal() {
      document.getElementById('addModal').style.display = 'none';
    }
    function openEditModal(id, name, contact, phone, address) {
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_supplier_name').value = name;
      document.getElementById('edit_contact_person').value = contact;
      document.getElementById('edit_phone_number').value = phone;
      document.getElementById('edit_address').value = address;
      document.getElementById('editModal').style.display = 'block';
    }
    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }
  </script>
</body>
</html>

