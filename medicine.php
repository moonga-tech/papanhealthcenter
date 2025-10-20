<?php
include 'db_connect.php';

$sql = 'SELECT * FROM medicines WHERE archived = 0 ORDER BY date_added DESC';
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Medicine Management</title>
    <link rel="stylesheet" href="assets/common_styles.css">
    <style>
        .btn-given {
            background: #2766ae;
            color: white;
        }

        .btn-given:hover {
            background: #1e4f87;
            transform: translateY(-2px);
        }

        .btn-request {
            background: #f39c12;
            color: white;
        }

        .btn-request:hover {
            background: #d35400;
            transform: translateY(-2px);
        }

        /* Override modal styles for medicine page */
        .modal {
            width: 100%;
        }

        .modal-content {
            width: 40%;
        }

        .modal input,
        .modal textarea,
        .modal select {
            width: 94%;
        }

        .modal input:focus,
        .modal textarea:focus,
        .modal select:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .box {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div class="container box">
        <h2>Medicine Management</h2>

        <?php
        // Check for low stock medicines
        $low_stock_sql = 'SELECT medicine_name, stock FROM medicines WHERE stock <= 5 ORDER BY stock ASC';
        $low_stock_result = $conn->query($low_stock_sql);
        if ($low_stock_result->num_rows > 0) {
            echo '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin-bottom: 20px;">';
            echo '<h3 style="color: #856404; margin: 0 0 10px 0;">⚠️ Low Stock Alert</h3>';
            while ($low_row = $low_stock_result->fetch_assoc()) {
                echo '<span style="background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 5px; margin: 3px; display: inline-block;">';
                echo $low_row['medicine_name'] . ' (' . $low_row['stock'] . ' left)</span>';
            }
            echo '</div>';
        }
        
        // Check for medicines expiring within 3 days
        $expiry_sql = 'SELECT medicine_name, expiry_date, DATEDIFF(expiry_date, CURDATE()) as days_left FROM medicines WHERE expiry_date IS NOT NULL AND DATEDIFF(expiry_date, CURDATE()) <= 3 AND DATEDIFF(expiry_date, CURDATE()) >= 0 ORDER BY expiry_date ASC';
        $expiry_result = $conn->query($expiry_sql);
        if ($expiry_result->num_rows > 0) {
            echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">';
            echo '<h3 style="color: #721c24; margin: 0 0 10px 0;">🚨 Expiry Alert - Expires Within 3 Days</h3>';
            while ($exp_row = $expiry_result->fetch_assoc()) {
                $days_text = $exp_row['days_left'] == 0 ? 'TODAY' : $exp_row['days_left'] . ' days';
                echo '<span style="background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; margin: 3px; display: inline-block;">';
                echo $exp_row['medicine_name'] . ' (Expires in ' . $days_text . ')</span>';
            }
            echo '</div>';
        }
        ?>

        <a href="dashboard.php" class="btn btn-back">⬅ Back to Dashboard</a>
        <a href="archived_medicines.php" class="btn btn-edit">View Archived Medicines</a>
        <a href="medicine_given.php" class="btn btn-given">📋 Given Medicines</a>
        <a href="view_stock_requests.php" class="btn btn-request">📦 View Requests</a>
        <a href="expired_medicines.php" class="btn btn-edit">Expired Medicines</a>
        <a href="medicine_inventory.php" class="btn btn-edit">Medicine Inventory</a>
        <button class="btn btn-add" onclick="openAddModal()">+ Add Medicine</button>
        <button class="btn btn-edit" onclick="window.print()">Print All</button>
        <button class="btn" style="background: #dc3545; color: white;" onclick="filterLowStock()">⚠️ Show Low Stock
            Only</button>
        <button class="btn" style="background: #fd7e14; color: white;" onclick="filterExpiring()">🚨 Show Expiring
            Soon</button>
        <button class="btn" style="background: #28a745; color: white;" onclick="showAllStock()">📋 Show All</button>

        <!-- table to display data from the database -->
        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>

                <tr>
                    <td><?= $row['medicine_name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <?php
                    if ($row['stock'] <= 5) {
                        echo "<td style='color: red; font-weight: bold; background: #ffe6e6;'>" . $row['stock'] . ' ⚠️</td>';
                    } else {
                        echo '<td>' . $row['stock'] . '</td>';
                    }
                    ?>
                    <?php
                    if ($row['expiry_date']) {
                        $days_left = (strtotime($row['expiry_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                        if ($days_left <= 3 && $days_left >= 0) {
                            $days_text = $days_left == 0 ? 'TODAY' : round($days_left) . ' days';
                            echo "<td style='color: white; font-weight: bold; background: #dc3545;'>" . $row['expiry_date'] . " 🚨<br><small>Expires in $days_text</small></td>";
                        } else {
                            echo '<td>' . $row['expiry_date'] . '</td>';
                        }
                    } else {
                        echo '<td>N/A</td>';
                    }
                    ?>
                    <td><?= $row['date_added'] ?></td>
                    <td>
                        <button class="btn btn-edit"
                            onclick="openEditModal(<?= $row['medicine_id'] ?>, '<?= $row['medicine_name'] ?>', '<?= $row['description'] ?>', <?= $row['stock'] ?>, '<?= $row['expiry_date'] ?>')">
                            Edit
                        </button>
                        <a href="archive_medicine.php?id=<?= $row['medicine_id'] ?>"
                            onclick="return confirm('Archive this medicine?')" class="btn btn-archive">Archive</a>
                        <button class="btn btn-request"
                            onclick="openRequestModal(<?= $row['medicine_id'] ?>, '<?= $row['medicine_name'] ?>')">
                            Request Stock
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Medicine Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add Medicine</h2>
            <form action="add_medicine.php" method="POST">
                <input type="text" name="medicine_name" placeholder="Medicine Name" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="number" name="stock" placeholder="Stock" required>
                <label>Expiry Date:</label>
                <input type="date" name="expiry_date" required>
                <button type="submit" class="btn btn-add">Save</button>
            </form>
        </div>
    </div>

    <!-- Edit Medicine Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Medicine</h2>
            <form action="update_medicine.php" method="POST">
                <input type="hidden" name="medicine_id" id="edit_id">
                <input type="text" name="medicine_name" id="edit_name" required>
                <textarea name="description" id="edit_description"></textarea>
                <input type="number" name="stock" id="edit_stock" required>
                <label>Expiry Date:</label>
                <input type="date" name="expiry_date" id="edit_expiry" required>
                <button type="submit" class="btn btn-edit">Update</button>
            </form>
        </div>
    </div>

    <!-- Stock Request Modal -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRequestModal()">&times;</span>
            <h2>Request Stock</h2>
            <form action="stock_request.php" method="POST">
                <input type="hidden" name="medicine_id" id="req_id">
                <label>Medicine Name:</label>
                <input type="text" id="req_name" disabled>
                <label>Quantity:</label>
                <input type="number" name="quantity" required>
                <button type="submit" class="btn btn-request">Submit Request</button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(id, name, description, stock, expiry) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_expiry').value = expiry;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openRequestModal(id, name) {
            document.getElementById('req_id').value = id;
            document.getElementById('req_name').value = name;
            document.getElementById('requestModal').style.display = 'block';
        }

        function closeRequestModal() {
            document.getElementById('requestModal').style.display = 'none';
        }

        // Check for low stock and expiry on page load
        window.onload = function() {
            <?php
            $low_stock_check = $conn->query('SELECT COUNT(*) as count FROM medicines WHERE stock <= 5');
            $low_count = $low_stock_check->fetch_assoc()['count'];
            
            $expiry_check = $conn->query('SELECT COUNT(*) as count FROM medicines WHERE expiry_date IS NOT NULL AND DATEDIFF(expiry_date, CURDATE()) <= 3 AND DATEDIFF(expiry_date, CURDATE()) >= 0');
            $exp_count = $expiry_check->fetch_assoc()['count'];
            
            $alerts = [];
            if ($low_count > 0) {
                $alerts[] = "$low_count medicine(s) have low stock (≤5 units)";
            }
            if ($exp_count > 0) {
                $alerts[] = "$exp_count medicine(s) expire within 3 days";
            }
            
            if (!empty($alerts)) {
                echo "alert('WARNING:\\n" . implode('\\n', $alerts) . "');";
            }
            ?>
        }

        /* filters low stock */

        function filterLowStock() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const stockCell = row.cells[3];
                const stockValue = parseInt(stockCell.textContent.replace(/[^0-9]/g, ''));
                if (stockValue > 5) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        }

        function showAllStock() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        function filterExpiring() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const expiryCell = row.cells[4];
                const isExpiring = expiryCell.style.background === 'rgb(220, 53, 69)' || expiryCell.innerHTML
                    .includes('🚨');
                if (!isExpiring) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        }
    </script>
</body>

</html>
