<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Before deleting, retrieve vaccine_id to restore stock
    $res = $conn->query("SELECT vaccine_id FROM child_immunizations WHERE immunization_id=" . intval($id));
    if ($res && $row = $res->fetch_assoc()) {
        $vaccine_id = intval($row['vaccine_id']);
        // increment vaccine stock by 1
        $conn->query("UPDATE vaccines SET quantity = quantity + 1 WHERE vaccine_id=" . $vaccine_id);
    }
    $conn->query("DELETE FROM child_immunizations WHERE immunization_id=" . intval($id));
}
header("Location: child_immunizations.php");
?>

