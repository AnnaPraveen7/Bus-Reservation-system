<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Handle login action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists
    $query = "SELECT * FROM admins WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session
        $_SESSION['username'] = $user['username'];
        header('Location: admin_dashboard.php'); // Redirect to home page
        exit();
    } else {
        // Invalid login
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
        }

        body {
            background-image: url('bus-that-is-lit-up-night-city_850140-88.avif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 120px;
            background: rgba(17, 20, 26, 0.9);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 25px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        nav a {
            font-size: 17px;
            color: #fff;
            text-decoration: none;
            font-weight: 300;
            margin-left: 35px;
            transition: .3s;
        }

        nav a:hover,
        nav a.active {
            color: rgb(23, 202, 202);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
        }

        .btn, .log-in-btn, .login-in-btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover, .log-in-btn:hover, .login-in-btn:hover {
            background-color: #0056b3;
        }

        .sign-out {
            background-color: #dc3545;
        }

        .sign-out:hover {
            background-color: #c82333;
        }

        main {
            max-width: 1300px;
            margin: 0 auto;
            text-align: center;
            padding: 30px 50px;
            font-size: 15px;
            line-height: 1.6;
            color: #fff;
            background-color: black;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .container {
            background-color: #000; /* Set background to black */
            border-radius: 10px;
            padding: 50px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Optional shadow for contrast */
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #e0e0e0;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #e0e0e0;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(23, 202, 202, 0.5);
        }

        .footer-text {
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-text a {
            color: rgb(23, 202, 202);
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            header {
                padding: 10px 20px;
            }

            nav a {
                font-size: 15px;
                margin-left: 20px;
            }

            .logo {
                font-size: 20px;
            }

            main {
                padding: 20px 30px;
                font-size: 13px;
            }

            .container {
                padding: 30px;
            }
        }

        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <a href="#" class="logo">BLUESHIPIN</a>
    <nav>
        <a href="index.php" class="active">Home</a>
        <a href="#about">About</a>
        <a href="#service">Service</a>
        <a href="#help">Help</a>
        <a href="#cancel">Cancellation</a>
    </nav>
    <div class="auth-buttons">
        <div class="dropdown">
            <button class="btn sign-in">Sign In</button>
            <div class="dropdown-content">
                <a href="parent_signin.php">Parent Sign In</a>
                <a href="driver_signin.php">Driver Sign In</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn login">Login</button>
            <div class="dropdown-content">
                <a href="parent_login.php">Parent Login</a>
                <a href="driver_login.php">Driver Login</a>
                <a href="admin_login.php">Admin Login</a>
            </div>
        </div>
    </div>
</header>
<main>
    <h2>Admin Login</h2>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="admin_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button class="btn login-in-btn">Login</button>
    </form>
</main>
</body>
</html>
