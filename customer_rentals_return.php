<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file
include 'header_Customer.php'; // Assuming you have a header file for your website
include 'leftside_Customer.php'; // Assuming you have a sidebar or left side navigation

// Function to fetch and display active car rents for customers
function displayActiveRents() {
    $pdo = db_connect(); // Establish database connection

    // Query to fetch active rentals for the logged-in customer with correct column names
    $sql = "SELECT rentals.id, rentals.car_id AS car_reference_number, cars.make AS car_make, cars.model AS car_model, cars.car_type AS car_type, rentals.rental_start, rentals.rental_end, rentals.return_location 
            FROM rentals 
            JOIN cars ON rentals.car_id = cars.id 
            WHERE rentals.user_id = :user_id AND rentals.rental_end >= NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the fetched rental information in a table format
    echo '<table>';
    echo '<tr>';
    echo '<th>Car Reference Number</th>';
    echo '<th>Car Make</th>';
    echo '<th>Car Type</th>';
    echo '<th>Car Model</th>';
    echo '<th>Rental Start</th>';
    echo '<th>Rental End</th>';
    echo '<th>Return Location</th>';
    echo '<th>Action</th>';
    echo '</tr>';

    foreach ($rentals as $rental) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($rental['car_reference_number']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['car_make']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['car_type']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['car_model']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['rental_start']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['rental_end']) . '</td>';
        echo '<td>' . htmlspecialchars($rental['return_location']) . '</td>';
        echo '<td>';
        echo '<form action="customer_return_success.php" method="post">';
        echo '<input type="hidden" name="rental_id" value="' . htmlspecialchars($rental['id']) . '">';
        echo '<input type="hidden" name="pickup_location" value="' . htmlspecialchars($rental['return_location']) . '">';
        echo '<a href="customer_return_success.php?rental_id=' . htmlspecialchars($rental['id']) . '&pickup_location=' . htmlspecialchars($rental['return_location']) . '" class="btn-return">Return</a>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

// Display active car rents if the user is a customer
if (isset($_SESSION['user_id'])) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Active Car Rentals</title>';
    echo '<link rel="stylesheet" href="styles.css">';
    echo '</head>';
    echo '<body>';

    echo '<header>';
    echo '<h1>Active Car Rentals</h1>';
    echo '</header>';

    echo '<div class="content">';
    displayActiveRents();
    echo '</div>';

    echo '<footer>';
    include 'footer.php';
    echo '</footer>';

    echo '</body>';
    echo '</html>';
} else {
    echo 'You need to be logged in to view this page.';
}
?>
