<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$techs = mysqli_query(
    $conn,
    /*search technician basic info*/
    "SELECT 
        u.user_id,
        u.username,
        u.email,
        u.phone,
        u.profile_image,

        /*total job*/
        COUNT(r.booking_id) AS total_jobs,

        /*total number of assigned*/
        SUM(CASE WHEN r.status='Assigned' THEN 1 ELSE 0 END) AS assigned_jobs,

        /*total number of progress*/
        SUM(CASE WHEN r.status='In Progress' THEN 1 ELSE 0 END) AS progress_jobs,

        /*total number of completed*/
        SUM(CASE WHEN r.status='Completed' THEN 1 ELSE 0 END) AS completed_jobs

    /*check users table*/
     FROM users u

     LEFT JOIN repair_bookings r
     ON u.user_id = r.technician_id

    /*only show user that role is technician*/
     WHERE u.role='technician'

     GROUP BY u.user_id

     ORDER BY total_jobs DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Workload</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-technician.css">

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
        <a href="technician-workload.php" class="active"><i class="fa-solid fa-user-gear"></i> Technician Workload</a>
        <a href="messages.php"><i class="fa-solid fa-message"></i> Messages</a>
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

                    <i class="fa-solid fa-message"></i><span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="notifications.php" class="top-icon badge-wrap">

                    <i class="fa-solid fa-bell"></i><span>Notification</span>

                    <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="../profile.php" class="admin-profile">
                    <img
                    src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>"
                    class="admin-avatar">

                    <span><?= $_SESSION['username']; ?></span>
                </a>
            </div>
        </div>

        <!--page code-->
        <div class="tech-workload-header">
            <h1>Technician Workload</h1>
            <p>Check each technician's current repair workload before assigning bookings.</p>
        </div>

        <div class="tech-workload-grid">

            <?php while($t = mysqli_fetch_assoc($techs)){ ?>

                <div class="tech-workload-card">

                    <div class="tech-profile-row">

                        <img
                        src="../<?= $t['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                        <div>
                            <h3><?= $t['username']; ?></h3>
                            <p><?= $t['email']; ?></p>
                            <small><?= $t['phone']; ?></small>
                        </div>

                    </div>

                    <div class="workload-stats">

                        <div>
                            <span><?= $t['assigned_jobs'] ?? 0; ?></span>
                            <p>Assigned</p>
                        </div>

                        <div>
                            <span><?= $t['progress_jobs'] ?? 0; ?></span>
                            <p>In Progress</p>
                        </div>

                        <div>
                            <span><?= $t['completed_jobs'] ?? 0; ?></span>
                            <p>Completed</p>
                        </div>

                        <div>
                            <span><?= $t['total_jobs'] ?? 0; ?></span>
                            <p>Total</p>
                        </div>

                    </div>
                    
                    <!--action button-->
                    <a
                    href="technician-detail.php?id=<?= $t['user_id']; ?>"
                    class="view-tech-btn">

                        View Assigned Bookings

                    </a>

                </div>

            <?php } ?>

        </div>

    </main>

</div>

<?php include("../includes/live-badges.php"); ?>

</body>
</html>