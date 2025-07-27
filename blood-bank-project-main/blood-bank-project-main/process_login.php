<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_type = $_POST['login_type'];
    $password = $_POST['password'];

    if ($login_type === 'user') {
        $id_proof = mysqli_real_escape_string($conn, $_POST['id_proof']);
        $sql = "SELECT * FROM users WHERE id_proof = '$id_proof'";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "SELECT * FROM hospitals WHERE email = '$email'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            
            if ($login_type === 'user') {
                $_SESSION['role'] = $row['role']; // Get role from database
                if ($row['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
            } else {
                $_SESSION['role'] = 'hospital';
                $_SESSION['hospital_id'] = $row['id'];
                header("Location: hospital_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='login.php';</script>";
    }
}

$conn->close();
?> 