<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Basket</title>
</head>
<body>
    <h1>Shopping Basket</h1>

    <?php
    // Start or resume the session
    session_start();

    // Check if the user is logged in
    if (empty($_SESSION['user_id'])) {
        // Redirect to the login page if the user is not logged in
        header("Location: login.php");
        exit();
    }

    // Include your database connection file
    include 'db.php.inc.php'; // Include your database connection file
    $pdo = db_connect(); // Establish database connection

    // Fetch ongoing car rentals that the customer has not confirmed
    $user_id = $_SESSION['user_id'];
    $sql_rentals = "SELECT * FROM rentals WHERE user_id = :user_id";
    $stmt_rentals = $pdo->prepare($sql_rentals);
    $stmt_rentals->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_rentals->execute();
    $rentals = $stmt_rentals->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rentals)) {
        echo '<p>No ongoing car rentals in your basket.</p>';
    } else {
        echo '<div class="layout_table">
        <table class="basket_table">
                <tr>
                    <th>Rental ID</th>
                    <th>Car ID</th>
                    <th>Rental Start</th>
                    <th>Rental End</th>
                </tr>';

        // Display the ongoing rentals in a table
        foreach ($rentals as $rental) {
            echo '<tr>
                    <td>' . $rental['id'] . '</td>
                    <td>' . $rental['car_id'] . '</td>
                    <td>' . $rental['rental_start'] . '</td>
                    <td>' . $rental['rental_end'] . '</td>
                  </tr>';
        }

        echo '</table></div>';
    }

    ?>

    <div class="checkout">
        <?php if (!empty($rentals)) { ?>
            <a class="btn-checkout" href="confirm_checkout.php?id=<?php echo $rental['id']; ?>" onclick="return confirm('Are you sure about confirming this rental?')">
                <p class="checkout_p">Confirm Rental</p>
            </a>
        <?php } ?>
    </div>

    <?php
    // Include your footer file
    include 'footer.php';
    ?>

</body>
</html>
