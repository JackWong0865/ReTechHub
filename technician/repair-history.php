<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$tech_id = $_SESSION['user_id'];

/*only display completed and cancelled status*/
$history = mysqli_query(
    $conn,
    "SELECT r.*, 
            u.username,
            u.profile_image
     FROM repair_bookings r
     LEFT JOIN users u ON r.user_id = u.user_id
     WHERE r.technician_id='$tech_id'
     AND r.status IN ('Completed','Cancelled')
     ORDER BY r.booking_id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repair History</title>

    <link rel="stylesheet" href="../assets/css/technician.css">
    <link rel="stylesheet" href="../assets/css/repair-history.css">

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
        <a href="repair-history.php" class="active"><i class="fa-solid fa-clock-rotate-left"></i> Repair History</a>
        <a href="messages.php"><i class="fa-solid fa-message"></i> Messages </a>
        <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

    </aside>

    <main class="tech-main">

        <!--top bar-->
        <div class="tech-top">

            <div class="tech-search">
                <form action="search.php" method="GET">
                    <input type="text" name="q" placeholder="Search booking ID, customer, device..." required>
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>

            <div class="tech-icons">

                <a href="messages.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-message"></i><span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="notifications.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-bell"></i><span>Notification</span>
                    <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="../profile.php" class="tech-profile">
                    <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">
                    <span><?= $_SESSION['username']; ?></span>
                </a>

            </div>

        </div>

        <!--page code-->
        <div class="page-header">

            <div>
                <h1>Repair History</h1>
                <p>View your completed and cancelled repair jobs.</p>
            </div>

        </div>

        <div class="history-table-card">

            <table>

                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Device</th>
                    <th>Repair Type</th>
                    <th>Preferred Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if(mysqli_num_rows($history) > 0){ ?>

                    <?php while($h = mysqli_fetch_assoc($history)){ ?>

                    <tr>
                        <td>#RB<?= $h['booking_id']; ?></td>

                        <td>
                            <div class="history-user">
                                <img src="../<?= $h['profile_image'] ?? 'uploads/profile/default.png'; ?>">
                                <span><?= $h['username']; ?></span>
                            </div>
                        </td>

                        <td><?= $h['device_type']; ?></td>
                        <td><?= $h['repair_type']; ?></td>
                        <td><?= $h['preferred_date']; ?></td>

                        <td>
                            <span class="history-status <?= strtolower($h['status']); ?>">
                                <?= $h['status']; ?>
                            </span>
                        </td>

                        <td>
                            <a
                            href="repair-detail.php?id=<?= $h['booking_id']; ?>&from=history"
                            class="history-view-btn">

                                View Detail

                            </a>
                        </td>
                    </tr>

                    <?php } ?>

                <?php }else{ ?>
                    <!--display when no history-->
                    <tr>
                        <td colspan="7" class="empty-row">
                            No repair history found.
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