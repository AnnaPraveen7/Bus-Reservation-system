<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit();
}

// Fetch bus details from the database
$query = "SELECT id, bus_number, driver_name, capacity, college_name, starting_point, stopping_point, city1, city2, city3, city4, starting_time, payment FROM buses";
$stmt = $pdo->prepare($query);
$stmt->execute();
$buses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle bus addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bus'])) {
    $bus_number = $_POST['bus_number'];
    $driver_name = $_POST['driver_name'];
    $capacity = $_POST['capacity'];
    $college_name = $_POST['college_name'];
    $starting_point = $_POST['starting_point'];
    $stopping_point = $_POST['stopping_point'];
    $city1 = $_POST['city1'];
    $city2 = $_POST['city2'];
    $city3 = $_POST['city3'];
    $city4 = $_POST['city4'];
    $starting_time = $_POST['starting_time'];
    $payment = $_POST['payment'];

    // Insert new bus into the database
    $insert_query = "INSERT INTO buses (bus_number, driver_name, capacity, college_name, starting_point, stopping_point, city1, city2, city3, city4, starting_time, payment)
                     VALUES (:bus_number, :driver_name, :capacity, :college_name, :starting_point, :stopping_point, :city1, :city2, :city3, :city4, :starting_time, :payment)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->execute([
        'bus_number' => $bus_number,
        'driver_name' => $driver_name,
        'capacity' => $capacity,
        'college_name' => $college_name,
        'starting_point' => $starting_point,
        'stopping_point' => $stopping_point,
        'city1' => $city1,
        'city2' => $city2,
        'city3' => $city3,
        'city4' => $city4,
        'starting_time' => $starting_time,
        'payment' => $payment
    ]);

    // Redirect after successful addition
    header("Location: bus_details.php");
    exit();
}

// Handle bus deletion by bus_number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_confirm'])) {
    try {
        $bus_number = $_POST['bus_number'];

        // Delete bus based on bus_number
        $delete_query = "DELETE FROM buses WHERE bus_number = :bus_number";
        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->execute(['bus_number' => $bus_number]);

        header("Location: bus_details.php");
        exit();
    } catch (Exception $e) {
        echo "Error deleting bus: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Details</title>
    <link rel="stylesheet" href="adminlogyu.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
            min-height: 100vh;
            padding-top: 60px; /* Prevent content overlap with header */
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
            gap: 10px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .signout-btn {
            background-color: #dc3545;
        }

        .signout-btn:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
            overflow-y: auto;
            max-height: 400px;
            display: block;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: darkred;
        }

        form {
            margin-top: 20px;
            padding: 10px;
        }

        /* Scrollable section */
        tbody {
            display: block;
            max-height: 300px;
            overflow-y: scroll;
        }

        thead, tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .main {
            max-width: 1300px;
            margin: 100px auto; /* Adjusted to prevent overlap with fixed header */
            text-align: center;
            padding: 30px 50px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        h2, h3 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(23, 202, 202, 0.5);
        }

        .back-btn, .add-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn:hover, .add-btn:hover {
            background-color: #0056b3;
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
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <form method="POST" action="admin_dashboard.php" style="display: inline;">
            <button type="submit" name="signout" class="btn signout-btn">Sign Out</button>
        </form>
    </div>
</header>

<div class="main">
    <h2>Bus Details</h2>

    <table>
        <thead>
            <tr>
                <th>Bus Number</th>
                <th>Driver Name</th>
                <th>Capacity</th>
                <th>College Name</th>
                <th>Starting Point</th>
                <th>Stopping Point</th>
                <th>City 1</th>
                <th>City 2</th>
                <th>City 3</th>
                <th>City 4</th>
                <th>Starting Time</th>
                <th>Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buses as $bus): ?>
            <tr>
                <td><?php echo htmlspecialchars($bus['bus_number']); ?></td>
                <td><?php echo htmlspecialchars($bus['driver_name']); ?></td>
                <td><?php echo htmlspecialchars($bus['capacity']); ?></td>
                <td><?php echo htmlspecialchars($bus['college_name']); ?></td>
                <td><?php echo htmlspecialchars($bus['starting_point']); ?></td>
                <td><?php echo htmlspecialchars($bus['stopping_point']); ?></td>
                <td><?php echo htmlspecialchars($bus['city1']); ?></td>
                <td><?php echo htmlspecialchars($bus['city2']); ?></td>
                <td><?php echo htmlspecialchars($bus['city3']); ?></td>
                <td><?php echo htmlspecialchars($bus['city4']); ?></td>
                <td><?php echo htmlspecialchars($bus['starting_time']); ?></td>
                <td><?php echo htmlspecialchars($bus['payment']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="bus_number" value="<?php echo htmlspecialchars($bus['bus_number']); ?>">
                        <button type="submit" name="delete_confirm" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Add New Bus</h3>
    <form method="POST">
        <input type="text" name="bus_number" placeholder="Bus Number" required>
        <input type="text" name="driver_name" placeholder="Driver Name" required>
        <input type="number" name="capacity" placeholder="Capacity" required>
        <input type="text" name="college_name" placeholder="College Name" required>
        <input type="text" name="starting_point" placeholder="Starting Point" required>
        <input type="text" name="stopping_point" placeholder="Stopping Point" required>
        <input type="text" name="city1" placeholder="City 1" required>
        <input type="text" name="city2" placeholder="City 2">
        <input type="text" name="city3" placeholder="City 3">
        <input type="text" name="city4" placeholder="City 4">
        <input type="time" name="starting_time" placeholder="Starting Time" required>
        <input type="number" name="payment" placeholder="Payment" required>
        <button type="submit" name="add_bus" class="btn add-btn">Add Bus</button>
    </form>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
