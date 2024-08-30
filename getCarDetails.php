<?php
function getCarDetails($pdo, $carId) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :car_id");
        $stmt->execute(['car_id' => $carId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);        
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        return false;
    }
    
}
?>

