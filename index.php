<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php session_start(); ?>
<header>
    <a href="#" class="logo">BLUESHIPIN</a>
    <nav>
        <a href="#home" class="active">Home</a>
        <a href="#about">About</a>
        <a href="#service">Service</a>
        <a href="#help">Help</a>
        <a href="#cancel">Cancellation</a>
    </nav>
    <div class="auth-buttons">
    <?php if (isset($_SESSION['username'])): ?> 
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn sign-out">Sign Out</a>
        <?php else: ?>
            <div class="dropdown">
                <button class="btn sign-in">Sign In</button>
                <div class="dropdown-content">
                    <a href="parent_signin.php">Parent Sign In</a>
                    <a href="driver_signin.php">Driver Sign In</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn login">Login</button>
                <div class="dropdown-content">
                    <a href="parent_login.php">Parent Login</a>
                    <a href="driver_login.php">Driver Login</a>
                    <a href="admin_login.php">Admin Login</a>
                </div>
            </div>
            <a href="logout.php" class="btn sign-out">Logout</a>
    </div>
    <?php endif; ?>
</header>

      <section id="home">
        <div class="content">
            <h1>College Bus Tracking System</h1>
            <p class="short-note">Easily book your bus tickets online with just a few clicks. Save time and avoid the hassle of long queues by reserving your seat in advance.</p>
            <button class="btn register-bus" onclick="window.location.href='parent_login.php';">Register Your Bus</button>
            <button class="btn track-bus" onclick="window.location.href='parent_login.php';">Track Your Bus</button>

        </div> 
      </section>
      <section id="about">
        <div class="about-content">
            <h2>College bus tracking on Blueshipin </h2>
            <p>Travellers love exploring India by road. The spellbinding landscapes provide a deeper insight into the culture and beauty of India. Unlike flights, travelling by road is much more intimate. 
                That's why buses are among the most preferred ways among travellers to explore India. Choosing the right online platform for bus booking is as important as choosing the right traveling partner for your trip. Blueshipin is one the India's biggest and most trusted bus booking online platforms. 
                You can make your bus booking on Blueshipin and travel to your preferred destination.If you are confused about how to book bus online, Blueshipin can help you with that. Blueshipin's user-friendly interface helps you to do your bus booking from the comfort of your home. You can easily navigate 
                through their website and app and book a bus ticket without any hassle.You can find all the required information regarding the buses, their prices and the bus routes on the official website of Blueshipin. to book their buses. The bus ticket booking system has been made quite easy, as you can make your bookings on Blueshipin within a few minutes.</p>
        </div>
      </section>
      <section id="service">
        <div class="service-content">
          <h2>College Bus tracking services by BlueShipin</h2>
          <p>BlueShipin is a one-stop destination for online booking bus. Bus booking can be done from anywhere and Blueshipin ensures that your bus journey is a smooth one.
             While making an online bus reservation, you might come up with a lot of questions. Keeping that in mind, we are providing some important information here that will help you in making your bus ticket booking more effectively.<br><br>
            *<strong>E-Tickets:</strong> Blueshipin provides e-tickets to passengers. When you make an online booking, an e-ticket is generated which can be downloaded. All you have to do is show that e-ticket in order to board your bus. Although, to have a stress-free journey, it is always advisable to carry the print of your e-tickets.<br><br>
            *<strong>Refunds:</strong> If you book bus online on Blueshipin and for some reason, either you have to cancel your trip or the bus trip was cancelled by the bus provider, then the refund process is initiated. Though, please note that if you are cancelling your trip at the last moment, then you will not be eligible for a refund.<br><br>
            *<strong>Cancel Booked Tickets:</strong> Steps to cancel your booked ticket are easy to follow. All you have to do is to go to the cancel tab on Blueshipin home page and put in the necessary details such as your booking ID and ticket number. After selecting the ticket, simply click on the cancel button.</p>
        </div>
      </section>
      <section id="help">Help</section>
      <section id="cancel">
        <div class="cancel-content">
            <h2>Cancellation of tickets</h2>
            <p>Steps to cancel booked tickets are easy to follow.<br>
               1. Click on cancel book ticket button.<br>
               2. Put in the necessary details such as your booking ID and ticket number.<br>
               3. Apply for refund and tickets are cancelled.<br>
            </p>
            <button class="btn cancel-now">Cancel book ticket</button>
        </div>
      </section>
      <footer>
        <div class="footer-content">
            <h3>Contact Us</h3>
            <p>Get in touch with us for any queries or support.</p>
            <ul class="contact-info">
                <li><strong>Email:</strong> <i class="fas fa-envelope"></i> support@blueshipin.com</li>
                <li><strong>Phone:</strong> <i class="fas fa-phone"></i> +91 9876543210</li>
                <li><strong>WhatsApp:</strong> <i class="fab fa-whatsapp"></i> +91 9876543210</li>
                <li><strong>Instagram:</strong> <i class="fab fa-instagram"></i> <a href="https://instagram.com/blueshipin" target="_blank">@blueshipin</a></li>
                <li><strong>Facebook:</strong> <i class="fab fa-facebook"></i> <a href="https://facebook.com/blueshipin" target="_blank">@blueshipin</a></li>
                <li><strong>Address:</strong> <i class="fas fa-map-marker-alt"></i> 123 Blueshipin Road, New Delhi, India</li>
                <li><strong>Working Hours:</strong> <i class="fas fa-clock"></i> Monday to Friday, 9 AM to 6 PM IST</li>
            </ul>
    
            <div class="about-section">
                <h4>About Us</h4>
                <p>We are committed to providing excellent service and support to our customers. Our mission is to...</p>
            </div>
    
            <div class="links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/terms-of-service">Terms of Service</a></li>
                    <li><a href="/faq">FAQ</a></li>
                </ul>
            </div>
    
            
        </div>
    </footer>
    
    


</body>
</html>

