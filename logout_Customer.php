<<?php
session_start();
include "db.php.inc.php";

// Get session cookie parameters
$cookieInfo = session_get_cookie_params();

// Delete session cookie
if (empty($cookieInfo['domain']) && empty($cookieInfo['secure'])) {
    setcookie(session_name(), '', time() - 3600, $cookieInfo['path']);
} elseif (empty($cookieInfo['secure'])) {
    setcookie(session_name(), '', time() - 3600, $cookieInfo['path'], $cookieInfo['domain']);
} else {
    setcookie(session_name(), '', time() - 3600, $cookieInfo['path'], $cookieInfo['domain'], $cookieInfo['secure']);
}

// Unset session cookie
unset($_COOKIE[session_name()]);

// Store referrer for redirection
$t = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : 'login.php';

// Destroy session
session_destroy();

// Include the header
include "header_Customer.php";
include 'leftside_Customer.php';

// Display logout message
echo 'Goodbye and come back soon!';

// Include the footer
include "footer.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>