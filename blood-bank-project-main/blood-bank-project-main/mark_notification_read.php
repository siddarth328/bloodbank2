<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['notification_id'])) {
    echo json_encode(['success' => false, 'message' => 'Notification ID is required']);
    exit();
}

$notification_id = $_POST['notification_id'];
$user_id = $_SESSION['user_id'];

// Verify that the notification belongs to the user
$verify_sql = "SELECT id FROM notifications WHERE id = '$notification_id' AND user_id = '$user_id'";
$verify_result = $conn->query($verify_sql);

if ($verify_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Notification not found']);
    exit();
}

// Mark notification as read
$update_sql = "UPDATE notifications SET is_read = TRUE WHERE id = '$notification_id'";
if ($conn->query($update_sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating notification']);
}
?> 