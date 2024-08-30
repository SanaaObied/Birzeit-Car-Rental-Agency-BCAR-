<?php
session_start();

include 'db.php.inc.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit(); // Stop further execution of the script
}

// Debug: Print session contents for debugging
var_dump($_SESSION);

// Check if car_id is set in the session
if (!isset($_SESSION['car_id'])) {
    echo "Error: Car ID not found in session.";
    exit();
}

// Now you can safely use $_SESSION['car_id'] in your code
$carId = $_SESSION['car_id'];
echo "Car ID from session: " . $carId;

$pdo = db_connect(); // Establish database connection

if ($pdo) {
    echo "Successfully connected";

    // Query for rentals
    $sqlRentals = "SELECT * FROM `rentals` WHERE user_id = :user_id AND car_id = :car_id";
    $stmtRentals = $pdo->prepare($sqlRentals);
    $stmtRentals->bindParam(':user_id', $customerId);
    $stmtRentals->bindParam(':car_id', $carId);
    $stmtRentals->execute();
    $rentalsData = $stmtRentals->fetch(PDO::FETCH_ASSOC);

    // Query for user
    $sqlUser = "SELECT * FROM `user` WHERE user_id = :user_id";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->bindParam(':user_id', $customerId);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        echo "Error: Customer data not found.";
        exit();
    }
} else {
    echo "Error: Database connection failed.";
    exit();
}

$carReferenceNumber = $rentData['car_reference_number'] ?? '';
$carModel = $rentData['car_model'] ?? '';
$carType = $rentData['car_type'] ?? '';
$fuelType = $rentData['fuel_type'] ?? '';
$description = $rentData['description'] ?? '';
$pickupDate = $rentData['pickup_date'] ?? '';
$returnDate = $rentData['return_date'] ?? '';
$pickupLocation = $rentData['pickup_location'] ?? '';
$totalRentAmount = $rentData['total_rent_amount'] ?? '';
$specialRequirements = $rentData['special_requirements'] ?? '';
$returnLocation = $rentData['return_location'] ?? '';
$babySeat = $rentData['baby_seat'] ?? 'no';
$insurance = $rentData['insurance'] ?? 'no';

// Calculate additional costs
$totalRentAmount = isset($rentData['total_rent_amount']) ? floatval($rentData['total_rent_amount']) : 0;

// Calculate additional costs
$additionalCosts = 0;
if ($returnLocation != $pickupLocation) {
    $additionalCosts += 20; // Example cost for different return location
}
if ($babySeat == 'yes') {
    $additionalCosts += 10; // Example cost for baby seat
}
if ($insurance == 'yes') {
    $additionalCosts += 15; // Example cost for insurance
}

