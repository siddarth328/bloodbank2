<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $location_type = mysqli_real_escape_string($conn, $_POST['location_type']);
    $location_id = mysqli_real_escape_string($conn, $_POST['location_id']);
    $donation_date = mysqli_real_escape_string($conn, $_POST['donation_date']);
    $time_slot = mysqli_real_escape_string($conn, $_POST['time_slot']);

    // Combine date and time
    $donation_datetime = date('Y-m-d H:i:s', strtotime("$donation_date $time_slot"));
    
    // Validate date is not in the past
    if (strtotime($donation_datetime) < strtotime('tomorrow')) {
        echo "<script>
            alert('Please select a future date for donation.');
            window.location.href='schedule_donation.php';
        </script>";
        exit();
    }

    // Validate not a weekend
    $day_of_week = date('w', strtotime($donation_date));
    if ($day_of_week == 0 || $day_of_week == 6) {
        echo "<script>
            alert('Donations cannot be scheduled on weekends. Please select a weekday.');
            window.location.href='schedule_donation.php';
        </script>";
        exit();
    }

    // Get user's blood group
    $user_sql = "SELECT blood_group FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();
    $blood_group = $user['blood_group'];

    // Check if user has donated in the last 3 months
    $last_donation_sql = "SELECT donation_date FROM donations 
                         WHERE user_id = ? 
                         AND status = 'completed' 
                         ORDER BY donation_date DESC 
                         LIMIT 1";
    $stmt = $conn->prepare($last_donation_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $last_donation_result = $stmt->get_result();

    if ($last_donation_result->num_rows > 0) {
        $last_donation = $last_donation_result->fetch_assoc();
        $last_donation_date = new DateTime($last_donation['donation_date']);
        $next_eligible_date = clone $last_donation_date;
        $next_eligible_date->modify('+3 months');
        $selected_date = new DateTime($donation_datetime);

        if ($selected_date < $next_eligible_date) {
            echo "<script>
                alert('You must wait 3 months between donations. Your next eligible donation date is " . $next_eligible_date->format('F j, Y') . "');
                window.location.href='schedule_donation.php';
            </script>";
            exit();
        }
    }

    // Check if the time slot is already booked
    $check_slot_sql = "SELECT id FROM donations 
                      WHERE location_type = ? 
                      AND location_id = ? 
                      AND donation_date = ? 
                      AND status != 'cancelled'";
    $stmt = $conn->prepare($check_slot_sql);
    $stmt->bind_param("sis", $location_type, $location_id, $donation_datetime);
    $stmt->execute();
    $slot_result = $stmt->get_result();

    if ($slot_result->num_rows > 0) {
        echo "<script>
            alert('This time slot is already booked. Please select a different time.');
            window.location.href='schedule_donation.php';
        </script>";
        exit();
    }

    // Insert donation schedule using prepared statement
    $insert_sql = "INSERT INTO donations (user_id, location_type, location_id, blood_group, donation_date, status) 
                   VALUES (?, ?, ?, ?, ?, 'scheduled')";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("isiss", $user_id, $location_type, $location_id, $blood_group, $donation_datetime);

    if ($stmt->execute()) {
        echo "<script>
            alert('Donation scheduled successfully!');
            window.location.href='user_dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Error scheduling donation. Please try again.');
            window.location.href='schedule_donation.php';
        </script>";
    }
} else {
    header("Location: schedule_donation.php");
    exit();
}
?> 