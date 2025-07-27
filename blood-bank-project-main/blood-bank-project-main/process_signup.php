<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $id_proof = mysqli_real_escape_string($conn, $_POST['id_proof']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if ID proof number already exists
    $check_query = "SELECT * FROM users WHERE id_proof = '$id_proof'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "<script>alert('ID proof number already registered!'); window.location.href='signup.php';</script>";
        exit();
    }

    // Insert data into database
    $sql = "INSERT INTO users (name, age, mobile, id_proof, country, state, city, blood_group, height, weight, password) 
            VALUES ('$name', '$age', '$mobile', '$id_proof', '$country', '$state', '$city', '$blood_group', '$height', '$weight', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?> 