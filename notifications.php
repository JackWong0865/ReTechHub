<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*update notification status*/
mysqli_query(
    $conn,
    "UPDATE notifications
     SET is_read=1
     /*only update current user's notification*/
     WHERE user_id='$user_id'"
);

/*check notification info*/
$notifications = mysqli_query(
    $conn,
    "SELECT *
     FROM notifications
     /*only check current user's notification*/
     WHERE user_id='$user_id'
     /*Latest notifications displayed first.*/
     ORDER BY notification_id DESC"
);
?>

<link rel="stylesheet" href="assets/css/notifications.css">

<div class="notification-page">

    <h1>Notifications</h1>

    <?php if(mysqli_num_rows($notifications) > 0){ ?>

        <?php while($n = mysqli_fetch_assoc($notifications)){ ?>

            <div class="notification-card">

                <div class="notification-icon">
                    <i class="fa-solid fa-bell"></i>
                </div>

                <div class="notification-content">

                    <h3><?= $n['title']; ?></h3>

                    <p><?= $n['message']; ?></p>

                    <span><?= $n['created_at']; ?></span>

                </div>

            </div>

        <?php } ?>

    <?php }else{ ?>

        <!--display when no notification-->
        <div class="empty-notification">
        
            <i class="fa-solid fa-bell-slash"></i>

            <h2>No Notifications</h2>

        </div>

    <?php } ?>

</div>