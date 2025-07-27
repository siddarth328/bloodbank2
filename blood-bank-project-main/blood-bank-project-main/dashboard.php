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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #e74c3c;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-card {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .info-item {
            margin-bottom: 1rem;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .logout-btn {
            background-color: #c0392b;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #a93226;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Blood Bank Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="profile-card">
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
            <div class="profile-info">
                <div class="info-item">
                    <span class="info-label">Age:</span>
                    <span><?php echo htmlspecialchars($user['age']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mobile:</span>
                    <span><?php echo htmlspecialchars($user['mobile']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Blood Group:</span>
                    <span><?php echo htmlspecialchars($user['blood_group']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Location:</span>
                    <span><?php echo htmlspecialchars($user['state'] . ', ' . $user['country']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Height:</span>
                    <span><?php echo htmlspecialchars($user['height']); ?> cm</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Weight:</span>
                    <span><?php echo htmlspecialchars($user['weight']); ?> kg</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 