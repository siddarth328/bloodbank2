<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a hospital
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit();
}

$hospital_id = $_SESSION['hospital_id'];
$blood_group = $_POST['blood_group'] ?? '';
$quantity = $_POST['quantity'] ?? 0;

// Validate input
if (empty($blood_group) || empty($quantity) || !is_numeric($quantity) || $quantity < 1) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request parameters'
    ]);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Get hospital state
    $hospital_sql = "SELECT state FROM hospitals WHERE id = ?";
    $stmt = $conn->prepare($hospital_sql);
    $stmt->bind_param('i', $hospital_id);
    $stmt->execute();
    $hospital = $stmt->get_result()->fetch_assoc();

    if (!$hospital) {
        throw new Exception('Hospital not found');
    }

    // Check state inventory
    $inventory_sql = "SELECT quantity FROM state_inventory WHERE state = ? AND blood_group = ?";
    $stmt = $conn->prepare($inventory_sql);
    $stmt->bind_param('ss', $hospital['state'], $blood_group);
    $stmt->execute();
    $inventory = $stmt->get_result()->fetch_assoc();

    if (!$inventory || $inventory['quantity'] < $quantity) {
        throw new Exception('Insufficient blood units available in your state');
    }

    // Find blood bank with sufficient quantity
    $blood_bank_sql = "SELECT bb.id, bb.name, bbi.quantity 
                       FROM blood_banks bb 
                       JOIN blood_bank_inventory bbi ON bb.id = bbi.blood_bank_id 
                       WHERE bb.state = ? AND bbi.blood_group = ? AND bbi.quantity >= ?
                       ORDER BY bbi.quantity DESC 
                       LIMIT 1";
    $stmt = $conn->prepare($blood_bank_sql);
    $stmt->bind_param('ssi', $hospital['state'], $blood_group, $quantity);
    $stmt->execute();
    $blood_bank = $stmt->get_result()->fetch_assoc();

    if (!$blood_bank) {
        throw new Exception('No single blood bank has sufficient quantity. Please try a smaller quantity.');
    }

    // Create blood request
    $request_sql = "INSERT INTO blood_requests (hospital_id, blood_group, quantity, status) VALUES (?, ?, ?, 'approved')";
    $stmt = $conn->prepare($request_sql);
    $stmt->bind_param('isi', $hospital_id, $blood_group, $quantity);
    $stmt->execute();
    $request_id = $conn->insert_id;

    // Update blood bank inventory
    $update_bank_sql = "UPDATE blood_bank_inventory 
                       SET quantity = quantity - ? 
                       WHERE blood_bank_id = ? AND blood_group = ?";
    $stmt = $conn->prepare($update_bank_sql);
    $stmt->bind_param('iis', $quantity, $blood_bank['id'], $blood_group);
    $stmt->execute();

    // Update state inventory
    $update_state_sql = "UPDATE state_inventory 
                        SET quantity = quantity - ? 
                        WHERE state = ? AND blood_group = ?";
    $stmt = $conn->prepare($update_state_sql);
    $stmt->bind_param('iss', $quantity, $hospital['state'], $blood_group);
    $stmt->execute();

    // Create notification for hospital
    $notification_sql = "INSERT INTO notifications (user_id, title, message, type, status) 
                        VALUES (?, ?, ?, 'blood_request', 'unread')";
    $title = "Blood Request Approved";
    $message = sprintf(
        "Your request for %d units of %s blood has been approved. Blood will be supplied from %s. Request ID: %d",
        $quantity,
        $blood_group,
        $blood_bank['name'],
        $request_id
    );
    $stmt = $conn->prepare($notification_sql);
    $stmt->bind_param('iss', $hospital_id, $title, $message);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Blood request approved successfully',
        'data' => [
            'request_id' => $request_id,
            'blood_bank' => $blood_bank['name'],
            'quantity' => $quantity,
            'blood_group' => $blood_group
        ]
    ]);

} catch (Exception $e) {
    $conn->rollback();
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 