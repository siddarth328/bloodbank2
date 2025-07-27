<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Get last donation date
$last_donation_sql = "SELECT donation_date FROM donations WHERE user_id = '$user_id' AND status = 'completed' ORDER BY donation_date DESC LIMIT 1";
$last_donation_result = $conn->query($last_donation_sql);
$last_donation = $last_donation_result->fetch_assoc();

// Get locations based on user's state
$locations_sql = "SELECT 'hospital' as location_type, id, name, city, 
                 (SELECT GROUP_CONCAT(CONCAT(blood_group, ':', quantity) SEPARATOR ',') 
                  FROM blood_inventory 
                  WHERE hospital_id = h.id) as inventory
                 FROM hospitals h 
                 WHERE state = '{$user['state']}' AND status = 'active'
                 
                 UNION ALL
                 
                 SELECT 'blood_bank' as location_type, id, name, city,
                 (SELECT GROUP_CONCAT(CONCAT(blood_group, ':', quantity) SEPARATOR ',') 
                  FROM blood_bank_inventory 
                  WHERE blood_bank_id = b.id) as inventory
                 FROM blood_banks b 
                 WHERE state = '{$user['state']}' AND status = 'active'
                 ORDER BY city, location_type, name";

$locations_result = $conn->query($locations_sql);

// Group locations by type and city
$locations_by_type = [
    'hospital' => [],
    'blood_bank' => []
];

while ($row = $locations_result->fetch_assoc()) {
    $type = $row['location_type'];
    $city = $row['city'];
    
    if (!isset($locations_by_type[$type][$city])) {
        $locations_by_type[$type][$city] = [];
    }
    $locations_by_type[$type][$city][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Donation - Blood Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #e74c3c;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
        }
        select, input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #e74c3c;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        .inventory-info {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Schedule Donation</h1>
        <a href="user_dashboard.php" class="btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="card">
            <h2>Schedule Your Donation</h2>
            <form action="process_donation.php" method="POST">
                <div class="form-group">
                    <label for="location_type">Select Location Type</label>
                    <select id="location_type" name="location_type" required onchange="updateCityOptions()">
                        <option value="">Select Location Type</option>
                        <option value="hospital">Hospital</option>
                        <option value="blood_bank">Blood Bank Center</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">Select City</label>
                    <select id="city" name="city" required onchange="updateLocationOptions()">
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location_id">Select Location</label>
                    <select id="location_id" name="location_id" required onchange="updateInventoryInfo()">
                        <option value="">Select Location</option>
                    </select>
                    <div id="inventory_info" class="inventory-info"></div>
                </div>

                <div class="form-group">
                    <label for="donation_date">Select Date</label>
                    <input type="date" id="donation_date" name="donation_date" required 
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                           onchange="updateTimeSlots()"
                           <?php 
                           if ($last_donation) {
                               $next_eligible_date = date('Y-m-d', strtotime($last_donation['donation_date'] . ' +3 months'));
                               echo "min=\"" . max(date('Y-m-d', strtotime('+1 day')), $next_eligible_date) . "\"";
                           }
                           ?>>
                </div>

                <div class="form-group">
                    <label for="time_slot">Select Time Slot</label>
                    <select id="time_slot" name="time_slot" required>
                        <option value="">Select Time Slot</option>
                    </select>
                </div>

                <button type="submit" class="btn">Schedule Donation</button>
            </form>
        </div>
    </div>

    <script>
        // Store location data in JavaScript
        const locationsData = <?php echo json_encode($locations_by_type); ?>;
        const userBloodGroup = '<?php echo $user['blood_group']; ?>';

        function updateCityOptions() {
            const locationType = document.getElementById('location_type').value;
            const citySelect = document.getElementById('city');
            const locationSelect = document.getElementById('location_id');
            
            // Reset selections
            citySelect.innerHTML = '<option value="">Select City</option>';
            locationSelect.innerHTML = '<option value="">Select Location</option>';
            document.getElementById('inventory_info').textContent = '';
            
            if (!locationType) return;
            
            // Get cities for selected location type
            const cities = Object.keys(locationsData[locationType]);
            if (cities.length === 0) {
                citySelect.innerHTML = '<option value="">No cities available</option>';
                return;
            }
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }

        function updateLocationOptions() {
            const locationType = document.getElementById('location_type').value;
            const city = document.getElementById('city').value;
            const locationSelect = document.getElementById('location_id');
            
            // Reset selection
            locationSelect.innerHTML = '<option value="">Select Location</option>';
            document.getElementById('inventory_info').textContent = '';
            
            if (!locationType || !city) return;
            
            // Get locations for selected city and type
            const locations = locationsData[locationType][city] || [];
            if (locations.length === 0) {
                locationSelect.innerHTML = '<option value="">No locations available</option>';
                return;
            }
            
            locations.forEach(location => {
                const option = document.createElement('option');
                option.value = location.id;
                option.textContent = location.name;
                option.setAttribute('data-inventory', location.inventory);
                locationSelect.appendChild(option);
            });
        }

        function updateInventoryInfo() {
            const locationSelect = document.getElementById('location_id');
            const selectedOption = locationSelect.options[locationSelect.selectedIndex];
            const inventoryData = selectedOption.getAttribute('data-inventory');
            const inventoryInfo = document.getElementById('inventory_info');
            
            if (inventoryData) {
                const inventory = {};
                inventoryData.split(',').forEach(item => {
                    const [bloodGroup, quantity] = item.split(':');
                    inventory[bloodGroup] = quantity;
                });
                
                const availableUnits = inventory[userBloodGroup] || 0;
                inventoryInfo.textContent = `Available ${userBloodGroup} units: ${availableUnits}`;
            } else {
                inventoryInfo.textContent = 'No inventory information available';
            }
        }

        function updateTimeSlots() {
            const dateInput = document.getElementById('donation_date');
            const timeSlotSelect = document.getElementById('time_slot');
            const selectedDate = new Date(dateInput.value);
            
            // Reset time slots
            timeSlotSelect.innerHTML = '<option value="">Select Time Slot</option>';
            
            // Check if it's a weekend
            if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) {
                alert('Donations are not scheduled on weekends. Please select a weekday.');
                dateInput.value = '';
                return;
            }
            
            // Define time slots (9 AM to 5 PM, 1-hour intervals)
            const timeSlots = [
                '09:00:00', '10:00:00', '11:00:00', '12:00:00',
                '13:00:00', '14:00:00', '15:00:00', '16:00:00'
            ];
            
            timeSlots.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                const hour = parseInt(time.split(':')[0]);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour > 12 ? hour - 12 : hour;
                option.textContent = `${hour12}:00 ${ampm}`;
                timeSlotSelect.appendChild(option);
            });
        }

        // Initialize the form
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners
            document.getElementById('location_type').addEventListener('change', updateCityOptions);
            document.getElementById('city').addEventListener('change', updateLocationOptions);
            document.getElementById('location_id').addEventListener('change', updateInventoryInfo);
        });
    </script>
</body>
</html> 