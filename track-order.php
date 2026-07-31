<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
    header("Location: my-order.php");
    exit();
}

$order_id = (int)$_GET['id'];

$order_query = mysqli_query(
    $conn,
    "SELECT * FROM orders
     WHERE order_id='$order_id'
     AND user_id='$user_id'"
);

if(mysqli_num_rows($order_query) == 0){
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_query);

$status_steps = ["Pending", "Processing", "Shipped", "Completed"];
$current_index = array_search($order['status'], $status_steps);
?>

<link rel="stylesheet" href="assets/css/track-order.css">

<div class="track-page">

    <a href="my-order.php" class="back-link">
        &lt; Back to My Orders
    </a>

    <div class="track-card">

        <div class="track-header">
            <div>
                <h1>Track Order</h1>
                <p>Order #ORD<?= $order['order_id']; ?></p>
            </div>

            <span class="track-status <?= strtolower($order['status']); ?>">
                <?= $order['status']; ?>
            </span>
        </div>

        <div class="track-steps">

            <?php foreach($status_steps as $index => $step){ ?>

                <div class="step <?= $index <= $current_index ? 'active' : ''; ?>">

                    <div class="circle">
                        <?php if($index <= $current_index){ ?>
                            <i class="fa-solid fa-check"></i>
                        <?php }else{ ?>
                            <?= $index + 1; ?>
                        <?php } ?>
                    </div>

                    <h3><?= $step; ?></h3>

                </div>

            <?php } ?>

        </div>

        <div class="order-info-box">

            <h2>Delivery Information</h2>

            <p><b>Receiver:</b> <?= $order['full_name']; ?></p>
            <p><b>Phone:</b> <?= $order['phone']; ?></p>
            <p><b>Address:</b> <?= $order['address']; ?></p>
            <p><b>Payment:</b> <?= $order['payment_method']; ?></p>
            <p><b>Total:</b> RM<?= number_format($order['total_amount'], 2); ?></p>

        </div>

    </div>

</div>