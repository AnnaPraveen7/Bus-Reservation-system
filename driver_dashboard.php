<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: driver_login.php');
    exit();
}

$username = $_SESSION['username'];  // Driver's username
include 'db_connection.php';

// Initialize variables
$students = [];
$bus_number = '';

// Fetch students based on bus number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bus_number'])) {
    $bus_number = $_POST['bus_number'];

    // Fetch students from the `bookings` table for the specified bus
    $query = "SELECT student_name, student_age, parent_username 
              FROM bookings 
              WHERE bus_number = :bus_number";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['bus_number' => $bus_number]);
    $students = $stmt->fetchAll();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: driver_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
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

    <!-- Form to select bus number -->
    <form method="POST" action="driver_dashboard.php">
        <label for="bus_number">Bus Number:</label>
        <input type="text" id="bus_number" name="bus_number" value="<?php echo htmlspecialchars($bus_number); ?>" required>

        <button type="submit" class="btn">Submit</button>
    </form>

    <?php if ($students): ?>
        <h3>Student List for Bus Number: <?php echo htmlspecialchars($bus_number); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Age</th>
                    <th>Username</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['student_age']); ?></td>
                        <td><?php echo htmlspecialchars($student['parent_username']); ?></td>
                        <td>
                            <form class="attendance-form">
                                <input type="hidden" name="student_name" value="<?php echo $student['student_name']; ?>">
                                <input type="hidden" name="student_age" value="<?php echo $student['student_age']; ?>">
                                <input type="hidden" name="username" value="<?php echo $student['parent_username']; ?>">
                                <input type="hidden" name="bus_number" value="<?php echo htmlspecialchars($bus_number); ?>">
                                <input type="hidden" name="attendance_date" value="<?php echo date('Y-m-d'); ?>">

                                <button type="button" class="btn attendance-btn" data-status="Present">Present</button>
                                <button type="button" class="btn attendance-btn" data-status="Absent">Absent</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<script>
$(document).ready(function() {
    // AJAX call for marking attendance
    $('.attendance-btn').click(function() {
        var form = $(this).closest('.attendance-form');
        var status = $(this).data('status');
        
        $.ajax({
            url: 'mark_attendance.php',  // New file to handle attendance updates
            type: 'POST',
            data: form.serialize() + '&attendance_status=' + status,
            success: function(response) {
                alert('Attendance marked as ' + status);
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
</body>
</html>
