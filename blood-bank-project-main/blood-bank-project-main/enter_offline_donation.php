<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a hospital
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header("Location: login.php");
    exit();
}

$hospital_id = $_SESSION['hospital_id'];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_name = mysqli_real_escape_string($conn, $_POST['donor_name']);
    $donor_mobile = mysqli_real_escape_string($conn, $_POST['donor_mobile']);
    $donor_email = mysqli_real_escape_string($conn, $_POST['donor_email']);
    $id_proof = mysqli_real_escape_string($conn, $_POST['id_proof']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $donation_date = mysqli_real_escape_string($conn, $_POST['donation_date']);
    
    // Check if user exists by ID proof
    $user_sql = "SELECT id FROM users WHERE id_proof = '$id_proof'";
    $user_result = $conn->query($user_sql);
    
    if ($user_result->num_rows > 0) {
        // User exists, get their ID
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        // Create new user account with ID proof
        $password = password_hash('temporary123', PASSWORD_DEFAULT); // Temporary password
        $insert_user_sql = "INSERT INTO users (name, mobile, email, password, blood_group, id_proof, status) 
                           VALUES ('$donor_name', '$donor_mobile', '$donor_email', '$password', '$blood_group', '$id_proof', 'active')";
        if ($conn->query($insert_user_sql)) {
            $user_id = $conn->insert_id;
        } else {
            echo "<script>alert('Error creating user account. Please try again.');</script>";
            exit();
        }
    }
    
    // Insert donation record
    $insert_donation_sql = "INSERT INTO donations (user_id, location_type, location_id, blood_group, donation_date, status) 
                           VALUES ('$user_id', 'hospital', '$hospital_id', '$blood_group', '$donation_date', 'completed')";
    
    if ($conn->query($insert_donation_sql)) {
        // Update hospital inventory
        $update_inventory_sql = "INSERT INTO blood_inventory (hospital_id, blood_group, quantity) 
                               VALUES ('$hospital_id', '$blood_group', 1)
                               ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $conn->query($update_inventory_sql);
        
        // Update state inventory
        $state_sql = "SELECT state FROM hospitals WHERE id = '$hospital_id'";
        $state_result = $conn->query($state_sql);
        $state = $state_result->fetch_assoc()['state'];
        
        $update_state_sql = "INSERT INTO state_inventory (state, blood_group, quantity) 
                            VALUES ('$state', '$blood_group', 1)
                            ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $conn->query($update_state_sql);
        
        echo "<script>alert('Donation recorded successfully!'); window.location.href='hospital_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error recording donation. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Offline Donation - Blood Bank</title>
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
        input, select {
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
        .note {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Enter Offline Donation</h1>
        <a href="hospital_dashboard.php" class="btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="card">
            <h2>Donor Information</h2>
            <form action="enter_offline_donation.php" method="POST">
                <div class="form-group">
                    <label for="donor_name">Donor Name</label>
                    <input type="text" id="donor_name" name="donor_name" required>
                </div>

                <div class="form-group">
                    <label for="id_proof">ID Proof Number</label>
                    <input type="text" id="id_proof" name="id_proof" required>
                    <div class="note">Enter Aadhar, PAN, or other government-issued ID number</div>
                </div>

                <div class="form-group">
                    <label for="donor_mobile">Mobile Number</label>
                    <input type="tel" id="donor_mobile" name="donor_mobile" required pattern="[0-9]{10}">
                </div>

                <div class="form-group">
                    <label for="donor_email">Email (Optional)</label>
                    <input type="email" id="donor_email" name="donor_email">
                </div>

                <div class="form-group">
                    <label for="blood_group">Blood Group</label>
                    <select id="blood_group" name="blood_group" required>
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="donation_date">Donation Date</label>
                    <input type="date" id="donation_date" name="donation_date" required 
                           max="<?php echo date('Y-m-d'); ?>">
                </div>

                <button type="submit" class="btn">Record Donation</button>
            </form>
        </div>
    </div>
</body>
</html> 