<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$tech_id = $_SESSION['user_id'];

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : "";
$number = preg_replace('/[^0-9]/', '', $q);

/*search function*/
$repairs = mysqli_query(
    $conn,
    "SELECT r.*, u.username AS customer_name
     FROM repair_bookings r
     JOIN users u ON r.user_id = u.user_id
     WHERE r.technician_id='$tech_id'
     AND (
        r.booking_id='$number'
        OR r.device_type LIKE '%$q%'
        OR r.repair_type LIKE '%$q%'
        OR r.issue_description LIKE '%$q%'
        OR r.status LIKE '%$q%'
        OR u.username LIKE '%$q%'
     )
     ORDER BY r.booking_id DESC"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Technician Search Results</title>

        <link rel="stylesheet" href="../assets/css/technician.css">
        <link rel="stylesheet" href="../assets/css/assigned-repairs.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="tech-layout">

            <!--side bar-->
            <aside class="tech-sidebar">

                <img src="../assets/images/logo.png" class="tech-logo">

                <a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="assigned-repairs.php"><i class="fa-solid fa-screwdriver-wrench"></i> Assigned Repairs</a>
                <a href="repair-requests.php"><i class="fa-solid fa-clipboard-list"></i> Repair Requests</a>
                <a href="repair-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Repair History</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i> Messages</a>

                <a href="../logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>

            </aside>

            <!--top bar-->
            <main class="tech-main">

                <div class="tech-top">

                    <div class="tech-search">

                        <form action="search.php" method="GET">

                            <input type="text" name="q" value="<?= htmlspecialchars($q); ?>" placeholder="Search booking ID, customer, device..." required>

                            <button type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>

                        </form>

                    </div>

                    <div class="tech-icons">

                        <a href="messages.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-message"></i><span>Message</span>
                            <span id="messageBadge" class="live-badge" style="display:none;">0</span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-bell"></i><span>Notification</span>
                            <span id="notificationBadge" class="live-badge" style="display:none;">0</span>
                        </a>

                        <a href="../profile.php" class="tech-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">
                            <span><?= $_SESSION['username']; ?></span>
                        </a>

                    </div>

                </div>

                <!--page code-->
                <div class="page-header">
                    <div>
                        <h1>Search Results</h1>
                        <p>Results for “<?= htmlspecialchars($q); ?>”</p>
                    </div>
                </div>

                <div class="repair-grid">

                    <?php if(mysqli_num_rows($repairs) > 0){ ?>

                        <?php while($r = mysqli_fetch_assoc($repairs)){ ?>

                            <div class="repair-card">

                                <div class="repair-top">

                                    <div class="customer-info">

                                        <div>
                                            <h3><?= $r['customer_name']; ?></h3>
                                            <span>Booking #RB<?= $r['booking_id']; ?></span>
                                        </div>

                                    </div>

                                    <span class="repair-status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                                        <?= $r['status']; ?>
                                    </span>

                                </div>

                                <div class="repair-body">

                                    <div class="repair-detail">
                                        <label>Device</label>
                                        <p><?= $r['device_type']; ?></p>
                                    </div>

                                    <div class="repair-detail">
                                        <label>Repair Type</label>
                                        <p><?= $r['repair_type']; ?></p>
                                    </div>

                                    <div class="repair-detail">
                                        <label>Issue</label>
                                        <p><?= $r['issue_description']; ?></p>
                                    </div>

                                    <div class="repair-detail">
                                        <label>Preferred Date</label>
                                        <p><?= $r['preferred_date']; ?></p>
                                    </div>

                                </div>

                                <a href="repair-detail.php?id=<?= $r['booking_id']; ?>&from=assigned" class="view-detail-btn">
                                    View Detail
                                </a>

                            </div>

                        <?php } ?>

                    <!--display when no result-->
                    <?php }else{ ?>

                        <div class="empty-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <h2>No Results Found</h2>
                            <p>No repair booking matched your search.</p>
                        </div>

                    <?php } ?>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>