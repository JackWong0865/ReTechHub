<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*repair booking*/
$repairs = mysqli_query(
    $conn,
    "SELECT r.booking_id AS id,
            r.user_id,
            u.username AS customer_name,
            r.device_type,
            r.repair_type,
            r.issue_description AS description,
            r.preferred_date,
            r.address,
            r.status,
            r.created_at,
            'repair' AS booking_type
     FROM repair_bookings r
     LEFT JOIN users u
     ON r.user_id = u.user_id
     WHERE r.status IN
     (
        'Pending',
        'Assigned',
        'In Progress',
        'Workshop Repair'
     )
     ORDER BY r.booking_id DESC"
);

/*sell request*/
$sells = mysqli_query(
    $conn,
    "SELECT s.sell_id AS id,
            s.user_id,
            u.username AS customer_name,
            s.device_type,
            NULL AS repair_type,
            s.description,
            s.preferred_date,
            s.address,
            s.status,
            s.created_at,
            'sell' AS booking_type
     FROM sell_requests s
     LEFT JOIN users u
     ON s.user_id = u.user_id
     WHERE s.status IN
     (
        'Pending',
        'Approved'
     )
     ORDER BY s.sell_id DESC"
);

/*repair history*/
$repair_history = mysqli_query(
    $conn,
    "SELECT r.booking_id AS id,
            r.user_id,
            u.username AS customer_name,
            r.device_type,
            r.repair_type,
            r.issue_description AS description,
            r.preferred_date,
            r.address,
            r.status,
            r.created_at
     FROM repair_bookings r
     LEFT JOIN users u
     ON r.user_id = u.user_id
     WHERE r.status IN
     (
        'Completed',
        'Cancelled'
     )
     ORDER BY r.booking_id DESC"
);

