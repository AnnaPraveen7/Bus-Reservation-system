<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit();
}

$username = $_SESSION['username']; // Get the logged-in admin's username

// Handle signout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin_login.php'); // Redirect to login page after signout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            flex-direction: column; /* Change to column layout */
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

        .welcome-signout {
            display: flex;
            align-items: center;
        }

        .signout-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .signout-btn:hover {
            background-color: #d93b3b;
        }

        main {
            flex: 1; /* Allow main to take up remaining space */
            display: flex;
            flex-direction: column; /* Stack content vertically */
            align-items: center; /* Center align items */
            justify-content: center; /* Center vertically */
            text-align: center;
            padding: 30px 50px;
            font-size: 15px;
            line-height: 1.6;
            color: #fff;
            background-color: black;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            margin-top: 70px; /* Ensure content isn't hidden under fixed header */
        }

        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .dashboard-buttons a {
            padding: 15px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .dashboard-buttons a:hover {
            background-color: #0056b3;
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
    <div class="welcome-signout">
        <p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        <form method="POST" style="display: inline;">
            <button type="submit" name="signout" class="signout-btn">Signout</button>
        </form>
    </div>
</header>
<main>
    <h2>Admin Dashboard</h2>
    <p>You can access various details and manage the system from here.</p>

    <div class="dashboard-buttons">
        <a href="parent_login_details.php">Parent Login Details</a>
        <a href="admin_login_details.php">Admin Login Details</a>
        <a href="driver_login_details.php">Driver Login Details</a>
        <a href="bus_details.php">Bus Details</a>
        <a href="student_details.php">Student Details</a>
        <a href="attendance_details.php">Attendance Details</a>
        <a href="payment_details.php">Payment and Total Payment</a>
        <a href="booking_history.php">Booking History</a>
    </div>
</main>
</body>
</html>
