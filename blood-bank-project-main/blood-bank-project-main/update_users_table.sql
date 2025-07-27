-- First, create a backup of the existing data
CREATE TABLE users_backup AS SELECT * FROM users;

-- Add new columns
ALTER TABLE users
ADD COLUMN house_no VARCHAR(50) AFTER blood_group,
ADD COLUMN colony VARCHAR(100) AFTER house_no,
ADD COLUMN street VARCHAR(100) AFTER colony,
ADD COLUMN landmark VARCHAR(100) AFTER street,
ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER role,
ADD COLUMN points INT DEFAULT 0 AFTER status,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

-- Remove country column
ALTER TABLE users DROP COLUMN country;

-- Add indexes for better performance
CREATE INDEX idx_state ON users(state);
CREATE INDEX idx_city ON users(city);
CREATE INDEX idx_blood_group ON users(blood_group);
CREATE INDEX idx_status ON users(status);

-- Update existing data (if needed)
-- This will split the existing address into the new columns
-- Note: Modify this based on your actual data structure
/*
UPDATE users 
SET 
    house_no = SUBSTRING_INDEX(address, ',', 1),
    colony = SUBSTRING_INDEX(SUBSTRING_INDEX(address, ',', 2), ',', -1),
    street = SUBSTRING_INDEX(SUBSTRING_INDEX(address, ',', 3), ',', -1),
    landmark = SUBSTRING_INDEX(address, ',', -1)
WHERE address IS NOT NULL;
*/

-- Drop the old address column after migration
-- ALTER TABLE users DROP COLUMN address;

-- Drop the backup table after successful migration
-- DROP TABLE IF EXISTS users_backup; 