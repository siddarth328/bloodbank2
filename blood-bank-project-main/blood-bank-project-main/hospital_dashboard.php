<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a hospital
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header("Location: login.php");
    exit();
}

// Get hospital data
$hospital_id = $_SESSION['hospital_id'];
$sql = "SELECT * FROM hospitals WHERE id = '$hospital_id'";
$result = $conn->query($sql);
$hospital = $result->fetch_assoc();

// Get blood inventory
$inventory_sql = "SELECT * FROM blood_inventory WHERE hospital_id = '$hospital_id'";
$inventory_result = $conn->query($inventory_sql);

// Get blood requests
$requests_sql = "SELECT * FROM blood_requests WHERE hospital_id = '$hospital_id' ORDER BY request_date DESC";
$requests_result = $conn->query($requests_sql);

// Get scheduled donations
$donations_sql = "SELECT d.*, u.name as donor_name, u.blood_group, u.mobile 
                  FROM donations d 
                  JOIN users u ON d.user_id = u.id 
                  WHERE d.location_type = 'hospital' 
                  AND d.location_id = '$hospital_id' 
                  AND d.status = 'scheduled'
                  ORDER BY d.donation_date ASC";
$donations_result = $conn->query($donations_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - Blood Bank</title>
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
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
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
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
        }
        input, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .nav-tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0 0 1rem 0;
            border-bottom: 1px solid #ddd;
        }
        .nav-tabs li {
            margin-right: 1rem;
        }
        .nav-tabs a {
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
        }
        .nav-tabs a.active {
            background-color: #f8f9fa;
            border-color: #ddd;
            border-bottom-color: #f8f9fa;
            margin-bottom: -1px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .donor-list {
            display: none;
            margin-top: 1rem;
        }
        .donor-card {
            border: 1px solid #ddd;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .donor-card p {
            margin: 0.5rem 0;
        }
        .donor-actions {
            margin-top: 1rem;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 1rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .donor-section {
            margin-bottom: 2rem;
        }
        .donor-section h4 {
            color: #2c3e50;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e74c3c;
        }
        .donor-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .donor-table th,
        .donor-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .donor-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        .donor-table tr:hover {
            background-color: #f5f5f5;
        }
        .distance-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: bold;
            background-color: #e2e3e5;
            color: #383d41;
        }
        .donor-count {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #2c3e50;
        }
        .donor-count strong {
            color: #e74c3c;
        }
        .btn-request {
            background-color: #27ae60;
            color: white;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn-request:hover {
            background-color: #219a52;
        }
        .btn-request:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }
        .btn-request.requested {
            background-color: #95a5a6;
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hospital Dashboard</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($hospital['name']); ?></span>
            <a href="logout.php" class="btn" style="margin-left: 1rem;">Logout</a>
        </div>
    </div>

    <div class="container">
        <ul class="nav-tabs">
            <li><a href="#inventory" class="active">Blood Inventory</a></li>
            <li><a href="#requests">Blood Requests</a></li>
            <li><a href="#scheduled">Scheduled Donations</a></li>
            <li><a href="#offline">Offline Donations</a></li>
        </ul>

        <div id="inventory" class="tab-content active">
            <div class="card">
                <h2>Blood Inventory</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Quantity</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $inventory_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="requests" class="tab-content">
            <div class="card">
                <h2>Request Blood</h2>
                <form id="bloodRequestForm" onsubmit="return handleBloodRequest(event)">
                    <div class="form-group">
                        <label for="request_type">Request Type</label>
                        <select id="request_type" name="request_type" required onchange="toggleQuantityField()">
                            <option value="blood_bank">Blood Bank</option>
                            <option value="donors">Nearby Donors</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group" id="quantityField">
                        <label for="quantity">Quantity (units)</label>
                        <input type="number" id="quantity" name="quantity" min="1">
                    </div>
                    <button type="submit" class="btn">Submit Request</button>
                </form>
                
                <div id="loading" class="loading">
                    <p>Searching for donors...</p>
                </div>
                
                <div id="donorList" class="donor-list">
                    <h3>Available Donors</h3>
                    <div id="donorCards"></div>
                </div>
            </div>

            <div class="card">
                <h2>Blood Requests History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Request Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $requests_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="scheduled" class="tab-content">
            <div class="card">
                <h2>Scheduled Donations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Donor Name</th>
                            <th>Blood Group</th>
                            <th>Mobile</th>
                            <th>Scheduled Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($donations_result->num_rows > 0): ?>
                            <?php while($row = $donations_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($row['donation_date']); ?></td>
                                <td>
                                    <button onclick="updateDonationStatus(<?php echo $row['id']; ?>, 'completed')" class="btn btn-success">Donated</button>
                                    <button onclick="updateDonationStatus(<?php echo $row['id']; ?>, 'cancelled')" class="btn btn-danger">Not Visited</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No scheduled donations</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="offline" class="tab-content">
            <div class="card">
                <h2>Offline Donations</h2>
                <p>Record donations that were made offline (not scheduled through the website).</p>
                <a href="enter_offline_donation.php" class="btn">Enter Offline Donation</a>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.nav-tabs a').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const target = e.target.getAttribute('href').substring(1);
                
                // Update active tab
                document.querySelectorAll('.nav-tabs a').forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
                
                // Show target content
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                document.getElementById(target).classList.add('active');
            });
        });

        function updateDonationStatus(donationId, status) {
            if (confirm('Are you sure you want to mark this donation as ' + status + '?')) {
                fetch('update_donation_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'donation_id=' + donationId + '&status=' + status + '&location_type=hospital'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Donation status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating donation status: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error updating donation status: ' + error);
                });
            }
        }

        function toggleQuantityField() {
            const requestType = document.getElementById('request_type').value;
            const quantityField = document.getElementById('quantityField');
            const quantityInput = document.getElementById('quantity');
            
            if (requestType === 'blood_bank') {
                quantityField.style.display = 'block';
                quantityInput.required = true;
            } else {
                quantityField.style.display = 'none';
                quantityInput.required = false;
                quantityInput.value = ''; // Clear the value when switching to donors
            }
        }

        function handleBloodRequest(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const requestType = formData.get('request_type');
            
            document.getElementById('loading').style.display = 'block';
            document.getElementById('donorList').style.display = 'none';
            
            fetch('process_donor_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                
                if (requestType === 'donors') {
                    displayDonors(data);
                } else {
                    if (data.status === 'success') {
                        alert('Blood request submitted successfully');
                        location.reload();
                    } else {
                        alert('Failed to submit blood request: ' + (data.message || 'Unknown error'));
                    }
                }
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                console.error('Error details:', error);
                alert('An error occurred: ' + error.message);
            });
            
            return false;
        }

        function displayDonors(data) {
            const donorList = document.getElementById('donorList');
            const donorCards = document.getElementById('donorCards');
            donorCards.innerHTML = '';
            
            if (data.status === 'success' && Array.isArray(data.donors)) {
                if (data.donors.length > 0) {
                    console.log('Donor data:', data.donors); // Debug log
                    
                    const donorCount = document.createElement('div');
                    donorCount.className = 'donor-count';
                    donorCount.innerHTML = `Found <strong>${data.donors.length}</strong> donors in ${data.hospital.state}`;
                    donorCards.appendChild(donorCount);
                    
                    const table = document.createElement('table');
                    table.className = 'donor-table';
                    
                    table.innerHTML = `
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Blood Group</th>
                                <th>Location</th>
                                <th>Distance</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.donors.map(donor => {
                                // Debug log for each donor
                                console.log('Processing donor:', donor);
                                return `
                                    <tr>
                                        <td>${donor.name || 'N/A'}</td>
                                        <td>${donor.blood_group || 'N/A'}</td>
                                        <td>${donor.location || 'N/A'}</td>
                                        <td>
                                            <span class="distance-badge">
                                                ${donor.distance !== null ? Number(donor.distance).toFixed(1) + ' km' : 'Distance N/A'}
                                            </span>
                                        </td>
                                        <td>${donor.mobile || 'N/A'}</td>
                                        <td>
                                            <button 
                                                class="btn-request" 
                                                onclick="sendDonorRequest('${donor.id || ''}', '${donor.blood_group || ''}')"
                                                id="request-${donor.id || ''}"
                                                ${!donor.id ? 'disabled' : ''}
                                            >
                                                ${!donor.id ? 'Invalid Donor' : 'Request Donation'}
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    `;
                    
                    donorCards.appendChild(table);
                } else {
                    donorCards.innerHTML = '<div class="alert alert-info">No donors found in your state.</div>';
                }
            } else {
                console.error('Invalid data structure:', data); // Debug log
                donorCards.innerHTML = `<div class="alert alert-error">Failed to fetch donor information: ${data.message || 'Unknown error'}</div>`;
            }
            
            donorList.style.display = 'block';
        }

        function sendDonorRequest(donorId, bloodGroup) {
            if (!donorId) {
                console.error('No donor ID provided');
                alert('Invalid donor ID');
                return;
            }
            
            const button = document.getElementById(`request-${donorId}`);
            if (!button) {
                console.error('Button not found for donor ID:', donorId);
                return;
            }
            
            button.disabled = true;
            button.textContent = 'Sending...';
            
            console.log('Sending request for donor:', donorId, 'blood group:', bloodGroup);
            
            const formData = new FormData();
            formData.append('donor_id', donorId);
            formData.append('blood_group', bloodGroup);
            
            fetch('send_donor_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text().then(text => {
                    console.log('Raw response:', text); // Debug log
                    try {
                        return JSON.parse(text);
                    } catch (error) {
                        console.error('Error parsing JSON:', error, 'Response text:', text);
                        throw new Error('Server returned invalid response: ' + text.substring(0, 100));
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.status === 'success') {
                    button.textContent = 'Request Sent';
                    button.classList.add('requested');
                    alert('Request sent successfully to the donor.');
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Request error:', error);
                button.disabled = false;
                button.textContent = 'Request Donation';
                alert('An error occurred while sending the request: ' + error.message);
            });
        }

        // Initialize the quantity field visibility
        document.addEventListener('DOMContentLoaded', toggleQuantityField);
    </script>
</body>
</html> 