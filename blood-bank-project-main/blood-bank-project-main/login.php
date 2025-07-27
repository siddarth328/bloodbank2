<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank - Login</title>
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
            max-width: 400px;
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
        .signup-link {
            text-align: center;
            margin-top: 1rem;
        }
        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        .login-type {
            margin-bottom: 1rem;
            text-align: center;
        }
        .login-type select {
            width: auto;
            padding: 0.5rem;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="process_login.php" method="POST">
            <div class="form-group login-type">
                <label for="login_type">Login As:</label>
                <select id="login_type" name="login_type" required>
                    <option value="user">User</option>
                    <option value="hospital">Hospital</option>
                </select>
            </div>

            <div class="form-group" id="user_login">
                <label for="id_proof">ID Proof Number</label>
                <input type="text" id="id_proof" name="id_proof">
            </div>

            <div class="form-group" id="hospital_login" style="display: none;">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>

    <script>
        document.getElementById('login_type').addEventListener('change', function() {
            const userLogin = document.getElementById('user_login');
            const hospitalLogin = document.getElementById('hospital_login');
            
            if (this.value === 'user') {
                userLogin.style.display = 'block';
                hospitalLogin.style.display = 'none';
                document.getElementById('id_proof').required = true;
                document.getElementById('email').required = false;
            } else {
                userLogin.style.display = 'none';
                hospitalLogin.style.display = 'block';
                document.getElementById('id_proof').required = false;
                document.getElementById('email').required = true;
            }
        });
    </script>
</body>
</html> 