$totalRentAmount += $additionalCosts; // Perform addition

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process payment details
    $creditCardNumber = $_POST['credit_card_number'] ?? '';
    $expirationDate = $_POST['expiration_date'] ?? '';
    $cardHolderName = $_POST['card_holder_name'] ?? '';
    $bankIssued = $_POST['bank_issued'] ?? '';
    $creditCardType = $_POST['credit_card_type'] ?? '';
    $acceptTerms = isset($_POST['accept_terms']) ? $_POST['accept_terms'] : 'no';
    $customerName = $_POST['customer_name'] ?? '';
    $currentDate = date('Y-m-d');

    // Validate credit card number
    if (!preg_match('/^\d{9}$/', $creditCardNumber)) {
        $error = "Credit card number must be 9 digits.";
    }

    // Validate expiration date
    $expirationDateParts = explode('-', $expirationDate);
    if (count($expirationDateParts) != 2 || !checkdate($expirationDateParts[1], 1, $expirationDateParts[0])) {
        $error = "Invalid expiration date.";
    }

    // Validate acceptance of terms
    if ($acceptTerms != 'yes') {
        $error = "You must accept the terms and conditions.";
    }

    // If no errors, proceed to next step
    if (!isset($error)) {
        // Generate a 10-digit invoice ID
        $invoiceId = mt_rand(1000000000, 9999999999);

        // Update the database with the rental information
        $sqlInsert = "INSERT INTO rentals (invoice_id, user_id, car_reference_number, car_model, car_type, fuel_type, description, pickup_date, return_date, pickup_location, return_location, special_requirements, baby_seat, insurance, total_rent_amount, credit_card_number, expiration_date, card_holder_name, bank_issued, credit_card_type, customer_name, current_date) 
        VALUES (:invoice_id, :user_id, :car_reference_number, :car_model, :car_type, :fuel_type, :description, :pickup_date, :return_date, :pickup_location, :return_location, :special_requirements, :baby_seat, :insurance, :total_rent_amount, :credit_card_number, :expiration_date, :card_holder_name, :bank_issued, :credit_card_type, :customer_name, :current_date)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->bindParam(':invoice_id', $invoiceId);
        $stmtInsert->bindParam(':user_id', $customerId);
        $stmtInsert->bindParam(':car_reference_number', $carReferenceNumber);
        $stmtInsert->bindParam(':car_model', $carModel);
        $stmtInsert->bindParam(':car_type', $carType);
        $stmtInsert->bindParam(':fuel_type', $fuelType);
        $stmtInsert->bindParam(':description', $description);
        $stmtInsert->bindParam(':pickup_date', $pickupDate);
        $stmtInsert->bindParam(':return_date', $returnDate);
        $stmtInsert->bindParam(':pickup_location', $pickupLocation);
        $stmtInsert->bindParam(':return_location', $returnLocation);
        $stmtInsert->bindParam(':special_requirements', $specialRequirements);
        $stmtInsert->bindParam(':baby_seat', $babySeat);
        $stmtInsert->bindParam(':insurance', $insurance);
        $stmtInsert->bindParam(':total_rent_amount', $totalRentAmount);
        $stmtInsert->bindParam(':credit_card_number', $creditCardNumber);
        $stmtInsert->bindParam(':expiration_date', $expirationDate);
        $stmtInsert->bindParam(':card_holder_name', $cardHolderName);
        $stmtInsert->bindParam(':bank_issued', $bankIssued);
        $stmtInsert->bindParam(':credit_card_type', $creditCardType);
        $stmtInsert->bindParam(':customer_name', $customerName);
        $stmtInsert->bindParam(':current_date', $currentDate);

        if ($stmtInsert->execute()) {
            // Display confirmation message
            echo "<h2>Confirmation</h2>";
            echo "<p>Thank you, " . htmlspecialchars($customerName) . ". The car has been successfully rented.</p>";
            echo "<p>Your Invoice ID is: " . htmlspecialchars($invoiceId) . ".</p>";
            echo "<p>Please keep this Invoice ID for future reference.</p>";

            // Unset rent data from the session
            unset($_SESSION['rent_data']);
        } else {
            echo "<p>Error: Unable to complete the rental process. Please try again later.</p>";
        }

        // Close the database connection
        $pdo = null;

        exit();
    }
}

// Display rental details and form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rental Invoice</title>
    <link rel="stylesheet" href="styles.css">
    <?php include 'header_Customer.php'; ?>
    <?php include 'leftside_Customer.php'; ?>
</head>
<body>
    <h2>Rental Invoice</h2>
    <p>Customer Name: <?php echo isset($userData['name']) ? htmlspecialchars($userData['name']) : 'N/A'; ?></p>
    <p>Car Model: <?php echo htmlspecialchars($carModel); ?></p>
    <p>Car Type: <?php echo htmlspecialchars($carType); ?></p>
    <p>Fuel Type: <?php echo htmlspecialchars($fuelType); ?></p>
    <p>Description: <?php echo htmlspecialchars($description); ?></p>
    <p>Pickup Date: <?php echo htmlspecialchars($pickupDate); ?></p>
    <p>Return Date: <?php echo htmlspecialchars($returnDate); ?></p>
    <p>Pickup Location: <?php echo htmlspecialchars($pickupLocation); ?></p>
    <p>Return Location: <?php echo htmlspecialchars($returnLocation); ?></p>
    <p>Total Rent Amount: $<?php echo htmlspecialchars($totalRentAmount); ?></p>
    <p>Special Requirements: <?php echo htmlspecialchars($specialRequirements); ?></p>

    <form method="post">
        <label for="credit_card_number">Credit Card Number (9 digits):</label>
        <input type="text" id="credit_card_number" name="credit_card_number" required pattern="\d{9}"><br><br>

        <label for="expiration_date">Expiration Date (YYYY-MM):</label>
        <input type="text" id="expiration_date" name="expiration_date" required pattern="\d{4}-\d{2}"><br><br>

        <label for="card_holder_name">Card Holder Name:</label>
        <input type="text" id="card_holder_name" name="card_holder_name" required><br><br>

        <label for="bank_issued">Bank Issued:</label>
        <input type="text" id="bank_issued" name="bank_issued" required><br><br>

        <label for="credit_card_type">Credit Card Type:</label>
        <input type="text" id="credit_card_type" name="credit_card_type" required><br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($userData['name']); ?>" readonly><br><br>

        <label for="accept_terms">Accept Terms and Conditions:</label>
        <input type="checkbox" id="accept_terms" name="accept_terms" value="yes" required><br><br>

        <input type="submit" value="Confirm Rental">
    </form>

    <?php
if (isset($error)) {
    echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
}
?>
<?php include 'footer.php'; ?>
</body>
</html>
