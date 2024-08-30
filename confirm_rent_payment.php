<?php
session_start();

include_once 'db.php.inc.php'; // Include your database connection file

// Fetch customer data from the session or database
$customerId = $_SESSION['user_id'];

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

    $sqlRentals = "SELECT * FROM `cars` WHERE id = :car_id";
    $stmtRentals = $pdo->prepare($sqlRentals);
    $stmtRentals->bindParam(':mode', $carModel);
    $stmtRentals->bindParam(':car_type', $carType);
    $stmtRentals->bindParam(':fuel_type', $fuelType);
    $stmtRentals->bindParam(':description', $description);
    $stmtRentals->bindParam(':car_id', $carReferenceNumber);
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

$rentData = $_SESSION['car_id'] ?? []; // Initialize as an empty array if not set
$carReferenceNumber = $rentData['car_reference_number'] ?? '';
$carModel = $rentData['car_model'] ?? '';
$carType = $rentData['car_type'] ?? '';
$fuelType = $rentData['fuel_type'] ?? '';
$description = $rentData['description'] ?? '';
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
        $stmtInsert->execute();

        // Store the invoice ID in the session
        $_SESSION['invoice_id'] = $invoiceId;

        // Redirect to the confirmation page
        header("Location: confirm_rent_payment.php");
        exit();
    }
}

// Fetch locations for the dropdown
$sqlLocations = "SELECT * FROM locations";
$stmtLocations = $pdo->prepare($sqlLocations);
$stmtLocations->execute();
$locations = $stmtLocations->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML form for rental details -->
<form method="POST">
    <label for="pickup_location">Pickup Location:</label>
    <select id="pickup_location" name="pickup_location">
        <?php foreach ($locations as $location): ?>
            <option value="<?php echo htmlspecialchars($location['name']); ?>"><?php echo htmlspecialchars($location['name']); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="return_location">Return Location:</label>
    <select id="return_location" name="return_location">
        <?php foreach ($locations as $location): ?>
            <option value="<?php echo htmlspecialchars($location['name']); ?>"><?php echo htmlspecialchars($location['name']); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="extra_requirements">Extra Requirements:</label>
    <input type="text" id="extra_requirements" name="extra_requirements">

    <button type="submit">Submit</button>
</form>
