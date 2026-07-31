<?php
session_start();
include("../includes/db.php");

/*check if the administrator is logged in*/
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
/*calculate the total number of users*/
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];

/*calculate the total number of products*/
$totalListings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

/*calculate the total number of the repair booking*/
$totalRepairs = 0;
$checkRepair = mysqli_query($conn, "SHOW TABLES LIKE 'repair_bookings'");
if(mysqli_num_rows($checkRepair) > 0){
    $totalRepairs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM repair_bookings"))['total'];
}

/*calculate total sales*/
$totalRevenue = 0;
$checkOrders = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
if(mysqli_num_rows($checkOrders) > 0){
    $revenueRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders"));
    $totalRevenue = $revenueRow['total'] ?? 0;
}

/*count the number of orders*/
$totalOrders = 0;
$checkOrders = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
if(mysqli_num_rows($checkOrders) > 0){
    $totalOrders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM orders"))['total'];
}

/*Get the latest products*/
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY product_id DESC LIMIT 6");

/*number of completed orders*/
$completedOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='Completed'" ))['total'];

/*total sales amount*/
$totalSales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders WHERE status!='Cancelled'" ))['total'] ?? 0;

/*number of sales requests*/
$totalSellRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM sell_requests" ))['total'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - ReTech Hub</title>
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
    
    <body>

        <div class="admin-layout">

            <aside class="sidebar">
                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
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
                        
                            <span> <?= $_SESSION['username']; ?> </span>

                        </a>
                    </div>
                </div>

                <h1>Admin Dashboard</h1>
                <p class="welcome">Welcome back, Admin! Here's what's happening on the platform.</p>

                <div class="stats-grid">

                    <div class="stat-card">
                        <i class="fa-solid fa-users blue"></i>
                        <div>
                            <p>Total Users</p>
                            <h2><?= $totalUsers; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-bag-shopping green"></i>
                        <div>
                            <p>Total Listings</p>
                            <h2><?= $totalListings; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-clipboard-list purple"></i>
                        <div>
                            <p>Total Repairs</p>
                            <h2><?= $totalRepairs; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-cart-shopping orange"></i>
                        <div>
                            <p>Total Orders</p>
                            <h2><?= $totalOrders; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-money-bill-wave blue"></i>
                        <div>
                            <p>Total Sales</p>
                            <h2>RM<?= number_format($totalSales, 2); ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-circle-check green"></i>
                        <div>
                            <p>Completed Orders</p>
                            <h2><?= $completedOrders; ?></h2>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-mobile-screen purple"></i>
                        <div>
                            <p>Sell Requests</p>
                            <h2><?= $totalSellRequests; ?></h2>
                        </div>
                    </div>

                </div>

                <div class="quick-grid">

                    <a href="upload.php" class="quick-card highlight">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <h3>Upload Product</h3>
                        <p>Add a new product to the marketplace</p>
                        <button>Upload Now</button>
                    </a>

                    <a href="products.php" class="quick-card">
                        <i class="fa-solid fa-tag"></i>
                        <h3>Manage Listings</h3>
                        <p>View and manage marketplace listings</p>
                        <button>Manage Listings</button>
                    </a>

                    <a href="repairs.php" class="quick-card">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <h3>Repair Requests</h3>
                        <p>Review and assign repair requests</p>
                        <button>View Requests</button>
                    </a>

                    <a href="users.php" class="quick-card">
                        <i class="fa-solid fa-user-group"></i>
                        <h3>User Management</h3>
                        <p>Manage users and permissions</p>
                        <button>Manage Users</button>
                    </a>

                </div>

                <div class="dashboard-bottom">

                    <div class="table-card full-width">
                        <div class="card-title">
                            <h3>Recent Listings</h3>
                            <a href="products.php" class="view-all-btn">View All<i class="fa-solid fa-arrow-right"></i></a>
                        </div>

                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Condition</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>

                            <?php while($p = mysqli_fetch_assoc($products)){ ?>
                            <tr>
                                <td>#<?= $p['product_id']; ?></td>
                                <td><?= $p['product_name']; ?></td>
                                <td><?= $p['category']; ?></td>
                                <td><?= $p['brand']; ?></td>
                                <td><?= $p['condition_type']; ?></td>
                                <td>RM<?= number_format($p['price'], 2); ?></td>
                                <td><?= $p['stock']; ?></td>
                                <td><?= $p['status']; ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>