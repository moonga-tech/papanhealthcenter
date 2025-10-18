<?php
include 'db_connect.php';

if (isset($_GET['record_id'])) {
    $record_id = $_GET['record_id'];
    $query = "SELECT * FROM medical_records WHERE record_id = $record_id";
    $result = mysqli_query($conn, $query);
    $record = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Medical Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        /* Modal background */
        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
        }

        /* Modal content box */
        .modal-content {
            background: #ffffff;
            padding: 25px;
            width: 500px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content h2 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #374151;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0px 0px 5px rgba(37, 99, 235, 0.5);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-save {
            background: #2563eb;
            color: white;
        }

        .btn-save:hover {
            background: #1e40af;
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
        }

        .btn-cancel:hover {
            background: #b91c1c;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="modal">
    <div class="modal-content">
        <h2>Edit Medical Record</h2>
        <form action="update_medical_record.php" method="POST">
            <input type="hidden" name="record_id" value="<?php echo $record['record_id']; ?>">

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" value="<?php echo $record['date']; ?>" required>
            </div>

            <div class="form-group">
                <label>Systolic BP</label>
                <input type="number" name="systolic_bp" value="<?php echo $record['systolic_bp']; ?>">
            </div>

            <div class="form-group">
                <label>Diastolic BP</label>
                <input type="number" name="diastolic_bp" value="<?php echo $record['diastolic_bp']; ?>">
            </div>

            <div class="form-group">
                <label>Height (cm)</label>
                <input type="number" step="0.01" name="height" value="<?php echo $record['height']; ?>">
            </div>

            <div class="form-group">
                <label>Weight (kg)</label>
                <input type="number" step="0.01" name="weight" value="<?php echo $record['weight']; ?>">
            </div>

            <div class="form-group">
                <label>Pulse</label>
                <input type="number" name="pulse" value="<?php echo $record['pulse']; ?>">
            </div>

            <div class="form-group">
                <label>Assessment</label>
                <textarea name="assessment"><?php echo $record['assessment']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Plan</label>
                <textarea name="plan"><?php echo $record['plan']; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">💾 Save</button>
                <a href="medical_records.php" class="btn btn-cancel">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

