<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $id_proof = mysqli_real_escape_string($conn, $_POST['id_proof']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    // Check if user already exists with this ID proof
    $check_sql = "SELECT id FROM users WHERE id_proof = '$id_proof'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // User exists, update their information
        $user = $check_result->fetch_assoc();
        $user_id = $user['id'];
        
        $update_sql = "UPDATE users SET 
                      name = '$name',
                      mobile = '$mobile',
                      email = '$email',
                      password = '$password',
                      blood_group = '$blood_group',
                      address = '$address',
                      city = '$city',
                      state = '$state',
                      country = '$country',
                      status = 'active'
                      WHERE id = '$user_id'";
        
        if ($conn->query($update_sql)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'user';
            
            // Check for existing donations
            $donations_sql = "SELECT d.*, 
                            CASE 
                                WHEN d.location_type = 'hospital' THEN h.name
                                WHEN d.location_type = 'blood_bank' THEN b.name
                            END as location_name
                            FROM donations d
                            LEFT JOIN hospitals h ON d.location_type = 'hospital' AND d.location_id = h.id
                            LEFT JOIN blood_banks b ON d.location_type = 'blood_bank' AND d.location_id = b.id
                            WHERE d.user_id = '$user_id'";
            $donations_result = $conn->query($donations_sql);
            
            if ($donations_result->num_rows > 0) {
                echo "<script>
                    alert('Welcome back! Your previous donations have been linked to your account.');
                    window.location.href='user_dashboard.php';
                </script>";
            } else {
                echo "<script>
                    alert('Registration successful!');
                    window.location.href='user_dashboard.php';
                </script>";
            }
        } else {
            echo "<script>alert('Error updating account. Please try again.'); window.location.href='register.php';</script>";
        }
    } else {
        // Create new user
        $insert_sql = "INSERT INTO users (name, mobile, email, password, blood_group, id_proof, address, city, state, country, status) 
                      VALUES ('$name', '$mobile', '$email', '$password', '$blood_group', '$id_proof', '$address', '$city', '$state', '$country', 'active')";
        
        if ($conn->query($insert_sql)) {
            $user_id = $conn->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'user';
            
            echo "<script>
                alert('Registration successful!');
                window.location.href='user_dashboard.php';
            </script>";
        } else {
            echo "<script>alert('Error creating account. Please try again.'); window.location.href='register.php';</script>";
        }
    }
} else {
    header("Location: register.php");
    exit();
}
?> 