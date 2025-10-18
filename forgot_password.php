<?php
include 'db_connect.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    
    $sql = "SELECT security_question FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION['reset_username'] = $username;
        $_SESSION['security_question'] = $row['security_question'];
        header("Location: security_question.php");
        exit;
    } else {
        $message = "Username not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    body { font-family: Arial; }
    .container { width: 400px; margin: 200px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(170, 60, 60, 0.1);}
    h2 { text-align: center; }
    input { padding: 10px; margin: 10px 0; }
    button { width: 100%; padding: 10px; background: #2e6a81ff; color: #fff; border: none; border-radius: 5px; }
    .message { color: red; text-align: center; }
  </style>
</head>
<body>
<div class="container">
  <h2>Forgot Password</h2>
  <?php if ($message) echo "<p class='message'>$message</p>"; ?>
  <form method="post">
    <input type="text" name="username" placeholder="Enter Username" required>
    <button type="submit">Next</button>
  </form>
</div>
</body>
</html>

