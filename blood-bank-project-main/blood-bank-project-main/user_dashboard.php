<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Get user's donation history with hospital names
$donation_sql = "SELECT d.*, 
                 CASE 
                    WHEN d.location_type = 'hospital' THEN h.name
                    WHEN d.location_type = 'blood_bank' THEN b.name
                 END as location_name
                 FROM donations d 
                 LEFT JOIN hospitals h ON d.location_type = 'hospital' AND d.location_id = h.id
                 LEFT JOIN blood_banks b ON d.location_type = 'blood_bank' AND d.location_id = b.id
                 WHERE d.user_id = '$user_id' 
                 ORDER BY d.donation_date DESC";
$donation_result = $conn->query($donation_sql);

// Get total donations count and lives saved prediction
$total_donations_sql = "SELECT COUNT(*) as total FROM donations WHERE user_id = '$user_id' AND status = 'completed'";
$total_donations_result = $conn->query($total_donations_sql);
$total_donations = $total_donations_result->fetch_assoc()['total'];
$lives_saved = $total_donations * 3; // Each donation can save up to 3 lives

// Get user's notifications with donation request details
$notifications_sql = "SELECT n.*, 
                     dr.blood_group as requested_blood_group,
                     dr.status as request_status,
                     h.name as hospital_name,
                     h.city as hospital_city,
                     h.state as hospital_state
                     FROM notifications n
                     LEFT JOIN donation_requests dr ON n.type = 'donation_request' 
                        AND dr.id = CAST(SUBSTRING_INDEX(n.message, 'Request ID: ', -1) AS UNSIGNED)
                     LEFT JOIN hospitals h ON dr.hospital_id = h.id
                     WHERE n.user_id = ? 
                     ORDER BY n.created_at DESC";
