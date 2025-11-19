<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

 $sql = "SELECT v.vaccine_id, v.vaccine_name, v.description, v.quantity, v.total_doses,
     v.recommended_ages, v.expiry_date, v.date_received, v.lot_number, s.supplier_name,
     (SELECT COUNT(*) FROM child_immunizations ci WHERE ci.vaccine_id = v.vaccine_id) as total_given,
     (v.quantity - (SELECT COUNT(*) FROM child_immunizations ci2 WHERE ci2.vaccine_id = v.vaccine_id)) as quantity_left,
     CASE
       WHEN v.quantity <= 5 THEN 'Low Stock'
       WHEN v.quantity <= 20 THEN 'Medium Stock'
       ELSE 'Good Stock'
     END as stock_status,
     CASE
       WHEN v.expiry_date < CURDATE() THEN 'Expired'
       WHEN DATEDIFF(v.expiry_date, CURDATE()) <= 30 THEN 'Expiring Soon'
       ELSE 'Good'
     END as expiry_status
   FROM vaccines v
   JOIN vaccine_suppliers s ON v.supplier_id = s.supplier_id
   WHERE v.archived = 0
   ORDER BY v.vaccine_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccine Inventory</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .low-stock { background-color: #ffebee; color: #c62828; }
    .medium-stock { background-color: #fff3e0; color: #ef6c00; }
    .good-stock { background-color: #e8f5e8; color: #2e7d32; }
    .expired { background-color: #ffcdd2; color: #d32f2f; }
    .expiring-soon { background-color: #ffe0b2; color: #f57c00; }
    /* Print styles (match medicine_inventory) */
    @media print {
      body { background: none !important; color: #000 !important; }
      .container { box-shadow: none !important; padding: 0 !important; margin: 0 !important; }
      .btn, .btn-edit, .btn-back { display: none !important; }
      a.btn { display: none !important; }
      h2 { display: none; }

      /* Show printable header/footer */
      .print-header, .print-footer { display: block !important; }

      table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
        font-size: 10pt !important;
      }
      thead { display: table-header-group; }
      tr { page-break-inside: avoid; }
      th, td {
        border: 1px solid #000 !important;
        padding: 6px !important;
        vertical-align: top !important;
        word-wrap: break-word !important;
      }
      th { background: #f0f0f0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

      /* Hide actions (if any) */
      th:last-child, td:last-child { display: none !important; }

      .print-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 9pt;
        color: #333;
        text-align: center;
        padding: 6px 0;
      }
      @page { margin: 15mm 10mm 25mm 10mm; }
      .page-number:after { content: "Page " counter(page) " of " counter(pages); }
    }

    .print-header, .print-footer { display: none; }
    .print-header { text-align: center; margin: 18px 0; }
    .print-header h1 { margin: 0; font-size: 20pt; }
    .print-header p { margin: 4px 0; color: #555; }
    .print-summary { margin-top: 8px; font-size: 11pt; }
  </style>
</head>
<body>
  <!-- Print header (hidden on screen) -->
  <div class="print-header">
    <h1>PAPAN HEALTH CENTER</h1>
    <h2>Vaccine Inventory Report</h2>
    <p>Generated on <?= date('F d, Y h:i A') ?></p>
    <div class="print-summary">
      <?php
        // aggregates
        $totalTypes = $result->num_rows;
        $totalStockRes = $conn->query("SELECT IFNULL(SUM(quantity),0) as total FROM vaccines WHERE archived = 0");
        $totalStock = $totalStockRes ? $totalStockRes->fetch_assoc()['total'] : 0;
        $totalGivenRes = $conn->query("SELECT COUNT(*) as total FROM child_immunizations");
        $totalGiven = $totalGivenRes ? $totalGivenRes->fetch_assoc()['total'] : 0;
        $totalLeft = max(0, intval($totalStock) - intval($totalGiven));
        $expiredRes = $conn->query("SELECT COUNT(*) as total FROM vaccines WHERE expiry_date < CURDATE() AND archived = 0");
        $expiredTypes = $expiredRes ? $expiredRes->fetch_assoc()['total'] : 0;
      ?>
      <p><strong>Vaccine Types:</strong> <?= $totalTypes ?></p>
      <p><strong>Total Stock (units):</strong> <?= $totalStock ?></p>
      <p><strong>Given (doses):</strong> <?= $totalGiven ?></p>
      <p><strong>Total Left (stock - given):</strong> <?= $totalLeft ?></p>
      <p><strong>Expired Types:</strong> <?= $expiredTypes ?></p>
    </div>
  </div>

  <div class="container">
    <h2>Vaccine Inventory</h2>
    <a href="vaccines.php" class="btn btn-back">← Back to Vaccines</a>
    <button class="btn btn-edit" onclick="printInventory()">Print Inventory</button>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Vaccine Name</th>
          <th>Description</th>
          <th>Supplier</th>
          <th>Quantity</th>
          <th>Total Given</th>
          <th>Quantity Left</th>
          <th>Stock Status</th>
          <th>Total Doses</th>
          <th>Expiry Date</th>
          <th>Expiry Status</th>
          <th>Lot Number</th>
          <th>Date Received</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['vaccine_id']); ?></td>
              <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td><?= htmlspecialchars($row['supplier_name']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['quantity']); ?>
              </td>
              <td><?= htmlspecialchars($row['total_given']); ?></td>
              <td><?= htmlspecialchars($row['quantity_left'] >= 0 ? $row['quantity_left'] : 0); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['total_doses']); ?></td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['expiry_status'])); ?>">
                <?= htmlspecialchars($row['expiry_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['lot_number']); ?></td>
              <td><?= htmlspecialchars($row['date_received']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="11" style="text-align:center;">No vaccines in inventory.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  
  <!-- Print footer (hidden on screen) -->
  <div class="print-footer">
    <div class="page-number"></div>
    <div class="admin-info">
      <p><strong>Administered by:</strong> <?= isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : '_____________________' ?></p>
      <p><strong>Position:</strong> <?= isset($_SESSION['role']) ? htmlspecialchars(ucfirst($_SESSION['role'])) : '_____________________' ?></p>
      <p><strong>Date:</strong> <?= date('F d, Y') ?></p>
    </div>
  </div>

  <script>
    function printInventory() {
      const style = document.createElement('style');
      style.id = 'print-inventory-style';
      style.innerHTML = `
        @media print {
          .print-footer { display: block !important; }
          .page-number:after { content: 'Page ' counter(page) ' of ' counter(pages); }
        }
      `;
      document.head.appendChild(style);
      window.print();
      setTimeout(() => {
        const s = document.getElementById('print-inventory-style');
        if (s) s.parentNode.removeChild(s);
      }, 1000);
    }
  </script>
</body>
</html>