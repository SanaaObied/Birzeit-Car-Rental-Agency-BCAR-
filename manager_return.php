<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file

// Function to fetch and display returning cars for the manager
function displayReturningCars() {
    $pdo = db_connect(); // Establish database connection

    // Query to fetch returning cars with customer name
    $sql = "SELECT rentals.id, cars.make, cars.model, cars.car_type, cars.pickup_location, cars.available, user.name AS customer_name
    FROM rentals
    JOIN cars ON rentals.car_id = cars.id
    JOIN user ON rentals.user_id = user.user_id
    WHERE rentals.rental_status = 'active' AND rentals.return_location = 'Ramallah'";


    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the fetched car information in a table format
        echo '<table>';
        echo '<tr>';
        echo '<th>Car ID</th>';
        echo '<th>Car Make</th>';
        echo '<th>Car Model</th>';
        echo '<th>Car Type</th>';
        echo '<th>Pickup Location</th>';
        echo '<th>Status</th>';
        echo '<th>Customer Name</th>';
        echo '<th>Action</th>';
        echo '</tr>';

        foreach ($cars as $car) {
            echo '<tr>';
            echo '<td>' . $car['id'] . '</td>';
            echo '<td>' . $car['make'] . '</td>';
            echo '<td>' . $car['model'] . '</td>';
            echo '<td>' . $car['car_type'] . '</td>';
            echo '<td>' . $car['pickup_location'] . '</td>';
            echo '<td>' . ($car['available'] == 1 ? 'Available' : 'Not Available') . '</td>';
            echo '<td>' . $car['customer_name'] . '</td>';
            echo '<td>';
            echo '<form action="manager_return_success.php" method="post">';
            echo '<input type="hidden" name="car_id" value="' . $car['id'] . '">';
            echo '<label for="pickup_location">Pickup Location:</label>';
            echo '<input type="text" id="pickup_location" name="pickup_location" value="' . $car['pickup_location'] . '" readonly>'; // Pickup location (editable)
            echo '<label for="car_status">Car Status:</label>';
            echo '<select id="car_status" name="car_status" required>'; // Car status (editable)
            echo '<option value="available">Available</option>';
            echo '<option value="damaged">Damaged</option>';
            echo '<option value="repair">Repair</option>';
            echo '</select>';
            echo '<input type="submit" name="return_btn" value="Return">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Display returning cars for the manager
if (isset($_SESSION['user_id'])) {
    include 'header.php'; // Include your manager header file
    include 'leftside.php'; // Include your manager sidebar or left side navigation

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Returning Cars</title>';
    echo '<link rel="stylesheet" href="styles.css">'; // Link your CSS file
    echo '</head>';
    echo '<body>';

    echo '<header>';
    echo '<h1>Returning Cars</h1>';
    echo '</header>';

    echo '<div class="content">';
    displayReturningCars();
    echo '</div>';

    echo '<footer>';
    include 'footer.php'; // Include your footer file
    echo '</footer>';

    echo '</body>';
    echo '</html>';
} else {
    echo 'You need to be logged in to view this page.';
}
?>
