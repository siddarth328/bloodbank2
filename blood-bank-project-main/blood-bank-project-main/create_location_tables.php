<?php
require_once 'config.php';

// Create states table
$create_states = "CREATE TABLE IF NOT EXISTS states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
)";

if ($conn->query($create_states)) {
    echo "States table created successfully<br>";
} else {
    echo "Error creating states table: " . $conn->error . "<br>";
}

// Create cities table
$create_cities = "CREATE TABLE IF NOT EXISTS cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    state_id INT NOT NULL,
    FOREIGN KEY (state_id) REFERENCES states(id)
)";

if ($conn->query($create_cities)) {
    echo "Cities table created successfully<br>";
} else {
    echo "Error creating cities table: " . $conn->error . "<br>";
}

// Insert some sample states
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
        echo "Inserted state: $state<br>";
    }
}

// Insert some sample cities for each state
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
    // Get state ID
    $state_sql = "SELECT id FROM states WHERE name = '$state'";
    $state_result = $conn->query($state_sql);
    if ($state_result && $state_row = $state_result->fetch_assoc()) {
        $state_id = $state_row['id'];
        
        // Insert cities for this state
        foreach ($city_list as $city) {
            $sql = "INSERT IGNORE INTO cities (name, state_id) VALUES ('$city', $state_id)";
            if ($conn->query($sql)) {
                echo "Inserted city: $city for state: $state<br>";
            }
        }
    }
}

echo "Location tables setup completed successfully!";
?> 