<?php
require_once 'config.php';

$message = '';
$error = '';

// Check if states table exists
$check_states = "SHOW TABLES LIKE 'states'";
$states_exist = $conn->query($check_states)->num_rows > 0;

if (!$states_exist) {
    // Create states table
    $create_states = "CREATE TABLE IF NOT EXISTS states (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )";

    if ($conn->query($create_states)) {
        $message .= "States table created successfully<br>";
    } else {
        $error .= "Error creating states table: " . $conn->error . "<br>";
    }

    // Create cities table
    $create_cities = "CREATE TABLE IF NOT EXISTS cities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        state_id INT NOT NULL,
        FOREIGN KEY (state_id) REFERENCES states(id)
    )";

    if ($conn->query($create_cities)) {
        $message .= "Cities table created successfully<br>";
    } else {
        $error .= "Error creating cities table: " . $conn->error . "<br>";
    }

    // Insert states
    $states = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
    ];

    foreach ($states as $state) {
        $sql = "INSERT IGNORE INTO states (name) VALUES ('$state')";
        if ($conn->query($sql)) {
            $message .= "Inserted state: $state<br>";
        }
    }

    // Insert cities
    $cities = [
        'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool'],
        'Arunachal Pradesh' => ['Itanagar', 'Naharlagun', 'Pasighat', 'Tawang', 'Ziro'],
        'Assam' => ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon'],
        'Bihar' => ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga'],
        'Chhattisgarh' => ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg'],
        'Goa' => ['Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda'],
        'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar'],
        'Haryana' => ['Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar'],
        'Himachal Pradesh' => ['Shimla', 'Mandi', 'Solan', 'Dharamshala', 'Bilaspur'],
        'Jharkhand' => ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Hazaribagh'],
        'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum'],
        'Kerala' => ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam'],
        'Madhya Pradesh' => ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain'],
        'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik'],
        'Manipur' => ['Imphal', 'Thoubal', 'Bishnupur', 'Churachandpur', 'Ukhrul'],
        'Meghalaya' => ['Shillong', 'Tura', 'Jowai', 'Nongstoin', 'Williamnagar'],
        'Mizoram' => ['Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib'],
        'Nagaland' => ['Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha'],
        'Odisha' => ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Brahmapur', 'Sambalpur'],
        'Punjab' => ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda'],
        'Rajasthan' => ['Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer'],
        'Sikkim' => ['Gangtok', 'Namchi', 'Mangan', 'Gyalshing', 'Singtam'],
        'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem'],
        'Telangana' => ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Ramagundam'],
        'Tripura' => ['Agartala', 'Udaipur', 'Dharmanagar', 'Kailashahar', 'Belonia'],
        'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Varanasi', 'Agra', 'Meerut'],
        'Uttarakhand' => ['Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur'],
        'West Bengal' => ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri']
    ];

    foreach ($cities as $state => $city_list) {
        $state_sql = "SELECT id FROM states WHERE name = '$state'";
        $state_result = $conn->query($state_sql);
        if ($state_result && $state_row = $state_result->fetch_assoc()) {
            $state_id = $state_row['id'];
            foreach ($city_list as $city) {
                $sql = "INSERT IGNORE INTO cities (name, state_id) VALUES ('$city', $state_id)";
                if ($conn->query($sql)) {
                    $message .= "Inserted city: $city for state: $state<br>";
                }
            }
        }
    }

    // Add state_id and city_id columns to users table if they don't exist
    $check_columns = "SHOW COLUMNS FROM users LIKE 'state_id'";
    if ($conn->query($check_columns)->num_rows == 0) {
        $alter_users = "ALTER TABLE users 
            ADD COLUMN state_id INT,
            ADD COLUMN city_id INT,
            ADD FOREIGN KEY (state_id) REFERENCES states(id),
            ADD FOREIGN KEY (city_id) REFERENCES cities(id)";

        if ($conn->query($alter_users)) {
            $message .= "Users table updated with state_id and city_id columns<br>";
        } else {
            $error .= "Error updating users table: " . $conn->error . "<br>";
        }

        // Update existing users with default state and city
        $update_users = "UPDATE users 
            SET state_id = (SELECT id FROM states WHERE name = 'Maharashtra' LIMIT 1),
                city_id = (SELECT id FROM cities WHERE name = 'Mumbai' LIMIT 1)
            WHERE state_id IS NULL OR city_id IS NULL";

        if ($conn->query($update_users)) {
            $message .= "Existing users updated with default state and city<br>";
        } else {
            $error .= "Error updating existing users: " . $conn->error . "<br>";
        }
    }
}

// Check if notifications table exists
$check_notifications = "SHOW TABLES LIKE 'notifications'";
$notifications_exist = $conn->query($check_notifications)->num_rows > 0;

if (!$notifications_exist) {
    // Create notifications table
    $create_notifications = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type ENUM('hospital', 'blood_bank') NOT NULL,
        location_id INT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    if ($conn->query($create_notifications)) {
        $message .= "Notifications table created successfully<br>";
    } else {
        $error .= "Error creating notifications table: " . $conn->error . "<br>";
    }
}

// If everything is set up, redirect to update profile
if (empty($error)) {
    echo "<script>window.location.href = 'update_profile.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Location Tables</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Setting up Location Tables</h1>
    
    <?php if ($message): ?>
        <div class="message success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($error)): ?>
        <p>Setup completed successfully! Redirecting to update profile page...</p>
    <?php else: ?>
        <p>There were errors during setup. Please check the error messages above.</p>
    <?php endif; ?>
</body>
</html> 