<?php
require_once 'config.php';

// Delete existing admin if exists
$delete_sql = "DELETE FROM users WHERE id_proof = 'ADMIN123'";
if ($conn->query($delete_sql) === TRUE) {
    echo "Existing admin user deleted successfully<br>";
}

// Create new admin user
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (
    name, 
    age, 
    mobile, 
    id_proof, 
    blood_group, 
    house_no, 
    colony, 
    street, 
    landmark, 
    city, 
    state, 
    height, 
    weight, 
    password, 
    role, 
    status
) VALUES (
    'Admin User',
    35,
    '9999999999',
    'ADMIN123',
    'O+',
    'Office 101',
    'Admin Block',
    'Main Road',
    'Near Government Hospital',
    'New Delhi',
    'Delhi',
    180,
    75,
    ?,
    'admin',
    'active'
)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "Admin user created successfully!<br>";
    echo "Login with:<br>";
    echo "ID Proof: ADMIN123<br>";
    echo "Password: admin123<br>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?> 