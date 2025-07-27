<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['state_id']) && !empty($_GET['state_id'])) {
    $state_id = $_GET['state_id'];
    
    $sql = "SELECT id, name FROM cities WHERE state_id = $state_id ORDER BY name";
    $result = $conn->query($sql);
    
    $cities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cities[] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];
        }
    }
    
    echo json_encode($cities);
} else {
    echo json_encode([]);
}
?> 