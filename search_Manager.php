<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Search</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php

    // Check if a sorting preference cookie exists
    $sortOrder = isset($_COOKIE['sortOrder']) ? $_COOKIE['sortOrder'] : 'ASC';
    $sortBy = isset($_COOKIE['sortBy']) ? $_COOKIE['sortBy'] : 'price_per_day';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['sort'])) {
        // Update sorting preferences based on user's click on column headers
        switch ($_GET['sort']) {
            case 'price_per_day':
            case 'car_type':
            case 'fuel_type':
                $sortBy = $_GET['sort'];
                break;
            default:
                $sortBy = 'price_per_day'; // Default sort by price_per_day
                break;
        }
        $sortOrder = ($sortOrder == 'ASC') ? 'DESC' : 'ASC'; // Toggle between ASC and DESC

        // Set cookies to remember user's sorting preferences
        setcookie('sortBy', $sortBy, time() + (86400 * 30), "/"); // 30 days expiration
        setcookie('sortOrder', $sortOrder, time() + (86400 * 30), "/");

        // Redirect to avoid resubmission on page reload
        header("Location: {$_SERVER['PHP_SELF']}?sort={$sortBy}&sortOrder={$sortOrder}");
        exit();
    }
    ?>

    <?php include 'header.php'; ?>
    <?php include 'leftside.php'; ?>

    <?php
    include 'db.php.inc.php';
    // Establish database connection
    $conn = db_connect();
    ?>

    <form class="car-search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="rental_start">Rental Start Date:</label>
        <input type="date" id="rental_start" name="rental_start">

        <label for="rental_end">Rental End Date:</label>
        <input type="date" id="rental_end" name="rental_end">

        <label for="car_type">Car Type:</label>
        <select id="car_type" name="car_type">
            <?php
            if ($conn) {
                $stmt = $conn->query("SELECT DISTINCT car_type FROM cars");
                $carTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($carTypes as $carType) {
                    echo "<option value='" . $carType['car_type'] . "'>" . ucfirst($carType['car_type']) . "</option>";
                }
            } else {
                echo "Database connection failed";
            }
            ?>
        </select>

        <label for="pickup_location">Pick-up Location:</label>
        <select id="pickup_location" name="pickup_location">
            <?php
            if ($conn) {
                $stmt = $conn->query("SELECT id, name FROM locations");
                $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($locations as $location) {
                    echo "<option value='" . $location['name'] . "'>" . $location['name'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="min_price">Minimum Price:</label>
        <input type="number" id="min_price" name="min_price" min="0">

        <label for="max_price">Maximum Price:</label>
        <input type="number" id="max_price" name="max_price" min="0">

        <input type="submit" value="Search">
    </form>

    <?php
    if ($conn) {
        // Default values for search parameters
        $rental_start = date("Y-m-d");
        $rental_end = date("Y-m-d", strtotime("+3 days"));
        $car_type = "sedan";
        $pickup_location = "Birzeit";
        $min_price = 200;
        $max_price = 1000;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $rental_start = !empty($_POST['rental_start']) ? $_POST['rental_start'] : $rental_start;
            $rental_end = !empty($_POST['rental_end']) ? $_POST['rental_end'] : $rental_end;
            $car_type = !empty($_POST['car_type']) ? $_POST['car_type'] : $car_type;
            $pickup_location = !empty($_POST['pickup_location']) ? $_POST['pickup_location'] : $pickup_location;
            $min_price = !empty($_POST['min_price']) ? $_POST['min_price'] : $min_price; // Added missing line
            $max_price = !empty($_POST['max_price']) ? $_POST['max_price'] : $max_price; // Added missing line

            // Get sort parameters if available
            $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_per_day';
            $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';

            // Check if the filter shortlist button is clicked
            $shortlistFilter = isset($_POST['filter_shortlist']) ? true : false;
            $shortlistIds = isset($_POST['shortlist']) ? $_POST['shortlist'] : [];

            // Construct SQL query with prepared statements
            $sql = "SELECT * FROM cars 
                WHERE car_type = :car_type 
                AND location_id = (SELECT id FROM locations WHERE name = :pickup_location) 
                AND price_per_day >= :min_price 
                AND price_per_day <= :max_price 
                AND id NOT IN (
                    SELECT car_id FROM rentals 
                    WHERE rental_start <= :rental_end 
                    AND rental_end >= :rental_start
                )";

            if ($shortlistFilter && !empty($shortlistIds)) {
                $placeholders = implode(',', array_fill(0, count($shortlistIds), '?'));
                $sql .= " AND id IN ($placeholders)";
            }

            $sql .= " ORDER BY $sortBy $sortOrder";

            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':car_type', $car_type);
            $stmt->bindParam(':pickup_location', $pickup_location);
            $stmt->bindParam(':min_price', $min_price);
            $stmt->bindParam(':max_price', $max_price);
            $stmt->bindParam(':rental_start', $rental_start);
            $stmt->bindParam(':rental_end', $rental_end);

            if ($shortlistFilter && !empty($shortlistIds)) {
                foreach ($shortlistIds as $index => $id) {
                    $stmt->bindValue($index + 1, $id);
                }
            }

            $stmt->execute();

            // Display search results
            if ($stmt->rowCount() > 0) {
                echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>"; // Changed method to POST
                echo "<table id='carTable'>";
                echo "<thead>";
                echo "<tr>
                        <th><button type='submit' name='filter_shortlist'>Filter Shortlist</button></th>
                        <th><a href='?sort=price_per_day&sortOrder=" . ($sortBy == 'price_per_day' ? ($sortOrder == 'ASC' ? 'DESC' : 'ASC') : 'ASC') . "'>Price per Day</a></th>
                        <th><a href='?sort=car_type&sortOrder=" . ($sortBy == 'car_type' ? ($sortOrder == 'ASC' ? 'DESC' : 'ASC') : 'ASC') . "'>Car Type</a></th>
                        <th><a href='?sort=fuel_type&sortOrder=" . ($sortBy == 'fuel_type' ? ($sortOrder == 'ASC' ? 'DESC' : 'ASC') : 'ASC') . "'>Fuel Type</a></th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>";
                echo "</thead>";
                echo "<tbody>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr class='" . strtolower($row['fuel_type']) . "'>";
                    echo "<td><input type='checkbox' name='shortlist[]' value='" . $row['id'] . "'></td>";
                    echo "<td>" . $row['price_per_day'] . "</td>";
                    echo "<td>" . $row['car_type'] . "</td>";
                    echo "<td class='" . strtolower($row['fuel_type']) . "'>" . ucfirst($row['fuel_type']) . "</td>";
                    echo "<td>";
                    if (!empty($row['image'])) {
                        echo "<figure><img src='" . $row['image'] . "' alt='Car Photo' width='100'><figcaption>Car Photo</figcaption></figure>";
                    } else {
                        echo "No photo available";
                    }
                    echo "</td>";
                    echo "<td><a href='Car_Details.php?id=" . $row['id'] . "'>Details</a></td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</form>"; // Closing form tag
            } else {
                echo "<p>No cars available based on the search criteria.</p>";
            }
        }
    }
    ?>

    <?php include 'footer.php'; ?>
</body>
</html>

