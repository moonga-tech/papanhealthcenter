<?php
session_start();
include 'db_connect.php';

$sql = 'SELECT *, COALESCE(unit, "pieces") as unit FROM medicines WHERE archived = 0 ORDER BY date_added DESC';
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

        /* Print Styles */
        @media print {
            body { background: none; }
            .container { 
                box-shadow: none !important; 
                padding: 0 !important;
                border: none !important;
                display: block !important;
            }
            .btn, .modal, .controls, .alert { 
                display: none !important;
            }
            h2 { display: none !important; }
            
            /* Show print header and footer */
            .print-header, .print-footer { display: block !important; }
            
            /* Table styles for print */
            table { 
                width: 100% !important;
                border-collapse: collapse;
                table-layout: fixed;
                margin-top: 20px;
                font-size: 10pt;
            }
            th, td { 
                border: 1px solid #000;
                padding: 8px;
                font-size: 9pt;
                word-wrap: break-word;
            }
            th { 
                background-color: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Hide action column */
            th:last-child, td:last-child { display: none; }
            
            /* Status colors in print */
            td[style*="color: red"] {
                background: #ffcccc !important;
                color: #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            td[style*="background: #dc3545"] {
                background: #fff3cd !important;
                color: #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            /* Page breaks */
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
            
            /* Adjust column widths for print */
            th:nth-child(1), td:nth-child(1) { width: 20%; }
            th:nth-child(2), td:nth-child(2) { width: 35%; }
            th:nth-child(3), td:nth-child(3) { width: 10%; }
            th:nth-child(4), td:nth-child(4) { width: 20%; }
            th:nth-child(5), td:nth-child(5) { width: 15%; }
        }
        
        .print-header, .print-footer {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        .print-header h1 {
            margin: 0;
            color: #333;
            font-size: 24pt;
            text-align: center;
            font-weight: bold;
        }
        .print-header h2 {
            margin: 10px 0;
            color: #333;
            font-size: 18pt;
            text-align: center;
        }
        .print-header p {
            margin: 5px 0;
            color: #666;
            font-size: 10pt;
        }
        .print-summary {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .print-summary p {
            margin: 5px 0;
            font-size: 11pt;
            color: #333;
        }
        .admin-info {
            margin-top: 20px;
            text-align: left;
            padding: 20px;
        }
        .admin-info p {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="print-header">
        <h1>PAPAN HEALTH CENTER</h1>
        <h2>Medicine Inventory Report</h2>
        <p>Generated on <?= date('F d, Y h:i A') ?></p>
        <div class="print-summary">
            <?php
            // Count medicines left
            $medicinesLeft = $result->num_rows;
            
            // Count given medicines
            $givenSql = "SELECT COUNT(*) as count FROM medicine_given WHERE archived = 0";
            $givenResult = $conn->query($givenSql);
            $givenCount = $givenResult->fetch_assoc()['count'];
            
            // Count expired medicines
            $expiredSql = "SELECT COUNT(*) as count FROM medicines WHERE expiry_date < CURDATE() AND archived = 0";
            $expiredResult = $conn->query($expiredSql);
            $expiredCount = $expiredResult->fetch_assoc()['count'];
            ?>
            <p><strong>Medicines Left:</strong> <?= $medicinesLeft ?></p>
            <p><strong>Given Medicines:</strong> <?= $givenCount ?></p>
            <p><strong>Expired Medicines:</strong> <?= $expiredCount ?></p>
        </div>
    </div>

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
        <button class="btn btn-edit" onclick="printMedicineList()">Print All</button>
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
                    $unit = isset($row['unit']) ? $row['unit'] : 'pieces';
                    if ($row['stock'] <= 5) {
                        echo "<td style='color: red; font-weight: bold; background: #ffe6e6;'>" . $row['stock'] . ' ' . $unit . ' ⚠️</td>';
                    } else {
                        echo '<td>' . $row['stock'] . ' ' . $unit . '</td>';
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
                            onclick="openEditModal(<?= $row['medicine_id'] ?>, '<?= $row['medicine_name'] ?>', '<?= $row['description'] ?>', <?= $row['stock'] ?>, '<?= $row['unit'] ?>', '<?= $row['expiry_date'] ?>')">
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
                <label>Unit of Measurement:</label>
                <select name="unit" required>
                    <option value="pieces">Pieces</option>
                    <option value="boxes">Boxes</option>
                    <option value="bottles">Bottles</option>
                    <option value="milligram">Milligram (mg)</option>
                    <option value="gram">Gram (g)</option>
                    <option value="milliliter">Milliliter (ml)</option>
                    <option value="litre">Litre (L)</option>
                </select>
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
                <label>Unit of Measurement:</label>
                <select name="unit" id="edit_unit" required>
                    <option value="pieces">Pieces</option>
                    <option value="boxes">Boxes</option>
                    <option value="bottles">Bottles</option>
                    <option value="milligram">Milligram (mg)</option>
                    <option value="gram">Gram (g)</option>
                    <option value="milliliter">Milliliter (ml)</option>
                    <option value="litre">Litre (L)</option>
                </select>
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

    <div class="print-footer">
        <p>Papan Health Center Medicine Inventory - Page {N}</p>
    </div>

    <script>
        function printMedicineList() {
            // Add page numbers and styles
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    .print-footer {
                        position: fixed;
                        bottom: 0;
                        width: 100%;
                        text-align: center;
                        font-size: 9pt;
                        color: #666;
                    }
                    .print-footer::after {
                        content: "Page " counter(page) " of " counter(pages);
                    }
                    @page {
                        margin: 15mm 10mm 15mm 10mm;
                        counter-increment: page;
                        counter-reset: pages;
                        @bottom-center {
                            content: counter(page) " of " counter(pages);
                        }
                    }
                    /* Ensure table header repeats on each page */
                    thead {
                        display: table-header-group;
                    }
                    tfoot {
                        display: table-footer-group;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Create footer with administrator info
            const footer = document.createElement('div');
            footer.className = 'print-footer';
            footer.innerHTML = `
                <p>Papan Health Center - Medicine Inventory Report</p>
                <div class="admin-info">
                    <p><strong>Administered by:</strong> <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : '_____________________'; ?></p>
                    <p><strong>Position:</strong> <?php echo isset($_SESSION['role']) ? htmlspecialchars(ucfirst($_SESSION['role'])) : '_____________________'; ?></p>
                    <p><strong>Date:</strong> <?php echo date('F d, Y'); ?></p>
                </div>
            `;
            document.body.appendChild(footer);
            
            window.print();
            
            // Cleanup
            setTimeout(() => {
                document.head.removeChild(style);
                document.body.removeChild(footer);
            }, 1000);
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(id, name, description, stock, unit, expiry) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_unit').value = unit || 'pieces';
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
            // Quantity is in the 3rd column (index 2)
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const stockCell = row.cells[2];
                if (!stockCell) return;
                // extract the first integer from the cell (handles units and emojis)
                const m = stockCell.textContent.match(/-?\d+/);
                const stockValue = m ? parseInt(m[0], 10) : 0;
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
            // Expiry date is in the 4th column (index 3)
            const rows = document.querySelectorAll('tbody tr');
            const today = new Date();
            // Normalize today's date to midnight
            today.setHours(0,0,0,0);
            rows.forEach(row => {
                const expiryCell = row.cells[3];
                if (!expiryCell) return;
                const text = expiryCell.textContent || '';
                // Try to extract a YYYY-MM-DD date from the cell
                const dateMatch = text.match(/(\d{4}-\d{2}-\d{2})/);
                if (!dateMatch) {
                    // no valid date -> hide
                    row.style.display = 'none';
                    return;
                }
                const expDate = new Date(dateMatch[1] + 'T00:00:00');
                expDate.setHours(0,0,0,0);
                const diffMs = expDate - today;
                const diffDays = Math.round(diffMs / (1000 * 60 * 60 * 24));
                // show if expires within 0..3 days
                if (diffDays >= 0 && diffDays <= 3) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
