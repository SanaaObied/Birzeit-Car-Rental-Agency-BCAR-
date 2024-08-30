<?php
// Include the database connection file
include 'db.php.inc.php';

// Call the db_connect function to establish the connection
$db = db_connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>DriveSanaa Rentals</title>
</head>
<body>

<?php 
     include 'header.php'; 
    ?>

<?php include 'leftside.php'; ?>


<div class="container">
    <main class="main-content">
        <h1>Welcome to Our Car Rental Agency</h1><br></br>
        <br></br>
        <br></br>
        <br></br>
        <br></br>
        <h1>Explore our selection of vehicles and find the perfect one for your needs.</h1>
        <br></br>
        <br></br>
        <br></br>
        </form>
        <div class="car-results">
        <?php
      if ($db) {
        $sql = "SELECT * FROM cars ORDER BY price_per_day ASC LIMIT 3";
        $stmt = $db->query($sql);
    
        if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="promotional-item">
                    <h2><?php echo $row['model']; ?></h2>
                    <?php if (!empty($row['image'])) : ?>
                        <figure>
                            <img src="carsImages/<?php echo $row['image']; ?>" alt="Car Image 1">
                            <figcaption><?php echo $row['description']; ?></figcaption>
                        </figure>
                    <?php endif; ?>
                    <?php if (!empty($row['image_2'])) : ?>
                        <figure>
                            <img src="carsImages/<?php echo $row['image_2']; ?>" alt="Car Image 2">
                            <figcaption><?php echo $row['description']; ?></figcaption>
                        </figure>
                    <?php endif; ?>
                    <?php if (!empty($row['image_3'])) : ?>
                        <figure>
                            <img src="carsImages/<?php echo $row['image_3']; ?>" alt="Car Image 3">
                            <figcaption><?php echo $row['description']; ?></figcaption>
                        </figure>
                    <?php endif; ?>
                    <h3>Price per Day: <?php echo $row['price_per_day']; ?> $</h3>
                </div>
                <?php
            }
        }
    }
    
       
        ?>

        </div>
    </main>
</div>

<?php include("footer.php"); ?>
    
</body>
</html>
