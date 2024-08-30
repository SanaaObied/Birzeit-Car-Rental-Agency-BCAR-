<?php
session_start();

if (!isset($_SESSION['customer_info']) || !isset($_SESSION['e_account'])) {
    header("Location: signup.php"); // Redirect to sign-up page if session data is not available
    exit();
}

include "db.php.inc.php"; // Include your database connection file

// Call the db_connect function to establish the connection
$pdo = db_connect();

$customer_info = $_SESSION['customer_info'];
$e_account = $_SESSION['e_account'];

// Generate a 10-digit user ID
$user_id = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

// Insert data into user table
$stmt = $pdo->prepare("INSERT INTO user (user_id, name, address_flat, address_street, address_city, address_country, date_of_birth, id_number, email, telephone, credit_card_number, credit_card_expiration_date, credit_card_name, bank_issued, password) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $customer_info['name'], $customer_info['address_flat'], $customer_info['address_street'], $customer_info['address_city'], $customer_info['address_country'], $customer_info['dob'], $customer_info['id_number'], $customer_info['email'], $customer_info['telephone'], $customer_info['cc_number'], $customer_info['cc_expiration'], $customer_info['cc_name'], $customer_info['cc_bank'], $e_account['password']]);

// Clear session data
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Confirmation</title>
    <link rel="stylesheet" href="styles.css">

<?php 
 include 'header_Customer.php'; 
?>

<?php include 'leftside_Customer.php'; ?>
</head>
<body>
    <h1>Customer Registration - Confirmation</h1>
    <p>Thank you for registering! Your details have been successfully submitted.</p>
    <p>Your customer ID is: <?php echo htmlspecialchars($user_id); ?></p>
    <?php include 'footer.php'; ?>

</body>
</html>
