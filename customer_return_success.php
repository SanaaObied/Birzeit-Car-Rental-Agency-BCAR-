<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file

// Check if the user is logged in and the required GET parameters are set
if (isset($_GET['rental_id']) && isset($_GET['pickup_location'])) {
    $rental_id = $_GET['rental_id'];
    $pickup_location = $_GET['pickup_location']; // Updated variable name

    try {
        $pdo = db_connect(); // Establish database connection

        // Update the rental status to "returning" and the pickup location to the return location
        $sql = "UPDATE rentals 
                SET rental_status = 'returning', return_location = :pickup_location 
                WHERE id = :id AND user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pickup_location', $pickup_location); // Updated parameter name
        $stmt->bindParam(':id', $rental_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        if ($stmt->execute()) {
            $message = "Car return initiated successfully.";
        } else {
            $message = "Failed to initiate car return. Please try again.";
        }

    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }

} else {
    $message = "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Return Status</title>
    <link rel="stylesheet" href="styles.css">
    <?php include 'header_Customer.php'; ?>
    <?php include 'leftside_Customer.php'; ?>
</head>
<body>

<header>
    <h1>Car Return Status</h1>
</header>

<div class="content">
    <?php
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    ?>
</div>

<footer>
<?php include 'footer.php'; ?>
</footer>

</body>
</html>


