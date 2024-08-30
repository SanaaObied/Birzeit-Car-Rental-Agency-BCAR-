<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rented Cars</title>
    <link rel="stylesheet" href="styles.css">
    <?php include 'header_Customer.php'; ?>
    <?php include 'leftside_Customer.php'; ?>
</head>
<body>
    <h2>View Rented Cars</h2>
    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Car Type</th>
                <th>Car Model</th>
                <th>Pick-Up Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Include your database connection file
            include 'db.php.inc.php';
            
            // Establish a database connection
            $pdo = db_connect();
            
            // Fetch rented car data from the rentals table
            $sql = "SELECT r.id AS invoice_id, r.rental_start AS invoice_date, c.car_type AS car_type, c.model AS car_model, r.rental_start AS pickup_date, r.rental_end AS return_date 
                    FROM rentals r 
                    JOIN cars c ON r.car_id = c.id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rentedCars = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Loop through rentedCars array to display data
            foreach ($rentedCars as $rentedCar) {
                // Determine the rental status and apply appropriate CSS class
                $rentalStatusClass = '';
                $statusText = '';
                if ($rentedCar['return_date'] === null) {
                    $rentalStatusClass = 'future';
                    $statusText = 'Future';
                } elseif (strtotime($rentedCar['return_date']) > time()) {
                    $rentalStatusClass = 'current';
                    $statusText = 'Current';
                } else {
                    $rentalStatusClass = 'past';
                    $statusText = 'Past';
                }
                // Output the table row with rental data and CSS class for status
                echo "<tr class='$rentalStatusClass'>";
                echo "<td>{$rentedCar['invoice_id']}</td>";
                echo "<td>{$rentedCar['invoice_date']}</td>";
                echo "<td>{$rentedCar['car_type']}</td>";
                echo "<td>{$rentedCar['car_model']}</td>";
                echo "<td>{$rentedCar['pickup_date']}</td>";
                echo "<td>{$rentedCar['return_date']}</td>";
                echo "<td>{$rentalStatusClass}</td>"; // Display status as text for clarity
                echo "</form>";
                echo "</td>"; // End Action column
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>



