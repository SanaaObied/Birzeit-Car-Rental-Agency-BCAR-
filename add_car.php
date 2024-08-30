
<?php
session_start();
include 'db.php.inc.php';

// Establish database connection
$pdo = db_connect();

function generateCarId($pdo) {
    do {
        $carId = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE id = ?");
        $stmt->execute([$carId]);
        $exists = $stmt->fetchColumn();
    } while ($exists);
    return $carId;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $carId = generateCarId($pdo);
    $model = $_POST['model'];
    $make = $_POST['make'];
    $registration_year = $_POST['registration_year'];
    $color = $_POST['color'];
    $capacity_people = $_POST['capacity_people'];
    $capacity_suitcases = $_POST['capacity_suitcases'];
    $price_per_day = $_POST['price_per_day'];
    $car_type = isset($_POST['car_type']) ? $_POST['car_type'] : ''; // Set default value if not provided
    $fuel_type = $_POST['fuel_type'];
    $avg_consumption = $_POST['avg_consumption'];
    $horsepower = $_POST['horsepower'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $gear_type = isset($_POST['gear_type']) ? $_POST['gear_type'] : ''; // Set default value if not provided
    $conditions = $_POST['conditions'];
    $description = $_POST['description'];
    $location_id = isset($_POST['location_id']) ? $_POST['location_id'] : 1; // Default value or 1
    $imageFiles = []; // Assuming you handle file uploads separately

    $total_price = $_POST['total_price'];

    // Handle file uploads
    $uploadDirectory = "carsImages/";
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    $imageFiles = [];
    for ($i = 1; $i <= 3; $i++) {
        $file = $_FILES["photo$i"];
        if ($file['error'] === UPLOAD_ERR_OK && in_array($file['type'], $allowedTypes)) {
            $filename = "car{$carId}img{$i}." . pathinfo($file['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($file['tmp_name'], $uploadDirectory . $filename)) {
                $imageFiles[] = $filename;
            } else {
                echo "Error uploading photo $i";
                exit;
            }
        } else {
            echo "Invalid file type or upload error for photo $i";
            exit;
        }
    }
    try {
        // Insert car details into the database
        $stmt = $pdo->prepare("INSERT INTO cars 
        (id, model, make, reg_year, color, people_capacity, suitcase_capacity, price_per_day, car_type, fuel_type, 
        avg_consumption, horsepower, length, width, gear_type, conditions, description, image, image_2, image_3, available, 
        location_id, total_price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $carId, $model, $make, $registration_year, $color, $capacity_people, $capacity_suitcases, $price_per_day, 
        $car_type, $fuel_type, $avg_consumption, $horsepower, $length, $width, $gear_type, $conditions, $description, 
        $imageFiles[0], $imageFiles[1], $imageFiles[2], 1, $location_id, $total_price
    ]);
    
        // Display confirmation message
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
        } else {
            // Other PDO exceptions
            echo "Error: " . $e->getMessage();
        }
    }
}

// Fetch car types, makes, and fuel types from the database
$carTypesQuery = "SELECT DISTINCT car_type FROM cars";
$carTypesStmt = $pdo->query($carTypesQuery);
if (!$carTypesStmt) {
    echo "Error fetching car types: " . $pdo->errorInfo()[2];
    exit;
}
$carTypes = $carTypesStmt->fetchAll(PDO::FETCH_ASSOC);

$carMakes = $pdo->query("SELECT DISTINCT make FROM cars")->fetchAll(PDO::FETCH_ASSOC);
$fuelTypes = $pdo->query("SELECT DISTINCT fuel_type FROM cars")->fetchAll(PDO::FETCH_ASSOC);

?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Add A Car</title>
    <link rel="stylesheet" href="styles.css">
    <?php 
     include 'header.php'; 
    ?>

<?php include 'leftside.php'; ?>

</head>
<body>
    <h1>Add A Car</h1>
    <form  class="car-search-form" action="add_car.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="model">Car Model:</label>
        <input type="text" id="model" name="model" required><br>

        <label for="make">Car Make:</label>
        <select id="make" name="make">
        <?php
        $stmt = $pdo->query("SELECT DISTINCT make FROM cars");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['make'] . "'>" . $row['make'] . "</option>";
        }
        ?>
        </select><br>

        <label for="type">Car Type:</label>
        <select id="type" name="car_type">
            <?php
            // Fetch car types from the database
            $stmt = $pdo->query("SELECT DISTINCT car_type FROM cars");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['car_type'] . "'>" . $row['car_type'] . "</option>";
            }
            ?>
        </select><br>

        <label for="registration_year">Registration Year:</label>
        <input type="number" id="registration_year" name="registration_year" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price_per_day">Price per Day:</label>
        <input type="number" id="price_per_day" name="price_per_day" required><br>

        <label for="capacity_people">Capacity (People):</label>
        <input type="number" id="capacity_people" name="capacity_people" required><br>

        <label for="capacity_suitcases">Capacity (Suitcases):</label>
        <input type="number" id="capacity_suitcases" name="capacity_suitcases" required><br>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required><br>

        <label for="fuel_type">Fuel Type:</label>
        <select id="fuel_type" name="fuel_type">
        <?php
        // Fetch fuel types from the database
        $stmt = $pdo->query("SELECT DISTINCT fuel_type FROM cars");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['fuel_type'] . "'>" . $row['fuel_type'] . "</option>";
        }
        ?>
        </select><br>
        
        <label for="avg_consumption">Average Consumption (per 100 km):</label>
        <input type="number" id="avg_consumption" name="avg_consumption" step="0.01" required><br>

        <label for="horsepower">Horsepower:</label>
        <input type="number" id="horsepower" name="horsepower" required><br>

        <label for="length">Length:</label>
        <input type="number" id="length" name="length" step="0.01" required><br>

        <label for="width">Width:</label>
        <input type="number" id="width" name="width" step="0.01" required><br>

        <label for="plate_number">Plate Number:</label>
        <input type="text" id="plate_number" name="plate_number" required><br>

        <label for="conditions">Conditions or Restrictions:</label>
        <textarea id="conditions" name="conditions"></textarea><br>
        <label for="total_price">Total Price:</label>
