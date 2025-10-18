-- Add archived column to patients table
ALTER TABLE patients ADD COLUMN archived TINYINT(1) DEFAULT 0;

-- Update existing patients to be active (not archived)
UPDATE patients SET archived = 0 WHERE archived IS NULL;