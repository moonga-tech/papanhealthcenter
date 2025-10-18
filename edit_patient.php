<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

$message = "";

// Get patient data
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = $conn->query("SELECT * FROM patients WHERE patient_id = $id");
  if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
  } else {
    header("Location: patients.php");
    exit();
  }
} else {
  header("Location: patients.php");
  exit();
}

// Fetch barangays for dropdown
$barangays = $conn->query("SELECT barangay_id, name FROM barangay ORDER BY name");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
  $full_name = $conn->real_escape_string($_POST['full_name']);
  $age = (int)$_POST['age'];
  $gender = $conn->real_escape_string($_POST['gender']);
  $barangay_id = (int)$_POST['barangay_id'];
  $patient_id = intval($_POST['patient_id']);

  $sql = "UPDATE patients SET full_name=?, age=?, gender=?, barangay_id=? WHERE patient_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sisii", $full_name, $age, $gender, $barangay_id, $patient_id);

  if ($stmt->execute()) {
    header("Location: patients.php");
    exit();
  } else {
    $message = "Error: " . $conn->error;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Patient</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 400px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
      from { opacity: 0; margin-top: -50px; }
      to { opacity: 1; margin-top: 0; }
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #4CAF50;
      margin-bottom: 10px;
    }
    .modal-header h3 {
      margin: 0;
    }
    .close {
      color: #aaa;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
    }
    .close:hover {
      color: #000;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    input, select {
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      padding: 10px;
      background: #4CAF50;
      border: none;
      color: white;
      cursor: pointer;
      border-radius: 5px;
      font-weight: bold;
    }
    button:hover {
      background: #45a049;
    }
    .message {
      text-align: center;
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <div class="modal-container">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Patient</h3>
        <a class="close" href="patients.php">&times;</a>
      </div>

      <?php if ($message) echo "<div class='message'>$message</div>"; ?>

      <form method="post">
        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient['patient_id']); ?>">
        <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
        <input type="number" name="age" placeholder="Age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="male" <?php if ($patient['gender'] == 'male') echo 'selected'; ?>>Male</option>
          <option value="female" <?php if ($patient['gender'] == 'female') echo 'selected'; ?>>Female</option>
        </select>
        <select name="barangay_id" required>
          <option value="">Select Barangay</option>
          <?php while($b = $barangays->fetch_assoc()): ?>
            <option value="<?php echo $b['barangay_id']; ?>" 
              <?php if ($patient['barangay_id'] == $b['barangay_id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($b['name']); ?>
            </option>
          <?php endwhile; ?>
        </select>
        <button type="submit" name="update_patient">Save Changes</button>
      </form>
    </div>
  </div>

</body>
</html>

