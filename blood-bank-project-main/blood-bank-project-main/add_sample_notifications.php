<?php
require_once 'config.php';

// Get a random hospital ID
$hospital_sql = "SELECT id FROM hospitals LIMIT 1";
$hospital_result = $conn->query($hospital_sql);
$hospital_id = $hospital_result->fetch_assoc()['id'];

// Get a random blood bank ID
$blood_bank_sql = "SELECT id FROM blood_banks LIMIT 1";
$blood_bank_result = $conn->query($blood_bank_sql);
$blood_bank_id = $blood_bank_result->fetch_assoc()['id'];

// Get all users
$users_sql = "SELECT id FROM users WHERE role = 'user'";
$users_result = $conn->query($users_sql);

while ($user = $users_result->fetch_assoc()) {
    $user_id = $user['id'];
    
    // Sample notifications
    $notifications = [
        [
            'title' => 'Donation Request',
            'message' => 'Urgent need for blood donation. Please visit our center if you are available.',
            'type' => 'hospital',
            'location_id' => $hospital_id
        ],
        [
            'title' => 'Thank You!',
            'message' => 'Thank you for your recent blood donation. You helped save lives!',
            'type' => 'blood_bank',
            'location_id' => $blood_bank_id
        ],
        [
            'title' => 'Donation Reminder',
            'message' => 'It has been 3 months since your last donation. You are now eligible to donate again.',
            'type' => 'blood_bank',
            'location_id' => $blood_bank_id
        ]
    ];

    foreach ($notifications as $notification) {
        $sql = "INSERT INTO notifications (user_id, title, message, type, location_id) 
                VALUES ('$user_id', 
                        '{$notification['title']}', 
                        '{$notification['message']}', 
                        '{$notification['type']}', 
                        '{$notification['location_id']}')";
        
        if ($conn->query($sql)) {
            echo "Added notification '{$notification['title']}' for user $user_id<br>";
        } else {
            echo "Error adding notification: " . $conn->error . "<br>";
        }
    }
}

echo "Sample notifications added successfully!";
?> 