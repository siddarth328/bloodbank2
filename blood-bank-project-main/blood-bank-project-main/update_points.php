<?php
require_once 'config.php';

// Function to update user points after donation
function updateUserPoints($userId, $pointsToAdd) {
    global $conn;
    
    // Get current points
    $sql = "SELECT points FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Calculate new points
    $newPoints = $user['points'] + $pointsToAdd;
    
    // Update points
    $updateSql = "UPDATE users SET points = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ii", $newPoints, $userId);
    $updateStmt->execute();
    
    return $newPoints;
}

// Example usage after a successful donation:
// updateUserPoints($userId, 150); // Add 150 points for each donation
?> 