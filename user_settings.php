<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please login first.");
}
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $security_question = trim($_POST['security_question']);
    $security_answer = trim($_POST['security_answer']);
    $password_sql = "";
    $params = [$username, $full_name, $security_question, $security_answer];
    $types = "ssss";

    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $params[] = $user_id;
    $types .= "i";

    $sql = "UPDATE users 
            SET username = ?, full_name = ?, security_question = ?, security_answer = ? 
            $password_sql
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = "✅ Settings updated successfully!";
    } else {
        $message = "❌ Failed to update settings. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <link rel="stylesheet" href="assets/common_styles.css">
    <style>
        .container {
            max-width: 500px;
        }
        .success { 
            background: linear-gradient(135deg, #d5f4e6, #a8e6cf); 
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }
        .error { 
            background: linear-gradient(135deg, #ffeaa7, #fab1a0); 
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>
<body>
<body>
  <div class="container">
    <h2>User Settings</h2>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>New Password (leave blank to keep current):</label>
        <input type="password" name="password" placeholder="Enter new password">

        <label>Security Question:</label>
        <input type="text" name="security_question" value="<?= htmlspecialchars($user['security_question']) ?>" required>

        <label>Security Answer:</label>
        <input type="text" name="security_answer" value="<?= htmlspecialchars($user['security_answer']) ?>" required>

        <button type="submit" class="btn btn-add">💾 Save Changes</button>
    </form>
    <a href="dashboard.php" class="btn btn-back" style="margin-top: 15px;">⬅ Back to Dashboard</a>
  </div>
</body>
</html>

