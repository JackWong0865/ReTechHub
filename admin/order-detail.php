<?php
session_start();
include("../includes/db.php");

/*check admin permission*/
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*check order id*/
if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

/*get order id*/
$order_id = (int)$_GET['id'];

/*check order info*/
$order_query = mysqli_query(
    $conn,
    "SELECT o.*, u.username, u.email
     FROM orders o
     LEFT JOIN users u ON o.user_id = u.user_id
     WHERE o.order_id='$order_id'"
);

/*check if the order exists*/
if(!$order_query || mysqli_num_rows($order_query) == 0){
    die("Order not found.");
}

/*get order info*/
$order = mysqli_fetch_assoc($order_query);

/*check the items purchased in the order*/
$items = mysqli_query(
    $conn,
    "SELECT oi.*, p.product_name, p.brand, p.condition_type
     FROM order_items oi
     JOIN products p ON oi.product_id = p.product_id
     WHERE oi.order_id='$order_id'"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Order Detail</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin-orders.css">

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
                <a href="orders.php" class="active"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
                <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i> Messages</a>
                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

            </aside>

            <main class="admin-main">

                <!--page code-->
                <div class="order-detail-card">

                    <a href="orders.php" class="back-link">
                        &lt; Back to Orders
                    </a>

                    <h1>Order #ORD<?= $order['order_id']; ?></h1>

                    <div class="detail-grid">

                        <div class="detail-box">
                            <h3>Customer Info</h3>
                            <p><b>Username:</b> <?= $order['username']; ?></p>
                            <p><b>Name:</b> <?= $order['full_name']; ?></p>
                            <p><b>Email:</b> <?= $order['email']; ?></p>
                            <p><b>Phone:</b> <?= $order['phone']; ?></p>
                        </div>

                        <div class="detail-box">
                            <h3>Order Info</h3>
                            <p><b>Status:</b> <?= $order['status']; ?></p>
                            <p><b>Payment:</b> <?= $order['payment_method']; ?></p>
                            <p><b>Subtotal:</b> RM<?= number_format($order['subtotal'], 2); ?></p>
                            <p><b>Delivery Fee:</b> RM<?= number_format($order['delivery_fee'], 2); ?></p>
                            <p><b>Total:</b> RM<?= number_format($order['total_amount'], 2); ?></p>
                        </div>

                        <div class="detail-box full">
                            <h3>Delivery Address</h3>
                            <p><?= $order['address']; ?></p>
                        </div>

                        <div class="detail-box full">
                            <h3>Order Items</h3>

                            <?php while($item = mysqli_fetch_assoc($items)){ ?>

                                <?php
                                $img_query = mysqli_query(
                                    $conn,
                                    "SELECT image_path FROM product_images
                                    WHERE product_id='".$item['product_id']."'
                                    LIMIT 1"
                                );

                                $img = mysqli_fetch_assoc($img_query);

                                $image_path = $img
                                    ? "../" . $img['image_path']
                                    : "../assets/images/products/default-product.png";
                                ?>

                                <div class="order-item-row">

                                    <img src="<?= $image_path; ?>">

                                    <div>
                                        <h4><?= $item['product_name']; ?></h4>
                                        <p><?= $item['brand']; ?> • <?= $item['condition_type']; ?></p>
                                        <span>Qty: <?= $item['quantity']; ?></span>
                                    </div>

                                    <b> RM<?= number_format($item['price'] * $item['quantity'], 2); ?> </b>

                                </div>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            </main>

        </div>

    </body>
</html>