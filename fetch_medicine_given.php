<?php
include 'db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM medicine_given WHERE give_id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
<h2>Edit Given Medicine</h2>
<form action="edit_medicine_given.php" method="POST">
  <input type="hidden" name="give_id" value="<?= $row['give_id']; ?>">

  <label>Patient:</label>
  <select name="patient_id" required>
    <?php
    $patients = $conn->query("SELECT * FROM patients");
    while ($p = $patients->fetch_assoc()) {
      $sel = ($p['patient_id']==$row['patient_id']) ? 'selected' : '';
      echo "<option value='{$p['patient_id']}' $sel>{$p['full_name']}</option>";
    }
    ?>
  </select><br>

  <label>Medicine:</label>
  <select name="medicine_id" required>
    <?php
    $meds = $conn->query("SELECT * FROM medicines");
    while ($m = $meds->fetch_assoc()) {
      $sel = ($m['medicine_id']==$row['medicine_id']) ? 'selected' : '';
      echo "<option value='{$m['medicine_id']}' $sel>{$m['medicine_name']}</option>";
    }
    ?>
  </select><br>

  <label>Quantity:</label>
  <input type="number" name="quantity_given" value="<?= $row['quantity_given']; ?>" required><br>

  <button type="submit" class="btn-save">Update</button>
</form>

