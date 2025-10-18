<?php
session_start();
include 'db_connection.php';  // your DB connect file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['full_name'] = $user['full_name'];
      $_SESSION['role'] = $user['role'];

      header("Location: dashboard.php");
      exit();
    } else {
      header("Location: login.php?error=Invalid Password");
      exit();
    }
  } else {
    header("Location: login.php?error=User not found");
    exit();
  }
} else {
  header("Location: login.php");
  exit();
}
?>

