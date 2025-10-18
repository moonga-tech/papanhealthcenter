<?php

session_start();

include 'db_connect.php';

// Kung naka-login pa, diretso dashboard.php
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$locked = false;
$remaining_time = 0;

// Check if user is locked out
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
        $locked = true;
        $remaining_time = $_SESSION['lockout_time'] - time();
    } else {
        // Reset attempts after lockout period
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$locked) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = 'SELECT * FROM users WHERE username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Reset login attempts on successful login
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid password.';
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        }
    } else {
        $error = 'User not found.';
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    }
    
    // Lock user after 3 failed attempts
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['lockout_time'] = time() + 10; // 10 seconds lockout
        $locked = true;
        $remaining_time = 10;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
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
            width: 420px;
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        h2 {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 35px;
        }

        input {
            width: 100%;
            padding: 15px 20px;
            margin: 12px 0;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus {
            border-color: #e74c3c;
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
            transform: translateY(-2px);
        }

        input:disabled {
            background: #f1f3f4;
            color: #9aa0a6;
            cursor: not-allowed;
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

        button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
        }

        button:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error {
            color: #e74c3c;
            background: linear-gradient(135deg, #ffeaa7, #fab1a0);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            border-left: 4px solid #e74c3c;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .link {
            margin-top: 20px;
            display: inline-block;
            color: #e74c3c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .link:hover {
            color: #c0392b;
            transform: translateY(-1px);
        }

        #countdown {
            font-weight: bold;
            color: #e74c3c;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin/Staff Login</h1>
        <h2>Papan Health Center</h2>

        <?php if ($locked): ?>
            <div class='error'>Too many failed attempts. Please wait <span id='countdown'><?= $remaining_time ?></span> seconds.</div>
        <?php elseif ($error): ?>
            <div class='error'><?= $error ?> (Attempt <?= $_SESSION['login_attempts'] ?? 0 ?>/3)</div>
        <?php endif; ?>

        <form method="post" id="loginForm">
            <input type="text" name="username" placeholder="Username" required <?= $locked ? 'disabled' : '' ?>>
            <input type="password" name="password" placeholder="Password" required <?= $locked ? 'disabled' : '' ?>>
            <button type="submit" <?= $locked ? 'disabled' : '' ?>>Login</button>
        </form>

        <!-- Forgot Password link -->
        <a class="link" href="forgot_password.php">Forgot Password?</a>
    </div>

    <?php if ($locked): ?>
    <script>
        let timeLeft = <?= $remaining_time ?>;
        const countdown = document.getElementById('countdown');
        const form = document.getElementById('loginForm');
        const inputs = form.querySelectorAll('input, button');
        
        const timer = setInterval(() => {
            timeLeft--;
            countdown.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                location.reload();
            }
        }, 1000);
    </script>
    <?php endif; ?>

</body>

</html>
