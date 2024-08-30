<?php
// Include database connection
include 'db.php.inc.php';

if (isset($_GET['id'])) {
    $carId = $_GET['id'];

    // Establish database connection
    $conn = db_connect();

    // Fetch car details from the database based on the provided car ID
    $sql = "SELECT * FROM cars WHERE id = :car_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':car_id', $carId);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($car) {
        // Car details fetched successfully
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <?php 
     include 'header.php'; 
    ?>

<?php include 'leftside.php'; ?>
</head>
<body>
    <div class="container">
        <header>
            <h1>Car Details</h1>
        </header>
        <main>
        <div class="car-details-container">
        <div class="car-image">
                <img src="<?php echo $car['image']; ?>" alt="Car Photo">
            </div>
            <form method="POST" action="rent_car.php?car_id=<?php echo $car['id']; ?>">
            <div class="car-info">
                <h2><?php echo $car['make']; ?></h2>
                <ul class="car-description">
                    <li><strong>Car Reference Number:</strong> <?php echo $car['id']; ?></li>
                    <li><strong>Car Model:</strong> <?php echo $car['model']; ?></li>
                    <li><strong>Car Type:</strong> <?php echo $car['car_type']; ?></li>
                    <li><strong>Car Make:</strong> <?php echo $car['make']; ?></li>
                    <li><strong>Registration Year:</strong> <?php echo isset($car['reg_year']) ? $car['reg_year'] : 'Not available'; ?></li>
                    <li><strong>Color:</strong> <?php echo isset($car['color']) ? $car['color'] : 'Not available'; ?></li>
                    <li><strong>Description:</strong> <?php echo $car['description']; ?></li>
                    <li><strong>Price per Day:</strong> $<?php echo $car['price_per_day']; ?></li>
                    <li><strong>Capacity of People:</strong> <?php echo isset($car['people_capacity']) ? $car['people_capacity'] : 'Not available'; ?></li>
                    <li><strong>Capacity of Suitcases:</strong> <?php echo isset($car['suitcase_capacity']) ? $car['suitcase_capacity'] : 'Not available'; ?></li>
                    <li><strong>Total Price for the Renting Period:</strong> <?php echo isset($car['total_price']) ? $car['total_price'] : 'Not available'; ?></li>
                    <li><strong>Fuel Type:</strong> <?php echo $car['fuel_type']; ?></li>
                    <li><strong>Average Consumption per 100 km:</strong> <?php echo isset($car['avg_consumption']) ? $car['avg_consumption'] : 'Not available'; ?></li>
                    <li><strong>Horsepower:</strong> <?php echo isset($car['horsepower']) ? $car['horsepower'] : 'Not available'; ?></li>
                    <li><strong>Length:</strong> <?php echo isset($car['length']) ? $car['length'] : 'Not available'; ?></li>
                    <li><strong>Width:</strong> <?php echo isset($car['width']) ? $car['width'] : 'Not available'; ?></li>
                    <li><strong>Gear Type:</strong> <?php echo isset($car['gear_type']) ? $car['gear_type'] : 'Not available'; ?></li>
                    <li><strong>Conditions or Restrictions:</strong> <?php echo isset($car['conditions']) ? $car['conditions'] : 'Not available'; ?></li>
                </ul>
                <button class="rent-button">Rent-a-Car</a>
                </div>
    </div>
                </form>
        </main>
        <div class="additional-info">
            <h3>Additional Information</h3>
            <p>This car is enjoyable to drive and offers a discount for long-term rentals.</p>
        </div>
        <footer>
        <?php include("footer.php"); ?>
        </footer>
    </div>
    
</body>
</html>
<?php
    } else {
        // Car not found in the database
        echo "Car not found.";
    }
} else {
    // Car ID not provided in the URL
    echo "Car ID not provided.";
}
?>





