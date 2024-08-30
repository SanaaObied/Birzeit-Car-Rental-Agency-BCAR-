<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file

// Default search parameters (if no search options are selected)
$startDate = date('Y-m-d');
$endDate = date('Y-m-d', strtotime('+1 week'));
$pickupLocation = ''; // Empty string indicates all locations
$returnDate = '';
$returnLocation = '';
$includeRepair = 1; // Include cars in repair by default
$includeDamage = 1; // Include cars in damage by default

// Process search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get search parameters from the form
    $startDate = date('Y-m-d', strtotime($_POST['start_date']));
    $endDate = date('Y-m-d', strtotime($_POST['end_date']));
    $pickupLocation = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : '';
    $returnDate = date('Y-m-d', strtotime($_POST['return_date']));
    $returnLocation = isset($_POST['return_location']) ? $_POST['return_location'] : '';
    $includeRepair = isset($_POST['include_repair']) ? 1 : 0;
    $includeDamage = isset($_POST['include_damage']) ? 1 : 0;

    // Construct SQL query based on search criteria
    $query = "
        SELECT cars.id AS id, cars.car_type, cars.model, cars.description, cars.image, cars.fuel_type, cars.available AS available
        FROM cars
        LEFT JOIN rentals ON cars.id = rentals.car_id
        WHERE 1=1
    ";

    if (!empty($start_date) && !empty($end_date)) {
        $query .= " AND cars.id NOT IN (
            SELECT car_id FROM rentals 
            WHERE (pickup_date BETWEEN :start_date AND :end_date) 
            OR (return_date BETWEEN :start_date AND :end_date)
        )";
    }

    if (!empty($pickup_location)) {
        $query .= " AND rentals.pickup_location = :pickup_location";
    }

    if (!empty($return_date)) {
        $query .= " AND cars.id IN (
            SELECT car_id FROM rentals 
            WHERE return_date = :return_date
        )";
    }

    if (!empty($return_location)) {
        $query .= " AND rentals.return_location = :return_location";
    }

    if (!empty($status)) {
        $query .= " AND cars.available = :status";
    }

    $pdo = db_connect(); // Establish database connection

    // Prepare the statement
    $stmt = $pdo->prepare($query);

    // Bind parameters
    if (!empty($start_date) && !empty($end_date)) {
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
    }

    if (!empty($pickup_location)) {
        $stmt->bindParam(':pickup_location', $pickup_location);
    }

    if (!empty($return_date)) {
        $stmt->bindParam(':return_date', $return_date);
    }

    if (!empty($return_location)) {
        $stmt->bindParam(':return_location', $return_location);
    }

    if (!empty($status)) {
        $stmt->bindParam(':status', $status);
    }

    // Execute the query
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars Inquire</title>
    <link rel="stylesheet" href="styles.css">
    <?php 
     include 'header.php'; 
        $_SESSION['location']="index.php";
    ?>

<?php include 'leftside.php'; ?>

</head>
<body>
    <h2>Cars Inquire</h2>
    <form class="car-search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>"><br><br>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>"><br><br>

        <label for="pickup_location">Pickup Location:</label>
        <input type="text" id="pickup_location" name="pickup_location" value="<?php echo $pickupLocation; ?>"><br><br>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" value="<?php echo $returnDate; ?>"><br><br>

        <label for="return_location">Return Location:</label>
        <input type="text" id="return_location" name="return_location" value="<?php echo $returnLocation; ?>"><br><br>

        <input type="checkbox" id="include_repair" name="include_repair" <?php if ($includeRepair) echo "checked"; ?>>
<label for="include_repair">Include Cars in Repair</label><br>

<input type="checkbox" id="include_damage" name="include_damage" <?php if ($includeDamage) echo "checked"; ?>>
<label for="include_damage">Include Cars in Damage</label><br><br>


        <input type="submit" value="Search">
    </form>

    <?php
    if (isset($cars) && !empty($cars)) {
        echo "<h3>Search Results:</h3>";
        echo "<table border='1'>";
        echo "<thead><tr><th>Car ID</th><th>Type</th><th>Model</th><th>Description</th><th>image</th><th>Fuel Type</th><th>Status</th></tr></thead>";
        echo "<tbody>";
        foreach ($cars as $row) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['car_type']}</td>";
            echo "<td>{$row['model']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "<td><img src='{$row['image']}' alt='Car Photo' style='width: 100px;'></td>";
            echo "<td>{$row['fuel_type']}</td>";
            echo "<td>{$row['available']}</td>"; // Display 'available' instead of 'status'
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } elseif (isset($cars) && empty($cars)) {
        echo "<p>No cars found matching the search criteria.</p>";
    }
    ?>
    <?php include("footer.php"); ?>

</body>
</html>



