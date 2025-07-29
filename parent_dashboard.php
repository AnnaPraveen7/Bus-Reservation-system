<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login if not signed in
    header('Location: parent_login.php');
    exit();
}

// Use the session data to personalize the dashboard
$username = $_SESSION['username'];  // Get username
$user_id = $_SESSION['user_id'];    // Get user ID

// Log out action
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: parent_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<header>
    <a href="#" class="logo">BLUESHIPIN</a>
    <nav>
        <a href="index.php" class="active">Home</a>
        <a href="#about">About</a>
        <a href="#service">Service</a>
        <a href="#help">Help</a>
    </nav>
    <div class="auth-buttons">
        <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
        <a href="index.php?logout=true" class="btn sign-out">Sign Out</a>
    </div>
</header>

<main>
    <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>Welcome to your dashboard.</p>
    
    <div class="actions">
        <a href="track_bus.php" class="btn">Track Bus</a>
        <a href="register_bus.php" class="btn">Register for Bus</a>
    </div>
</main>
</body>
</html>
