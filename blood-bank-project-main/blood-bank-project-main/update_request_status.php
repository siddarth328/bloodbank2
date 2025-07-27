<?php
session_start();
require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get request parameters
$request_id = $_POST['request_id'] ?? 0;
$status = $_POST['status'] ?? '';

if (!$request_id || !in_array($status, ['approved', 'rejected'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Get request details including hospital state and blood group
    $request_sql = "SELECT br.*, h.state, h.name as hospital_name 
                    FROM blood_requests br 
                    JOIN hospitals h ON br.hospital_id = h.id 
                    WHERE br.id = ?";
    $stmt = $conn->prepare($request_sql);
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    $request = $stmt->get_result()->fetch_assoc();

    if (!$request) {
        throw new Exception('Request not found');
    }

    // Check if request is already processed
    if ($request['status'] !== 'pending') {
        throw new Exception('Request has already been ' . $request['status']);
    }

    // Only proceed with inventory checks and updates if approving
    if ($status === 'approved') {
        // Validate quantity
        if (!is_numeric($request['quantity']) || $request['quantity'] <= 0) {
            throw new Exception('Invalid quantity requested');
        }

        // Check if sufficient blood units are available in state inventory
        $check_inventory_sql = "SELECT quantity FROM state_inventory 
                              WHERE state = ? AND blood_group = ?";
        $stmt = $conn->prepare($check_inventory_sql);
        $stmt->bind_param('ss', $request['state'], $request['blood_group']);
        $stmt->execute();
        $inventory = $stmt->get_result()->fetch_assoc();

        if (!$inventory) {
            throw new Exception('No inventory found for this blood group in your state');
        }

        if ($inventory['quantity'] < $request['quantity']) {
            throw new Exception(sprintf(
                'Insufficient blood units available. Requested: %d, Available: %d',
                $request['quantity'],
                $inventory['quantity']
            ));
        }

        // Find blood bank with sufficient quantity
        $find_bank_sql = "SELECT bb.id, bb.name, bbi.quantity 
                         FROM blood_banks bb 
                         JOIN blood_bank_inventory bbi ON bb.id = bbi.blood_bank_id 
                         WHERE bb.state = ? 
                         AND bbi.blood_group = ? 
                         AND bbi.quantity >= ?
                         ORDER BY bbi.quantity DESC 
                         LIMIT 1";
        $stmt = $conn->prepare($find_bank_sql);
        $stmt->bind_param('ssi', 
            $request['state'], 
            $request['blood_group'],
            $request['quantity']
        );
        $stmt->execute();
        $blood_bank = $stmt->get_result()->fetch_assoc();

        if (!$blood_bank) {
            throw new Exception('No single blood bank has sufficient quantity. Please split your request.');
        }

        // Update blood bank inventory
        $update_bank_sql = "UPDATE blood_bank_inventory 
                           SET quantity = quantity - ?
                           WHERE blood_bank_id = ? 
                           AND blood_group = ? 
                           AND quantity >= ?";
        $stmt = $conn->prepare($update_bank_sql);
        $stmt->bind_param('iisi', 
            $request['quantity'], 
            $blood_bank['id'],
            $request['blood_group'],
            $request['quantity']
        );
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception('Failed to update blood bank inventory');
        }

        // Update state inventory
        $update_state_sql = "UPDATE state_inventory 
                            SET quantity = quantity - ?
                            WHERE state = ? 
                            AND blood_group = ? 
                            AND quantity >= ?";
        $stmt = $conn->prepare($update_state_sql);
        $stmt->bind_param('issi', 
            $request['quantity'], 
            $request['state'], 
            $request['blood_group'],
            $request['quantity']
        );
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception('Failed to update state inventory');
        }

        // Store blood bank info for notification
        $source_bank_name = $blood_bank['name'];
    }

    // Update request status
    $update_sql = "UPDATE blood_requests 
                   SET status = ?
                   WHERE id = ? AND status = 'pending'";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $status, $request_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update request status');
    }

    // Create notification for hospital
    $notification_title = $status === 'approved' ? 
        "Blood Request Approved" : 
        "Blood Request Rejected";
    
    $notification_message = $status === 'approved' ?
        sprintf(
            "Your request for %d units of %s blood has been approved. Blood will be supplied from %s. Please collect within 24 hours.",
            $request['quantity'],
            $request['blood_group'],
            $source_bank_name
        ) :
        sprintf(
            "Your request for %d units of %s blood has been rejected. Please contact support for more information.",
            $request['quantity'],
            $request['blood_group']
        );

    $notification_sql = "INSERT INTO notifications (user_id, title, message, type, status) 
                        VALUES (?, ?, ?, 'blood_request', 'unread')";
    $stmt = $conn->prepare($notification_sql);
    $stmt->bind_param('iss', $request['hospital_id'], $notification_title, $notification_message);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to create notification');
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Request ' . $status . ' successfully',
        'data' => [
            'request_id' => $request_id,
            'status' => $status,
            'hospital' => $request['hospital_name'],
            'quantity' => $request['quantity'],
            'blood_group' => $request['blood_group'],
            'source_bank' => $status === 'approved' ? $source_bank_name : null
        ]
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 