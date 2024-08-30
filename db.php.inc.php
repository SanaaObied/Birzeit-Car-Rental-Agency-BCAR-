<?php
define('DBHOST', 'localhost');
define('DBNAME', 'bcar');
define('DBUSER', 'root');
define('DBPASS', '');

function db_connect($dbhost = DBHOST, $dbname = DBNAME, $username = DBUSER, $password = DBPASS) {
    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
