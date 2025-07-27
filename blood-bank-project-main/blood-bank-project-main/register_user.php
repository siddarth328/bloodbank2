<?php
session_start();
require_once 'config.php';

// Indian states and their cities
$indian_states = [
    'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool'],
    'Arunachal Pradesh' => ['Itanagar', 'Naharlagun', 'Tawang', 'Bomdila', 'Pasighat'],
    'Assam' => ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon'],
    'Bihar' => ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga'],
    'Chhattisgarh' => ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg'],
    'Goa' => ['Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda'],
    'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar'],
    'Haryana' => ['Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar'],
    'Himachal Pradesh' => ['Shimla', 'Mandi', 'Solan', 'Dharamshala', 'Bilaspur'],
    'Jharkhand' => ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Hazaribagh'],
    'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum'],
    'Kerala' => ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Kollam', 'Thrissur'],
    'Madhya Pradesh' => ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain'],
    'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik'],
    'Manipur' => ['Imphal', 'Thoubal', 'Bishnupur', 'Churachandpur', 'Ukhrul'],
    'Meghalaya' => ['Shillong', 'Tura', 'Jowai', 'Nongstoin', 'Williamnagar'],
    'Mizoram' => ['Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib'],
    'Nagaland' => ['Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha'],
    'Odisha' => ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Brahmapur', 'Sambalpur'],
    'Punjab' => ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda'],
    'Rajasthan' => ['Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer'],
    'Sikkim' => ['Gangtok', 'Namchi', 'Mangan', 'Gyalshing', 'Ravong'],
    'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem'],
    'Telangana' => ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Ramagundam'],
    'Tripura' => ['Agartala', 'Udaipur', 'Dharmanagar', 'Kailasahar', 'Belonia'],
    'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Varanasi'],
    'Uttarakhand' => ['Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur'],
    'West Bengal' => ['Kolkata', 'Asansol', 'Siliguri', 'Durgapur', 'Bardhaman']
];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $id_proof = mysqli_real_escape_string($conn, $_POST['id_proof']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $house_no = mysqli_real_escape_string($conn, $_POST['house_no']);
    $colony = mysqli_real_escape_string($conn, $_POST['colony']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $landmark = mysqli_real_escape_string($conn, $_POST['landmark']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    $errors = [];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Check password strength
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // Validate age
    if ($age < 18 || $age > 65) {
        $errors[] = "Age must be between 18 and 65 years";
    }

    // Validate height and weight
    if ($height < 100 || $height > 250) {
        $errors[] = "Height must be between 100cm and 250cm";
    }
    if ($weight < 30 || $weight > 200) {
        $errors[] = "Weight must be between 30kg and 200kg";
    }

    // Check if ID proof already exists
    $check_sql = "SELECT id FROM users WHERE id_proof = '$id_proof'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        $errors[] = "ID proof number already registered";
    }

    // Check if email already exists
    $check_email_sql = "SELECT id FROM users WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_sql);
    if ($check_email_result->num_rows > 0) {
        $errors[] = "Email already registered";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Combine address components
        $address = "$house_no, $colony, $street, $landmark";

        // Insert user data
        $sql = "INSERT INTO users (name, age, mobile, email, id_proof, blood_group, address, city, state, height, weight, password, role) 
                VALUES ('$name', '$age', '$mobile', '$email', '$id_proof', '$blood_group', '$address', '$city', '$state', '$height', '$weight', '$hashed_password', 'user')";

        if ($conn->query($sql) === TRUE) {
            // Set success message
            $_SESSION['success_message'] = "Registration successful! You can now login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blood Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            color: #e74c3c;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #e74c3c;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
        }
        button:hover {
            background-color: #c0392b;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        .form-row {
            display: flex;
            gap: 1rem;
        }
        .form-row .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register as Donor</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="register_user.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required min="18" max="65" value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}" value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_proof">ID Proof Number</label>
                    <input type="text" id="id_proof" name="id_proof" required value="<?php echo isset($_POST['id_proof']) ? htmlspecialchars($_POST['id_proof']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="blood_group">Blood Group</label>
                    <select id="blood_group" name="blood_group" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="house_no">House No./Flat No.</label>
                <input type="text" id="house_no" name="house_no" required value="<?php echo isset($_POST['house_no']) ? htmlspecialchars($_POST['house_no']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="colony">Colony/Society</label>
                <input type="text" id="colony" name="colony" required value="<?php echo isset($_POST['colony']) ? htmlspecialchars($_POST['colony']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="street">Street/Road</label>
                <input type="text" id="street" name="street" required value="<?php echo isset($_POST['street']) ? htmlspecialchars($_POST['street']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="landmark">Landmark</label>
                <input type="text" id="landmark" name="landmark" value="<?php echo isset($_POST['landmark']) ? htmlspecialchars($_POST['landmark']) : ''; ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="state">State</label>
                    <select id="state" name="state" required onchange="updateCities()">
                        <option value="">Select State</option>
                        <?php foreach ($indian_states as $state_name => $cities): ?>
                            <option value="<?php echo $state_name; ?>" <?php echo (isset($_POST['state']) && $_POST['state'] == $state_name) ? 'selected' : ''; ?>>
                                <?php echo $state_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <select id="city" name="city" required>
                        <option value="">Select City</option>
                        <?php if (isset($_POST['state']) && isset($indian_states[$_POST['state']])): ?>
                            <?php foreach ($indian_states[$_POST['state']] as $city): ?>
                                <option value="<?php echo $city; ?>" <?php echo (isset($_POST['city']) && $_POST['city'] == $city) ? 'selected' : ''; ?>>
                                    <?php echo $city; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" name="height" required min="100" max="250" value="<?php echo isset($_POST['height']) ? htmlspecialchars($_POST['height']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" name="weight" required min="30" max="200" value="<?php echo isset($_POST['weight']) ? htmlspecialchars($_POST['weight']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>

            <button type="submit">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        // JavaScript object containing states and their cities
        const indianStates = <?php echo json_encode($indian_states); ?>;

        function updateCities() {
            const stateSelect = document.getElementById('state');
            const citySelect = document.getElementById('city');
            const selectedState = stateSelect.value;

            // Clear existing options
            citySelect.innerHTML = '<option value="">Select City</option>';

            if (selectedState && indianStates[selectedState]) {
                // Add cities for selected state
                indianStates[selectedState].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html> 