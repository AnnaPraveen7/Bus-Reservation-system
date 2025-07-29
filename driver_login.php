<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Handle login action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists
    $query = "SELECT * FROM drivers WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session
        $_SESSION['username'] = $user['username'];
        header('Location: driver_dashboard.php'); // Redirect to home page
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
    <title>Parent Login</title>
    <link rel="stylesheet" href="drivlog.css">
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
    <h2>Driver Login</h2>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="driver_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button class="btn login-in-btn">Login</button>
    </form>
    <p>Don't have an account? <a href="driver_signin.php">Sign In</a></p>
</main>
</body>
</html>
