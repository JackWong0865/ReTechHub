<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$notify_count = 0;
$cart_count = 0;

if(isset($_SESSION['user_id']) && isset($conn)){

    /*get current user id*/
    $user_id = $_SESSION['user_id'];

    /*check the number of unread notifications*/
    $unread_notifications = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT COUNT(*) AS total
             FROM notifications
             WHERE user_id='$user_id'
             AND is_read=0"
        )
    );

    $notify_count = $unread_notifications['total'];

    /*check the number of items in the cart*/
    $cart_query = mysqli_query(
        $conn,
        "SELECT SUM(quantity) AS total
         FROM cart
         WHERE user_id='$user_id'"
    );

    /*get shopping cart quantity*/
    $cart_result = mysqli_fetch_assoc($cart_query);

    $cart_count = $cart_result['total'] ?? 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ReTech Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
    href="assets/css/style.css">
</head>

<body>


    <!--user navbar-->
    <header class="main-header">

        <!--top bar-->
        <div class="top-bar">

            <div class="container top-info">

                <span>
                    <i class="fa-solid fa-phone"></i>
                    012-3456789
           
                    <i class="fa-solid fa-envelope"></i>
                    retechhubofficial@gmail.com
                
                    <i class="fa-solid fa-location-dot"></i>
                    Taman Sri Tebrau
                </span>

            </div>

        </div>

        <!--Logo Search Login-->

        <div class="header-middle">

            <div class="container middle-content">

                <div class="logo-section">

                    <img src="assets/images/logo.png" class="logo-img" alt="Logo">

                </div>

                <div class="search-section">

                    <form action="marketplace.php" method="GET">

                        <input type="text" name="search" placeholder="Search Here" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

                        <button type="submit">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </button>

                    </form>

                </div>

                <div class="login-section">
                        
                    <?php if(isset($_SESSION['user_id'])){ ?>

                        <a href="cart.php" class="header-icon cart-icon-wrap">
                            <i class="fa-solid fa-cart-shopping"></i>
                                
                            <span>My Cart</span>
                                
                            <?php if($cart_count > 0){ ?>
                                <span class="cart-badge">
                                    <?= $cart_count; ?>
                                </span>

                            <?php } ?>

                        </a>

                        <a href="messages.php" class="header-icon message-icon-wrap">
                            <i class="fa-solid fa-message"></i>
                            <span>Message</span>

                            <span id="messageBadge" class="message-badge" style="display:none;">
                                0
                            </span>
                        </a>

                        <a href="notifications.php" class="notification-btn">

                            <i class="fa-solid fa-bell"></i>
                            <span>Notification</span>

                            <span id="notificationBadge" class="notify-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <div class="user-dropdown">

                            <div class="user-info">

                                <img src="<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>" class="user-avatar">

                                <div class="user-details">

                                    <span class="user-name">
                                        <?= $_SESSION['username']; ?>
                                    </span>

                                </div>

                                <i class="fa-solid fa-chevron-down"></i>

                            </div>

                            <div class="dropdown-menu-custom">

                                <a href="profile.php">
                                    <i class="fa-solid fa-user"></i>
                                    My Profile
                                </a>

                                <a href="change-password.php">
                                    <i class="fa-solid fa-key"></i>
                                    Change Password
                                </a>

                                <a href="logout.php">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    Logout
                                </a>

                            </div>

                        </div>

                    <?php } else { ?>

                        <a href="login.php" class="login-btn">
                            Login
                        </a>

                    <?php } ?>

                </div>

            </div>

        </div>

        <!--Menu-->
        <nav class="navbar-custom">

            <ul class="menu">

                <li><a href="index.php">Home</a></li>

                <li><a href="marketplace.php">Marketplace</a></li>

                <li><a href="booking.php">Sell / Repair Device</a></li>

                <li><a href="my-order.php">My Order</a></li>

                <li><a href="my-booking.php">My Booking</a></li>

                <li><a href="about.php">About Us</a></li>

            </ul>

        </nav>

    </header>

    <script>

    /*updates to Message and Notification Badge*/
    function loadLiveBadges(){

        fetch("fetch-message-count.php")
        .then(response => response.text())
        .then(count => {
            const badge = document.getElementById("messageBadge");

            if(!badge) return;

            count = parseInt(count);

        if(count > 0){
                badge.style.display = "flex";
                badge.innerText = count;
            }else{
                badge.style.display = "none";
            }
        });

        fetch("fetch-notification-count.php")
        .then(response => response.text())
        .then(count => {
            const badge = document.getElementById("notificationBadge");

            if(!badge) return;

            count = parseInt(count);

            if(count > 0){
                badge.style.display = "flex";
                badge.innerText = count;
            }else{
                badge.style.display = "none";
            }
        });
    }

    setInterval(loadLiveBadges, 2000);
    loadLiveBadges();
    </script>

    <script>

    /*update shopping cart badge*/
    function loadCartBadge(){

        fetch("fetch-cart-count.php")
        .then(response => response.text())
        .then(count => {

            count = parseInt(count);

            let badge = document.querySelector(".cart-badge");

            if(count > 0){

                if(!badge){

                    const cartBtn =
                    document.querySelector(".cart-icon-wrap");

                    badge = document.createElement("span");

                    badge.className = "cart-badge";

                    cartBtn.appendChild(badge);
                }

                badge.innerText = count;

            }else{

                if(badge){
                    badge.remove();
                }
            }
        });
    }

    setInterval(loadCartBadge, 2000);

    loadCartBadge();
    </script>
</body>