<?php
session_start();

include "db.php.inc.php"; // Include your database connection file

// Call the db_connect function to establish the connection
$pdo = db_connect();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process sign-up form data
    $name = $_POST['name'];
    $address_flat = $_POST['address_flat'];
    $address_street = $_POST['address_street'];
    $address_city = $_POST['address_city'];
    $address_country = $_POST['address_country'];
    $dob = $_POST['dob']; // Date of Birth
    $id_number = $_POST['id_number']; // ID Number
    $email = $_POST['email']; // Email
    $telephone = $_POST['telephone']; // Telephone
    $cc_number = $_POST['cc_number']; // Credit Card Number
    $cc_expiration = $_POST['cc_expiration']; // Credit Card Expiration
    $cc_name = $_POST['cc_name']; // Credit Card Name
    $cc_bank = $_POST['cc_bank']; // Credit Card Bank

    // Validate data
    if (empty($name) || empty($address_flat) || empty($address_street) || empty($address_city) || empty($address_country) || empty($dob) || empty($id_number) || empty($email) || empty($telephone) || empty($cc_number) || empty($cc_expiration) || empty($cc_name) || empty($cc_bank)) {
        $error = "All fields are required.";
    } else {
        // Store data in session
        $_SESSION['customer_info'] = [
            'name' => $name,
            'address_flat' => $address_flat,
            'address_street' => $address_street,
            'address_city' => $address_city,
            'address_country' => $address_country,
            'dob' => $dob,
            'id_number' => $id_number,
            'email' => $email,
            'telephone' => $telephone,
            'cc_number' => $cc_number,
            'cc_expiration' => $cc_expiration,
            'cc_name' => $cc_name,
            'cc_bank' => $cc_bank,
        ];

        // Redirect to e-account creation step
        header("Location: e_account.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php 
     include 'header_Customer.php'; 
    ?>

<?php include 'leftside_Customer.php'; ?>
<body>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form class="profile-form" method="POST" action="signup.php">
    <h1>Sign Up</h1>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="address_flat">Flat:</label>
        <input type="text" id="address_flat" name="address_flat" required><br><br>
        
        <label for="address_street">Street:</label>
        <input type="text" id="address_street" name="address_street" required><br><br>
        
        <label for="address_city">City:</label>
        <input type="text" id="address_city" name="address_city" required><br><br>
        
        <label for="address_country">Country:</label>
        <input type="text" id="address_country" name="address_country" required><br><br>
        
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        
        <label for="id_number">ID Number:</label>
        <input type="text" id="id_number" name="id_number" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" required><br><br>
        
        <label for="cc_number">Credit Card Number:</label>
        <input type="text" id="cc_number" name="cc_number" required><br><br>
        
        <label for="cc_expiration">Credit Card Expiration:</label>
        <input type="date" id="cc_expiration" name="cc_expiration" required><br><br>
        
        <label for="cc_name">Credit Card Name:</label>
        <input type="text" id="cc_name" name="cc_name" required><br><br>
        
        <label for="cc_bank">Credit Card Bank:</label>
        <input type="text" id="cc_bank" name="cc_bank" required><br><br>
        
        <input type="submit" value="Register">
    </form>
    <?php include 'footer.php'; ?>

</body>
</html>

