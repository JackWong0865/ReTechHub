<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$tech_id = (int)$_GET['id'];  /*get technician id*/

/*check technician info*/
$tech = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE user_id='$tech_id'
         AND role='technician'"
    )
);

/*check technician repair work*/
$bookings = mysqli_query(
    $conn,
    "SELECT r.*, u.username AS customer_name
     FROM repair_bookings r
     LEFT JOIN users u ON r.user_id = u.user_id
     WHERE r.technician_id='$tech_id'
     AND r.status NOT IN ('Completed','Cancelled')
     ORDER BY r.booking_id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Detail</title>

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

        <!--page code-->
        <div class="tech-detail-card">

            <a href="technician-workload.php" class="back-link">
                &lt; Back to Technician Workload
            </a>

            <div class="tech-profile-row big">

                <img
                src="../<?= $tech['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                <div>
                    <h1><?= $tech['username']; ?></h1>
                    <p><?= $tech['email']; ?></p>
                    <small><?= $tech['phone']; ?></small>
                </div>

            </div>

            <h2>Current Assigned Bookings</h2>

            <!--assigned booking table-->
            <table class="tech-booking-table">

                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Device</th>
                    <th>Repair Type</th>
                    <th>Preferred Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if(mysqli_num_rows($bookings) > 0){ ?>

                    <?php while($b = mysqli_fetch_assoc($bookings)){ ?>

                        <tr>
                            <td>#RB<?= $b['booking_id']; ?></td>
                            <td><?= $b['customer_name']; ?></td>
                            <td><?= $b['device_type']; ?></td>
                            <td><?= $b['repair_type']; ?></td>
                            <td><?= $b['preferred_date']; ?></td>

                            <td>
                                <span class="tech-status <?= strtolower(str_replace(' ', '-', $b['status'])); ?>">
                                    <?= $b['status']; ?>
                                </span>
                            </td>

                            <td>
                                <a
                                href="booking-detail.php?type=repair&id=<?= $b['booking_id']; ?>"
                                class="view-tech-btn small">

                                    View / Reassign

                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                <?php }else{ ?>

                    <!--display when no assigned booking-->
                    <tr>
                        <td colspan="7" class="empty-row">
                            No active bookings for this technician.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </main>

</div>

</body>
</html>