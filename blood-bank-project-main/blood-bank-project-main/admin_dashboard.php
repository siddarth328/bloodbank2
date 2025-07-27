<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get all blood requests with hospital details
$requests_sql = "SELECT br.*, h.name as hospital_name, h.city, h.state, h.contact_number 
                 FROM blood_requests br 
                 JOIN hospitals h ON br.hospital_id = h.id 
                 ORDER BY br.request_date DESC";
$requests_result = $conn->query($requests_sql);

// Get state-wise blood inventory
$inventory_sql = "SELECT 
                    state,
                    MAX(CASE WHEN blood_group = 'A+' THEN quantity ELSE 0 END) as 'A+',
                    MAX(CASE WHEN blood_group = 'A-' THEN quantity ELSE 0 END) as 'A-',
                    MAX(CASE WHEN blood_group = 'B+' THEN quantity ELSE 0 END) as 'B+',
                    MAX(CASE WHEN blood_group = 'B-' THEN quantity ELSE 0 END) as 'B-',
                    MAX(CASE WHEN blood_group = 'AB+' THEN quantity ELSE 0 END) as 'AB+',
                    MAX(CASE WHEN blood_group = 'AB-' THEN quantity ELSE 0 END) as 'AB-',
                    MAX(CASE WHEN blood_group = 'O+' THEN quantity ELSE 0 END) as 'O+',
                    MAX(CASE WHEN blood_group = 'O-' THEN quantity ELSE 0 END) as 'O-',
                    MAX(last_updated) as last_updated
                FROM state_inventory
                GROUP BY state
                ORDER BY state";
$inventory_result = $conn->query($inventory_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blood Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #e74c3c;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .btn {
            background-color: #e74c3c;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        .btn-success {
            background-color: #27ae60;
        }
        .btn-success:hover {
            background-color: #219a52;
        }
        .btn-danger {
            background-color: #c0392b;
        }
        .btn-danger:hover {
            background-color: #a93226;
        }
        .status-pending {
            color: #f39c12;
        }
        .status-approved {
            color: #27ae60;
        }
        .status-rejected {
            color: #c0392b;
        }
        .status-completed {
            color: #3498db;
        }
        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .inventory-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .blood-group {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 0.5rem;
        }
        .quantity {
            font-size: 1.2rem;
            color: #2c3e50;
        }
        #initializeInventory {
            background-color: #28a745;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }
        #initializeInventory:hover {
            background-color: #218838;
        }
        .table-responsive {
            overflow-x: auto;
            margin-top: 1rem;
        }
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .inventory-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #dee2e6;
        }
        .inventory-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .state-name {
            font-weight: bold;
            text-align: left !important;
            color: #2c3e50;
            background-color: #f8f9fa;
        }
        .blood-quantity {
            font-family: monospace;
            font-size: 1.1em;
        }
        .low-stock {
            color: #dc3545;
            font-weight: bold;
            background-color: #fff3f3;
        }
        .last-updated {
            font-size: 0.9em;
            color: #6c757d;
        }
        .state-summary {
            margin-top: 2rem;
        }
        .state-summary h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .summary-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-card h4 {
            color: #2c3e50;
            margin: 0 0 1rem 0;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 0.5rem;
        }
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        .stat-item {
            text-align: center;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .stat-label {
            font-size: 0.9em;
            color: #6c757d;
        }
        .stat-value {
            font-size: 1.2em;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 0.2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="btn">Logout</a>
    </div>

    <div class="container">
        <div class="card">
            <h2>Blood Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Hospital</th>
                        <th>Location</th>
                        <th>Blood Group</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $requests_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['city'] . ', ' . $row['state']); ?></td>
                        <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td class="status-<?php echo htmlspecialchars($row['status']); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                            <button class="btn btn-success" onclick="updateRequestStatus(<?php echo $row['id']; ?>, 'approved')">Approve</button>
                            <button class="btn btn-danger" onclick="updateRequestStatus(<?php echo $row['id']; ?>, 'rejected')">Reject</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>State-wise Blood Inventory</h2>
            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>State</th>
                            <th>A+</th>
                            <th>A-</th>
                            <th>B+</th>
                            <th>B-</th>
                            <th>AB+</th>
                            <th>AB-</th>
                            <th>O+</th>
                            <th>O-</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $inventory_result->fetch_assoc()): ?>
                        <tr>
                            <td class="state-name"><?php echo htmlspecialchars($row['state']); ?></td>
                            <?php foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $blood_group): ?>
                                <td class="blood-quantity <?php echo $row[$blood_group] < 100 ? 'low-stock' : ''; ?>">
                                    <?php echo htmlspecialchars($row[$blood_group]); ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="last-updated"><?php echo date('Y-m-d H:i', strtotime($row['last_updated'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2>Blood Inventory Management</h2>
            <button id="initializeInventory" class="btn btn-primary">Initialize Blood Inventory (1000 units each)</button>
            
            <div id="inventoryStatus" class="mt-4">
                <h3>Current Inventory Levels</h3>
                <div class="inventory-grid">
                    <?php
                    $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                    foreach ($blood_groups as $blood_group) {
                        $sql = "SELECT SUM(quantity) as total FROM blood_bank_inventory WHERE blood_group = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('s', $blood_group);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        $total = $result['total'] ?? 0;
                        ?>
                        <div class="inventory-item">
                            <div class="blood-group"><?php echo $blood_group; ?></div>
                            <div class="quantity"><?php echo $total; ?> units</div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="state-summary">
        <h3>State-wise Summary</h3>
        <div class="summary-grid">
            <?php
            // Reset the result pointer
            $inventory_result->data_seek(0);
            while($row = $inventory_result->fetch_assoc()):
                $total_units = 0;
                $low_stock_count = 0;
                foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $blood_group) {
                    $total_units += $row[$blood_group];
                    if($row[$blood_group] < 100) {
                        $low_stock_count++;
                    }
                }
            ?>
            <div class="summary-card">
                <h4><?php echo htmlspecialchars($row['state']); ?></h4>
                <div class="summary-stats">
                    <div class="stat-item">
                        <div class="stat-label">Total Units</div>
                        <div class="stat-value"><?php echo number_format($total_units); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Low Stock Groups</div>
                        <div class="stat-value <?php echo $low_stock_count > 0 ? 'low-stock' : ''; ?>">
                            <?php echo $low_stock_count; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function updateRequestStatus(requestId, status) {
            if (confirm('Are you sure you want to ' + status + ' this request?')) {
                fetch('update_request_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'request_id=' + requestId + '&status=' + status
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Request status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating request status: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error updating request status: ' + error);
                });
            }
        }

        document.getElementById('initializeInventory').addEventListener('click', function() {
            if (confirm('This will set all blood groups to 1000 units in each blood bank. Continue?')) {
                fetch('initialize_blood_inventory.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Blood inventory initialized successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while initializing inventory.');
                });
            }
        });
    </script>
</body>
</html> 