-- Add unit column to medicines and vaccines tables
ALTER TABLE medicines ADD COLUMN unit VARCHAR(20) DEFAULT 'pieces';
ALTER TABLE vaccines ADD COLUMN unit VARCHAR(20) DEFAULT 'pieces';

-- Update existing records to have default unit
UPDATE medicines SET unit = 'pieces' WHERE unit IS NULL;
UPDATE vaccines SET unit = 'pieces' WHERE unit IS NULL;