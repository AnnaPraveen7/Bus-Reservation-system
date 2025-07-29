<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: parent_login.php');
    exit();
}

// Include the database connection
include 'db_connection.php';

// Get the booking ID from the URL
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : 0;

if ($booking_id) {
    // Fetch booking details from the database
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();
}

if (!$booking) {
    echo "Booking not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <link rel="stylesheet" href="success.css">
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
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="index.php?signout=true" class="btn sign-out">Sign Out</a>
    </div>
</header>

<main>
    <h2>Booking Successful!</h2>
    <p>Thank you for your booking. Here are the details:</p>

    <table>
        <tr><td>Parent Username:</td><td><?php echo htmlspecialchars($booking['parent_username']); ?></td></tr>
        <tr><td>Student Name:</td><td><?php echo htmlspecialchars($booking['student_name']); ?></td></tr>
        <tr><td>Student Age:</td><td><?php echo htmlspecialchars($booking['student_age']); ?></td></tr>
        <tr><td>Parent Name:</td><td><?php echo htmlspecialchars($booking['parent_name']); ?></td></tr>
        <tr><td>Parent Phone:</td><td><?php echo htmlspecialchars($booking['parent_phone']); ?></td></tr>
        <tr><td>Parent Email:</td><td><?php echo htmlspecialchars($booking['parent_email']); ?></td></tr>
        <tr><td>Bus Number:</td><td><?php echo htmlspecialchars($booking['bus_number']); ?></td></tr>
        <tr><td>Driver Name:</td><td><?php echo htmlspecialchars($booking['driver_name']); ?></td></tr>
        <tr><td>Booking Date:</td><td><?php echo htmlspecialchars($booking['booking_date']); ?></td></tr>
        <tr><td>Payment:</td><td><?php echo htmlspecialchars($booking['payment']); ?></td></tr>
    </table>

    <a href="print.php?booking_id=<?php echo $booking_id; ?>" class="btn">Print Booking</a>
</main>
</body>
</html>
