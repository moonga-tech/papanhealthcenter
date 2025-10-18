<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT ci.*, c.full_name, v.vaccine_name, v.lot_number 
              FROM child_immunizations ci
              JOIN children c ON ci.child_id = c.child_id
              JOIN vaccines v ON ci.vaccine_id = v.vaccine_id
              WHERE ci.immunization_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Children dropdown options
    $childrenOptions = "";
    $children = $conn->query("SELECT child_id, full_name FROM children ORDER BY full_name ASC");
    while ($c = $children->fetch_assoc()) {
        $selected = ($c['child_id'] == $row['child_id']) ? "selected" : "";
        $childrenOptions .= "<option value='{$c['child_id']}' $selected>{$c['full_name']}</option>";
    }

    // Vaccine dropdown options (with lot_number in data attribute)
    $vaccineOptions = "";
    $vaccines = $conn->query("SELECT vaccine_id, vaccine_name, lot_number FROM vaccines ORDER BY vaccine_name ASC");
    while ($v = $vaccines->fetch_assoc()) {
        $selected = ($v['vaccine_id'] == $row['vaccine_id']) ? "selected" : "";
        $vaccineOptions .= "<option value='{$v['vaccine_id']}' data-lot='{$v['lot_number']}' $selected>{$v['vaccine_name']}</option>";
    }

    echo json_encode([
        "immunization_id"   => $row['immunization_id'],
        "child_id"          => $row['child_id'],
        "vaccine_id"        => $row['vaccine_id'],
        "dose_number"       => $row['dose_number'],
        "date_given"        => $row['date_given'],
        "lot_number"        => $row['lot_number'], // gikan sa vaccine
        "vaccinator"        => $row['vaccinator'],
        "place_given"       => $row['place_given'],
        "remarks"           => $row['remarks'],
        "children_options"  => $childrenOptions,
        "vaccine_options"   => $vaccineOptions
    ]);
}
?>

