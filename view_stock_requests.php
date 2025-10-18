<?php
include 'db_connect.php';

$query = "SELECT sr.request_id, sr.quantity, sr.request_date, m.medicine_name 
          FROM stock_request sr 
          JOIN medicines m ON sr.medicine_id = m.medicine_id 
          ORDER BY sr.request_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stock Requests</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <style>
    .btn-print {
      background: #28a745;
      color: white;
    }
    .btn-print:hover {
      background: #218838;
      transform: translateY(-2px);
    }
  </style>
  <script>
    function printRequest(id, name, qty, date) {
      let content = `
        <div style="font-family: Arial; padding: 20px;">
          <h2 style="text-align:center;">Medicine Stock Request</h2>
          <p><strong>Request ID:</strong> ${id}</p>
          <p><strong>Medicine Name:</strong> ${name}</p>
          <p><strong>Quantity:</strong> ${qty}</p>
          <p><strong>Request Date:</strong> ${date}</p>
          <br><br>
          <p>_____________________________<br>Authorized Signature</p>
        </div>
      `;
      let win = window.open('', '', 'height=600,width=800');
      win.document.write('<html><head><title>Print Request</title></head><body>');
      win.document.write(content);
      win.document.write('</body></html>');
      win.document.close();
      win.print();
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Stock Requests</h2>
    <a href="medicine.php" class="btn btn-back">⬅ Back to Medicine Management</a>
    
    <table>
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Medicine Name</th>
          <th>Quantity</th>
          <th>Request Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['request_id']; ?></td>
            <td><?= $row['medicine_name']; ?></td>
            <td><?= $row['quantity']; ?></td>
            <td><?= $row['request_date']; ?></td>
            <td>
              <button class="btn btn-print"
                onclick="printRequest(
                  '<?= $row['request_id']; ?>',
                  '<?= $row['medicine_name']; ?>',
                  '<?= $row['quantity']; ?>',
                  '<?= $row['request_date']; ?>'
                )">🖨 Print</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>

