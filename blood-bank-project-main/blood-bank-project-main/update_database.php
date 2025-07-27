<?php
require_once 'config.php';

// Check if aadhar column exists
$check_aadhar = "SHOW COLUMNS FROM users LIKE 'aadhar'";
$result = $conn->query($check_aadhar);

// Check if id_proof column exists
$check_id_proof = "SHOW COLUMNS FROM users LIKE 'id_proof'";
$result_id_proof = $conn->query($check_id_proof);

// Check if city column exists
$check_city = "SHOW COLUMNS FROM users LIKE 'city'";
$result_city = $conn->query($check_city);

// Build ALTER TABLE statements
$sql = "ALTER TABLE users ";

// Drop aadhar if it exists
if ($result->num_rows > 0) {
    $sql .= "DROP COLUMN aadhar, ";
}

// Add id_proof if it doesn't exist
if ($result_id_proof->num_rows == 0) {
    $sql .= "ADD COLUMN id_proof VARCHAR(20) NOT NULL UNIQUE AFTER mobile, ";
}

// Add city if it doesn't exist
if ($result_city->num_rows == 0) {
    $sql .= "ADD COLUMN city VARCHAR(50) NOT NULL AFTER state";
}

// Remove trailing comma if present
$sql = rtrim($sql, ", ");

// Execute the ALTER TABLE statement if there are changes to make
if ($sql != "ALTER TABLE users ") {
    if ($conn->query($sql) === TRUE) {
        echo "Table structure updated successfully";
    } else {
        echo "Error updating table structure: " . $conn->error;
    }
} else {
    echo "No changes needed - table structure is already up to date";
}

$conn->close();
?> 