/*sell history*/
$sell_history = mysqli_query(
    $conn,
    "SELECT s.sell_id AS id,
            s.user_id,
            u.username AS customer_name,
            s.device_type,
            s.description,
            s.preferred_date,
            s.address,
            s.status,
            s.created_at
     FROM sell_requests s
     LEFT JOIN users u
     ON s.user_id = u.user_id
     WHERE s.status IN
     (
        'Completed',
        'Rejected'
     )
     ORDER BY s.sell_id DESC"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Booking Details - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin-repairs.css">

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
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                <a href="repairs.php" class="active"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
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
                        <a href="messages.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-message"></i><span>Message</span>
                            <span id="messageBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-bell"></i><span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="../profile.php" class="admin-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>" class="admin-avatar">
                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <div class="repair-header">
                    <div>
                        <h1>Booking Details</h1>
                        <p>View user repair and sell device bookings.</p>
                    </div>
                </div>

                <div class="booking-tabs">

                    <button class="booking-tab-btn active"
                        onclick="showBookingTab('repair')">
                        Repair Bookings
                    </button>

                    <button class="booking-tab-btn"
                        onclick="showBookingTab('sell')">
                        Sell Requests
                    </button>

                    <button class="booking-tab-btn"
                        onclick="showBookingTab('repairHistory')">
                        Repair History
                    </button>

                    <button class="booking-tab-btn"
                        onclick="showBookingTab('sellHistory')">
                        Sell History
                    </button>

                </div>

            <div id="repairTab">

                <div class="repairs-table-card">

                    <div class="booking-card">

                        <div class="booking-card-top">

                            <!--repair booking page code-->
                            <div>
                                <h2>Repair Bookings</h2>
                                <p>Manage all repair bookings from users.</p>
                            </div>

                        </div>

                        <table>
                            <!--repair booking table and data-->
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Device</th>
                                <th>Repair Type</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <?php while($r = mysqli_fetch_assoc($repairs)){ ?>

                                <tr>

                                    <td>#RB<?= $r['id']; ?></td>
                                    <td><?= $r['customer_name']; ?></td>
                                    <td><?= $r['device_type']; ?></td>
                                    <td><?= $r['repair_type']; ?></td>
                                    <td><?= $r['preferred_date']; ?></td>

                                    <td>
                                        <span class="repair-status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                                            <?= $r['status']; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <!--action button-->
                                        <a href="booking-detail.php?type=repair&id=<?= $r['id']; ?>" class="small-btn red">
                                            View Detail
                                        </a>
                                    </td>

                                </tr>

                            <?php } ?>

                        </table>

                    </div>

                </div>

            </div>

            <div id="sellTab" style="display:none;">

                <div class="repairs-table-card">

                    <div class="booking-card">
                        <!--sell request page code-->
                        <div class="booking-card-top">

                            <div>
                                <h2>Sell Requests</h2>
                                <p>Manage all sell device requests.</p>
                            </div>

                        </div>

                        <table>
                            <!--sell request table and data-->
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Device</th>
                                <th>Description</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <?php while($s = mysqli_fetch_assoc($sells)){ ?>

                                <tr>

                                    <td>#SB<?= $s['id']; ?></td>
                                    <td><?= $s['customer_name']; ?></td>
                                    <td><?= $s['device_type']; ?></td>
                                    <td class="description-cell"><?= htmlspecialchars($s['description']); ?></td>
                                    <td><?= $s['preferred_date']; ?></td>

                                    <td>
                                        <span class="repair-status <?= strtolower(str_replace(' ', '-', $s['status'])); ?>">
                                            <?= $s['status']; ?>
                                        </span>
                                    </td>

                                    <td class="action-cell">
                                
                                        <!--action button-->
                                        <a href="booking-detail.php?type=sell&id=<?= $s['id']; ?>" class="small-btn red">
                                            View Detail
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>

                    </div>

                </div>

            </div>

            <div id="repairHistoryTab" style="display:none;">

                <div class="repairs-table-card">

                    <div class="booking-card">

                        <div class="booking-card-top">

                            <!--repair history page code-->
                            <div>
                                <h2>Repair History</h2>
                                <p>Review all completed repair bookings from users.</p>
                            </div>

                        </div>

                        <table>
                            <!--repair history table and data-->
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Device</th>
                                <th>Repair Type</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <?php while($r = mysqli_fetch_assoc($repair_history)){ ?>

                                <tr>

                                    <td>#RB<?= $r['id']; ?></td>
                                    <td><?= $r['customer_name']; ?></td>
                                    <td><?= $r['device_type']; ?></td>
                                    <td><?= $r['repair_type']; ?></td>
                                    <td><?= $r['preferred_date']; ?></td>

                                    <td>
                                        <span class="repair-status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                                            <?= $r['status']; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <!--action button-->
                                        <a href="booking-detail.php?type=repair&id=<?= $r['id']; ?>" class="small-btn red">
                                            View Detail
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>

                    </div>

                </div>

            </div>

            <div id="sellHistoryTab" style="display:none;">

                <div class="repairs-table-card">

                    <div class="booking-card">
                        <!--sell history page code-->
                        <div class="booking-card-top">

                            <div>
                                <h2>Sell History</h2>
                                <p>Review all the completed sell device requests.</p>
                            </div>

                        </div>

                        <table>
                            <!--sell history table and data-->
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Device</th>
                                <th>Description</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                            <?php while($s = mysqli_fetch_assoc($sell_history)){ ?>

                                <tr>

                                    <td>#SB<?= $s['id']; ?></td>
                                    <td><?= $s['customer_name']; ?></td>
                                    <td><?= $s['device_type']; ?></td>
                                    <td class="description-cell"><?= htmlspecialchars($s['description']); ?></td>
                                    <td><?= $s['preferred_date']; ?></td>

                                    <td>
                                        <span class="repair-status <?= strtolower(str_replace(' ', '-', $s['status'])); ?>">
                                            <?= $s['status']; ?>
                                        </span>
                                    </td>

                                    <td class="action-cell">
                            
                                        <!--action button-->
                                        <a href="booking-detail.php?type=sell&id=<?= $s['id']; ?>" class="small-btn red">
                                            View Detail
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <?php include("../includes/live-badges.php"); ?>

        <!--change tab button between repair and sell-->
        <script>
        function showBookingTab(type){

            const repairTab = document.getElementById("repairTab");

            const sellTab = document.getElementById("sellTab");

            const repairHistoryTab = document.getElementById("repairHistoryTab");

            const sellHistoryTab = document.getElementById("sellHistoryTab");

            repairTab.style.display = "none";
            sellTab.style.display = "none";
            repairHistoryTab.style.display = "none";
            sellHistoryTab.style.display = "none";

            const buttons = document.querySelectorAll(".booking-tab-btn");

            buttons.forEach(btn=>{ btn.classList.remove("active"); });

            if(type=="repair"){
                repairTab.style.display="block";
                buttons[0].classList.add("active");
            }

            if(type=="sell"){
                sellTab.style.display="block";
                buttons[1].classList.add("active");
            }

            if(type=="repairHistory"){
                repairHistoryTab.style.display="block";
                buttons[2].classList.add("active");
            }

            if(type=="sellHistory"){
                sellHistoryTab.style.display="block";
                buttons[3].classList.add("active");
            }

        }
        </script>

    </body>
</html>