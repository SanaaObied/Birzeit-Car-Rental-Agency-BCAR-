<?php
function getUserDetails($conn, $userId) {
    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            echo "Debug: No user found with user_id: " . $userId . "<br>";
        }
        return $result;
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        return false;
    }
}
?>