<input type="number" id="total_price" name="total_price" required><br>


        <label for="photo1">Photo 1:</label>
        <input type="file" id="photo1" name="photo1" required><br>

        <label for="photo2">Photo 2:</label>
        <input type="file" id="photo2" name="photo2" required><br>

        <label for="photo3">Photo 3:</label>
        <input type="file" id="photo3" name="photo3" required><br>
        <label for="gear_type">Gear Type:</label>
<select id="gear_type" name="gear_type" required>
    <option value="" disabled selected>Select Gear Type</option>
    <?php
    // Fetch gear types from the database
    $stmt = $pdo->query("SELECT DISTINCT gear_type FROM cars");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['gear_type'] . "'>" . $row['gear_type'] . "</option>";
    }
    ?>
</select><br>

<label for="location_id">Location ID:</label>
<select id="location_id" name="location_id" required>
    <option value="" disabled selected>Select Location ID</option>
    <?php
    $stmt = $pdo->query("SELECT DISTINCT location_id FROM cars");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['location_id'] . "'>" . $row['location_id'] . "</option>";
    }
    ?>
</select><br>
<input type="hidden" name="submitted" value="1">
<input type="submit" value="Add Car">


</div>
    </form>
    <?php
// Check if the form is submitted
if (isset($_POST['submitted'])) {
    try {
        // Your database insertion code goes here

        // Get the last inserted ID after successful insertion
        $carId = $pdo->lastInsertId();

        // Display success message with car ID
        echo "<p class='success-message'>Car successfully added with ID: $carId</p>";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            // Duplicate entry error
            echo "<p class='error-message'>Error: Duplicate entry detected. Please check your data.</p>";
        } else {
            // Other PDO exceptions
            echo "<p class='error-message'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>
    <?php include("footer.php"); ?>

</body>
</html>



    