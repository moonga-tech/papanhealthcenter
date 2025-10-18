<?php
include 'db_connect.php';
session_start();
$message = "";

if (!isset($_SESSION['reset_username'])) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $username = $_SESSION['reset_username'];

    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $username);

    if ($stmt->execute()) {
        $message = "Password reset successful! <a href='index.php'>Login here</a>";
        session_destroy();
    } else {
        $message = "Error resetting password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    body { font-family: Arial; }
    .container { width: 400px; margin: 200px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);}
    h2 { text-align: center; }
    input { padding: 10px; margin: 10px 0; }
    button { width: 100%; padding: 10px; background: #3cabb9ff; color: #fff; border: none; border-radius: 5px; }
    .message { text-align: center; color: green; }
  </style>
</head>
<body>
<div class="container">
  <h2>Reset Password</h2>
  <?php if ($message) echo "<p class='message'>$message</p>"; ?>
  <form method="post">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
  </form>
</div>
</body>
</html>

