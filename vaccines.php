<?php
session_start();
include 'db_connect.php';

// Fetch all vaccine records with supplier
$sql = 'SELECT v.vaccine_id, v.vaccine_name, v.description, v.quantity, v.total_doses, v.recommended_ages, v.expiry_date, v.date_received, v.lot_number, s.supplier_name, COALESCE(v.unit, "pieces") as unit FROM vaccines v JOIN vaccine_suppliers s ON v.supplier_id = s.supplier_id WHERE v.archived = 0 ORDER BY v.date_received DESC';

$result = $conn->query($sql);

// Fetch vaccines that are expired or will expire within 3 days
$soonSql = "SELECT vaccine_id, vaccine_name, expiry_date
            FROM vaccines
            WHERE expiry_date IS NOT NULL
              AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
            ORDER BY expiry_date ASC";
$soonRes = $conn->query($soonSql);

// helper function for days left (server-side)
function days_left($expiry_date)
{
    if (!$expiry_date) {
        return null;
    }
    try {
        $expiry = new DateTime($expiry_date);
        $today = new DateTime('today');
        // difference
        $interval = $today->diff($expiry);
        // if expiry < today, diff->invert == 1
        if ($interval->invert) {
            // expired days ago
            return -(int) $interval->days;
        } else {
            return (int) $interval->days;
        }
    } catch (Exception $e) {
        return null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vaccine Records</title>
    <link rel="stylesheet" href="assets/common_styles.css">
    <style>
        /* Print Styles */
        @media print {
            body { background: none; }
            .container { box-shadow: none; padding: 0; }
            .btn, .modal, .controls, .alert { display: none !important; }
            
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
            
            /* Badge colors in print */
            .badge {
                border: 1px solid #000;
                padding: 2px 6px;
                font-size: 8pt;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .badge-expired { background: #ffcccc !important; color: #000 !important; }
            .badge-soon { background: #fff3cd !important; color: #000 !important; }
            .badge-ok { background: #d4edda !important; color: #000 !important; }
            
            /* Page breaks */
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
            
            /* Adjust column widths for print */
            th:nth-child(1), td:nth-child(1) { width: 5%; }
            th:nth-child(2), td:nth-child(2) { width: 15%; }
            th:nth-child(3), td:nth-child(3) { width: 20%; }
            th:nth-child(4), td:nth-child(4) { width: 10%; }
            th:nth-child(5), td:nth-child(5) { width: 7%; }
            th:nth-child(6), td:nth-child(6) { width: 8%; }
            th:nth-child(7), td:nth-child(7) { width: 12%; }
            th:nth-child(8), td:nth-child(8) { width: 8%; }
            th:nth-child(9), td:nth-child(9) { width: 8%; }
            th:nth-child(10), td:nth-child(10) { width: 7%; }
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
    </style>
</head>

<body>
    <div class="print-header">
        <h1>PAPAN HEALTH CENTER</h1>
        <h2>Vaccine Inventory Report</h2>
        <p>Generated on <?= date('F d, Y h:i A') ?></p>
        <div class="print-summary">
            <?php
            // total vaccine types (rows)
            $totalTypes = $result->num_rows;
            // total stock across all vaccines
            $totalStockRes = $conn->query("SELECT IFNULL(SUM(quantity),0) as total FROM vaccines WHERE archived = 0");
            $totalStock = $totalStockRes ? $totalStockRes->fetch_assoc()['total'] : 0;
            // total given doses (count of immunization records)
            $totalGivenRes = $conn->query("SELECT COUNT(*) as total FROM child_immunizations");
            $totalGiven = $totalGivenRes ? $totalGivenRes->fetch_assoc()['total'] : 0;
            // total left (stock - given)
            $totalLeft = max(0, intval($totalStock) - intval($totalGiven));
            // count expired vaccine types
            $expiredRes = $conn->query("SELECT COUNT(*) as total FROM vaccines WHERE expiry_date < CURDATE() AND archived = 0");
            $expiredTypes = $expiredRes ? $expiredRes->fetch_assoc()['total'] : 0;
            ?>
            <p><strong>Vaccine Types:</strong> <?= $totalTypes ?></p>
            <p><strong>Total Stock (in units):</strong> <?= $totalStock ?></p>
            <p><strong>Given Vaccines (doses):</strong> <?= $totalGiven ?></p>
            <p><strong>Total Left (stock - given):</strong> <?= $totalLeft ?></p>
            <p><strong>Expired Vaccine Types:</strong> <?= $expiredTypes ?></p>
        </div>
    </div>

    <div class="container">
        <h2>Vaccine Records</h2>

        <div style="margin-bottom: 15px;">
            <?php if ($soonRes && $soonRes->num_rows > 0): ?>
            <?php
            $expiring = [];
            while ($r = $soonRes->fetch_assoc()) {
                $d = days_left($r['expiry_date']);
                $expiring[] = ['name' => $r['vaccine_name'], 'expiry' => $r['expiry_date'], 'days' => $d];
            }
            ?>
            <?php
            // Separate expired vs soon
            $expiredList = array_filter($expiring, fn($x) => $x['days'] !== null && $x['days'] < 0);
            $soonList = array_filter($expiring, fn($x) => $x['days'] !== null && $x['days'] >= 0);
            ?>
            <?php if (count($expiredList) > 0): ?>
            <div class="alert alert-danger">
                <strong>Expired:</strong>
                <?php foreach ($expiredList as $it) {
                    echo htmlspecialchars($it['name']) . ' (expired ' . abs($it['days']) . ' days ago) ';
                } ?>
            </div>
            <?php endif; ?>

            <?php if (count($soonList) > 0): ?>
            <div class="alert alert-warning">
                <strong>Expiring soon (within 3 days):</strong>
                <?php foreach ($soonList as $it) {
                    echo htmlspecialchars($it['name']) . ' (in ' . $it['days'] . ' days) ';
                } ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;">
            <div style="display:flex;gap:10px;align-items:center;">
                <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
                <a href="archived_vaccines.php" class="btn btn-edit">View Archived Vaccines</a>
                <button class="btn btn-add" onclick="openAddModal()">+ Add Vaccine</button>
                <a href="view_vaccine_requests.php" class="btn btn-edit">📄 View Requests</a>
                <a href="given_vaccines.php" class="btn btn-edit">Given Vaccines</a>
                <a href="expired_vaccines.php" class="btn btn-edit">Expired Vaccines</a>
                <a href="vaccine_inventory.php" class="btn btn-edit">Vaccine Inventory</a>
                <button class="btn btn-edit" onclick="printAll()">Print All</button>
            </div>
            <div class="muted">Total records: <strong><?= $result->num_rows ?></strong></div>
        </div>

        <div class="controls">
            <label for="filterSelect" class="muted">Filter:</label>
            <select id="filterSelect">
                <option value="all">All</option>
                <option value="expired">Expired</option>
                <option value="expiring_3">Expiring within 3 days</option>
                <option value="low_stock">Low stock (&lt;5)</option>
            </select>

            <label for="sortSelect" class="muted">Sort:</label>
            <select id="sortSelect">
                <option value="default">Default (date received)</option>
                <option value="expiry_asc">Expiry ↑</option>
                <option value="expiry_desc">Expiry ↓</option>
                <option value="qty_asc">Quantity ↑</option>
                <option value="qty_desc">Quantity ↓</option>
            </select>

            <button class="btn-request" id="clearFilters">Clear</button>
        </div>



        <table id="vaccineTable" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 12%;">Vaccine Name</th>
                    <th style="width: 15%;">Description</th>
                    <th style="width: 10%;">Supplier</th>
                    <th style="width: 8%;">Stock In</th>
                    <th style="width: 8%;">Given</th>
                    <th style="width: 8%;">Left</th>
                    <th style="width: 8%;">Expiry Date</th>
                    <th style="width: 8%;">Lot Number</th>
                    <th style="width: 8%;">Total Doses</th>
                    <th style="width: 10%;">Recommended Ages</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 8%;">Date Received</th>
                    <th style="width: 12%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=$result->fetch_assoc()): ?>
                <?php 
                $days = days_left($row['expiry_date']); 
                $low = intval($row['quantity']) < 5;
                
                // Calculate given vaccines for this vaccine
                // count immunization records for this vaccine (each record equals one dose given)
                $givenVaccinesSql = "SELECT COUNT(*) as count FROM child_immunizations WHERE vaccine_id = {$row['vaccine_id']} AND archived = 0";
                $givenVaccinesResult = $conn->query($givenVaccinesSql);
                $givenVaccines = $givenVaccinesResult->fetch_assoc()['count'];
                
                // Check if expired
                $isExpired = $days !== null && $days < 0 ? 1 : 0;
                
                // Vaccines left (quantity - given)
                $vaccinesLeft = max(0, intval($row['quantity']) - $givenVaccines);
                ?>
                <tr class="<?= $low ? 'low-stock' : '' ?>" data-expiry-days="<?= $days === null ? '' : $days ?>"
                    data-quantity="<?= intval($row['quantity']) ?>" data-given="<?= intval($givenVaccines) ?>" data-left="<?= intval($vaccinesLeft) ?>" data-expiry-date="<?= $row['expiry_date'] ?>">
                    <td><?= $row['vaccine_id'] ?></td>
                    <td><?= htmlspecialchars($row['vaccine_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                    <td><?= intval($row['quantity']) . ' ' . htmlspecialchars($row['unit']) ?></td>
                    <td><?= intval($givenVaccines) ?></td>
                    <td><?= intval($vaccinesLeft) ?></td>
                    <td><?= $row['expiry_date'] ?></td>
                    <td><?= htmlspecialchars($row['lot_number']) ?></td>
                    <td><?= $row['total_doses'] ?></td>
                    <td><?= htmlspecialchars($row['recommended_ages']) ?></td>
                    <td>
                        <?php if ($days === null): ?>
                        <span class="badge badge-ok">No date</span>
                        <?php elseif ($days < 0): ?>
                        <span class="badge badge-expired">Expired <?= abs($days) ?>d</span>
                        <?php elseif ($days <= 3): ?>
                        <span class="badge badge-soon">Expiring in <?= $days ?>d</span>
                        <?php else: ?>
                        <span class="badge badge-ok">OK (<?= $days ?>d)</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['date_received'] ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="openEditModal(<?= $row['vaccine_id'] ?>)">Edit</button>
                        <a href="archive_vaccine.php?id=<?= $row['vaccine_id'] ?>" class="btn btn-archive"
                            onclick="return confirm('Are you sure to archive this record?')">Archive</a>
                        <?php $isExpired = $days !== null && $days < 0; ?>
                        <button class="btn btn-edit<?= $isExpired ? ' disabled' : '' ?>"
                            <?= $isExpired ? 'disabled' : '' ?>
                            onclick="openRequestModal(<?= $row['vaccine_id'] ?>, '<?= htmlspecialchars(addslashes($row['vaccine_name'])) ?>')">Request
                            Stock</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add Vaccine</h2>
            <form action="add_vaccine.php" method="POST">
                <label>Vaccine Name:</label>
                <input type="text" name="vaccine_name" required>
                <label>Description:</label>
                <textarea name="description"></textarea>
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <?php
                    $suppliers = $conn->query('SELECT * FROM vaccine_suppliers');
                    while ($s = $suppliers->fetch_assoc()) {
                        echo "<option value='{$s['supplier_id']}'>{$s['supplier_name']}</option>";
                    }
                    ?>
                </select>
                <label>Quantity:</label>
                <input type="number" name="quantity" required>
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
                <label>Total Doses:</label>
                <input type="number" name="total_doses" required>
                <label>Recommended Ages:</label>
                <input type="text" name="recommended_ages" placeholder="e.g. 0-6 months, 9 months" required>
                <label>Expiry Date:</label>
                <input type="date" name="expiry_date" required>
                <label>Date Received:</label>
                <input type="date" name="date_received" required>
                <label>Lot Number:</label>
                <input type="text" name="lot_number" required>
                <button type="submit" class="btn btn-add">Save</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content" id="editFormContainer"></div>
    </div>

    <!-- Request Modal -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRequestModal()">&times;</span>
            <h2>Request Vaccine Stock</h2>
            <form action="request_vaccine_stock.php" method="POST">
                <input type="hidden" id="req_vaccine_id" name="vaccine_id">
                <label>Vaccine:</label>
                <input type="text" id="req_vaccine_name" disabled>
                <label>Quantity to Request:</label>
                <input type="number" name="quantity" required>
                <button type="submit" class="btn btn-add">Submit Request</button>
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

        function openEditModal(id) {
            fetch('fetch_vaccine.php?id=' + id)
                .then(res => res.text())
                .then(data => {
                    document.getElementById('editFormContainer').innerHTML = data;
                    document.getElementById('editModal').style.display = 'block';
                });
        }

        function openRequestModal(id, name) {
            document.getElementById('req_vaccine_id').value = id;
            document.getElementById('req_vaccine_name').value = name;
            document.getElementById('requestModal').style.display = 'block';
        }

        function closeRequestModal() {
            document.getElementById('requestModal').style.display = 'none';
        }

        // Filtering and sorting logic (client-side)
        document.addEventListener('DOMContentLoaded', () => {
            const filterSelect = document.getElementById('filterSelect');
            const sortSelect = document.getElementById('sortSelect');
            const clearBtn = document.getElementById('clearFilters');
            if (filterSelect) filterSelect.addEventListener('change', applyFilters);
            if (sortSelect) sortSelect.addEventListener('change', applyFilters);
            if (clearBtn) clearBtn.addEventListener('click', () => {
                filterSelect.value = 'all';
                sortSelect.value = 'default';
                applyFilters();
            });
        });

        function applyFilters() {
            const filter = document.getElementById('filterSelect').value;
            const sort = document.getElementById('sortSelect').value;
            const table = document.getElementById('vaccineTable');
            if (!table) return;
            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Filter
            rows.forEach(r => {
                const daysAttr = r.getAttribute('data-expiry-days');
                const qty = parseInt(r.getAttribute('data-quantity') || '0', 10);
                const leftQty = parseInt(r.getAttribute('data-left') || '0', 10);
                const days = daysAttr === '' ? null : parseInt(daysAttr, 10);
                let show = true;
                if (filter === 'expired') show = (days !== null && days < 0);
                if (filter === 'expiring_3') show = (days !== null && days >= 0 && days <= 3);
                if (filter === 'low_stock') show = (leftQty < 5);
                r.style.display = show ? '' : 'none';
            });

            // Sort (only visible rows considered)
            const visibleRows = rows.filter(r => r.style.display !== 'none');
            let comparator = null;
            if (sort === 'expiry_asc') comparator = (a, b) => compareExpiry(a, b, 1);
            if (sort === 'expiry_desc') comparator = (a, b) => compareExpiry(a, b, -1);
            if (sort === 'qty_asc') comparator = (a, b) => compareQty(a, b, 1);
            if (sort === 'qty_desc') comparator = (a, b) => compareQty(a, b, -1);

            if (comparator) {
                visibleRows.sort(comparator);
                // re-append in order
                visibleRows.forEach(r => tbody.appendChild(r));
            }
        }

        function compareExpiry(a, b, dir) {
            const ax = a.getAttribute('data-expiry-date') || '';
            const bx = b.getAttribute('data-expiry-date') || '';
            // treat empty expiry as far future
            const ad = ax ? new Date(ax) : new Date(8640000000000000);
            const bd = bx ? new Date(bx) : new Date(8640000000000000);
            return (ad - bd) * dir;
        }

        function compareQty(a, b, dir) {
            const aq = parseInt(a.getAttribute('data-quantity') || '0', 10);
            const bq = parseInt(b.getAttribute('data-quantity') || '0', 10);
            return (aq - bq) * dir;
        }

        function printAll() {
            // Add page numbers
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
                <p>Papan Health Center - Vaccine Inventory Report</p>
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
    </script>
</body>

</html>
