<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit();
}

// Fetch admin login details from the database
$query = "SELECT id, username, email FROM drivers";
$stmt = $pdo->prepare($query);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle admin addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if username or email already exists
    $check_query = "SELECT * FROM drivers WHERE username = :username OR email = :email";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->execute(['username' => $username, 'email' => $email]);
    $existing_admin = $check_stmt->fetch();

    if ($existing_admin) {
        $error_message = "Username or email already exists.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new admin into the database
        $insert_query = "INSERT INTO drivers (username, email, password) VALUES (:username, :email, :password)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ]);

        // Redirect after successful addition
        header("Location: driver_login_details.php");
        exit();
    }
}

// Handle admin deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_confirm'])) {
    $admin_id = $_POST['admin_id'];
    $entered_username = $_POST['confirm_username'];

    // Fetch the username based on admin_id
    $fetch_query = "SELECT username FROM drivers WHERE id = :id";
    $fetch_stmt = $pdo->prepare($fetch_query);
    $fetch_stmt->execute(['id' => $admin_id]);
    $admin = $fetch_stmt->fetch();

    // Check if the entered username matches the admin username
    if ($admin && $entered_username === $admin['username']) {
        $delete_query = "DELETE FROM drivers WHERE id = :id";
        $delete_stmt = $pdo->prepare($delete_query);
        $delete_stmt->execute(['id' => $admin_id]);
        header("Location: driver_login_details.php");
        exit();
    } else {
        $error_message = "The username does not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Details</title>
    <style>
        body {
            background-image: url('bus-that-is-lit-up-night-city_850140-88.avif'); /* Background image */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: black; /* Black header */
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a.logo {
            font-size: 24px;
            color: white;
            text-decoration: none;
        }

        nav {
            display: flex;
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
        }

        nav a.active {
            font-weight: bold;
        }

        .welcome-signout {
            display: flex;
            align-items: center;
        }

        .signout-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .signout-btn:hover {
            background-color: darkred;
        }

        main {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent black for contrast */
            color: white;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Slightly darker shadow for emphasis */
        }

        h2, h3 {
            color: white; /* Bright color for headings */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #0056b3; /* Darker blue for table header */
            color: white;
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

        .confirm-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            display: none;
        }

        .confirm-form.active {
            display: block;
        }

        form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .error-message {
            color: red;
            font-weight: bold;
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
            <button type="submit" name="signout" class="signout-btn">Signout</button>
        </form>
    </div>
</header>
<main>
    <h2>Driver Login Details</h2>
    
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    
    <!-- Table to display admin login details -->
    <table>
        <thead>
            <tr>
                <th>Driver ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['id']); ?></td>
                    <td><?php echo htmlspecialchars($admin['username']); ?></td>
                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                    <td>
                        <!-- Delete admin button -->
                        <button class="delete-btn" onclick="showConfirmForm(<?php echo $admin['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Back button to return to admin dashboard -->
    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

    <!-- Confirmation form for deleting admin -->
    <form method="POST" action="driver_login_details.php" class="confirm-form" id="confirm-form">
        <input type="hidden" name="admin_id" id="admin_id">
        <label for="confirm_username">Enter Username to Confirm Deletion:</label>
        <input type="text" id="confirm_username" name="confirm_username" required>
        <button type="submit" name="delete_confirm" class="delete-btn">Confirm Delete</button>
    </form>

    <!-- Add new admin section -->
    <h3>Add New Admin</h3>
    <form method="POST" action="driver_login_details.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="add_admin" class="add-btn">Add Admin</button>
    </form>
</main>

<script>
function showConfirmForm(adminId) {
    document.getElementById('confirm-form').classList.add('active');
    document.getElementById('admin_id').value = adminId;
}
</script>

</body>
</html>

