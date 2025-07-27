<?php
require_once 'config.php';

// Users from Amravati (Amaravati)
$amravati_users = [
    ['name' => 'Arjun Reddy', 'age' => 28, 'mobile' => '9876543210', 'id_proof' => 'AP9876543210', 'blood_group' => 'O+', 'height' => 175, 'weight' => 70],
    ['name' => 'Sneha Reddy', 'age' => 25, 'mobile' => '9876543211', 'id_proof' => 'AP9876543211', 'blood_group' => 'A+', 'height' => 165, 'weight' => 55],
    ['name' => 'Vikram Raju', 'age' => 32, 'mobile' => '9876543212', 'id_proof' => 'AP9876543212', 'blood_group' => 'B+', 'height' => 170, 'weight' => 65],
    ['name' => 'Meera Rao', 'age' => 30, 'mobile' => '9876543213', 'id_proof' => 'AP9876543213', 'blood_group' => 'AB+', 'height' => 168, 'weight' => 68],
    ['name' => 'Krishna Reddy', 'age' => 27, 'mobile' => '9876543214', 'id_proof' => 'AP9876543214', 'blood_group' => 'O-', 'height' => 162, 'weight' => 52],
    ['name' => 'Ananya Rao', 'age' => 35, 'mobile' => '9876543215', 'id_proof' => 'AP9876543215', 'blood_group' => 'A-', 'height' => 172, 'weight' => 72],
    ['name' => 'Rahul Kumar', 'age' => 29, 'mobile' => '9876543216', 'id_proof' => 'AP9876543216', 'blood_group' => 'B-', 'height' => 169, 'weight' => 67],
    ['name' => 'Divya Reddy', 'age' => 26, 'mobile' => '9876543217', 'id_proof' => 'AP9876543217', 'blood_group' => 'AB-', 'height' => 164, 'weight' => 54],
    ['name' => 'Sai Kumar', 'age' => 33, 'mobile' => '9876543218', 'id_proof' => 'AP9876543218', 'blood_group' => 'O+', 'height' => 171, 'weight' => 69],
    ['name' => 'Lakshmi Reddy', 'age' => 31, 'mobile' => '9876543219', 'id_proof' => 'AP9876543219', 'blood_group' => 'A+', 'height' => 173, 'weight' => 71],
    ['name' => 'Ravi Kumar', 'age' => 24, 'mobile' => '9876543220', 'id_proof' => 'AP9876543220', 'blood_group' => 'B+', 'height' => 163, 'weight' => 53],
    ['name' => 'Priya Rao', 'age' => 36, 'mobile' => '9876543221', 'id_proof' => 'AP9876543221', 'blood_group' => 'O-', 'height' => 174, 'weight' => 73],
    ['name' => 'Suresh Reddy', 'age' => 34, 'mobile' => '9876543222', 'id_proof' => 'AP9876543222', 'blood_group' => 'A-', 'height' => 176, 'weight' => 74],
    ['name' => 'Swathi Kumar', 'age' => 28, 'mobile' => '9876543223', 'id_proof' => 'AP9876543223', 'blood_group' => 'B-', 'height' => 166, 'weight' => 56],
    ['name' => 'Mohan Reddy', 'age' => 37, 'mobile' => '9876543224', 'id_proof' => 'AP9876543224', 'blood_group' => 'AB+', 'height' => 177, 'weight' => 75]
];

// Common password for all users
$password = password_hash('password123', PASSWORD_DEFAULT);

// Insert users
$success_count = 0;
$error_count = 0;

foreach ($amravati_users as $user) {
    // Check if user already exists
    $check_sql = "SELECT id FROM users WHERE id_proof = '{$user['id_proof']}'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows == 0) {
        // Insert new user
        $sql = "INSERT INTO users (
            name, age, mobile, id_proof, blood_group, 
            house_no, colony, street, landmark,
            city, state, height, weight, password, role, status
        ) VALUES (
            '{$user['name']}', {$user['age']}, '{$user['mobile']}', '{$user['id_proof']}', '{$user['blood_group']}',
            'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall',
            'Amravati', 'Andhra Pradesh', {$user['height']}, {$user['weight']}, '$password', 'user', 'active'
        )";
        
        if ($conn->query($sql)) {
            $success_count++;
        } else {
            $error_count++;
            echo "Error adding user {$user['name']}: " . $conn->error . "<br>";
        }
    } else {
        echo "User with ID proof {$user['id_proof']} already exists.<br>";
    }
}

echo "Operation completed:<br>";
echo "Successfully added: $success_count users<br>";
echo "Failed to add: $error_count users<br>";

$conn->close();
?> 