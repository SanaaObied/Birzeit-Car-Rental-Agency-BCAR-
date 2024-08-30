<?php
// Start or resume the session
session_start();

// Check if the user is logged in
if (empty($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Include your database connection file
include("db.php.inc.php");
$pdo = db_connect(); // Establish database connection

// Check if the rental ID is provided in the URL
if (isset($_GET['id'])) {
    $rental_id = $_GET['id'];
    
    // Update the rental confirmation status in the database
    $sql_update = "UPDATE rentals SET rental_status = 'active' WHERE id = :rental_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':rental_id', $rental_id, PDO::PARAM_INT);
    
    if ($stmt_update->execute()) {
        echo "Rental confirmed successfully.";
    } else {
        echo "Error confirming rental. Please try again.";
    }
} else {
    echo "Invalid rental ID.";
}

// Close the database connection
$conn = null;
?>
