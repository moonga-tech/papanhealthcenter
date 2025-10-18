<?php
include 'db_connect.php';
session_start();
$message = "";

if (!isset($_SESSION['reset_username']) || !isset($_SESSION['security_question'])) {
    header("Location: forgot_password.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = trim($_POST['security_answer']);
    $username = $_SESSION['reset_username'];

    $sql = "SELECT security_answer FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (strcasecmp($row['security_answer'], $answer) == 0) {
            header("Location: reset_password.php");
            exit;
        } else {
            $message = "Incorrect answer!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Security Question</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    body { font-family: Arial; }
    .container { width: 400px; margin: 200px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);}
    h2 { text-align: center; }
    input { padding: 10px; margin: 10px 0; }
    button { width: 100%; padding: 10px; background: #4697acff; color: #fff; border: none; border-radius: 5px; }
    .message { color: red; text-align: center; }
  </style>
</head>
<body>
<div class="container">
  <h2>Security Question</h2>
  <p><b>Question:</b> <?= htmlspecialchars($_SESSION['security_question']); ?></p>
  <?php if ($message) echo "<p class='message'>$message</p>"; ?>
  <form method="post">
    <input type="text" name="security_answer" placeholder="Your Answer" required>
    <button type="submit">Verify</button>
  </form>
</div>
</body>
</html>

