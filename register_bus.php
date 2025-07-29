<?php 
session_start(); 
if (!isset($_SESSION['username'])) { 
    header('Location: parent_login.php'); 
    exit(); 
} 
include 'db_connection.php'; // Include the database connection file 
$bus_details = []; 
$college_name = ''; 

// Function to get the routes 
function getRoutes($bus) { 
    // Collect the cities into an array, filtering out empty values 
    $cities = array_filter([$bus['starting_point'], $bus['city1'], $bus['city2'], $bus['city3'], $bus['city4'], $bus['stopping_point']]); 
    // Join them into a string for display 
    return implode(" -> ", $cities); 
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // Get the college name from the form 
    $college_name = trim($_POST['college_name']); 
    // Prepare the SQL statement with named parameter 
    $query = "SELECT * FROM buses WHERE college_name = :college_name"; 
    $stmt = $pdo->prepare($query); 
    $stmt->execute(['college_name' => $college_name]); 
    $bus_details = $stmt->fetchAll(); // Fetch all matching bus details 
} 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Bus Details</title> 
    <link rel="stylesheet" href="register.css"> 
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
    <h2>Search for Buses</h2> 
    <form action="register_bus.php" method="POST"> 
        <label for="college_name">Enter College Name:</label> 
        <input type="text" id="college_name" name="college_name" value="<?php echo htmlspecialchars($college_name); ?>" required> 
        <button type="submit" class="btn">Search Buses</button> 
    </form> 

    <?php if (!empty($bus_details)): ?> 
        <h3>Available Buses for <?php echo htmlspecialchars($college_name); ?>:</h3> 
        <table> 
        <thead> 
            <tr> 
                <th>Bus Number</th> 
                <th>Driver Name</th> 
                <th>Starting Point</th> 
                <th>Stopping Point</th> 
                <th>Capacity</th> 
                <th>Starting Time</th> 
                <th>Route</th> 
                <th>Payment</th> <!-- New column for payment --> 
                <th>Action</th> 
            </tr> 
        </thead> 
        <tbody> 
        <?php foreach ($bus_details as $bus): ?> 
            <tr> 
                <td><?php echo htmlspecialchars($bus['bus_number']); ?></td> 
                <td><?php echo htmlspecialchars($bus['driver_name']); ?></td> 
                <td><?php echo htmlspecialchars($bus['starting_point']); ?></td> 
                <td><?php echo htmlspecialchars($bus['stopping_point']); ?></td> 
                <td><?php echo htmlspecialchars($bus['capacity']); ?></td> 
                <td><?php echo htmlspecialchars($bus['starting_time']); ?></td> 
                <td><?php echo getRoutes($bus); ?></td> 
                <td><?php echo htmlspecialchars($bus['payment']); ?></td> <!-- Display the payment amount --> 
                <td> 
                    <form action="book_seat.php" method="GET"> <!-- Change POST to GET --> 
                        <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus['id']); ?>"> 
                        <input type="hidden" name="bus_number" value="<?php echo htmlspecialchars($bus['bus_number']); ?>"> 
                        <input type="hidden" name="driver_name" value="<?php echo htmlspecialchars($bus['driver_name']); ?>"> 
                        <button type="submit" class="btn">Book a Seat</button> 
                    </form> 
                </td> 
            </tr> 
        <?php endforeach; ?> 
        </tbody> 
        </table> 
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?> 
        <p>No buses found for the entered college name.</p> 
    <?php endif; ?> 
</main> 
</body> 
</html>
