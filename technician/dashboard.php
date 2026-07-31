<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$tech_id = $_SESSION['user_id'];

$filter = $_GET['filter'] ?? 'week';

$dateCondition = "";

if($filter == "today"){
    $dateCondition = "AND DATE(updated_at) = CURDATE()";
}elseif($filter == "year"){
    $dateCondition = "AND YEAR(updated_at) = YEAR(CURDATE())";
}elseif($filter == "week"){
    $dateCondition = "AND YEARWEEK(updated_at, 1) = YEARWEEK(CURDATE(), 1)";
}

/* CARD STATS - always all time */
$assigned = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id'"
))['total'];

$progress = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='In Progress'"
))['total'];

$workshop = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Workshop Repair'"
))['total'];

$completed = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Completed'"
))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Assigned'"
))['total'];

/* CHART STATS - affected by filter */
$chartProgress = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='In Progress' $dateCondition"
))['total'];

$chartWorkshop = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Workshop Repair' $dateCondition"
))['total'];

$chartCompleted = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Completed' $dateCondition"
))['total'];

$chartAssigned = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM repair_bookings WHERE technician_id='$tech_id' AND status='Assigned' $dateCondition"
))['total'];

$totalForChart = $chartProgress + $chartWorkshop + $chartCompleted + $chartAssigned;

$progressDeg = $totalForChart > 0 ? ($chartProgress / $totalForChart) * 360 : 0;
$workshopDeg = $totalForChart > 0 ? ($chartWorkshop / $totalForChart) * 360 : 0;
$completedDeg = $totalForChart > 0 ? ($chartCompleted / $totalForChart) * 360 : 0;
$pendingDeg = $totalForChart > 0 ? ($chartAssigned / $totalForChart) * 360 : 0;

$repairs = mysqli_query($conn,
    "SELECT r.*, u.username AS customer_name
     FROM repair_bookings r
     JOIN users u ON r.user_id = u.user_id
     WHERE r.technician_id='$tech_id'
     ORDER BY r.booking_id DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Technician Dashboard</title>
        <link rel="stylesheet" href="../assets/css/technician.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="tech-layout">

            <!--side bar-->
            <aside class="tech-sidebar">
                <img src="../assets/images/logo.png" class="tech-logo">

                <a class="active" href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="assigned-repairs.php"><i class="fa-solid fa-screwdriver-wrench"></i> Assigned Repairs</a>
                <a href="repair-requests.php"><i class="fa-solid fa-clipboard-list"></i> Repair Requests </a>
                <a href="repair-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Repair History</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i> Messages </a>
                

                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </aside>

            <main class="tech-main">

                <!--top bar-->
                <div class="tech-top">
                    <div class="tech-search">
                        <form action="search.php" method="GET">
                            <input type="text" name="q" placeholder="Search booking ID, customer, device..." required>
                            <button type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <div class="tech-icons">
                        <a href="messages.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-message"></i><span>Message</span>
                            <span id="messageBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-bell"></i><span>Notification</span>
                            <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="../profile.php" class="tech-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">
                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <h1>Technician Dashboard 👋</h1>
                <p class="welcome">Good morning, <?= $_SESSION['username']; ?>! Here's your overview for today.</p>

                <div class="tech-stats">

                    <div class="stat-card">
                        <i class="fa-solid fa-clipboard-list red"></i>
                        <div>
                            <p>Assigned Repairs</p>
                            <h2><?= $assigned; ?></h2>
                            <a href="assigned-repairs.php">View all →</a>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-spinner blue"></i>
                        <div>
                            <p>In Progress</p>
                            <h2><?= $progress; ?></h2>
                            <a href="assigned-repairs.php">View all →</a>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-warehouse blue"></i>
                        <div>
                            <p>Workshop Repair</p>
                            <h2><?= $workshop; ?></h2>
                            <a href="assigned-repairs.php">View all →</a>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-circle-check green"></i>
                        <div>
                            <p>Completed Today</p>
                            <h2><?= $completed; ?></h2>
                            <a href="repair-history.php">View all →</a>
                        </div>
                    </div>

                    <div class="stat-card">
                        <i class="fa-solid fa-box orange"></i>
                        <div>
                            <p>Pending Parts</p>
                            <h2><?= $pending; ?></h2>
                            <a href="assigned-repairs.php">View all →</a>
                        </div>
                    </div>

                </div>

                <div class="tech-content">

                    <div class="table-card">
                        <div class="card-head">
                            <h3>My Assigned Repairs</h3>
                            <a href="assigned-repairs.php">View All</a>
                        </div>

                        <table>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Device</th>
                                <th>Problem</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>

                            <?php while($r = mysqli_fetch_assoc($repairs)){ ?>
                                <tr>
                                    <td>#RB<?= $r['booking_id']; ?></td>
                                    <td><?= $r['customer_name']; ?></td>
                                    <td><?= $r['device_type']; ?></td>
                                    <td><?= $r['issue_description']; ?></td>
                                    <td>
                                        <span class="status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                                            <?= $r['status']; ?>
                                        </span>
                                    </td>
                                    <td><?= $r['preferred_date']; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>

                    <div class="side-cards">
                    
                        <div class="overview-card">
                            <div class="card-head">
                                <h3>Repair Status Overview</h3>
                                <select onchange="window.location.href='dashboard.php?filter=' + this.value">
                                    <option value="all" <?= $filter=='all'?'selected':''; ?>>All</option>
                                    <option value="today" <?= $filter=='today'?'selected':''; ?>>Today</option>
                                    <option value="week" <?= $filter=='week'?'selected':''; ?>>This Week</option>
                                    <option value="year" <?= $filter=='year'?'selected':''; ?>>This Year</option>
                                </select>
                            </div>

                            <div class="donut" 
                                style="background:conic-gradient(
                                    #2563eb 0deg <?= $progressDeg; ?>deg,

                                    #8b5cf6 <?= $progressDeg; ?>deg <?= $progressDeg+$workshopDeg; ?>deg,

                                    #16a34a <?= $progressDeg+$workshopDeg; ?>deg <?= $progressDeg+$workshopDeg+$completedDeg; ?>deg,

                                    #f59e0b <?= $progressDeg+$workshopDeg+$completedDeg; ?>deg 360deg
                                );">

                                <div class="donut-inner">
                                    <h2><?= $totalForChart; ?></h2>
                                    <p>Total</p>
                                </div>
                            </div>

                            <div class="legend">
                                <p><span class="blue-dot"></span> In Progress <b><?= $chartProgress; ?></b></p>
                                <p><span class="purple-dot"></span> Workshop Repair <b><?= $chartWorkshop; ?></b></p>
                                <p><span class="green-dot"></span> Completed <b><?= $chartCompleted; ?></b></p>
                                <p><span class="orange-dot"></span> Assigned <b><?= $chartAssigned; ?></b></p>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>