<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*update order status*/
if(isset($_POST['update_status'])){

    /*get order info*/
    $order_id = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query(
        $conn,
        "UPDATE orders
         SET status='$status'
         WHERE order_id='$order_id'"
    );

    $order = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM orders
             WHERE order_id='$order_id'"
        )
    );

    /*save update success message*/
    $_SESSION['order_status_success'] =
        "Order #ORD$order_id status updated to $status successfully.";

    header("Location: orders.php");
    exit();

    /*get notification recipient*/
    $notify_user = $order['user_id'];

    /*notification content*/
    $title = "Order Status Updated";

    $message = "Your order #ORD$order_id status has been updated to $status.";

    mysqli_query(
        $conn,
        "INSERT INTO notifications
        (user_id, title, message, type)
        VALUES
        ('$notify_user','$title','$message','order')"
    );

    header("Location: orders.php");
    exit();
}

/*get active orders only*/
$orders = mysqli_query(
    $conn,
    "SELECT 
        o.*,
        u.username,
        u.email,
        u.phone
     FROM orders o
     LEFT JOIN users u
        ON o.user_id = u.user_id
     WHERE o.status NOT IN ('Completed', 'Cancelled')
     ORDER BY o.order_id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders - ReTech Hub</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-orders.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="admin-layout">
    <!--side and top bar-->
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

        <div class="admin-top">

            <form class="admin-search" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search user ID, product, booking ID, order ID..."required>

                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <div class="admin-icons">

                <a href="messages.php" class="top-icon badge-wrap">

                    <i class="fa-solid fa-message"></i><span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="notifications.php" class="top-icon badge-wrap">

                    <i class="fa-solid fa-bell"></i><span>Notification</span>

                    <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="../profile.php" class="admin-profile">
                    <img
                    src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default-avatar.png'; ?>"
                    class="admin-avatar">

                    <span>
                        <?= $_SESSION['username']; ?>
                    </span>
                </a>

            </div>

        </div>

        <!--page code-->
        <div class="orders-header">
            <div>
                <h1>Order Management</h1>
                <p>View and manage all customer orders.</p>
            </div>
        </div>

        <div class="orders-card">

            <!--orders table-->
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                <!--display data in table-->
                <?php if(mysqli_num_rows($orders) > 0){ ?>

                    <?php while($o = mysqli_fetch_assoc($orders)){ ?>

                        <tr>
                            <td>#ORD<?= $o['order_id']; ?></td>

                            <td>
                                <b><?= $o['username']; ?></b><br>
                                <small><?= $o['full_name']; ?></small>
                            </td>

                            <td>
                                <?= $o['phone']; ?><br>
                                <small><?= $o['email']; ?></small>
                            </td>

                            <td>
                                <b>RM<?= number_format($o['total_amount'], 2); ?></b>
                            </td>

                            <td><?= $o['payment_method']; ?></td>

                            <td>
                                <span class="order-status <?= strtolower($o['status']); ?>">
                                    <?= $o['status']; ?>
                                </span>
                            </td>

                            <td><?= $o['created_at']; ?></td>

                            <td class="order-actions">

                                <a
                                href="order-detail.php?id=<?= $o['order_id']; ?>"
                                class="view-btn">

                                    View

                                </a>

                                <form method="POST">

                                    <input
                                    type="hidden"
                                    name="order_id"
                                    value="<?= $o['order_id']; ?>">

                                    <select name="status">
                                        <option value="Pending" <?= $o['status']=='Pending'?'selected':''; ?>>Pending</option>
                                        <option value="Processing" <?= $o['status']=='Processing'?'selected':''; ?>>Processing</option>
                                        <option value="Shipped" <?= $o['status']=='Shipped'?'selected':''; ?>>Shipped</option>
                                        <option value="Completed" <?= $o['status']=='Completed'?'selected':''; ?>>Completed</option>
                                        <option value="Cancelled" <?= $o['status']=='Cancelled'?'selected':''; ?>>Cancelled</option>
                                    </select>

                                    <button type="submit" name="update_status">
                                        Update
                                    </button>

                                </form>

                            </td>
                        </tr>

                    <?php } ?>
                
                    <!--display this when no order-->
                <?php }else{ ?>

                    <tr>
                        <td colspan="8" class="empty-row">
                            No orders found.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </main>

</div>

<?php include("../includes/live-badges.php"); ?>

<!--order status update window-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['order_status_success'])){ ?>

<script>
Swal.fire({
    icon: "success",
    title: "Order Status Updated",
    text: <?= json_encode(
        $_SESSION['order_status_success']
    ); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>

<?php
unset($_SESSION['order_status_success']);
}
?>

</body>
</html>