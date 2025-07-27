<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's state
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT state FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
$user_state = $user['state'];

// Get state inventory
$inventory_sql = "SELECT * FROM state_inventory WHERE state = '$user_state' ORDER BY blood_group";
$inventory_result = $conn->query($inventory_sql);

// Get hospitals in the state
$hospitals_sql = "SELECT * FROM hospitals WHERE state = '$user_state' AND status = 'active' ORDER BY city, name";
$hospitals_result = $conn->query($hospitals_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>State Blood Inventory - <?php echo $user_state; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .inventory-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .blood-group {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .quantity {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
        .hospital-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .hospital-item {
            border-left: 4px solid #dc3545;
            margin-bottom: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="text-center">Blood Inventory in <?php echo $user_state; ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="inventory-card">
                    <h2 class="mb-4">Available Blood Groups</h2>
                    <div class="row">
                        <?php
                        $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        while ($row = $inventory_result->fetch_assoc()) {
                            echo '<div class="col-md-3 mb-4">';
                            echo '<div class="text-center">';
                            echo '<div class="blood-group">' . $row['blood_group'] . '</div>';
                            echo '<div class="quantity">' . $row['quantity'] . '</div>';
                            echo '<small class="text-muted">Units Available</small>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="inventory-card">
                    <h2 class="mb-4">Hospitals in <?php echo $user_state; ?></h2>
                    <div class="hospital-list">
                        <?php
                        $current_city = '';
                        while ($hospital = $hospitals_result->fetch_assoc()) {
                            if ($current_city !== $hospital['city']) {
                                echo '<h5 class="mt-3 mb-2">' . $hospital['city'] . '</h5>';
                                $current_city = $hospital['city'];
                            }
                            echo '<div class="hospital-item">';
                            echo '<h6>' . $hospital['name'] . '</h6>';
                            echo '<p class="mb-0">' . $hospital['address'] . '</p>';
                            echo '<small class="text-muted">Contact: ' . $hospital['contact_number'] . '</small>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-center">
                <a href="user_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 