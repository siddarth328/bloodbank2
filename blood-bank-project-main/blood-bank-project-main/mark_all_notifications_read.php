<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Mark all notifications as read
$update_sql = "UPDATE notifications SET is_read = TRUE WHERE user_id = '$user_id' AND is_read = FALSE";
if ($conn->query($update_sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating notifications']);
}
?> 