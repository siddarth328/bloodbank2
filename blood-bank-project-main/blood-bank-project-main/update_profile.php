<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Get current user data
$sql = "SELECT u.*, s.name as state_name, c.name as city_name 
        FROM users u 
        LEFT JOIN states s ON u.state_id = s.id 
        LEFT JOIN cities c ON u.city_id = c.id 
        WHERE u.id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Check if states table exists
$check_states = "SHOW TABLES LIKE 'states'";
$states_exist = $conn->query($check_states)->num_rows > 0;

if (!$states_exist) {
    // Redirect to setup page if tables don't exist
    header("Location: setup_location_tables.php");
    exit();
}

// Get all states
$states_sql = "SELECT * FROM states ORDER BY name";
$states_result = $conn->query($states_sql);

// Get cities for current state
$cities_sql = "SELECT * FROM cities WHERE state_id = " . $user['state_id'] . " ORDER BY name";
$cities_result = $conn->query($cities_sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $mobile = $_POST['mobile'];
    $state_id = $_POST['state_id'];
    $city_id = $_POST['city_id'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    // Validate input
    if (empty($name) || empty($age) || empty($mobile) || empty($state_id) || empty($city_id) || empty($height) || empty($weight)) {
        $error = "All fields are required";
    } elseif (!is_numeric($age) || $age < 18 || $age > 65) {
        $error = "Age must be between 18 and 65";
    } elseif (!is_numeric($height) || $height < 100 || $height > 250) {
        $error = "Height must be between 100 and 250 cm";
    } elseif (!is_numeric($weight) || $weight < 30 || $weight > 150) {
        $error = "Weight must be between 30 and 150 kg";
    } else {
        // Update user profile
        $update_sql = "UPDATE users SET 
                      name = '$name',
                      age = '$age',
                      mobile = '$mobile',
                      state_id = '$state_id',
                      city_id = '$city_id',
                      height = '$height',
                      weight = '$weight'
                      WHERE id = '$user_id'";

        if ($conn->query($update_sql)) {
            $message = "Profile updated successfully!";
            // Refresh user data
            $result = $conn->query($sql);
            $user = $result->fetch_assoc();
            
            // Refresh cities list for the selected state
            $cities_sql = "SELECT * FROM cities WHERE state_id = " . $state_id . " ORDER BY name";
            $cities_result = $conn->query($cities_sql);
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Blood Bank</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #2c3e50;
            --accent-color: #3498db;
            --text-color: #333;
            --light-gray: #f4f4f4;
            --white: #ffffff;
            --success-color: #27ae60;
            --error-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        .header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .nav-bar {
            background-color: var(--secondary-color);
            padding: 0.8rem 2rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-gray);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #c0392b;
        }

        .btn i {
            font-size: 1rem;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: var(--success-color);
        }

        .error {
            background-color: #f8d7da;
            color: var(--error-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        function loadCities(stateId) {
            if (stateId === '') {
                document.getElementById('city_id').innerHTML = '<option value="">Select City</option>';
                return;
            }

            fetch('get_cities.php?state_id=' + stateId)
                .then(response => response.json())
                .then(cities => {
                    const citySelect = document.getElementById('city_id');
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Update Profile</h1>
        <a href="user_dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    <div class="nav-bar">
        <a href="nearby_locations.php" class="btn"><i class="fas fa-map-marker-alt"></i> Find Nearby Locations</a>
        <a href="schedule_donation.php" class="btn"><i class="fas fa-calendar-plus"></i> Schedule Donation</a>
        <a href="update_profile.php" class="btn"><i class="fas fa-user-edit"></i> Update Profile</a>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Edit Your Profile Information</h2>
            </div>

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

            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" min="18" max="65" required>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="state_id">State</label>
                        <select id="state_id" name="state_id" onchange="loadCities(this.value)" required>
                            <option value="">Select State</option>
                            <?php
                            while ($state = $states_result->fetch_assoc()) {
                                $selected = ($state['id'] == $user['state_id']) ? 'selected' : '';
                                echo "<option value='" . $state['id'] . "' " . $selected . ">" . 
                                     htmlspecialchars($state['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="city_id">City</label>
                        <select id="city_id" name="city_id" required>
                            <option value="">Select City</option>
                            <?php
                            while ($city = $cities_result->fetch_assoc()) {
                                $selected = ($city['id'] == $user['city_id']) ? 'selected' : '';
                                echo "<option value='" . $city['id'] . "' " . $selected . ">" . 
                                     htmlspecialchars($city['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" min="100" max="250" required>
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" min="30" max="150" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 2rem; text-align: center;">
                    <button type="submit" class="btn"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 