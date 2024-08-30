<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter_shortlist'])) {
    // Check if shortlist array is present in $_POST
    if (isset($_POST['shortlist']) && is_array($_POST['shortlist'])) {
        // Establish database connection
        include 'db.php.inc.php';
        $conn = db_connect();

        // Check if connection is successful
        if (!$conn) {
            die("Connection failed: " . db_error());
        }

        // Filter shortlisted cars based on checked items
        $shortlist = $_POST['shortlist'];
        $shortlistIds = implode(',', array_map('intval', $shortlist)); // Convert IDs to integers and implode into comma-separated string

        // Construct SQL query with shortlisted IDs
        $sql = "SELECT * FROM cars WHERE id IN ($shortlistIds)";

        // Execute the query
        $stmt = $conn->query($sql);

        // Display the shortlisted cars in the same table
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Car Type</th><th>Price per Day</th></tr>";
            foreach ($stmt as $car) {
                echo "<tr><td>{$car['id']}</td><td>{$car['car_type']}</td><td>{$car['price_per_day']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No cars found in the shortlist.";
        }

        // Close the database connection
        db_close($conn);
    } else {
        echo "Shortlist is empty or invalid.";
    }
} else {
    // Redirect to the main page if accessed directly without form submission
    header("Location: index.php");
    exit();
}
?>
