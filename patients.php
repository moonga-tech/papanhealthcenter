<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$message = "";

// Handle Add Patient form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_patient'])) {
  $full_name = $conn->real_escape_string($_POST['full_name']);
  $age = (int)$_POST['age'];
  $gender = $conn->real_escape_string($_POST['gender']);
  $barangay_id = (int)$_POST['barangay_id'];

  $sql = "INSERT INTO patients (full_name, age, gender, barangay_id) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sisi", $full_name, $age, $gender, $barangay_id);

  if ($stmt->execute()) {
    header("Location: patients.php");
    exit();
  } else {
    $message = "Error: " . $conn->error;
  }
}

// Fetch active patients with barangay name
$sql = "SELECT p.patient_id, p.full_name, p.age, p.gender, b.name AS barangay_name, p.date_created 
        FROM patients p
        JOIN barangay b ON p.barangay_id = b.barangay_id
        WHERE p.archived = 0
        ORDER BY p.full_name";
$result = $conn->query($sql);

// Fetch barangays for dropdown
$barangays = $conn->query("SELECT barangay_id, name FROM barangay ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Records</title>
  <link rel="stylesheet" href="assets/common_styles.css">
</head>
<body>
  <div class="container">
    <h2>Patient List</h2>
    <a href="dashboard.php" class="btn btn-back">⬅ Back to Dashboard</a>
    <a href="archived_patients.php" class="btn btn-edit">View Archived Patients</a>
    <button class="btn btn-add" onclick="openModal()">+ Add Patient</button>
    <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Barangay</th>
        <th>Date Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['patient_id']); ?></td>
            <td><?= htmlspecialchars($row['full_name']); ?></td>
            <td><?= htmlspecialchars($row['age']); ?></td>
            <td><?= htmlspecialchars($row['gender']); ?></td>
            <td><?= htmlspecialchars($row['barangay_name']); ?></td>
            <td><?= htmlspecialchars($row['date_created']); ?></td>
            <td>
              <a class="btn btn-edit" href="edit_patient.php?id=<?= $row['patient_id']; ?>">Edit</a>
              <a class="btn btn-archive" href="archive_patient.php?id=<?= $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to archive this patient?')">Archive</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No patient records found.</td></tr>
      <?php endif; ?>
    </tbody>
    </table>
  </div>

  <!-- Add Patient Modal -->
  <div id="addModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3 style="text-align:center; color:#e74c3c;">Add New Patient</h3>
      <?php if ($message) echo "<div class='message'>$message</div>"; ?>
      <form method="post">
        <input type="hidden" name="add_patient" value="1">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
        <select name="barangay_id" required>
          <option value="">Select Barangay</option>
          <?php while($b = $barangays->fetch_assoc()): ?>
            <option value="<?= $b['barangay_id']; ?>"><?= htmlspecialchars($b['name']); ?></option>
          <?php endwhile; ?>
        </select>
        <button type="submit">Add Patient</button>
      </form>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById('addModal').style.display = 'block';
    }
    function closeModal() {
      document.getElementById('addModal').style.display = 'none';
    }
    window.onclick = function(event) {
      let modal = document.getElementById('addModal');
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }
    function confirmLogout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>
</body>
</html>

