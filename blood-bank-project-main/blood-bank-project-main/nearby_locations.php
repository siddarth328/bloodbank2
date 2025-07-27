<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's location
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT city, state, house_no, colony, street, landmark FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

// Get nearby hospitals
$hospitals_sql = "SELECT * FROM hospitals 
                 WHERE state = '{$user['state']}' 
                 AND city = '{$user['city']}'
                 ORDER BY name";
$hospitals_result = $conn->query($hospitals_sql);

// Get nearby blood banks
$blood_banks_sql = "SELECT * FROM blood_banks 
                   WHERE state = '{$user['state']}' 
                   AND city = '{$user['city']}'
                   ORDER BY name";
$blood_banks_result = $conn->query($blood_banks_sql);

// Prepare user's full address for map
$user_address = "{$user['house_no']}, {$user['colony']}, {$user['street']}, {$user['landmark']}, {$user['city']}, {$user['state']}";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nearby Locations - Blood Bank</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #d32f2f;
        }
        .map-container {
            height: 500px;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        #mainMap {
            height: 100%;
            width: 100%;
        }
        .location-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .location-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .location-info {
            flex: 1;
        }
        .location-card h3 {
            margin-top: 0;
            color: #d32f2f;
        }
        .location-info p {
            margin: 5px 0;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #d32f2f;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filter-section select, .filter-section input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            min-width: 150px;
        }
        .filter-section button {
            padding: 8px 15px;
            background-color: #d32f2f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-section button:hover {
            background-color: #b71c1c;
        }
        .location-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .distance-badge {
            background-color: #d32f2f;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .selected {
            border: 2px solid #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="user_dashboard.php" class="back-link">← Back to Dashboard</a>
        <h1>Nearby Locations</h1>
        <p>Showing locations in <?php echo htmlspecialchars($user['city'] . ', ' . $user['state']); ?></p>

        <div class="filter-section">
            <select id="typeFilter">
                <option value="all">All Locations</option>
                <option value="hospital">Hospitals Only</option>
                <option value="blood_bank">Blood Banks Only</option>
            </select>
            <select id="bloodGroupFilter">
                <option value="all">All Blood Groups</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
            <input type="number" id="distanceFilter" placeholder="Max Distance (km)" min="1" max="100">
            <button onclick="filterLocations()">Filter</button>
        </div>

        <div class="map-container">
            <div id="mainMap"></div>
        </div>

        <div class="location-grid" id="locationsGrid">
            <!-- Locations will be populated here by JavaScript -->
        </div>
    </div>

    <script>
        // Initialize map
        const map = L.map('mainMap').setView([0, 0], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Store locations data
        const locations = [];
        const markers = [];
        let userMarker = null;

        // Function to calculate distance between two coordinates
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of the earth in km
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c; // Distance in km
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }

        // Function to get coordinates from address
        async function getCoordinates(address) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
                const data = await response.json();
                if (data && data[0]) {
                    return {
                        lat: parseFloat(data[0].lat),
                        lon: parseFloat(data[0].lon)
                    };
                }
            } catch (error) {
                console.error('Error getting coordinates:', error);
            }
            return null;
        }

        // Function to create location card
        function createLocationCard(location, type) {
            const card = document.createElement('div');
            card.className = 'location-card';
            card.dataset.type = type;
            card.dataset.bloodGroup = location.blood_group || 'all';
            card.dataset.id = location.id;

            const distance = location.distance ? `<span class="distance-badge">${location.distance.toFixed(1)} km</span>` : '';
            
            card.innerHTML = `
                <div class="location-info">
                    <h3>${location.name} ${distance}</h3>
                    <p><strong>Address:</strong> ${location.address}</p>
                    <p><strong>Contact:</strong> ${location.contact_number}</p>
                    <p><strong>Email:</strong> ${location.email}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent('<?php echo $user_address; ?>')}&destination=${encodeURIComponent(location.address + ', ' + location.city + ', ' + location.state)}" 
                       target="_blank">Get Directions</a>
                </div>
            `;

            card.addEventListener('click', () => {
                // Remove selected class from all cards
                document.querySelectorAll('.location-card').forEach(c => c.classList.remove('selected'));
                // Add selected class to clicked card
                card.classList.add('selected');
                // Center map on selected location
                map.setView([location.lat, location.lon], 15);
            });

            return card;
        }

        // Function to filter locations
        function filterLocations() {
            const typeFilter = document.getElementById('typeFilter').value;
            const bloodGroupFilter = document.getElementById('bloodGroupFilter').value;
            const distanceFilter = parseFloat(document.getElementById('distanceFilter').value) || Infinity;

            const grid = document.getElementById('locationsGrid');
            grid.innerHTML = '';

            locations.forEach(location => {
                if ((typeFilter === 'all' || location.type === typeFilter) &&
                    (bloodGroupFilter === 'all' || location.blood_group === bloodGroupFilter) &&
                    (!location.distance || location.distance <= distanceFilter)) {
                    grid.appendChild(createLocationCard(location, location.type));
                }
            });
        }

        // Initialize locations
        async function initializeLocations() {
            // Get user's current location using Geolocation API
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const userCoords = {
                            lat: position.coords.latitude,
                            lon: position.coords.longitude
                        };
                        
                        map.setView([userCoords.lat, userCoords.lon], 13);
                        
                        // Add user marker with custom icon
                        const userIcon = L.divIcon({
                            className: 'user-location-marker',
                            html: '<div style="background-color: #d32f2f; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });
                        
                        userMarker = L.marker([userCoords.lat, userCoords.lon], {icon: userIcon})
                            .addTo(map)
                            .bindPopup('Your Current Location')
                            .openPopup();

                        // Demo Hospitals Data
                        const demoHospitals = [
                            {
                                id: 1,
                                name: 'City General Hospital',
                                address: '123 Medical Center Drive',
                                city: '<?php echo $user['city']; ?>',
                                state: '<?php echo $user['state']; ?>',
                                contact_number: '555-123-4567',
                                email: 'info@citygeneral.com',
                                lat: userCoords.lat + 0.01,
                                lon: userCoords.lon + 0.01,
                                inventory: {
                                    'A+': 15,
                                    'A-': 8,
                                    'B+': 12,
                                    'B-': 6,
                                    'AB+': 5,
                                    'AB-': 3,
                                    'O+': 20,
                                    'O-': 10
                                }
                            },
                            {
                                id: 2,
                                name: 'Metro Medical Center',
                                address: '456 Health Avenue',
                                city: '<?php echo $user['city']; ?>',
                                state: '<?php echo $user['state']; ?>',
                                contact_number: '555-987-6543',
                                email: 'contact@metromedical.com',
                                lat: userCoords.lat - 0.01,
                                lon: userCoords.lon - 0.01,
                                inventory: {
                                    'A+': 10,
                                    'A-': 5,
                                    'B+': 8,
                                    'B-': 4,
                                    'AB+': 3,
                                    'AB-': 2,
                                    'O+': 15,
                                    'O-': 7
                                }
                            }
                        ];

                        // Demo Blood Banks Data
                        const demoBloodBanks = [
                            {
                                id: 1,
                                name: 'Life Blood Center',
                                address: '789 Donation Street',
                                city: '<?php echo $user['city']; ?>',
                                state: '<?php echo $user['state']; ?>',
                                contact_number: '555-456-7890',
                                email: 'donate@lifeblood.com',
                                lat: userCoords.lat + 0.02,
                                lon: userCoords.lon - 0.02,
                                inventory: {
                                    'A+': 25,
                                    'A-': 12,
                                    'B+': 20,
                                    'B-': 10,
                                    'AB+': 8,
                                    'AB-': 5,
                                    'O+': 30,
                                    'O-': 15
                                }
                            },
                            {
                                id: 2,
                                name: 'Community Blood Services',
                                address: '321 Health Plaza',
                                city: '<?php echo $user['city']; ?>',
                                state: '<?php echo $user['state']; ?>',
                                contact_number: '555-789-0123',
                                email: 'info@communityblood.org',
                                lat: userCoords.lat - 0.02,
                                lon: userCoords.lon + 0.02,
                                inventory: {
                                    'A+': 20,
                                    'A-': 10,
                                    'B+': 15,
                                    'B-': 8,
                                    'AB+': 6,
                                    'AB-': 4,
                                    'O+': 25,
                                    'O-': 12
                                }
                            }
                        ];

                        // Function to create inventory HTML
                        function createInventoryHTML(inventory) {
                            let html = '<div style="margin-top: 10px;"><strong>Blood Inventory:</strong><br>';
                            for (const [bloodGroup, quantity] of Object.entries(inventory)) {
                                const color = quantity > 10 ? '#4CAF50' : (quantity > 5 ? '#FFC107' : '#F44336');
                                html += `<span style="display: inline-block; margin: 2px; padding: 2px 5px; background-color: ${color}; color: white; border-radius: 3px;">${bloodGroup}: ${quantity}</span>`;
                            }
                            html += '</div>';
                            return html;
                        }

                        // Add demo hospitals to map
                        demoHospitals.forEach(hospital => {
                            const distance = calculateDistance(
                                userCoords.lat, userCoords.lon,
                                hospital.lat, hospital.lon
                            );
                            
                            locations.push({
                                ...hospital,
                                distance: distance,
                                type: 'hospital'
                            });
                            
                            const hospitalIcon = L.divIcon({
                                className: 'hospital-marker',
                                html: '<div style="background-color: #4CAF50; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                            
                            const marker = L.marker([hospital.lat, hospital.lon], {icon: hospitalIcon})
                                .addTo(map)
                                .bindPopup(`
                                    <b>${hospital.name}</b><br>
                                    ${hospital.address}<br>
                                    Distance: ${distance.toFixed(1)} km<br>
                                    ${createInventoryHTML(hospital.inventory)}
                                `);
                            markers.push(marker);
                        });

                        // Add demo blood banks to map
                        demoBloodBanks.forEach(bloodBank => {
                            const distance = calculateDistance(
                                userCoords.lat, userCoords.lon,
                                bloodBank.lat, bloodBank.lon
                            );
                            
                            locations.push({
                                ...bloodBank,
                                distance: distance,
                                type: 'blood_bank'
                            });
                            
                            const bloodBankIcon = L.divIcon({
                                className: 'blood-bank-marker',
                                html: '<div style="background-color: #2196F3; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                            
                            const marker = L.marker([bloodBank.lat, bloodBank.lon], {icon: bloodBankIcon})
                                .addTo(map)
                                .bindPopup(`
                                    <b>${bloodBank.name}</b><br>
                                    ${bloodBank.address}<br>
                                    Distance: ${distance.toFixed(1)} km<br>
                                    ${createInventoryHTML(bloodBank.inventory)}
                                `);
                            markers.push(marker);
                        });

                        // Initial filter
                        filterLocations();
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        alert('Unable to get your location. Please enable location services and try again.');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        // Initialize the page
        initializeLocations();
    </script>
</body>
</html> 