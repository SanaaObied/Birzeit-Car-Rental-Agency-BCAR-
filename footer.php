<footer class="footer">
    <div class="footer-logo">
        <img src="images/logo_rent2.png" alt="Smaller Logo" class="logo-img">
    </div>
    <div class="copywrite">
        &copy; <?php echo date("Y"); ?> Your Agency Name. All rights reserved.
    </div>
    <div class="contact-info">
        Address: <?php echo isset($_SESSION['flat']) ? 'flat ' . $_SESSION['flat'] . ', ' : ''; ?>
        <?php echo isset($_SESSION['street']) ? 'Street: ' . $_SESSION['street'] . ', ' : 'Your Street'; ?>
        <?php echo isset($_SESSION['city']) ? 'City: ' . $_SESSION['city'] . ', ' : 'Your City'; ?>
        <?php echo isset($_SESSION['country']) ? 'Country: ' . $_SESSION['country'] : 'Your Country'; ?>
        <br>
        Email: <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'carRent@example.com'; ?>
        <br>
        Phone: <?php echo isset($_SESSION['telephone']) ? $_SESSION['telephone'] : '+123456789'; ?>
    </div>
</footer>

