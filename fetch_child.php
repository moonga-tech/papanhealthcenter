<?php
include 'db_connect.php';
$id = $_GET['id'];
$child = $conn->query("SELECT * FROM children WHERE child_id=$id")->fetch_assoc();
?>

<span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
<h2>Edit Child</h2>
<form action="update_child.php" method="POST">
  <input type="hidden" name="child_id" value="<?= $child['child_id']; ?>">

  <label>Full Name:</label>
  <input type="text" name="full_name" value="<?= $child['full_name']; ?>" required>

  <label>Date of Birth:</label>
  <input type="date" name="date_of_birth" value="<?= $child['date_of_birth']; ?>" required>

  <label>Place of Birth:</label>
  <input type="text" name="place_of_birth" value="<?= $child['place_of_birth']; ?>" required>

  <label>Sex:</label>
  <select name="sex" required>
    <option value="Male" <?= $child['sex']=='Male'?'selected':''; ?>>Male</option>
    <option value="Female" <?= $child['sex']=='Female'?'selected':''; ?>>Female</option>
  </select>

  <label>Mother's Name:</label>
  <input type="text" name="mother_name" value="<?= $child['mother_name']; ?>" required>

  <label>Father's Name:</label>
  <input type="text" name="father_name" value="<?= $child['father_name']; ?>" required>

  <label>Birth Height (cm):</label>
  <input type="number" step="0.01" name="birth_height" value="<?= $child['birth_height']; ?>" required>

  <label>Birth Weight (kg):</label>
  <input type="number" step="0.01" name="birth_weight" value="<?= $child['birth_weight']; ?>" required>

  <label>Barangay:</label>
  <select name="barangay_id" required>
    <?php
    $barangays = $conn->query("SELECT * FROM barangay");
    while($b = $barangays->fetch_assoc()){
      $selected = $child['barangay_id']==$b['barangay_id'] ? "selected" : "";
      echo "<option value='{$b['barangay_id']}' $selected>{$b['name']}</option>";
    }
    ?>
  </select>

  <label>Family:</label>
  <select name="family_id" required>
    <?php
    $families = $conn->query("SELECT * FROM family_number");
    while($f = $families->fetch_assoc()){
      $selected = $child['family_id']==$f['family_id'] ? "selected" : "";
      echo "<option value='{$f['family_id']}' $selected>
              {$f['family_no']} - {$f['family_head']}
            </option>";
    }
    ?>
  </select>

  <button type="submit" class="btn-save">Update</button>
</form>

