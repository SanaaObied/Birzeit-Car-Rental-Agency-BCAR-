<?php
session_start();

if (!isset($_SESSION['customer_info'])) {
    header("Location: signup.php"); // Redirect to sign-up page if session data is not available
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process e-account form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate data
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 6 || strlen($username) > 13) {
        $error = "Username must be between 6 and 13 characters.";
    } elseif (strlen($password) < 8 || strlen($password) > 12) {
        $error = "Password must be between 8 and 12 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Store e-account details in session
        $_SESSION['e_account'] = [
            'username' => $username,
            'password' => $password
        ];

        // Redirect to confirmation step
        header("Location: confirmation.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-account Creation</title>
    <link rel="stylesheet" href="styles.css">

    <?php 
     include 'header_Customer.php'; 
    ?>

<?php include 'leftside_Customer.php'; ?>
</head>
<body>
    <h1>E-account Creation</h1>
    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>Error: $error</p>";
    }
    ?>
    <form class="profile-form" method="post" action="e_account.php">
        <label for="username">Username (6-13 characters):</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password (8-12 characters):</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <input type="submit" value="Create E-account">
    </form>
    <?php include 'footer.php'; ?>

</body>
</html>

