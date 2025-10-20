<?php
session_start();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header .title {
            font-size: 22px;
            font-weight: 600;
        }

        header .top-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
        }

        header .top-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
            gap: 20px;
            padding: 20px;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            height: fit-content;
        }

        .sidebar h3 {
            margin-top: 0;
            color: #e74c3c;
            text-align: center;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar a, #immunizeDropdown a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .dropdown-btn {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .dropdown-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .dropdown-container {
            display: none;
            padding-left: 15px;
        }

        .dropdown-container a {
            background: #3498db;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .dropdown-container a:hover {
            background: #2980b9;
        }

        .content {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .welcome {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .content p {
            font-size: 16px;
            color: #7f8c8d;
            line-height: 1.6;
        }
        .div-welcome {
          background-image: url(health.jpg);
          background-position: center;
          background-size: cover;
          background-repeat: no-repeat;
          background-color: rgba(50, 50, 50, 1);
          background-blend-mode: multiply;
        }
    </style>
</head>

<body>

    <header>
        <div class="title">Papan Health Center Management System</div>
        <div class="top-links">
            <a href="user_settings.php">User Settings</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="signup.php">Add Member</a>
            <?php endif; ?>
            <a href="#" onclick="confirmLogout()">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <h3>Management</h3>
            <a href="patients.php">Patients</a>
            <a href="medical_records.php">Medical Records</a>
            <a href="medicine.php">Medicines</a>
            <a href="vaccines.php">Vaccines</a>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="prenatal_records.php">Pre Natal</a>

            <div>
                <div class="dropdown-btn" onclick="toggleDropdown()">Immunize ▾</div>
                <div class="dropdown-container" id="immunizeDropdown">
                    <a href="children.php">Children</a>
                    <a href="child_immunizations.php">Child Immunization</a>
                </div>
            </div>

            <a href="vaccine_suppliers.php">Vaccine Supplier</a>
            <a href="barangay.php">Barangay</a>
            <a href="family_number.php">Family Number</a>
            <?php endif; ?>
        </div>

        <div class="content div-welcome">
            <div class="welcome" style="color: white;">
                Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)
            </div>
            <p style="color: white;">Select an option from the sidebar to begin.</p>
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        function toggleDropdown() {
            var dropdown = document.getElementById("immunizeDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }
    </script>
</body>

</html>
