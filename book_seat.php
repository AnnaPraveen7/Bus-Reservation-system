<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: parent_login.php');
    exit();
}

// Include the database connection
include 'db_connection.php';

// Initialize variables
$bus_id = '';
$bus_number = '';
$driver_name = '';

// Check if the bus ID, number, and driver name are set via GET request
if (isset($_GET['bus_id'])) {
    $bus_id = $_GET['bus_id'];
}
if (isset($_GET['bus_number'])) {
    $bus_number = $_GET['bus_number'];
}
if (isset($_GET['driver_name'])) {
    $driver_name = $_GET['driver_name'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $parent_username = trim($_POST['parent_username']);
    $student_name = trim($_POST['student_name']);
    $student_age = trim($_POST['student_age']);
    $parent_name = trim($_POST['parent_name']);
    $parent_phone = trim($_POST['parent_phone']);
    $parent_email = trim($_POST['parent_email']);

    // Validate inputs
    if (empty($parent_username) || empty($student_name) || empty($student_age) || empty($parent_name) || empty($parent_phone) || empty($parent_email)) {
        echo "All fields are required.";
        exit();
    }

    // Validate email format
    if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Validate phone number format (simple numeric check)
    if (!preg_match('/^[0-9]{10}$/', $parent_phone)) {
        echo "Invalid phone number format. Please enter a 10-digit number.";
        exit();
    }

    try {
        // Start a transaction to ensure atomic updates
        $pdo->beginTransaction();

        // Get the current capacity and payment of the selected bus
        $stmt = $pdo->prepare("SELECT capacity, payment FROM buses WHERE id = ?");
        $stmt->execute([$bus_id]);
        $bus = $stmt->fetch();

        if (!$bus || $bus['capacity'] <= 0) {
            // If no bus found or capacity is 0 or less, reject booking
            echo "Sorry, no more seats available on this bus.";
            exit();
        }

        // Decrease the capacity by 1
        $new_capacity = $bus['capacity'] - 1;

        // Update the bus capacity in the database
        $stmt = $pdo->prepare("UPDATE buses SET capacity = ? WHERE id = ?");
        $stmt->execute([$new_capacity, $bus_id]);

        // Store booking details in the bookings table, including payment
        $stmt = $pdo->prepare("INSERT INTO bookings (parent_username, student_name, student_age, parent_name, parent_phone, parent_email, bus_number, driver_name, payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$parent_username, $student_name, $student_age, $parent_name, $parent_phone, $parent_email, $bus_number, $driver_name, $bus['payment']]);

        // Get the last inserted booking ID for redirecting to the success page
        $last_id = $pdo->lastInsertId();

        // Commit the transaction
        $pdo->commit();

        // Redirect to success page with the booking ID
        header('Location: success.php?booking_id=' . $last_id);
        exit();

    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo 'Error: ' . htmlspecialchars($e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Seat</title>
    <link rel="stylesheet" href="bookseat.css">
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
        <a href="index.php?logout=true" class="btn sign-out">Sign Out</a>
    </div>
</header>

<main>
    <h2>Book a Seat for Bus Number: <?php echo htmlspecialchars($bus_number); ?></h2>

    <form action="book_seat.php?bus_id=<?php echo htmlspecialchars($bus_id); ?>&bus_number=<?php echo htmlspecialchars($bus_number); ?>&driver_name=<?php echo htmlspecialchars($driver_name); ?>" method="POST">
        <label for="parent_username">Parent Username:</label>
        <input type="text" id="parent_username" name="parent_username" required>

        <label for="student_name">Student Name:</label>
        <input type="text" id="student_name" name="student_name" required>

        <label for="student_age">Student Age:</label>
        <input type="number" id="student_age" name="student_age" required>

        <label for="parent_name">Parent Name:</label>
        <input type="text" id="parent_name" name="parent_name" required>

        <label for="parent_phone">Parent Phone Number:</label>
        <input type="tel" id="parent_phone" name="parent_phone" required>

        <label for="parent_email">Parent Email:</label>
        <input type="email" id="parent_email" name="parent_email" required>

        <button type="submit" class="btn">Submit Booking</button>
    </form>
</main>
</body>
</html>
