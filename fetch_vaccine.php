<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $query = $conn->query("SELECT * FROM vaccines WHERE vaccine_id = $id");
    $row = $query->fetch_assoc();
}
?>

<span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
<h2>Edit Vaccine</h2>
<form action="update_vaccine.php" method="POST">
  <input type="hidden" name="vaccine_id" value="<?= $row['vaccine_id']; ?>">

  <label>Vaccine Name:</label>
  <input type="text" name="vaccine_name" value="<?= $row['vaccine_name']; ?>" required>

  <label>Description:</label>
  <textarea name="description"><?= $row['description']; ?></textarea>

  <label>Supplier:</label>
  <select name="supplier_id" required>
    <?php
    $suppliers = $conn->query("SELECT * FROM vaccine_suppliers");
    while($s = $suppliers->fetch_assoc()){
      $selected = ($s['supplier_id'] == $row['supplier_id']) ? "selected" : "";
      echo "<option value='{$s['supplier_id']}' $selected>{$s['supplier_name']}</option>";
    }
    ?>
  </select>

  <label>Quantity:</label>
  <input type="number" name="quantity" value="<?= $row['quantity']; ?>" required>

  <label>Total Doses:</label>
  <input type="number" name="total_doses" value="<?= $row['total_doses']; ?>" required>

  <label>Recommended Ages:</label>
  <input type="text" name="recommended_ages" value="<?= $row['recommended_ages']; ?>" required>

  <label>Expiry Date:</label>
  <input type="date" name="expiry_date" value="<?= $row['expiry_date']; ?>" required>

  <label>Date Received:</label>
  <input type="date" name="date_received" value="<?= $row['date_received']; ?>" required>

  <label>Lot Number:</label>
  <input type="text" name="lot_number" value="<?= $row['lot_number']; ?>" required>

  <button type="submit" class="btn-save">Update</button>
</form>

