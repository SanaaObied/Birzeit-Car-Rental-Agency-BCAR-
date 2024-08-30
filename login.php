<?php
session_start();
include 'db.php.inc.php';

$PHP_SELF = $_SERVER['PHP_SELF'];
do_authentication();

function do_authentication() {
    global $PHP_SELF;

    if (!isset($_POST['userid'])) {
        login_form();
        exit;
    } else {
        $_SESSION['userpassword'] = $_POST['userpassword'];
        $_SESSION['userid'] = $_POST['userid']; // Set user ID in session
        $userid = $_POST['userid']; // Get user ID from form
        $userpassword = $_POST['userpassword']; // Get user password from form

        $pdo = db_connect(); // Establish database connection

        if (!$pdo) {
            echo "Error: Database connection failed.";
            exit;
        }

        $query = "SELECT * FROM user WHERE user_id = :userid AND password = :userpassword";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['userid' => $userid, 'userpassword' => $userpassword]);

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            error_message("Authorization failed. You must enter a valid userid and password combo.");

        } else {
            $_SESSION['user_id'] = $userData['user_id']; // Set user ID in session

            // Determine user type and set it in the session
            if ($userData['type'] == 'Customer') {
                $_SESSION['type'] = 1;
            } elseif ($userData['type'] == 'Manager') {
                $_SESSION['type'] = 2;
            } else {
                $_SESSION['type'] = 3; // Default type
            }

            // Redirect based on user type
            if ($_SESSION['type'] == 1) {
                header("Location: index_employees.php");
                exit;
            } elseif ($_SESSION['type'] == 2) {
                header("Location: index.php");
                exit;
            } else {
                header("Location: default_page.php");
                exit;
            }
        }
    }
}
// Rest of your code...



function login_form() {
    global $PHP_SELF;
    ?>
    <html>
    <head>
        <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <?php 
     include 'header.php'; 
    ?>

<?php include 'leftside.php'; ?>
</head>
    <body>
       <div class="form-container2">
    <h1>Please log in to access the page you requested.</h1>
    <form method="POST" action="<?php echo htmlspecialchars($PHP_SELF); ?>">
        <table class="form-table2">
            <tr>
                <th>ID</th>
                <td><input type="text" name="userid" class="form-input2" /></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="userpassword" class="form-input2" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login" name="submit" class="form-submit2" /></td>
            </tr>
        </table>
    </form>
</div>
<?php include("footer.php"); ?>
</body>
</html>
    <?php
}

function error_message($message) {
    ?>
    <html>
    <head>
        <title>Error</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <?php include 'header.php'; ?>
        <?php include 'leftside.php'; ?>
    </head>
    <body>
    <div class="error-container">
        <p>Error: <?php echo $message; ?></p>
        <p>You must enter a valid userid and password combo.</p>
        <p><a href='login.php'>Login</a></p>
        <p>If you're not a member yet, click <a href='signup.php'>here</a> to signup.</p>
    </div>
    <?php include("footer.php"); ?>
    </body>
    </html>
    <?php
    exit;
}










