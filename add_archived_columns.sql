-- Add archived column to all tables that need archive functionality
ALTER TABLE medicines ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE vaccines ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE barangay ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE children ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE family_number ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE child_immunizations ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE medical_records ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE medicine_given ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE prenatal_records ADD COLUMN archived TINYINT(1) DEFAULT 0;
ALTER TABLE vaccine_suppliers ADD COLUMN archived TINYINT(1) DEFAULT 0;

-- Update existing records to be active (not archived)
UPDATE medicines SET archived = 0 WHERE archived IS NULL;
UPDATE vaccines SET archived = 0 WHERE archived IS NULL;
UPDATE barangay SET archived = 0 WHERE archived IS NULL;
UPDATE children SET archived = 0 WHERE archived IS NULL;
UPDATE family_number SET archived = 0 WHERE archived IS NULL;
UPDATE child_immunizations SET archived = 0 WHERE archived IS NULL;
UPDATE medical_records SET archived = 0 WHERE archived IS NULL;
UPDATE medicine_given SET archived = 0 WHERE archived IS NULL;
UPDATE prenatal_records SET archived = 0 WHERE archived IS NULL;
UPDATE vaccine_suppliers SET archived = 0 WHERE archived IS NULL;