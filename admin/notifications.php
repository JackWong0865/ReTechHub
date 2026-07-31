<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*mark read*/

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

    <title>Admin Notifications</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/notifications.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    </head>

    <body>

        <div class="admin-layout">

            <!--side bar-->

            <aside class="sidebar">

                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                    <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                    <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                    <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                    <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                    <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                    <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                    <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
                    <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
                    <a href="messages.php"><i class="fa-solid fa-message"></i>Messages</a>
                    <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

            </aside>

            <main class="admin-main">

                <!--top bar-->

                <div class="admin-top">

                    <form class="admin-search" action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Search user ID, product, booking ID, order ID..."required>

                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <div class="admin-icons">

                        <a href="messages.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-message"></i>
                            <span>Message</span>

                            <span id="messageBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-bell"></i>
                            <span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="../profile.php" class="admin-profile">

                            <img
                            src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                            <span>
                                <?= $_SESSION['username']; ?>
                            </span>

                        </a>

                    </div>

                </div>

                <!--page code-->

                <div class="notification-page">

                    <h1>Notifications</h1>

                    <?php if(mysqli_num_rows($notifications) > 0){ ?>

                        <?php while($n = mysqli_fetch_assoc($notifications)){ ?>

                            <?php

                            /*notification icon*/
                            $icon = "fa-bell";
                            $class = "default";

                            if($n['type'] == "user"){
                                $icon = "fa-user";
                                $class = "blue";
                            }

                            if($n['type'] == "order"){
                                $icon = "fa-box";
                                $class = "green";
                            }

                            if($n['type'] == "booking"){
                                $icon = "fa-screwdriver-wrench";
                                $class = "orange";
                            }

                            if($n['type'] == "repair"){
                                $icon = "fa-screwdriver";
                                $class = "purple";
                            }

                            if($n['type'] == "message"){
                                $icon = "fa-message";
                                $class = "red";
                            }

                            ?>

                            <div class="notification-card">

                                <div class="notification-icon <?= $class; ?>">

                                    <i class="fa-solid <?= $icon; ?>"></i>

                                </div>

                                <div class="notification-content">

                                    <h3>
                                        <?= $n['title']; ?>
                                    </h3>

                                    <p>
                                        <?= $n['message']; ?>
                                    </p>

                                    <span>
                                        <?= $n['created_at']; ?>
                                    </span>

                                </div>

                            </div>

                        <?php } ?>

                    <?php }else{ ?>

                        <!--display when no notification-->
                        <div class="empty-notification">

                            <i class="fa-solid fa-bell-slash"></i>

                            <h2>No Notifications</h2>

                            <p>No notifications yet.</p>

                        </div>

                    <?php } ?>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>
