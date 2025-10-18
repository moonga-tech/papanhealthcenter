<?php
include 'db_connect.php';

// Fetch children
$children = $conn->query('SELECT child_id, full_name FROM children ORDER BY full_name ASC');

// Fetch vaccines (lot_number included)
$vaccines = $conn->query('SELECT vaccine_id, vaccine_name, lot_number FROM vaccines ORDER BY vaccine_name ASC');

// Fetch active immunization records
$immunizations = $conn->query("
    SELECT ci.immunization_id, c.full_name, v.vaccine_name, v.lot_number, 
           ci.dose_number, ci.date_given, ci.vaccinator, ci.place_given, ci.remarks
    FROM child_immunizations ci
    JOIN children c ON ci.child_id = c.child_id
    JOIN vaccines v ON ci.vaccine_id = v.vaccine_id
    WHERE ci.archived = 0
    ORDER BY ci.date_given DESC
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Child Immunizations</title>
    <link rel="stylesheet" href="assets/common_styles.css">
</head>

<body>
    <div style="border: 1px solid black; padding: 1em; background-color: white; border-radius: 10px">
        <h2>Child Immunizations</h2>
        <a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>
        <a href="archived_child_immunizations.php" class="btn btn-edit">View Archived Records</a>
        <button class="btn btn-add" onclick="openAddModal()">Add Immunization</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child</th>
                    <th>Vaccine</th>
                    <th>Lot No.</th>
                    <th>Dose</th>
                    <th>Date Given</th>
                    <th>Vaccinator</th>
                    <th>Place</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $immunizations->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['immunization_id']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['vaccine_name']) ?></td>
                    <td><?= htmlspecialchars($row['lot_number']) ?></td>
                    <td><?= htmlspecialchars($row['dose_number']) ?></td>
                    <td><?= htmlspecialchars($row['date_given']) ?></td>
                    <td><?= htmlspecialchars($row['vaccinator']) ?></td>
                    <td><?= htmlspecialchars($row['place_given']) ?></td>
                    <td><?= htmlspecialchars($row['remarks']) ?></td>
                    <td>
                        <button class="btn btn-edit"
                            onclick="editImmunization(<?= $row['immunization_id'] ?>)">Edit</button>
                        <a class="btn btn-archive" href="archive_immunization.php?id=<?= $row['immunization_id'] ?>"
                            onclick="return confirm('Archive this record?')">Archive</a>
                        <a class="btn btn-edit" href="print_immunization.php?id=<?= $row['immunization_id'] ?>"
                            target="_blank">Print</a>
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
            <h3>Add Immunization</h3>
            <form method="POST" action="add_immunization.php">
                <label>Child:</label>
                <select name="child_id" required>
                    <option value="">Select Child</option>
                    <?php while($c = $children->fetch_assoc()): ?>
                    <option value="<?= $c['child_id'] ?>"><?= $c['full_name'] ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Vaccine:</label>
                <select name="vaccine_id" id="vaccineSelect" required onchange="autoFillLot()">
                    <option value="">Select Vaccine</option>
                    <?php mysqli_data_seek($vaccines, 0); while($v = $vaccines->fetch_assoc()): ?>
                    <option value="<?= $v['vaccine_id'] ?>" data-lot="<?= $v['lot_number'] ?>">
                        <?= $v['vaccine_name'] ?>
                    </option>
                    <?php endwhile; ?>
                </select>

                <label>Lot Number:</label>
                <input type="text" name="lot_number" id="lotNumber" readonly>

                <label>Dose Number:</label>
                <input type="number" name="dose_number" required>

                <label>Date Given:</label>
                <input type="date" name="date_given" required>

                <label>Vaccinator:</label>
                <input type="text" name="vaccinator" required>

                <label>Place Given:</label>
                <input type="text" name="place_given" required>

                <label>Remarks:</label>
                <textarea name="remarks"></textarea>

                <button type="submit" class="btn btn-add">Save</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Edit Immunization</h3>
            <form method="POST" action="update_immunization.php" id="editForm">
                <input type="hidden" name="immunization_id" id="edit_id">

                <label>Child:</label>
                <select name="child_id" id="edit_child_id" required></select>

                <label>Vaccine:</label>
                <select name="vaccine_id" id="edit_vaccine_id" required onchange="autoFillEditLot()"></select>

                <label>Lot Number:</label>
                <input type="text" name="lot_number" id="edit_lotNumber" readonly>

                <label>Dose Number:</label>
                <input type="number" name="dose_number" id="edit_dose_number" required>

                <label>Date Given:</label>
                <input type="date" name="date_given" id="edit_date_given" required>

                <label>Vaccinator:</label>
                <input type="text" name="vaccinator" id="edit_vaccinator" required>

                <label>Place Given:</label>
                <input type="text" name="place_given" id="edit_place_given" required>

                <label>Remarks:</label>
                <textarea name="remarks" id="edit_remarks"></textarea>

                <button type="submit" class="btn btn-edit">Update</button>
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

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function autoFillLot() {
            let select = document.getElementById('vaccineSelect');
            let lot = select.options[select.selectedIndex].getAttribute('data-lot');
            document.getElementById('lotNumber').value = lot || '';
        }

        function autoFillEditLot() {
            let select = document.getElementById('edit_vaccine_id');
            let lot = select.options[select.selectedIndex].getAttribute('data-lot');
            document.getElementById('edit_lotNumber').value = lot || '';
        }

        function editImmunization(id) {
            fetch('fetch_immunization.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.immunization_id;

                    // Populate children dropdown
                    let childSelect = document.getElementById('edit_child_id');
                    childSelect.innerHTML = data.children_options;
                    childSelect.value = data.child_id;

                    // Populate vaccine dropdown
                    let vaccineSelect = document.getElementById('edit_vaccine_id');
                    vaccineSelect.innerHTML = data.vaccine_options;
                    vaccineSelect.value = data.vaccine_id;

                    document.getElementById('edit_lotNumber').value = data.lot_number;
                    document.getElementById('edit_dose_number').value = data.dose_number;
                    document.getElementById('edit_date_given').value = data.date_given;
                    document.getElementById('edit_vaccinator').value = data.vaccinator;
                    document.getElementById('edit_place_given').value = data.place_given;
                    document.getElementById('edit_remarks').value = data.remarks;

                    document.getElementById('editModal').style.display = 'block';
                });
        }
    </script>

</body>

</html>
