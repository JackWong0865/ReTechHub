<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*mark as read*/

mysqli_query(
    $conn,
    "UPDATE notifications
     SET is_read=1
     WHERE user_id='$user_id'"
);

/*get notification*/

$notifications = mysqli_query(
    $conn,
    "SELECT *
     FROM notifications
     WHERE user_id='$user_id'
     ORDER BY notification_id DESC"
);
?>

<!DOCTYPE html>

<html>
    <head>
        
    <title>Technician Notifications</title>

    <link rel="stylesheet" href="../assets/css/technician.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="tech-layout">

            <!--side bar-->
            <aside class="tech-sidebar">

                <img src="../assets/images/logo.png" class="tech-logo">

                <a href="dashboard.php">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

                <a href="assigned-repairs.php">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    Assigned Repairs
                </a>

                <a href="repair-requests.php">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Repair Requests
                </a>

                <a href="repair-history.php">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Repair History
                </a>

                <a href="messages.php">
                    <i class="fa-solid fa-message"></i>
                    Messages
                </a>

                <a href="../logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </aside>

            <main class="tech-main">

                <!--top bar-->

                <div class="tech-top">

                    <div class="tech-search">
                        <form action="search.php" method="GET">
                            <input type="text" name="q" placeholder="Search booking ID, customer, device..." required>
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <div class="tech-icons">

                        <a href="messages.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-message"></i>
                            <span>Message</span>

                            <span id="messageBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap active-top-icon">

                            <i class="fa-solid fa-bell"></i>
                            <span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="../profile.php" class="tech-profile">

                            <img
                            src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                            <span>
                                <?= $_SESSION['username']; ?>
                            </span>

                        </a>

                    </div>

                </div>

                <!--page code-->

                <div class="notification-page tech-notification-page">

                    <h1>Notifications</h1>

                    <?php if(mysqli_num_rows($notifications) > 0){ ?>

                        <?php while($n = mysqli_fetch_assoc($notifications)){ ?>

                            <div class="notification-card">

                                <div class="notification-icon">
                                    <i class="fa-solid fa-bell"></i>
                                </div>

                                <div class="notification-content">

                                    <h3> <?= $n['title']; ?> </h3>
                                    <p> <?= $n['message']; ?> </p>
                                    <span> <?= $n['created_at']; ?> </span>

                                </div>

                            </div>

                        <?php } ?>

                    <?php }else{ ?>

                        <div class="empty-notification">

                            <i class="fa-solid fa-bell-slash"></i>
                            <h2>No Notifications</h2>
                            <p> You have no notifications right now. </p>

                        </div>

                    <?php } ?>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>
