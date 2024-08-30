<?php
session_start(); // Start the session at the beginning of the script if it's not already started

// Include necessary files
include_once 'db.php.inc.php'; // Include database connection function
include_once 'getUserDetails.php'; // Include getUserDetails function
include_once 'getCarDetails.php'; // Include getCarDetails function

// Establish a database connection
$pdo = db_connect(); // This call will print "Successfully connected" once

if (!$pdo) {
    echo "Error: Database connection failed.";
    exit;
}

// Check if car_id is provided in the URL
if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];
} else {
    echo "Error: Car ID not provided in URL.";
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Validate and sanitize input data
    $carId = isset($_GET['car_id']) ? htmlspecialchars($_GET['car_id']) : null;
    // Validate other form fields as needed

    // Check if the car_id parameter is provided in POST data and is not empty
    if (empty($carId)) {
        echo "Error: Car ID not provided or empty.";
        exit();
    }

    // Fetch the car data based on car ID
    $carData = getCarDetails($pdo, $carId);
    // Debugging statement to print car data
    echo 'Debug: CarData fetched: ';
    var_dump($carData);

    if ($carData) {
        // Continue processing the form submission
        // Prepare rental data and store in session
        $rentData = [
            'car_id' => $carId,
            'special_requirements' => $_POST['special_requirements'],
            // Add other form fields to rental data as needed
        ];
        $_SESSION['rent_data'] = $rentData;

        // Redirect to the next step (rent_invoice.php) after processing
        header("Location: rent_invoice.php");
        exit();
    } else {
        echo "Error: Car not found for ID: " . $carId;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent A Car</title>
    <link rel="stylesheet" href="styles.css">
    <?php include 'header_Customer.php'; ?>
    <?php include 'leftside_Customer.php'; ?>
</head>
<body>
    <h1>Rent A Car</h1>
    <form class="profile-form" action="rent_invoice.php?car_id=<?php echo $car['id']; ?>" method="POST">
<label for="credit_card_number">Credit Card Number:</label>
<input type="text" id="credit_card_number" name="credit_card_number" required><br><br>
<label for="expiration_date">Expiration Date:</label>
<input type="text" id="expiration_date" name="expiration_date" required><br><br>
<label for="card_holder_name">Card Holder Name:</label>
<input type="text" id="card_holder_name" name="card_holder_name" required><br><br>
<label for="bank_issued">Bank Issued:</label>
<input type="text" id="bank_issued" name="bank_issued" required><br><br>
<label for="credit_card_type">Credit Card Type:</label>
<input type="text" id="credit_card_type" name="credit_card_type" required><br><br>

<!-- Other form fields related to the rental process -->
<label for="pickup_date">Pickup Date:</label>
<input type="date" id="pickup_date" name="pickup_date" required><br><br>
<label for="return_date">Return Date:</label>
<input type="date" id="return_date" name="return_date" required><br><br>
<label for="pickup_location">Pickup Location:</label>
<input type="text" id="pickup_location" name="pickup_location" required><br><br>
<label for="return_location">Return Location:</label>
<input type="text" id="return_location" name="return_location" required><br><br>
<label for="baby_seat">Baby Seat:</label>
<input type="checkbox" id="baby_seat" name="baby_seat"><br><br>
<label for="insurance">Insurance:</label>
<input type="checkbox" id="insurance" name="insurance"><br><br>

        <input type="submit" value="Confirm Rent">
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>
