<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: repair-requests.php");
    exit();
}

$tech_id = $_SESSION['user_id'];
$booking_id = (int)$_GET['id'];

/*accept job*/

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

/*get request detail*/

$query = mysqli_query(
    $conn,
    "SELECT r.*, 
            u.username,
            u.email,
            u.phone,
            u.profile_image
     FROM repair_bookings r
     LEFT JOIN users u ON r.user_id = u.user_id
     WHERE r.booking_id='$booking_id'
     AND r.status='Pending'
     AND r.technician_id IS NULL"
);

if(!$query || mysqli_num_rows($query) == 0){
    die("Repair request not found or already accepted.");
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Repair Request Detail</title>

        <link rel="stylesheet" href="../assets/css/technician.css">
        <link rel="stylesheet" href="../assets/css/repair-detail.css">

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

                <a href="messages.php">
                    <i class="fa-solid fa-message"></i>
                    Messages
                </a>

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

                <!--page code-->
                <div class="repair-detail-card">

                    <a href="repair-requests.php" class="back-link">
                        &lt; Back to Repair Requests
                    </a>

                    <h1>Repair Request Detail</h1>

                    <div class="detail-grid">

                        <!--customer info card-->
                        <div class="detail-box">

                            <h3>Customer Information</h3>

                            <div class="customer-profile">

                                <img src="../<?= $data['profile_image'] ?? 'uploads/profile/default.png'; ?>">

                                <div>
                                    <h4><?= $data['username']; ?></h4>
                                    <p><?= $data['email']; ?></p>
                                    <p><?= $data['phone']; ?></p>
                                </div>

                            </div>

                        </div>

                        <!--repair info card-->
                        <div class="detail-box">

                            <h3>Repair Information</h3>

                            <p><b>Booking ID:</b> #RB<?= $data['booking_id']; ?></p>
                            <p><b>Device:</b> <?= $data['device_type']; ?></p>
                            <p><b>Repair Type:</b> <?= $data['repair_type']; ?></p>
                            <p><b>Issue:</b> <?= $data['issue_description']; ?></p>
                            <p><b>Preferred Date:</b> <?= $data['preferred_date']; ?></p>
                            <p><b>Address:</b> <?= $data['address']; ?></p>

                            <p>
                                <b>Status:</b>
                                <span class="status pending"> <?= $data['status']; ?> </span>
                            </p>

                        </div>

                        <!--upload photo card-->
                        <div class="detail-box full">

                            <h3>User Uploaded Photo</h3>

                            <?php
                            $booking_images = mysqli_query(
                                $conn,
                                "SELECT * FROM booking_images
                                WHERE booking_type='repair'
                                AND booking_id='$booking_id'
                                ORDER BY image_id ASC"
                            );
                            ?>

                            <?php if(mysqli_num_rows($booking_images) > 0){ ?>

                                <div class="repair-image-grid">

                                    <?php while($img = mysqli_fetch_assoc($booking_images)){ ?>

                                        <a href="../<?= $img['image_path']; ?>" target="_blank">
                                            <img
                                            src="../<?= $img['image_path']; ?>"
                                            class="repair-booking-image">
                                        </a>

                                    <?php } ?>

                                </div>

                            <!--display when user didn't upload picture-->
                            <?php }else{ ?>

                                <p>No image uploaded.</p>

                            <?php } ?>

                        </div>

                        <!--accept button-->
                        <div class="detail-box full">

                            <form method="POST" class="accept-job-form">

                                <input type="hidden" name="booking_id" value="<?= $data['booking_id']; ?>">

                                <!-- Ensure accept_job is submitted after form.submit() -->
                                <input type="hidden" name="accept_job" value="1">

                                <button type="submit">
                                    Accept Job
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </main>

        </div>

        <?php include("../includes/live-badges.php"); ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        document.querySelector(".accept-job-form").addEventListener("submit", function(e){

            e.preventDefault();

            const form = this;

            Swal.fire({
                title: "Accept Repair Job?",
                text: "Are you sure you want to accept this repair request?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Accept",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d32f2f",
                cancelButtonColor: "#6c757d",
            }).then(function(result){

                if(result.isConfirmed){
                    form.submit();
                }

            });

        });
        </script>

    </body>
</html>