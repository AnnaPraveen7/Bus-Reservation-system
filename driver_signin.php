<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Handle sign-in action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $query = "INSERT INTO drivers (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);
            header('Location: driver_login.php'); // Redirect to login page after successful sign-in
            exit();
        } catch (PDOException $e) {
            // Handle duplicate username/email error or other exceptions
            if ($e->errorInfo[1] == 1062) {
                $error_message = "Username or Email already exists.";
            } else {
                $error_message = "Error occurred: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Sign In</title>
    <link rel="stylesheet" href="drivsign.css">
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
    <h2>Driver Sign In</h2>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="driver_signin.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <button class="btn sign-in-btn">Sign In</button>
    </form>
    <p>Already have an account? <a href="driver_login.php">Login</a></p>
</main>
</body>
</html>
