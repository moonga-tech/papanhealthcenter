<?php
include 'db_connect.php';

$query = "SELECT vr.vaccine_request_id, vr.quantity, vr.request_date, v.vaccine_name
          FROM vaccine_stock_requests vr
          JOIN vaccines v ON vr.vaccine_id = v.vaccine_id
          ORDER BY vr.request_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vaccine Stock Requests</title>
  <link rel="stylesheet" href="assets/common_styles.css">
  <script>
    function printRequest(requestId, vaccineName, quantity, date) {
      let printWindow = window.open('', '', 'height=600,width=800');
      printWindow.document.write('<html><head><title>Print Request</title></head><body>');
      printWindow.document.write('<h2 style="text-align:center;">Vaccine Stock Request</h2>');
      printWindow.document.write('<p><strong>Request ID:</strong> ' + requestId + '</p>');
      printWindow.document.write('<p><strong>Vaccine Name:</strong> ' + vaccineName + '</p>');
      printWindow.document.write('<p><strong>Quantity:</strong> ' + quantity + '</p>');
      printWindow.document.write('<p><strong>Request Date:</strong> ' + date + '</p>');
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Vaccine Stock Requests</h2>
    <a href="vaccines.php" class="btn btn-back">⬅ Back to Vaccine Management</a>
    
    <table>
      <thead>
        <tr>
          <th>Request ID</th>
          <th>Vaccine Name</th>
          <th>Quantity</th>
          <th>Request Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['vaccine_request_id']; ?></td>
            <td><?= $row['vaccine_name']; ?></td>
            <td><?= $row['quantity']; ?></td>
            <td><?= $row['request_date']; ?></td>
            <td>
              <button class="btn btn-edit" 
                onclick="printRequest('<?= $row['vaccine_request_id']; ?>','<?= $row['vaccine_name']; ?>','<?= $row['quantity']; ?>','<?= $row['request_date']; ?>')">
                🖨 Print
              </button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>

