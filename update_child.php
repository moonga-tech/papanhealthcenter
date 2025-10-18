<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $child_id = $_POST['child_id'];
    $full_name = $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $sex = $_POST['sex'];
    $mother_name = $_POST['mother_name'];
    $father_name = $_POST['father_name'];
    $birth_height = $_POST['birth_height'];
    $birth_weight = $_POST['birth_weight'];
    $barangay_id = $_POST['barangay_id'];
    $family_id = $_POST['family_id'];

    $sql = "UPDATE children SET 
            full_name='$full_name',
            date_of_birth='$date_of_birth',
            place_of_birth='$place_of_birth',
            sex='$sex',
            mother_name='$mother_name',
            father_name='$father_name',
            birth_height='$birth_height',
            birth_weight='$birth_weight',
            barangay_id='$barangay_id',
            family_id='$family_id'
            WHERE child_id='$child_id'";

    if ($conn->query($sql)) {
        header("Location: children.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

