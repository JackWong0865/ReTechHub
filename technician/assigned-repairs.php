<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$tech_id = $_SESSION['user_id'];

/* UPDATE STATUS */

if(isset($_POST['update_status'])){

    $booking_id = (int)$_POST['booking_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    /*update repair status*/
    mysqli_query(
        $conn,
        "UPDATE repair_bookings
         SET status='$status'
         WHERE booking_id='$booking_id'
         AND technician_id='$tech_id'"
    );

    /*get user id*/
    $booking = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT user_id
             FROM repair_bookings
             WHERE booking_id='$booking_id'"
        )
    );

    /*send notification to user*/
    if($booking){

        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id, title, message, type)
            VALUES
            (
                '".$booking['user_id']."',
                'Repair Status Updated',
                'Your repair booking #RB".$booking_id." status has been updated to ".$status.".',
                'repair'
            )"
        );

    }

    $_SESSION['status_success'] = "Repair status updated successfully.";

    header("Location: assigned-repairs.php");
    exit();
}

/*get repairs*/

$repairs = mysqli_query(
    $conn,
    "SELECT r.*, u.username, u.profile_image
     FROM repair_bookings r
     JOIN users u ON r.user_id = u.user_id
     WHERE r.technician_id='$tech_id'
     AND r.status NOT IN ('Completed', 'Cancelled')
     ORDER BY r.booking_id DESC"
);
?>

<!DOCTYPE html>
<html>
    <head>

        <title>Assigned Repairs</title>

        <link rel="stylesheet" href="../assets/css/technician.css">
        <link rel="stylesheet" href="../assets/css/assigned-repairs.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="tech-layout">

            <!--side bar-->

            <aside class="tech-sidebar">

                <img
                src="../assets/images/logo.png"
                class="tech-logo">

                <a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a class="active" href="assigned-repairs.php"><i class="fa-solid fa-screwdriver-wrench"></i> Assigned Repairs</a>
                <a href="repair-requests.php"><i class="fa-solid fa-clipboard-list"></i> Repair Requests </a>
                <a href="repair-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Repair History</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i> Messages </a>


                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>

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
                            <span id="messageBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-bell"></i><span>Notification</span>
                            <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="../profile.php" class="tech-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default.png'; ?>">
                            <span> <?= $_SESSION['username']; ?> </span>
                        </a>

                    </div>

                </div>

                <!--page code-->

                <div class="page-header">

                    <div>
                        <h1>Assigned Repairs</h1>
                        <p>Manage all repair requests assigned to you.</p>
                    </div>

                </div>

                <!--repair list-->

                <div class="repair-grid">

                    <?php if(mysqli_num_rows($repairs) > 0){ ?>

                        <?php while($r = mysqli_fetch_assoc($repairs)){ ?>

                            <div class="repair-card">

                                <div class="repair-top">

                                    <div class="customer-info">

                                        <img src="../<?= !empty($r['profile_image']) ? $r['profile_image'] : 'uploads/profile/default.png'; ?>">
                                    
                                        <div>
                                            <h3> <?= $r['username']; ?> </h3>
                                            <span> Booking #RB<?= $r['booking_id']; ?> </span>
                                        </div>

                                    </div>

                                    <span class="repair-status <?= strtolower(str_replace(' ','-',$r['status'])); ?>">
                                        <?= $r['status']; ?>
                                    </span>

                                </div>

                                <div class="repair-body">

                                    <div class="repair-detail">

                                        <label>Device</label>
                                        <p> <?= $r['device_type']; ?> </p>

                                    </div>

                                    <div class="repair-detail">

                                        <label>Issue</label>
                                        <p><?= $r['issue_description']; ?></p>

                                    </div>

                                    <div class="repair-detail">

                                        <label>Address</label>
                                        <p> <?= $r['address']; ?> </p>

                                    </div>

                                    <div class="repair-detail">

                                        <label>Preferred Date</label>
                                        <p> <?= $r['preferred_date']; ?></p>

                                    </div>

                                </div>

                                <form method="POST" class="repair-actions">

                                    <input type="hidden" name="booking_id" value="<?= $r['booking_id']; ?>">

                                    <select name="status">

                                        <option value="Assigned" <?= $r['status']=='Assigned'?'selected':''; ?>>
                                            Assigned
                                        </option>

                                        <option value="In Progress" <?= $r['status']=='In Progress'?'selected':''; ?>>
                                            In Progress
                                        </option>

                                        <option value="Workshop Repair" <?= $r['status']=='Workshop Repair'?'selected':''; ?>>
                                            Workshop Repair
                                        </option>

                                        <option value="Completed" <?= $r['status']=='Completed'?'selected':''; ?>>
                                            Completed
                                        </option>

                                        <option value="Cancelled" <?= $r['status']=='Cancelled'?'selected':''; ?>>
                                            Cancelled
                                        </option>

                                    </select>

                                    <button type="submit" name="update_status">
                                        Update Status
                                    </button>

                                </form>

                                <a href="repair-detail.php?id=<?= $r['booking_id']; ?>&from=assigned" class="view-detail-btn">
                                    View Detail
                                </a>

                            </div>

                        <?php } ?>

                    <?php }else{ ?>

                        <!--display when no assigned repair-->
                        <div class="empty-box">

                            <i class="fa-solid fa-box-open"></i>
                            <h2> No Assigned Repairs </h2>
                            <p> You currently have no assigned repairs. </p>

                        </div>

                    <?php } ?>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

        <!--success window-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php if(isset($_SESSION['status_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: <?= json_encode($_SESSION['status_success']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#d32f2f"
        });
        </script>

        <?php
        unset($_SESSION['status_success']);
        }
        ?>

    </body>
</html>