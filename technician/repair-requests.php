<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$tech_id = $_SESSION['user_id'];

if(isset($_POST['accept_job'])){

    $booking_id = (int)$_POST['booking_id'];

    mysqli_query(
        $conn,
        "UPDATE repair_bookings
         SET technician_id='$tech_id',
             status='Assigned'
         WHERE booking_id='$booking_id'
         AND technician_id IS NULL
         AND status='Pending'"
    );

    $booking = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT user_id FROM repair_bookings
             WHERE booking_id='$booking_id'"
        )
    );

    $tech = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT username FROM users
             WHERE user_id='$tech_id'"
        )
    );

    if($booking && $tech){

        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id, title, message, type)
            VALUES
            (
                '".$booking['user_id']."',
                'Technician Accepted Your Repair',
                'Technician ".$tech['username']." has accepted your repair booking #RB".$booking_id.".',
                'repair'
            )"
        );
    }

    $_SESSION['status_success'] = "Repair job accepted successfully.";

    header("Location: assigned-repairs.php");
    exit();
}

$requests = mysqli_query(
    $conn,
    "SELECT r.*, 
            u.username,
            u.profile_image
     FROM repair_bookings r
     LEFT JOIN users u ON r.user_id = u.user_id
     WHERE r.status='Pending'
     AND r.technician_id IS NULL
     ORDER BY r.booking_id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repair Requests</title>

    <link rel="stylesheet" href="../assets/css/technician.css">
    <link rel="stylesheet" href="../assets/css/repair-requests.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="tech-layout">

    <!--side bar-->
    <aside class="tech-sidebar">

        <img src="../assets/images/logo.png" class="tech-logo">

        <a href="dashboard.php">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="assigned-repairs.php">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            Assigned Repairs
        </a>

        <a href="repair-requests.php" class="active">
            <i class="fa-solid fa-clipboard-list"></i>
            Repair Requests
        </a>

        <a href="repair-history.php">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Repair History
        </a>

        <a href="messages.php"><i class="fa-solid fa-message"></i> Messages </a>

        

        <a href="../logout.php" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

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

        <div class="page-header">

            <!--page code-->
            <div>
                <h1>Repair Requests</h1>
                <p>Browse pending repair requests and accept jobs.</p>
            </div>

        </div>

        <div class="request-grid">

            <?php if(mysqli_num_rows($requests) > 0){ ?>

                <?php while($r = mysqli_fetch_assoc($requests)){ ?>

                    <div class="request-card">

                        <div class="request-top">

                            <div class="customer-info">

                                <img src="../<?= $r['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                                <div>
                                    <h3><?= $r['username']; ?></h3>
                                    <span>Booking #RB<?= $r['booking_id']; ?></span>
                                </div>

                            </div>

                            <span class="request-status">
                                <?= $r['status']; ?>
                            </span>

                        </div>

                        <!--display first picture-->
                        <?php
                        $img_query = mysqli_query(
                            $conn,
                            "SELECT image_path
                             FROM booking_images
                             WHERE booking_type='repair'
                             AND booking_id='".$r['booking_id']."'
                             ORDER BY image_id ASC
                             LIMIT 1"
                        );

                        $img = mysqli_fetch_assoc($img_query);
                        ?>

                        <div class="request-image">

                            <?php if($img){ ?>

                                <img src="../<?= $img['image_path']; ?>">

                            <?php }else{ ?>

                                <!--display when user no upload picture-->
                                <div class="no-image">
                                    <i class="fa-regular fa-image"></i>
                                    No Image
                                </div>

                            <?php } ?>

                        </div>

                        <div class="request-details">

                            <p>
                                <b>Device:</b>
                                <?= $r['device_type']; ?>
                            </p>

                            <p>
                                <b>Repair Type:</b>
                                <?= $r['repair_type']; ?>
                            </p>

                            <p>
                                <b>Issue:</b>
                                <?= $r['issue_description']; ?>
                            </p>

                            <p>
                                <b>Preferred Date:</b>
                                <?= $r['preferred_date']; ?>
                            </p>

                            <p>
                                <b>Address:</b>
                                <?= $r['address']; ?>
                            </p>

                        </div>

                        <div class="request-actions">

                            <a
                            href="repair-request-detail.php?id=<?= $r['booking_id']; ?>"
                            class="view-btn">

                                View Detail

                            </a>

                            <form method="POST" class="accept-form">

                                <input
                                type="hidden"
                                name="booking_id"
                                value="<?= $r['booking_id']; ?>">

                                <!-- Ensure accept_job is submitted after form.submit() -->
                                <input
                                    type="hidden"
                                    name="accept_job"
                                    value="1"
                                >

                                <button
                                type="submit"
                                name="accept_job"
                                class="accept-btn">

                                    Accept Job

                                </button>

                            </form>

                        </div>

                    </div>

                <?php } ?>

            <?php }else{ ?>

                <!--display when No Pending Repair Requests-->
                <div class="empty-box">

                    <i class="fa-solid fa-clipboard-check"></i>

                    <h2>No Pending Repair Requests</h2>

                    <p>There are currently no repair requests available.</p>

                </div>

            <?php } ?>

        </div>

    </main>

</div>

<?php include("../includes/live-badges.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll(".accept-form").forEach(function(form){

    form.addEventListener("submit", function(e){

        e.preventDefault();

        Swal.fire({
            title: "Accept Repair Job?",
            text: "Are you sure you want to accept this repair request?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, Accept",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d32f2f",
            cancelButtonColor: "#6c757d"
        }).then((result)=>{

            if(result.isConfirmed){
                form.submit();
            }

        });

    });

});
</script>

</body>
</html>