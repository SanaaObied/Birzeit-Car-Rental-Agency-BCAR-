<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Initialize variables and error messages
$name = $address = $telephone = "";
$nameErr = $addressErr = $telephoneErr = "";
$successMessage = $errorMessage = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } else {
        $name = htmlspecialchars($_POST['name']); // Sanitize input
    }

    // Validate address
    if (empty($_POST['address'])) {
        $addressErr = "Address is required";
    } else {
        $address = htmlspecialchars($_POST['address']); // Sanitize input
    }

    // Validate telephone number
    if (empty($_POST['telephone'])) {
        $telephoneErr = "Telephone number is required";
    } else {
        $telephone = htmlspecialchars($_POST['telephone']); // Sanitize input
    }

    // Check and sanitize optional fields
    $propertyNumber = !empty($_POST['property_number']) ? htmlspecialchars($_POST['property_number']) : null;
    $streetName = !empty($_POST['street_name']) ? htmlspecialchars($_POST['street_name']) : null;
    $city = !empty($_POST['city']) ? htmlspecialchars($_POST['city']) : null;
    $postalCode = !empty($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : null;
    $country = !empty($_POST['country']) ? htmlspecialchars($_POST['country']) : null;

    // If all required fields are provided, insert the new location into the database
    if (!empty($name) && !empty($address) && !empty($telephone)) {
        $pdo = db_connect(); // Establish database connection

        // Prepare and execute the SQL statement to insert the new location
        $sql = "INSERT INTO locations (name, address, telephone, property_number, street_name, city, postal_code, country) 
                VALUES (:name, :address, :telephone, :property_number, :street_name, :city, :postal_code, :country)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':property_number', $propertyNumber);
        $stmt->bindParam(':street_name', $streetName);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postalCode);
        $stmt->bindParam(':country', $country);

        if ($stmt->execute()) {
            // Location added successfully
            $successMessage = "Location added successfully.";
            // Clear form fields
            $name = $address = $telephone = "";
        } else {
            $errorMessage = "Error adding location: " . $stmt->errorInfo()[2]; // Get the error message from PDO
        }

        // Close the database connection
        $pdo = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location</title>
    <link rel="stylesheet" href="styles.css">
    <?php 
     include 'header.php'; 
        $_SESSION['location']="index.php";
    ?>

<?php include 'leftside.php'; ?>

</head>
<body>
    <h1>Add New Location</h1>
    <?php if (!empty($successMessage)): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  class="profile-form" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <span class="error"><?php echo $nameErr; ?></span><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
        <span class="error"><?php echo $addressErr; ?></span><br><br>

        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>">
        <span class="error"><?php echo $telephoneErr; ?></span><br><br>

        <label for="property_number">Property Number:</label>
        <input type="text" id="property_number" name="property_number"><br><br>

        <label for="street_name">Street Name:</label>
        <input type="text" id="street_name" name="street_name"><br><br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city"><br><br>

        <label for="postal_code">Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code"><br><br>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country"><br><br>

        <input type="submit" value="Add Location">
    </form>
    <?php include("footer.php"); ?>
</body>
</html>
