<?php
// prenatal_management.php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prenatal Records Management</title>
    <link rel="stylesheet" href="assets/common_styles.css">
</head>
<body>
<div class="container">
    <h2>Prenatal Records</h2>
    <div>
        <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        <a href="archived_prenatal_records.php" class="btn btn-edit">View Archived Records</a>
        <button class="btn btn-add" onclick="document.getElementById('addModal').style.display='block'">+ Add Prenatal Record</button>
    </div>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Patient</th>
            <th>Visit Date</th>
            <th>LMP</th>
            <th>EDD</th>
            <th>Gestational Age</th>
            <th>Blood Pressure</th>
            <th>Weight</th>
            <th>Height</th>
            <th>FHR</th>
            <th>Fundal Height</th>
            <th>Complaints</th>
            <th>Diagnosis</th>
            <th>Treatment</th>
            <th>Next Visit</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT pr.*, p.full_name 
                FROM prenatal_records pr
                JOIN patients p ON pr.patient_id = p.patient_id
                WHERE pr.archived = 0
                ORDER BY pr.visit_date DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['prenatal_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['visit_date']}</td>
                    <td>{$row['lmp']}</td>
                    <td>{$row['edd']}</td>
                    <td>{$row['gestational_age']}</td>
                    <td>{$row['blood_pressure']}</td>
                    <td>{$row['weight']}</td>
                    <td>{$row['height']}</td>
                    <td>{$row['fetal_heart_rate']}</td>
                    <td>{$row['fundal_height']}</td>
                    <td>{$row['complaints']}</td>
                    <td>{$row['diagnosis']}</td>
                    <td>{$row['treatment']}</td>
                    <td>{$row['next_visit']}</td>
                    <td>
                        <button class='btn btn-edit' onclick=\"openEditModal(
                            '{$row['prenatal_id']}',
                            '{$row['patient_id']}',
                            '{$row['visit_date']}',
                            '{$row['lmp']}',
                            '{$row['edd']}',
                            '{$row['gestational_age']}',
                            '{$row['blood_pressure']}',
                            '{$row['weight']}',
                            '{$row['height']}',
                            '{$row['fetal_heart_rate']}',
                            '{$row['fundal_height']}',
                            `".addslashes($row['complaints'])."`,
                            `".addslashes($row['diagnosis'])."`,
                            `".addslashes($row['treatment'])."`,
                            '{$row['next_visit']}'
                        )\">Edit</button>
                        <a class='btn btn-archive' href='archive_prenatal.php?id={$row['prenatal_id']}' onclick\"return confirm('Delete this record?')\">Achieve </a>
                        <a class='btn btn-edit' href='print_prenatal.php?id={$row['prenatal_id']}' target='_blank'>🖨 Print</a>
</td>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addModal').style.display='none'">&times;</span>
        <h3>Add Prenatal Record</h3>
        <form action="add_prenatal.php" method="POST">
            <label>Patient</label>
            <select name="patient_id" required>
                <?php
                $patients = $conn->query("SELECT patient_id, full_name FROM patients ORDER BY full_name ASC");
                while ($p = $patients->fetch_assoc()) {
                    echo "<option value='{$p['patient_id']}'>{$p['full_name']}</option>";
                }
                ?>
            </select>
            <label>Visit Date</label><input type="date" name="visit_date" required>
            <label>LMP</label><input type="date" name="lmp">
            <label>EDD</label><input type="date" name="edd">
            <label>Gestational Age</label><input type="text" name="gestational_age">
            <label>Blood Pressure</label><input type="text" name="blood_pressure">
            <label>Weight (kg)</label><input type="number" step="0.01" name="weight">
            <label>Height (cm)</label><input type="number" step="0.01" name="height">
            <label>Fetal Heart Rate</label><input type="text" name="fetal_heart_rate">
            <label>Fundal Height</label><input type="text" name="fundal_height">
            <label>Complaints</label><textarea name="complaints"></textarea>
            <label>Diagnosis</label><textarea name="diagnosis"></textarea>
            <label>Treatment</label><textarea name="treatment"></textarea>
            <label>Next Visit</label><input type="date" name="next_visit">
            <button type="submit" class="btn btn-add">Save</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h3>Edit Prenatal Record</h3>
        <form action="update_prenatal.php" method="POST">
            <input type="hidden" name="prenatal_id" id="edit_id">
            <label>Patient</label>
            <select name="patient_id" id="edit_patient_id" required></select>
            <label>Visit Date</label><input type="date" name="visit_date" id="edit_visit_date">
            <label>LMP</label><input type="date" name="lmp" id="edit_lmp">
            <label>EDD</label><input type="date" name="edd" id="edit_edd">
            <label>Gestational Age</label><input type="text" name="gestational_age" id="edit_gestational_age">
            <label>Blood Pressure</label><input type="text" name="blood_pressure" id="edit_blood_pressure">
            <label>Weight</label><input type="number" step="0.01" name="weight" id="edit_weight">
            <label>Height</label><input type="number" step="0.01" name="height" id="edit_height">
            <label>Fetal Heart Rate</label><input type="text" name="fetal_heart_rate" id="edit_fetal_heart_rate">
            <label>Fundal Height</label><input type="text" name="fundal_height" id="edit_fundal_height">
            <label>Complaints</label><textarea name="complaints" id="edit_complaints"></textarea>
            <label>Diagnosis</label><textarea name="diagnosis" id="edit_diagnosis"></textarea>
            <label>Treatment</label><textarea name="treatment" id="edit_treatment"></textarea>
            <label>Next Visit</label><input type="date" name="next_visit" id="edit_next_visit">
            <button type="submit" class="btn btn-edit">Update</button>
        </form>
    </div>
</div>

<script>
let patientsList = <?php
    $arr = [];
    $res = $conn->query("SELECT patient_id, full_name FROM patients ORDER BY full_name ASC");
    while($p = $res->fetch_assoc()){ $arr[] = $p; }
    echo json_encode($arr);
?>;

function openEditModal(id, patient_id, visit_date, lmp, edd, gestational_age, blood_pressure, weight, height, fhr, fundal_height, complaints, diagnosis, treatment, next_visit) {
    document.getElementById('edit_id').value = id;
    let select = document.getElementById('edit_patient_id');
    select.innerHTML = "";
    patientsList.forEach(p => {
        let opt = document.createElement("option");
        opt.value = p.patient_id;
        opt.text = p.full_name;
        if(p.patient_id == patient_id) opt.selected = true;
        select.appendChild(opt);
    });
    document.getElementById('edit_visit_date').value = visit_date;
    document.getElementById('edit_lmp').value = lmp;
    document.getElementById('edit_edd').value = edd;
    document.getElementById('edit_gestational_age').value = gestational_age;
    document.getElementById('edit_blood_pressure').value = blood_pressure;
    document.getElementById('edit_weight').value = weight;
    document.getElementById('edit_height').value = height;
    document.getElementById('edit_fetal_heart_rate').value = fhr;
    document.getElementById('edit_fundal_height').value = fundal_height;
    document.getElementById('edit_complaints').value = complaints;
    document.getElementById('edit_diagnosis').value = diagnosis;
    document.getElementById('edit_treatment').value = treatment;
    document.getElementById('edit_next_visit').value = next_visit;
    document.getElementById('editModal').style.display = 'block';
}
</script>
</body>
</html>
