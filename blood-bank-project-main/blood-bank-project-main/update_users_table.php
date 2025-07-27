<?php
require_once 'config.php';

// Add status column to users table if it doesn't exist
$check_column = "SHOW COLUMNS FROM users LIKE 'status'";
$result = $conn->query($check_column);

if ($result->num_rows == 0) {
    $alter_sql = "ALTER TABLE users 
                  ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'";
    
    if ($conn->query($alter_sql)) {
        echo "Status column added successfully to users table.<br>";
        
        // Update all existing users to 'active' status
        $update_sql = "UPDATE users SET status = 'active' WHERE status IS NULL";
        if ($conn->query($update_sql)) {
            echo "Updated existing users to active status.<br>";
        } else {
            echo "Error updating existing users: " . $conn->error . "<br>";
        }
    } else {
        echo "Error adding status column: " . $conn->error . "<br>";
    }
} else {
    echo "Status column already exists in users table.<br>";
}

// Create indexes for commonly queried columns if they don't exist
$indexes = [
    "CREATE INDEX IF NOT EXISTS idx_user_status ON users(status)",
    "CREATE INDEX IF NOT EXISTS idx_user_blood_group ON users(blood_group)",
    "CREATE INDEX IF NOT EXISTS idx_user_state ON users(state)"
];

foreach ($indexes as $index_sql) {
    if ($conn->query($index_sql)) {
        echo "Index created successfully.<br>";
    } else {
        echo "Error creating index: " . $conn->error . "<br>";
    }
}

$conn->close();
echo "Done.<br>";
?> 