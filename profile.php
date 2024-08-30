<?php
session_start();
include 'db.php.inc.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$pdo = db_connect();

// Check if the form is submitted for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated profile data from form submission
    $name = $_POST['name'];
    $flat_house_no = $_POST['flat_house_no'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $dob = $_POST['dob'];
    $id_number = $_POST['id_number'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $cc_number = $_POST['cc_number'];
    $cc_expiration = $_POST['cc_expiration'];
    $cc_name = $_POST['cc_name'];
    $cc_bank = $_POST['cc_bank'];

    // Update user's information in the database
    $sql_update = "UPDATE user SET 
                    name = :name, 
                    address_flat = :flat_house_no,
                    address_street = :street,
                    address_city = :city,
                    address_country = :country,
                    date_of_birth = :dob,
                    id_number = :id_number,
                    email = :email,
                    telephone = :telephone,
                    credit_card_number = :cc_number,
                    credit_card_expiration_date = :cc_expiration,
                    credit_card_name = :cc_name,
                    bank_issued = :cc_bank
                WHERE user_id = :id";

    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':name', $name);
    $stmt_update->bindParam(':flat_house_no', $flat_house_no);
    $stmt_update->bindParam(':street', $street);
    $stmt_update->bindParam(':city', $city);
    $stmt_update->bindParam(':country', $country);
    $stmt_update->bindParam(':dob', $dob);
    $stmt_update->bindParam(':id_number', $id_number);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':telephone', $telephone);
    $stmt_update->bindParam(':cc_number', $cc_number);
    $stmt_update->bindParam(':cc_expiration', $cc_expiration);
    $stmt_update->bindParam(':cc_name', $cc_name);
    $stmt_update->bindParam(':cc_bank', $cc_bank);
    $stmt_update->bindParam(':id', $_SESSION['user_id']);

    if ($stmt_update->execute()) {
        // Update successful, redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        // Update failed, handle error
        echo "Error: Update failed";
    }
}

// Retrieve user details for the logged-in user
$sql_select = "SELECT user_id, name, address_flat, address_street, address_city, address_country, date_of_birth, id_number, email, telephone, credit_card_number, credit_card_expiration_date, credit_card_name, bank_issued FROM user WHERE user_id = :id";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->bindParam(':id', $_SESSION['user_id']);
$stmt_select->execute();
$user_profile = $stmt_select->fetch(PDO::FETCH_ASSOC);

// Initialize missing keys to avoid warnings
$user_profile = array_merge([
    'address_flat' => '',
    'address_street' => '',
    'address_city' => '',
    'address_country' => '',
    'date_of_birth' => '',
    'credit_card_number' => '',
    'credit_card_expiration_date' => '',
    'credit_card_name' => '',
    'bank_issued' => ''
], $user_profile);

// Display the user profile form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php 
     include 'header.php'; 
        $_SESSION['location']="index.php";
    ?>

<?php include 'leftside.php'; ?>

<body>
    <div class="container">
        <h1>User Profile</h1>
        
        <!-- Form to display and edit user details -->
        <form action="profile.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_profile['user_id']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_profile['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="flat_house_no">Flat/House No:</label>
                <input type="text" id="flat_house_no" name="flat_house_no" value="<?php echo htmlspecialchars($user_profile['address_flat']); ?>" required>
            </div>

            <div class="form-group">
                <label for="street">Street:</label>
                <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($user_profile['address_street']); ?>" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user_profile['address_city']); ?>" required>
            </div>

            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user_profile['address_country']); ?>" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user_profile['date_of_birth']); ?>" required>
            </div>

            <div class="form-group">
                <label for="id_number">ID Number:</label>
                <input type="text" id="id_number" name="id_number" value="<?php echo htmlspecialchars($user_profile['id_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_profile['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="telephone">Telephone:</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user_profile['telephone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cc_number">Credit Card Number:</label>
                <input type="text" id="cc_number" name="cc_number" value="<?php echo htmlspecialchars($user_profile['credit_card_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cc_expiration">Credit Card Expiration Date:</label>
                <input type="text" id="cc_expiration" name="cc_expiration" value="<?php echo htmlspecialchars($user_profile['credit_card_expiration_date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cc_name">Credit Card Name:</label>
                <input type="text" id="cc_name" name="cc_name" value="<?php echo htmlspecialchars($user_profile['credit_card_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cc_bank">Bank Issued:</label>
                <input type="text" id="cc_bank" name="cc_bank" value="<?php echo htmlspecialchars($user_profile['bank_issued']); ?>" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Update Profile" class="btn">
            </div>
        </form>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>
