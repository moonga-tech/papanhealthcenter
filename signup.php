<?php
include 'db_connect.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $full_name = trim($_POST['full_name']);
  $role = trim($_POST['role']);
  $security_question = trim($_POST['security_question']);
  $security_answer = trim($_POST['security_answer']);

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (username, password, full_name, role, security_question, security_answer) 
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $username, $hashed_password, $full_name, $role, $security_question, $security_answer);

  if ($stmt->execute()) {
    $message = "Account created successfully!";
  } else {
    $message = "Error: Username might already exist.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <style>
    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 450px;
      background: rgba(255, 255, 255, 0.95);
      padding: 50px 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
      text-align: center;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-back {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 16px;
      background: #6c757d;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-back:hover {
      background: #5a6268;
      transform: translateY(-2px);
    }

    h2 {
      color: #2c3e50;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 30px;
    }

    input, select {
      width: 100%;
      padding: 15px 20px;
      margin: 12px 0;
      border: 2px solid #e1e8ed;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    input:focus, select:focus {
      border-color: #e74c3c;
      outline: none;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
      transform: translateY(-2px);
    }

    button {
      margin: 25px 0 20px;
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
    }

    .message {
      color: #27ae60;
      background: linear-gradient(135deg, #d5f4e6, #a8e6cf);
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      font-weight: 500;
      border-left: 4px solid #27ae60;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    <h2>Add New Account</h2>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    <form method="post">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="text" name="username" placeholder="Username" required>

      <!-- PASSWORD PATTERN LOGIC STARTS HERE -->
      <input type="password" name="password" id="password" placeholder="Password" pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="Password must contain at least 8 characters including letters, numbers, and special characters" required>
      <div id="password-requirements" style="font-size: 12px; color: #666; margin-top: 5px; text-align: left;">
        Password must contain:
        <ul style="margin: 5px 0; padding-left: 20px;">
          <li id="req-length">At least 8 characters</li>
          <li id="req-letter">At least one letter</li>
          <li id="req-number">At least one number</li>
          <li id="req-special">At least one special character (@$!%*?&)</li>
        </ul>
      </div>

      <select name="role" required>
        <option value="">Select Role</option>
        <option value="staff">Staff</option>
        <option value="admin">Admin</option>
      </select>

      <select name="security_question" required>
        <option value="">Select Security Question</option>
        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
        <option value="What was your first school?">What was your first school?</option>
        <option value="What city were you born in?">What city were you born in?</option>
      </select>

      <input type="text" name="security_answer" placeholder="Your Answer" required>

      <button type="submit">Create Account</button>
    </form>
  </div>
  
  <script>
    const password = document.getElementById('password');
    const reqLength = document.getElementById('req-length');
    const reqLetter = document.getElementById('req-letter');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');
    
    password.addEventListener('input', function() {
      const value = this.value;
      
      // Check length
      if (value.length >= 8) {
        reqLength.style.color = '#27ae60';
      } else {
        reqLength.style.color = '#e74c3c';
      }
      
      // Check letter
      if (/[a-zA-Z]/.test(value)) {
        reqLetter.style.color = '#27ae60';
      } else {
        reqLetter.style.color = '#e74c3c';
      }
      
      // Check number
      if (/\d/.test(value)) {
        reqNumber.style.color = '#27ae60';
      } else {
        reqNumber.style.color = '#e74c3c';
      }
      
      // Check special character
      if (/[@$!%*?&]/.test(value)) {
        reqSpecial.style.color = '#27ae60';
      } else {
        reqSpecial.style.color = '#e74c3c';
      }
    });
  </script>
</body>
</html>

