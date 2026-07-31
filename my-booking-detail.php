<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['type']) || !isset($_GET['id'])){
    header("Location: my-booking.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$type = $_GET['type'];
$id = (int)$_GET['id'];

if($type == "repair"){

    $query = mysqli_query(
        $conn,
        "SELECT r.*,
                t.username AS technician_name
         FROM repair_bookings r
         LEFT JOIN users t
         ON r.technician_id = t.user_id
         WHERE r.booking_id='$id'
         AND r.user_id='$user_id'"
    );

}else{

    $query = mysqli_query(
        $conn,
        "SELECT *
         FROM sell_requests
         WHERE sell_id='$id'
         AND user_id='$user_id'"
    );
}

if(!$query || mysqli_num_rows($query) == 0){
    die("Booking not found.");
}

$data = mysqli_fetch_assoc($query);
?>

<link rel="stylesheet" href="assets/css/my-booking-detail.css">

<div class="booking-detail-page">

    <a href="my-booking.php" class="back-btn">
        ← Back to My Booking
    </a>

    <div class="booking-detail-card">

        <div class="detail-header">

            <h1>
                <?php if($type == "repair"){ ?>
                    Repair Booking Detail
                <?php }else{ ?>
                    Sell Request Detail
                <?php } ?>
            </h1>

            <span class="booking-status <?= strtolower(str_replace(' ', '-', $data['status'])); ?>">
                <?= $data['status']; ?>
            </span>

        </div>

        <div class="detail-grid">

            <div class="detail-box">

                <!--booking info card-->
                <h3>Booking Information</h3>

                <p>
                    <b>Device:</b>
                    <?= $data['device_type']; ?>
                </p>
                
                <?php if($type == "repair"){ ?>

                    <p>
                        <b>Repair Type:</b>
                        <?= $data['repair_type']; ?>
                    </p>

                    <p>
                        <b>Issue Description:</b>
                        <?= $data['issue_description']; ?>
                    </p>

                    <p>
                        <b>Assigned Technician:</b>
                        <?= !empty($data['technician_name']) ? $data['technician_name'] : 'Not Assigned Yet'; ?>
                    </p>

                <?php }else{ ?>

                    <p>
                        <b>Brand:</b>
                        <?= $data['brand']; ?>
                    </p>

                    <p>
                        <b>Model:</b>
                        <?= $data['model']; ?>
                    </p>

                    <p>
                        <b>Condition:</b>
                        <?= $data['condition_type']; ?>
                    </p>

                    <p>
                        <b>Description:</b>
                        <?= $data['description']; ?>
                    </p>

                <?php } ?>

            </div>

            <div class="detail-box">

                <!--schedule info card-->
                <h3>Schedule Information</h3>

                <p>
                    <b>Preferred Date:</b>
                    <?= $data['preferred_date']; ?>
                </p>

                <p>
                    <b>Address:</b>
                    <?= $data['address']; ?>
                </p>

                <p>
                    <b>Status:</b>
                    <span class="booking-status small <?= strtolower(str_replace(' ', '-', $data['status'])); ?>">
                        <?= $data['status']; ?>
                    </span>
                </p>

            </div>

            <!--picture card-->
            <div class="detail-box full">

                <h3>Uploaded Photo</h3>

                <?php
                $images = mysqli_query(
                    $conn,
                    "SELECT image_path
                     FROM booking_images
                     WHERE booking_type='$type'
                     AND booking_id='$id'"
                );
                ?>

                <?php if(mysqli_num_rows($images) > 0){ ?>

                    <div class="booking-image-grid">

                        <?php while($img = mysqli_fetch_assoc($images)){ ?>

                            <img
                            src="<?= $img['image_path']; ?>"
                            class="booking-image">

                        <?php } ?>

                    </div>

                <?php }else{ ?>

                    <p>No image uploaded.</p>

                <?php } ?>

            </div>

        </div>

    </div>

</div>