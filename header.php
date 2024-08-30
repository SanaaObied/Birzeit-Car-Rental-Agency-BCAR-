

<header class="header">
    <div class="logo">
        <a href="index.php">
            <img src="images/logo_rent2.png" alt="logo" class="logo-img">
        </a>
    </div>
    <div class="agency-name">DriveSanaa Rentals</div>
    <nav class="nav-menu">
        <a href="about.php">About Us</a>
        <a href="basket.php">
        <img src="images/shoping.png" alt="Shopping Basket">
        </a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>

