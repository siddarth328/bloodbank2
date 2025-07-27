<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank - Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #e74c3c;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #e74c3c;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
        }
        button:hover {
            background-color: #c0392b;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="process_signup.php" method="POST" id="signupForm">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required min="18" max="65">
            </div>
            
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}">
            </div>
            
            <div class="form-group">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                    <option value="">Select Country</option>
                    <option value="India">India</option>
                    <option value="USA">USA</option>
                    <option value="UK">UK</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="state">State</label>
                <select id="state" name="state" required>
                    <option value="">Select State</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city" required>
                    <option value="">Select City</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_proof">ID Proof Number</label>
                <input type="text" id="id_proof" name="id_proof" required>
                <small id="id_proof_help" class="form-text text-muted"></small>
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
            
            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input type="number" id="height" name="height" required min="100" max="250">
            </div>
            
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <input type="number" id="weight" name="weight" required min="30" max="200">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        // State options based on country
        const stateOptions = {
            'India': ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'],
            'USA': ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'],
            'UK': ['England', 'Scotland', 'Wales', 'Northern Ireland']
        };

        // City options based on state
        const cityOptions = {
            'Andhra Pradesh': ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool'],
            'Arunachal Pradesh': ['Itanagar', 'Naharlagun', 'Pasighat', 'Tawang', 'Ziro'],
            'Assam': ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon'],
            'Bihar': ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga'],
            'Chhattisgarh': ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg'],
            'Goa': ['Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda'],
            'Gujarat': ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar'],
            'Haryana': ['Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar'],
            'Himachal Pradesh': ['Shimla', 'Mandi', 'Solan', 'Dharamshala', 'Bilaspur'],
            'Jharkhand': ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Hazaribagh'],
            'Karnataka': ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum'],
            'Kerala': ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam'],
            'Madhya Pradesh': ['Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain'],
            'Maharashtra': ['Mumbai', 'Pune', 'Nagpur', 'Nashik', 'Aurangabad'],
            'Manipur': ['Imphal', 'Thoubal', 'Bishnupur', 'Churachandpur', 'Ukhrul'],
            'Meghalaya': ['Shillong', 'Tura', 'Jowai', 'Nongstoin', 'Williamnagar'],
            'Mizoram': ['Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib'],
            'Nagaland': ['Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha'],
            'Odisha': ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur'],
            'Punjab': ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda'],
            'Rajasthan': ['Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer'],
            'Sikkim': ['Gangtok', 'Namchi', 'Mangan', 'Gyalshing', 'Singtam'],
            'Tamil Nadu': ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem'],
            'Telangana': ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Ramagundam'],
            'Tripura': ['Agartala', 'Udaipur', 'Dharmanagar', 'Kailashahar', 'Belonia'],
            'Uttar Pradesh': ['Lucknow', 'Kanpur', 'Varanasi', 'Agra', 'Meerut'],
            'Uttarakhand': ['Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur'],
            'West Bengal': ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri'],
            'Alabama': ['Birmingham', 'Montgomery', 'Mobile', 'Huntsville', 'Tuscaloosa'],
            'Alaska': ['Anchorage', 'Fairbanks', 'Juneau', 'Sitka', 'Ketchikan'],
            'Arizona': ['Phoenix', 'Tucson', 'Mesa', 'Chandler', 'Glendale'],
            'Arkansas': ['Little Rock', 'Fort Smith', 'Fayetteville', 'Springdale', 'Jonesboro'],
            'California': ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento', 'San Jose'],
            'Colorado': ['Denver', 'Colorado Springs', 'Aurora', 'Fort Collins', 'Lakewood'],
            'Connecticut': ['Bridgeport', 'New Haven', 'Hartford', 'Stamford', 'Waterbury'],
            'Delaware': ['Wilmington', 'Dover', 'Newark', 'Middletown', 'Smyrna'],
            'Florida': ['Jacksonville', 'Miami', 'Tampa', 'Orlando', 'St. Petersburg'],
            'Georgia': ['Atlanta', 'Augusta', 'Columbus', 'Savannah', 'Athens'],
            'Hawaii': ['Honolulu', 'Hilo', 'Kailua', 'Kaneohe', 'Pearl City'],
            'Idaho': ['Boise', 'Meridian', 'Nampa', 'Idaho Falls', 'Pocatello'],
            'Illinois': ['Chicago', 'Aurora', 'Rockford', 'Joliet', 'Naperville'],
            'Indiana': ['Indianapolis', 'Fort Wayne', 'Evansville', 'South Bend', 'Carmel'],
            'Iowa': ['Des Moines', 'Cedar Rapids', 'Davenport', 'Sioux City', 'Waterloo'],
            'Kansas': ['Wichita', 'Overland Park', 'Kansas City', 'Olathe', 'Topeka'],
            'Kentucky': ['Louisville', 'Lexington', 'Bowling Green', 'Owensboro', 'Covington'],
            'Louisiana': ['New Orleans', 'Baton Rouge', 'Shreveport', 'Lafayette', 'Lake Charles'],
            'Maine': ['Portland', 'Lewiston', 'Bangor', 'South Portland', 'Auburn'],
            'Maryland': ['Baltimore', 'Rockville', 'Frederick', 'Gaithersburg', 'Bowie'],
            'Massachusetts': ['Boston', 'Worcester', 'Springfield', 'Cambridge', 'Lowell'],
            'Michigan': ['Detroit', 'Grand Rapids', 'Warren', 'Sterling Heights', 'Ann Arbor'],
            'Minnesota': ['Minneapolis', 'Saint Paul', 'Rochester', 'Duluth', 'Bloomington'],
            'Mississippi': ['Jackson', 'Gulfport', 'Hattiesburg', 'Southaven', 'Biloxi'],
            'Missouri': ['Kansas City', 'St. Louis', 'Springfield', 'Independence', 'Columbia'],
            'Montana': ['Billings', 'Missoula', 'Great Falls', 'Bozeman', 'Butte'],
            'Nebraska': ['Omaha', 'Lincoln', 'Bellevue', 'Grand Island', 'Kearney'],
            'Nevada': ['Las Vegas', 'Henderson', 'Reno', 'North Las Vegas', 'Sparks'],
            'New Hampshire': ['Manchester', 'Nashua', 'Concord', 'Dover', 'Rochester'],
            'New Jersey': ['Newark', 'Jersey City', 'Paterson', 'Elizabeth', 'Edison'],
            'New Mexico': ['Albuquerque', 'Las Cruces', 'Rio Rancho', 'Santa Fe', 'Roswell'],
            'New York': ['New York City', 'Buffalo', 'Rochester', 'Syracuse', 'Albany'],
            'North Carolina': ['Charlotte', 'Raleigh', 'Greensboro', 'Durham', 'Winston-Salem'],
            'North Dakota': ['Fargo', 'Bismarck', 'Grand Forks', 'Minot', 'West Fargo'],
            'Ohio': ['Columbus', 'Cleveland', 'Cincinnati', 'Toledo', 'Akron'],
            'Oklahoma': ['Oklahoma City', 'Tulsa', 'Norman', 'Broken Arrow', 'Lawton'],
            'Oregon': ['Portland', 'Eugene', 'Salem', 'Gresham', 'Hillsboro'],
            'Pennsylvania': ['Philadelphia', 'Pittsburgh', 'Allentown', 'Erie', 'Reading'],
            'Rhode Island': ['Providence', 'Warwick', 'Cranston', 'Pawtucket', 'East Providence'],
            'South Carolina': ['Columbia', 'Charleston', 'North Charleston', 'Mount Pleasant', 'Rock Hill'],
            'South Dakota': ['Sioux Falls', 'Rapid City', 'Aberdeen', 'Brookings', 'Watertown'],
            'Tennessee': ['Nashville', 'Memphis', 'Knoxville', 'Chattanooga', 'Clarksville'],
            'Texas': ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth'],
            'Utah': ['Salt Lake City', 'West Valley City', 'Provo', 'West Jordan', 'Orem'],
            'Vermont': ['Burlington', 'South Burlington', 'Rutland', 'Barre', 'Montpelier'],
            'Virginia': ['Virginia Beach', 'Norfolk', 'Chesapeake', 'Richmond', 'Newport News'],
            'Washington': ['Seattle', 'Spokane', 'Tacoma', 'Vancouver', 'Bellevue'],
            'West Virginia': ['Charleston', 'Huntington', 'Morgantown', 'Parkersburg', 'Wheeling'],
            'Wisconsin': ['Milwaukee', 'Madison', 'Green Bay', 'Kenosha', 'Racine'],
            'Wyoming': ['Cheyenne', 'Casper', 'Laramie', 'Gillette', 'Rock Springs'],
            'England': ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds'],
            'Scotland': ['Edinburgh', 'Glasgow', 'Aberdeen', 'Dundee', 'Inverness'],
            'Wales': ['Cardiff', 'Swansea', 'Newport', 'Bangor', 'St Asaph'],
            'Northern Ireland': ['Belfast', 'Derry', 'Lisburn', 'Newry', 'Armagh']
        };

        // ID proof patterns and help text based on country
        const idProofConfig = {
            'India': {
                pattern: '[0-9]{12}',
                help: 'Enter 12-digit Aadhar Card Number'
            },
            'USA': {
                pattern: '[0-9]{9}',
                help: 'Enter 9-digit Social Security Number'
            },
            'UK': {
                pattern: '[A-Z]{2}[0-9]{6}[A-Z]',
                help: 'Enter National Insurance Number (Format: AB123456C)'
            }
        };

        document.getElementById('country').addEventListener('change', function() {
            const stateSelect = document.getElementById('state');
            const citySelect = document.getElementById('city');
            const idProofInput = document.getElementById('id_proof');
            const idProofHelp = document.getElementById('id_proof_help');
            
            // Reset state and city dropdowns
            stateSelect.innerHTML = '<option value="">Select State</option>';
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            const selectedCountry = this.value;
            if (selectedCountry && stateOptions[selectedCountry]) {
                // Populate states
                stateOptions[selectedCountry].forEach(state => {
                    const option = document.createElement('option');
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                });

                // Update ID proof field
                if (idProofConfig[selectedCountry]) {
                    idProofInput.pattern = idProofConfig[selectedCountry].pattern;
                    idProofHelp.textContent = idProofConfig[selectedCountry].help;
                }
            }
        });

        document.getElementById('state').addEventListener('change', function() {
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            const selectedState = this.value;
            if (selectedState && cityOptions[selectedState]) {
                cityOptions[selectedState].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        });

        // Password validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html> 