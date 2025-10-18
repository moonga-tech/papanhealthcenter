<?php
include 'db_connect.php';

if(isset($_GET['id'])){
  $id = intval($_GET['id']);
  $query = $conn->query("SELECT * FROM family_number WHERE family_id = $id");
  $row = $query->fetch_assoc();
}
?>

<span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
<h2>Edit Family</h2>
<form action="update_family.php" method="POST">
  <input type="hidden" name="family_id" value="<?= $row['family_id']; ?>">

  <label>Family No:</label>
  <input type="text" name="family_no" value="<?= $row['family_no']; ?>" required>

  <label>Family Head:</label>
  <input type="text" name="family_head" value="<?= $row['family_head']; ?>" required>

  <label>Barangay:</label>
  <select name="barangay_id" required>
    <?php
    $barangays=$conn->query("SELECT * FROM barangay");
    while($b=$barangays->fetch_assoc()){
      $selected = ($b['barangay_id'] == $row['barangay_id']) ? "selected" : "";
      echo "<option value='{$b['barangay_id']}' $selected>{$b['name']}</option>";
    }
    ?>
  </select>

  <button type="submit" class="btn-save">Update</button>
</form>

