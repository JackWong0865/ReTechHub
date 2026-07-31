<?php
session_start();
include("../includes/db.php");

/*check admin login*/
if(
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'admin'
){
    header("Location: ../login.php");
    exit();
}

/*get completed and cancelled orders*/
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
     WHERE o.status IN ('Completed', 'Cancelled')
     ORDER BY o.order_id DESC"
);

/*check query error*/
if(!$orders){
    die("Unable to retrieve order history: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
    <head>

        <title>Order History - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin-orders.css">

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        >

    </head>

    <body>

        <div class="admin-layout">

            <!--sidebar-->
            <aside class="sidebar">

                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php" class="active"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
                <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i> Messages</a>
                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

            </aside>

            <main class="admin-main">

                <!--top bar-->
                <div class="admin-top">

                    <form class="admin-search" action="search.php" method="GET" >

                        <input type="text" name="q"
                            placeholder="Search user ID, product, booking ID, order ID..." required >

                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                    </form>

                    <div class="admin-icons">

                        <a href="messages.php" class="top-icon badge-wrap" >
                            <i class="fa-solid fa-message"></i>
                            <span>Message</span>

                            <span id="messageBadge" class="live-badge" style="display:none;" >
                                0
                            </span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap" >
                            <i class="fa-solid fa-bell"></i>
                            <span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;" >
                                0
                            </span>
                        </a>

                        <a href="../profile.php" class="admin-profile" >

                            <img
                                src="../<?= htmlspecialchars(
                                    $_SESSION['profile_image']
                                    ?? 'uploads/profile/default-avatar.png'
                                ); ?>"
                                class="admin-avatar"
                            >

                            <span> <?= htmlspecialchars($_SESSION['username']); ?> </span>

                        </a>

                    </div>

                </div>

                <!--page header-->
                <div class="orders-header">

                    <div>
                        <h1>Order History</h1>

                        <p> View completed and cancelled customer orders.</p>
                    </div>

                </div>

                <!--history table-->
                <div class="orders-card">

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

                        <?php if(mysqli_num_rows($orders) > 0){ ?>

                            <?php while($o = mysqli_fetch_assoc($orders)){ ?>

                                <tr>

                                    <td> #ORD<?= (int)$o['order_id']; ?> </td>

                                    <td>

                                        <b> <?= htmlspecialchars( $o['username'] ?? 'Unknown User' ); ?> </b>

                                        <?php if(!empty($o['full_name'])){ ?>
                                            <br>
                                            <small> <?= htmlspecialchars( $o['full_name'] ); ?> </small>
                                        <?php } ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars( $o['phone'] ?? '-' ); ?>
                                        <br>
                                        <small> <?= htmlspecialchars( $o['email'] ?? '-' ); ?> </small>

                                    </td>

                                    <td>

                                        <b> RM<?= number_format( (float)$o['total_amount'], 2 ); ?> </b>

                                    </td>

                                    <td> <?= htmlspecialchars( $o['payment_method'] ?? '-' ); ?> </td>

                                    <td>

                                        <span class="order-status <?= strtolower(
                                            htmlspecialchars($o['status'])
                                        ); ?>">

                                            <?= htmlspecialchars($o['status']); ?>

                                        </span>

                                    </td>

                                    <td> <?= htmlspecialchars($o['created_at']); ?> </td>

                                    <td class="order-actions">

                                        <a href="order-history-detail.php?id=<?= (int)$o['order_id']; ?>"class="view-btn">View</a>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php }else{ ?>

                            <!--display when no history found-->
                            <tr>

                                <td colspan="8" class="empty-row">
                                    No completed or cancelled orders found.
                                </td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>