<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : ""; /*get user input*/
$number = preg_replace('/[^0-9]/', '', $q); /*get number */

/*search user*/
$users = mysqli_query($conn,
    "SELECT * FROM users
     WHERE user_id='$number'
     OR username LIKE '%$q%'
     OR email LIKE '%$q%'"
);

/*search product*/
$products = mysqli_query($conn,
    "SELECT * FROM products
     WHERE product_id='$number'
     OR product_name LIKE '%$q%'
     OR brand LIKE '%$q%'"
);

/*search order*/
$orders = mysqli_query($conn,
    "SELECT * FROM orders
     WHERE order_id='$number'"
);

/*search repair booking*/
$repairs = mysqli_query($conn,
    "SELECT * FROM repair_bookings
     WHERE booking_id='$number'
     OR device_type LIKE '%$q%'"
);

/*search sell request*/
$sells = mysqli_query($conn,
    "SELECT * FROM sell_requests
     WHERE sell_id='$number'
     OR device_type LIKE '%$q%'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="admin-layout">

<!--side and top bar-->
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
    <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i> Technician Workload</a>
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

                <!-- MESSAGE -->

                <a href="messages.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-message"></i>
                    <span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;">0</span>
                </a>

                <!-- NOTIFICATION -->

                <a href="notifications.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notification</span>
                    <span id="notificationBadge" class="live-badge" style="display:none;">0</span>
                </a>

                <!-- ADMIN -->

                <a href="../profile.php" class="admin-profile">

                    <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>"
                    class="admin-avatar">
                
                    <span>
                        <?= $_SESSION['username']; ?>
                    </span>

                </a>
            </div>
        </div>

    <!--page code-->
    <div class="search-page">

        <h1 class="search-title">
            Search Results for “<?= htmlspecialchars($q); ?>”
        </h1>

        <!--search user-->
        <div class="search-card">

            <h2>Users</h2>

            <?php if(mysqli_num_rows($users) > 0){ ?>

                <?php while($u = mysqli_fetch_assoc($users)){ ?>

                    <a href="users.php" class="result-row">

                        User #<?= $u['user_id']; ?>
                        -
                        <?= $u['username']; ?>
                        (<?= $u['email']; ?>)

                    </a>

                <?php } ?>

            <!--display when no user found-->
            <?php }else{ ?>

                <p class="no-result">No users found.</p>

            <?php } ?>

        </div>

        <div class="search-card">
            <!--search product-->
            <h2>Products</h2>

            <?php if(mysqli_num_rows($products) > 0){ ?>

                <?php while($p = mysqli_fetch_assoc($products)){ ?>

                    <a
                    href="view-product.php?id=<?= $p['product_id']; ?>"
                    class="result-row">

                        Product #<?= $p['product_id']; ?>
                        -
                        <?= $p['product_name']; ?>
                        /
                        <?= $p['brand']; ?>

                    </a>

                <?php } ?>

            <!--display when no product found-->
            <?php }else{ ?>

                <p class="no-result">No products found.</p>

            <?php } ?>

        </div>

        <div class="search-card">

            <h2>Orders</h2>
            <!--search order-->
            <?php if(mysqli_num_rows($orders) > 0){ ?>

                <?php while($o = mysqli_fetch_assoc($orders)){ ?>

                    <a
                    href="order-detail.php?id=<?= $o['order_id']; ?>"
                    class="result-row">

                        Order #ORD<?= $o['order_id']; ?>
                        -
                        RM<?= number_format($o['total_amount'], 2); ?>
                        -
                        <?= $o['status']; ?>

                    </a>

                <?php } ?>

            <!--display when no order found-->
            <?php }else{ ?>

                <p class="no-result">No orders found.</p>

            <?php } ?>

        </div>

        <div class="search-card">
            <!--search repair booking-->
            <h2>Repair Bookings</h2>

            <?php if(mysqli_num_rows($repairs) > 0){ ?>

                <?php while($r = mysqli_fetch_assoc($repairs)){ ?>

                    <a
                    href="booking-detail.php?type=repair&id=<?= $r['booking_id']; ?>"
                    class="result-row">

                        Repair #RB<?= $r['booking_id']; ?>
                        -
                        <?= $r['device_type']; ?>
                        -
                        <?= $r['status']; ?>

                    </a>

                <?php } ?>

            <!--display when no repair booking-->
            <?php }else{ ?>

                <p class="no-result">No repair bookings found.</p>

            <?php } ?>

        </div>

        <div class="search-card">

            <!--search sell request-->
            <h2>Sell Requests</h2>

            <?php if(mysqli_num_rows($sells) > 0){ ?>

                <?php while($s = mysqli_fetch_assoc($sells)){ ?>

                    <a
                    href="booking-detail.php?type=sell&id=<?= $s['sell_id']; ?>"
                    class="result-row">

                        Sell #SB<?= $s['sell_id']; ?>
                        -
                        <?= $s['device_type']; ?>
                        -
                        <?= $s['status']; ?>

                    </a>

                <?php } ?>

            <!--display when no sell request found-->
            <?php }else{ ?>

                <p class="no-result">No sell requests found.</p>

            <?php } ?>

        </div>

    </div>

</main>
</div>

<?php include("../includes/live-badges.php"); ?>

</body>
</html>