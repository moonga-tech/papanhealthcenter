<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include 'db_connect.php';

  $sql = "SELECT m.medicine_id, m.medicine_name, m.description, m.stock, m.expiry_date, m.date_added,
              (SELECT IFNULL(SUM(mg.quantity_given),0) FROM medicine_given mg WHERE mg.medicine_id = m.medicine_id) as total_given,
              CASE 
                 WHEN m.stock <= 5 THEN 'Low Stock'
                 WHEN m.stock <= 20 THEN 'Medium Stock'
                 ELSE 'Good Stock'
              END as stock_status,
              CASE 
                 WHEN m.expiry_date < CURDATE() THEN 'Expired'
                 WHEN DATEDIFF(m.expiry_date, CURDATE()) <= 30 THEN 'Expiring Soon'
                 ELSE 'Good'
              END as expiry_status
        FROM medicines m
        WHERE m.archived = 0
        ORDER BY m.medicine_name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medicine Inventory</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .low-stock { background-color: #ffebee; color: #c62828; }
    .medium-stock { background-color: #fff3e0; color: #ef6c00; }
    .good-stock { background-color: #e8f5e8; color: #2e7d32; }
    .expired { background-color: #ffcdd2; color: #d32f2f; }
    .expiring-soon { background-color: #ffe0b2; color: #f57c00; }

    /* Print styles for inventory table */
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

      /* Footer fixed at bottom */
      .print-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        font-size: 9pt;
        color: #333;
        text-align: center;
        background: transparent;
        padding: 6px 0;
      }

      /* Page counter (works in many browsers) */
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
  <div class="print-header">
    <h1>PAPAN HEALTH CENTER</h1>
    <h2>Medicine Inventory Report</h2>
    <p>Generated on <?= date('F d, Y h:i A') ?></p>
    <div class="print-summary">
      <?php
        // summary counts
        $medicinesLeft = $result->num_rows;
        $givenTotalRes = $conn->query("SELECT IFNULL(SUM(quantity_given),0) as total FROM medicine_given");
        $givenTotal = $givenTotalRes ? $givenTotalRes->fetch_assoc()['total'] : 0;
        $expiredCountRes = $conn->query("SELECT COUNT(*) as cnt FROM medicines WHERE expiry_date < CURDATE() AND archived = 0");
        $expiredCount = $expiredCountRes ? $expiredCountRes->fetch_assoc()['cnt'] : 0;
      ?>
      <p><strong>Medicines Left:</strong> <?= $medicinesLeft ?></p>
      <p><strong>Given Medicines:</strong> <?= $givenTotal ?></p>
      <p><strong>Expired Medicines:</strong> <?= $expiredCount ?></p>
    </div>
  </div>

  <div class="container">
    <h2>Medicine Inventory</h2>
    <a href="medicine.php" class="btn btn-back">← Back to Medicines</a>
    <button class="btn btn-edit" onclick="printInventory()">Print Inventory</button>
    
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Medicine Name</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Given</th>
          <th>Stock Status</th>
          <th>Expiry Date</th>
          <th>Expiry Status</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['medicine_id']); ?></td>
              <td><?= htmlspecialchars($row['medicine_name']); ?></td>
              <td><?= htmlspecialchars($row['description']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock']); ?>
              </td>
              <td>
                <?= htmlspecialchars($row['total_given']); ?>
              </td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                <?= htmlspecialchars($row['stock_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['expiry_date']); ?></td>
              <td class="<?= strtolower(str_replace(' ', '-', $row['expiry_status'])); ?>">
                <?= htmlspecialchars($row['expiry_status']); ?>
              </td>
              <td><?= htmlspecialchars($row['date_added']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" style="text-align:center;">No medicines in inventory.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <div class="print-footer">
    <div class="page-number"></div>
    <div class="admin-info">
      <p><strong>Administered by:</strong> _____________________</p>
      <p><strong>Position:</strong> _____________________</p>
      <p><strong>Date:</strong> _____________________</p>
    </div>
  </div>

  <script>
    function printInventory() {
      // Add temporary styles to ensure footer and page numbers work
      const style = document.createElement('style');
      style.id = 'print-inventory-style';
      style.innerHTML = `
        @media print {
          .print-footer { display: block !important; }
          .page-number:after { content: 'Page ' counter(page) ' of ' counter(pages); }
        }
      `;
      document.head.appendChild(style);

      // Trigger print
      window.print();

      // Cleanup after print (delay to allow print dialog)
      setTimeout(() => {
        const s = document.getElementById('print-inventory-style');
        if (s) s.parentNode.removeChild(s);
      }, 1000);
    }
  </script>
</body>
</html>