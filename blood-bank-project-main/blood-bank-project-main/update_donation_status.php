<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a hospital or blood bank
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'hospital' && $_SESSION['role'] !== 'blood_bank')) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donation_id = mysqli_real_escape_string($conn, $_POST['donation_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $location_type = mysqli_real_escape_string($conn, $_POST['location_type']);
    $location_id = $_SESSION['hospital_id'];

    // Verify that the donation belongs to this location
    $verify_sql = "SELECT d.*, u.blood_group, u.id as user_id,
                   CASE 
                      WHEN d.location_type = 'hospital' THEN h.state
                      WHEN d.location_type = 'blood_bank' THEN b.state
                   END as state
                   FROM donations d 
                   JOIN users u ON d.user_id = u.id 
                   LEFT JOIN hospitals h ON d.location_type = 'hospital' AND d.location_id = h.id
                   LEFT JOIN blood_banks b ON d.location_type = 'blood_bank' AND d.location_id = b.id
                   WHERE d.id = '$donation_id' 
                   AND d.location_type = '$location_type'
                   AND d.location_id = '$location_id'";
    $verify_result = $conn->query($verify_sql);

    if ($verify_result->num_rows == 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid donation record']);
        exit();
    }

    $donation = $verify_result->fetch_assoc();
    $state = $donation['state'];
    $blood_group = $donation['blood_group'];
    $user_id = $donation['user_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update donation status
        $update_sql = "UPDATE donations SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $donation_id);
        $stmt->execute();

        if ($status === 'completed') {
            // Update inventory
            if ($location_type === 'hospital') {
                $inventory_sql = "INSERT INTO blood_inventory (hospital_id, blood_group, quantity) 
                                VALUES (?, ?, 1)
                                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            } else {
                $inventory_sql = "INSERT INTO blood_bank_inventory (blood_bank_id, blood_group, quantity) 
                                VALUES (?, ?, 1)
                                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            }
            $stmt = $conn->prepare($inventory_sql);
            $stmt->bind_param("is", $location_id, $blood_group);
            $stmt->execute();

            // Update state inventory
            $state_sql = "INSERT INTO state_inventory (state, blood_group, quantity) 
                         VALUES (?, ?, 1)
                         ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            $stmt = $conn->prepare($state_sql);
            $stmt->bind_param("ss", $state, $blood_group);
            $stmt->execute();

            // Add points to user
            require_once 'update_points.php';
            updateUserPoints($user_id, 150);
        }

        $conn->commit();
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error updating donation status']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 