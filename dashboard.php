<?php
session_start();
include_once 'db.php.inc.php'; // Include your database connection file

// Include header and navigation if applicable
include("header.php");
include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add any additional CSS or stylesheets -->
    <style>
        /* Add custom styles for the dashboard */
        .container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .container h2 {
            color: #007bff;
        }
        .container p {
            margin-bottom: 10px;
        }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Dashboard</h2>
        <p>Here, you can access various features and manage your account.</p>
        <!-- Add links/buttons to different sections or actions -->
        <a href="rent_car.php">Rented Cars</a><br>

        <a href="view_rented_cars.php">View Rented Cars</a><br>
        <a href="return_car.php">Return Car</a><br>
        <a href="edit_profile.php">Edit Profile</a><br>
        <!-- Add more links/buttons as needed -->
    </div>

    <?php
    // Check if referrer is set and redirect to it
    if (isset($_SESSION['referrer'])) {
        $referrer = $_SESSION['referrer'];
        unset($_SESSION['referrer']); // Unset the referrer after redirection
        header("Location: $referrer");
        exit();
    }
    ?>
</body>
</html>