$stmt = $conn->prepare($notifications_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$notifications_result = $stmt->get_result();

// Get unread notifications count
$unread_count_sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND status = 'unread'";
$stmt = $conn->prepare($unread_count_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$unread_count = $stmt->get_result()->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Blood Bank</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #2c3e50;
            --accent-color: #3498db;
            --text-color: #333;
            --light-gray: #f4f4f4;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        .header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .header-right {
            display: flex;
            align-items: center;
            position: relative;
        }

        .nav-bar {
            background-color: var(--secondary-color);
            padding: 0.8rem 2rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-gray);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background-color: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            padding: 0.8rem;
            background-color: var(--light-gray);
            border-radius: 6px;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .points-display {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2em;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--secondary-color);
        }

        tr:hover {
            background-color: var(--light-gray);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-scheduled {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #c0392b;
        }

        .btn i {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-info {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .notification-bell {
            position: relative;
            margin-right: 1rem;
            cursor: pointer;
            display: inline-block;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
            min-width: 20px;
            text-align: center;
        }

        .notification-dropdown {
            position: fixed;
            width: 380px;
            max-height: 500px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            overflow-y: auto;
        }

        .notification-dropdown.active {
            display: block;
        }

        /* Add arrow to dropdown */
        .notification-dropdown::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 15px;
            width: 16px;
            height: 16px;
            background-color: white;
            transform: rotate(45deg);
            box-shadow: -2px -2px 4px rgba(0,0,0,0.05);
        }

        .notification-dropdown::after {
            content: '';
            position: absolute;
            top: 0;
            right: 15px;
            width: 16px;
            height: 16px;
            background-color: white;
            transform: rotate(45deg);
        }

        .notification-header {
            position: relative;
            z-index: 1;
            background-color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 1.2rem;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h3 {
            color: var(--secondary-color);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-header h3 i {
            color: var(--primary-color);
        }

        .mark-all-read {
            color: var(--accent-color);
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .notification-item {
            position: relative;
            z-index: 1;
            padding: 1.2rem;
            border-bottom: 1px solid var(--light-gray);
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            display: flex;
            gap: 1rem;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .notification-item.unread {
            background-color: #f0f7ff;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .notification-icon.hospital {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .notification-icon.blood_bank {
            background-color: #fce4ec;
            color: #c2185b;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-message {
            color: var(--text-color);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .notification-location {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .notification-time {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .no-notifications {
            padding: 2rem;
            text-align: center;
            color: #6c757d;
            background-color: white;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .no-notifications i {
            font-size: 2rem;
            color: var(--light-gray);
        }

        .no-notifications p {
            font-size: 1rem;
            margin: 0;
        }

        .notifications-list {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item.unread {
            background-color: #f0f7ff;
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .notification-time {
            font-size: 0.85rem;
            color: #666;
        }
        
        .notification-body {
            color: #444;
        }
        
        .requester-info {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .notification-actions {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn-accept {
            background-color: #28a745;
            color: white;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .badge {
            background-color: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 999px;
            font-size: 0.85rem;
        }
        
        .no-notifications {
            text-align: center;
            color: #666;
            padding: 2rem;
        }

        /* Add these new styles */
        .emergency-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(220, 53, 69, 0.1);
            z-index: 999;
            animation: emergencyBlink 2s infinite;
            pointer-events: none;
        }

        @keyframes emergencyBlink {
            0% { opacity: 0.1; }
            50% { opacity: 0.3; }
            100% { opacity: 0.1; }
        }

        .emergency-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 0;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(220, 53, 69, 0.3);
            z-index: 1000;
            max-width: 550px;
            width: 90%;
            animation: emergencyPulse 2s infinite;
            overflow: hidden;
        }

        .emergency-popup::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }

        .emergency-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0;
            border-bottom: none;
        }

        .emergency-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: blink 1s infinite;
        }

        .emergency-icon i {
            color: white;
            font-size: 1.8rem;
        }

        .emergency-title {
            color: white;
            font-size: 1.8rem;
            margin: 0;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .emergency-content {
            padding: 2rem;
            margin-bottom: 0;
        }

        .emergency-message {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #dc3545;
            border-bottom: 2px solid #f8d7da;
            padding-bottom: 1rem;
        }

        .emergency-details {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .emergency-details p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }

        .emergency-details p:last-child {
            margin-bottom: 0;
        }

        .emergency-details i {
            width: 25px;
            margin-right: 10px;
            color: #dc3545;
        }

        .emergency-note {
            background-color: #fff3f3;
            padding: 1.2rem;
            border-radius: 10px;
            border-left: 5px solid #dc3545;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .emergency-note i {
            font-size: 2rem;
            color: #dc3545;
        }

        .emergency-note p {
            margin: 0;
            font-size: 1.1rem;
            color: #dc3545;
            font-weight: 500;
        }

        .emergency-actions {
            background-color: #f8f9fa;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid #dee2e6;
        }

        .emergency-close {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .emergency-view {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .emergency-close:hover, .emergency-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .emergency-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 0.3rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        @keyframes emergencyPulse {
            0% { box-shadow: 0 5px 25px rgba(220, 53, 69, 0.3); }
            50% { box-shadow: 0 5px 35px rgba(220, 53, 69, 0.6); }
            100% { box-shadow: 0 5px 25px rgba(220, 53, 69, 0.3); }
        }
    </style>
</head>
<body>
    <div id="emergency-overlay" class="emergency-overlay"></div>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <div class="header-right">
            <div class="notification-bell" onclick="toggleNotifications()" id="notificationBell">
                <i class="fas fa-bell" style="font-size: 1.5rem;"></i>
                <?php if ($unread_count > 0): ?>
                    <span class="notification-badge"><?php echo $unread_count; ?></span>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="nav-bar">
        <a href="nearby_locations.php" class="btn"><i class="fas fa-map-marker-alt"></i> Find Nearby Locations</a>
        <a href="schedule_donation.php" class="btn"><i class="fas fa-calendar-plus"></i> Schedule Donation</a>
        <a href="update_profile.php" class="btn"><i class="fas fa-user-edit"></i> Update Profile</a>
    </div>

    <div class="container">
        <div class="dashboard-grid">
            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Your Profile</h2>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $total_donations; ?></div>
                            <div class="stat-label">Total Donations</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value points-display"><?php echo htmlspecialchars($user['points']); ?></div>
                            <div class="stat-label">Reward Points</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" style="color: #27ae60;"><?php echo $lives_saved; ?></div>
                            <div class="stat-label">Lives Saved</div>
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div><?php echo htmlspecialchars($user['name']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Age</div>
                            <div><?php echo htmlspecialchars($user['age']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Mobile</div>
                            <div><?php echo htmlspecialchars($user['mobile']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Blood Group</div>
                            <div><?php echo htmlspecialchars($user['blood_group']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div><?php echo htmlspecialchars($user['city'] . ', ' . $user['state']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Height</div>
                            <div><?php echo htmlspecialchars($user['height']); ?> cm</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Weight</div>
                            <div><?php echo htmlspecialchars($user['weight']); ?> kg</div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Donation History</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Blood Group</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($donation_result->num_rows > 0) {
                                while($row = $donation_result->fetch_assoc()) {
                                    $donation_date = new DateTime($row['donation_date']);
                                    echo "<tr>";
                                    echo "<td>" . $donation_date->format('F j, Y h:i A') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['location_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                                    echo "<td><span class='status-badge status-" . htmlspecialchars($row['status']) . "'>" . 
                                         htmlspecialchars($row['status']) . "</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center;'>No donation history found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Recent Notifications</h2>
                    <?php if ($unread_count > 0): ?>
                        <span class="badge"><?php echo $unread_count; ?> unread</span>
                    <?php endif; ?>
                </div>
                <div class="notifications-list">
                    <?php if ($notifications_result && $notifications_result->num_rows > 0): ?>
                        <?php while($notification = $notifications_result->fetch_assoc()): ?>
                            <div class="notification-item <?php echo $notification['status'] === 'unread' ? 'unread' : ''; ?>">
                                <div class="notification-header">
                                    <h3><?php echo htmlspecialchars($notification['title']); ?></h3>
                                    <span class="notification-time">
                                        <?php echo date('M d, Y H:i', strtotime($notification['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="notification-body">
                                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                    <?php if ($notification['type'] === 'donation_request' && $notification['hospital_name']): ?>
                                        <div class="requester-info">
                                            <p><strong>Hospital:</strong> <?php echo htmlspecialchars($notification['hospital_name']); ?></p>
                                            <p><strong>Location:</strong> <?php echo htmlspecialchars($notification['hospital_city'] . ', ' . $notification['hospital_state']); ?></p>
                                        </div>
                                        <div class="notification-actions">
                                            <button class="btn btn-accept" onclick="respondToDonationRequest(<?php echo $notification['id']; ?>, 'accept')">Accept</button>
                                            <button class="btn btn-reject" onclick="respondToDonationRequest(<?php echo $notification['id']; ?>, 'reject')">Decline</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-notifications">No notifications to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="emergencyPopup" class="emergency-popup">
        <div class="emergency-header">
            <div class="emergency-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="emergency-title">Emergency Blood Request</h3>
        </div>
        <div class="emergency-content">
            <div id="emergencyMessage"></div>
        </div>
        <div class="emergency-actions">
            <button class="emergency-close" onclick="closeEmergencyPopup()">
                <i class="fas fa-times"></i> Close
            </button>
            <button class="emergency-view" onclick="viewEmergencyDetails()">
                <i class="fas fa-eye"></i> View Details
            </button>
        </div>
    </div>

    <script>
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notification-bell');
        const bellRect = bell.getBoundingClientRect();
        
        if (!dropdown.classList.contains('active')) {
            // Position the dropdown relative to the bell
            dropdown.style.top = (bellRect.bottom + 10) + 'px';
            dropdown.style.right = (window.innerWidth - bellRect.right) + 'px';
        }
        
        dropdown.classList.toggle('active');

        // If opening the dropdown, mark notifications as read
        if (dropdown.classList.contains('active')) {
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                const notificationId = notification.dataset.notificationId;
                markAsRead(notificationId);
            });
        }
    }

    function markAsRead(notificationId) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notification) {
                    notification.classList.remove('unread');
                    updateUnreadCount();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        fetch('mark_all_notifications_read.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                document.querySelector('.notification-badge').remove();
                document.querySelector('.mark-all-read').remove();
            }
        });
    }

    function updateUnreadCount() {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            const count = parseInt(badge.textContent) - 1;
            if (count > 0) {
                badge.textContent = count;
            } else {
                badge.remove();
                const markAllRead = document.querySelector('.mark-all-read');
                if (markAllRead) markAllRead.remove();
            }
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notification-bell');
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Update dropdown position on scroll
    window.addEventListener('scroll', function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown.classList.contains('active')) {
            const bell = document.querySelector('.notification-bell');
            const bellRect = bell.getBoundingClientRect();
            dropdown.style.top = (bellRect.bottom + 10) + 'px';
            dropdown.style.right = (window.innerWidth - bellRect.right) + 'px';
        }
    });

    function respondToDonationRequest(notificationId, response) {
        if (!confirm(`Are you sure you want to ${response} this donation request?`)) {
            return;
        }
        
        fetch('respond_to_donation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `notification_id=${notificationId}&response=${response}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your response.');
        });
    }

    function checkEmergencyNotifications() {
        const notifications = <?php 
            $emergency_sql = "SELECT 
                n.*,
                CASE 
                    WHEN n.type = 'donation_request' THEN dr.blood_group 
                    ELSE NULL 
                END as requested_blood_group,
                CASE 
                    WHEN n.type = 'donation_request' THEN dr.status 
                    ELSE NULL 
                END as request_status,
                h.name as hospital_name,
                h.city as hospital_city,
                h.state as hospital_state
                FROM notifications n
                LEFT JOIN donation_requests dr ON n.type = 'donation_request' 
                    AND dr.id = CAST(
                        CASE 
                            WHEN n.message LIKE '%Request ID: %' 
                            THEN SUBSTRING_INDEX(n.message, 'Request ID: ', -1)
                            ELSE 0 
                        END AS UNSIGNED
                    )
                LEFT JOIN hospitals h ON dr.hospital_id = h.id
                WHERE n.user_id = ? 
                AND n.status = 'unread' 
                AND n.type = 'donation_request'
                AND dr.status = 'pending'
                ORDER BY n.created_at DESC 
                LIMIT 1";
            $stmt = $conn->prepare($emergency_sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $emergency_result = $stmt->get_result();
            $emergency_data = $emergency_result->fetch_assoc();
            echo json_encode($emergency_data);
        ?>;

        if (notifications && notifications.hospital_name) {
            showEmergencyPopup(notifications);
            document.getElementById('notificationBell').classList.add('emergency');
        }
    }

    function showEmergencyPopup(notification) {
        const popup = document.getElementById('emergencyPopup');
        const messageDiv = document.getElementById('emergencyMessage');
        const overlay = document.getElementById('emergency-overlay');
        
        // Check if all required data is present
        if (!notification.hospital_name || !notification.requested_blood_group) {
            console.error('Incomplete notification data:', notification);
            return;
        }
        
        let content = `
            <p class="emergency-message">
                <strong>${notification.hospital_name}</strong> has requested an emergency blood donation
            </p>
            
            <div class="emergency-details">
                <p><i class="fas fa-hospital"></i> <strong>Hospital:</strong> ${notification.hospital_name}</p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> ${notification.hospital_city || ''}, ${notification.hospital_state || ''}</p>
                <p><i class="fas fa-tint"></i> <strong>Blood Group Needed:</strong> ${notification.requested_blood_group}</p>
                <p><i class="fas fa-clock"></i> <strong>Request Time:</strong> ${new Date(notification.created_at).toLocaleString()}</p>
            </div>

            <div class="emergency-stats">
                <div class="stat-item">
                    <div class="stat-value">4.2 km</div>
                    <div class="stat-label">Distance from you</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">Urgent</div>
                    <div class="stat-label">Priority Level</div>
                </div>
            </div>

            <div class="emergency-note">
                <i class="fas fa-heartbeat"></i>
                <p>This is an emergency request. Your immediate response could save a life. Please respond as soon as possible.</p>
            </div>
        `;
        
        messageDiv.innerHTML = content;
        popup.style.display = 'block';
        overlay.style.display = 'block';

        // Play notification sound
        playEmergencySound();
    }

    function playEmergencySound() {
        const audio = new Audio('notification.mp3'); // Make sure to add this file to your server
        audio.play().catch(error => console.log('Audio autoplay failed:', error));
    }

    function closeEmergencyPopup() {
        document.getElementById('emergencyPopup').style.display = 'none';
        document.getElementById('emergency-overlay').style.display = 'none';
    }

    function viewEmergencyDetails() {
        closeEmergencyPopup();
        toggleNotifications();
    }

    // Call this when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        checkEmergencyNotifications();
    });
    </script>
</body>
</html> 