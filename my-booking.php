<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*Searching for ongoing Repair Bookings*/
$repairs = mysqli_query($conn,
    "SELECT * FROM repair_bookings
     WHERE user_id='$user_id'
     AND status NOT IN ('Completed','Cancelled')
     ORDER BY booking_id DESC"
);

/*Searching for ongoing sell requests*/
$sells = mysqli_query($conn,
    "SELECT * FROM sell_requests
     WHERE user_id='$user_id'
     AND status NOT IN ('Approved','Rejected')
     ORDER BY sell_id DESC"
);

/*Search for completed Repair Bookings*/
$history_repairs = mysqli_query($conn,
    "SELECT * FROM repair_bookings
     WHERE user_id='$user_id'
     AND status IN ('Completed','Cancelled')
     ORDER BY booking_id DESC"
);

/*Search for completed sell requests*/
$history_sells = mysqli_query($conn,
    "SELECT * FROM sell_requests
     WHERE user_id='$user_id'
     AND status IN ('Approved','Rejected')
     ORDER BY sell_id DESC"
);

include("includes/header.php");
?>

<link rel="stylesheet" href="assets/css/my-booking.css">

<div class="my-booking-page">

    <h1>My Bookings</h1>
    <p>View your repair and sell device booking status.</p>

    <!--switch button-->
    <div class="booking-tabs">
        <button class="booking-tab-btn active" onclick="showBookingTab('active')">
            Active Bookings
        </button>

        <button class="booking-tab-btn" onclick="showBookingTab('history')">
            Booking History
        </button>
    </div>

    <div id="activeBookingTab">
    
        <div class="booking-section">
            <h2>Repair Bookings</h2>

            <div class="booking-grid">
                
                <!--repair card-->
                <?php while($r = mysqli_fetch_assoc($repairs)){ ?>
                    <div class="booking-card">
                        <h3>#RB<?= $r['booking_id']; ?> - <?= $r['device_type']; ?></h3>
                        <p><b>Repair Type:</b> <?= $r['repair_type']; ?></p>
                        <p><b>Issue:</b> <?= $r['issue_description']; ?></p>
                        <p><b>Date:</b> <?= $r['preferred_date']; ?></p>
                       <p><b>Status:</b> <span class="status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>"><?= $r['status']; ?></span></p>
                       <a href="my-booking-detail.php?type=repair&id=<?= $r['booking_id']; ?>" class="view-booking-btn"> View Detail</a>
                   </div>
                <?php } ?>
            </div>
        </div>
    
        <div class="booking-section">
            <h2>Sell Requests</h2>

            <div class="booking-grid">

                <!--sell card-->
                <?php while($s = mysqli_fetch_assoc($sells)){ ?>
                    <div class="booking-card">
                        <h3>#SB<?= $s['sell_id']; ?> - <?= $s['device_type']; ?></h3>
                        <p><b>Brand:</b> <?= $s['brand']; ?></p>
                        <p><b>Model:</b> <?= $s['model']; ?></p>
                        <p><b>Date:</b> <?= $s['preferred_date']; ?></p>
                        <p><b>Status:</b><span class="status <?= strtolower(str_replace(' ', '-', $s['status'])); ?>"><?= $s['status']; ?></span></p>
                        <a href="my-booking-detail.php?type=sell&id=<?= $s['sell_id']; ?>" class="view-booking-btn"> View Detail</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="historyBookingTab" style="display:none;">

        <div class="booking-section">
            <h2>Repair Bookings History</h2>

            <div class="booking-grid">

                <!--completed repair card-->
                <?php while($r = mysqli_fetch_assoc($history_repairs)){ ?>
                    <div class="booking-card">
                        <h3>#RB<?= $r['booking_id']; ?> - <?= $r['device_type']; ?></h3>
                        <p><b>Repair Type:</b> <?= $r['repair_type']; ?></p>
                        <p><b>Issue:</b> <?= $r['issue_description']; ?></p>
                        <p><b>Date:</b> <?= $r['preferred_date']; ?></p>
                        <p><b>Status:</b>
                            <span class="status <?= strtolower(str_replace(' ', '-', $r['status'])); ?>">
                                <?= $r['status']; ?>
                            </span>
                        </p>
                        <a href="my-booking-detail.php?type=repair&id=<?= $r['booking_id']; ?>" class="view-booking-btn">View Detail</a>
                    </div>
                <?php } ?>

            </div>
        </div>

        <div class="booking-section">
            <h2>Sell Request History</h2>

            <div class="booking-grid">

                <!--completed sell card-->
                <?php while($s = mysqli_fetch_assoc($history_sells)){ ?>
                    <div class="booking-card">
                        <h3>#SB<?= $s['sell_id']; ?> - <?= $s['device_type']; ?></h3>
                        <p><b>Brand:</b> <?= $s['brand']; ?></p>
                        <p><b>Model:</b> <?= $s['model']; ?></p>
                        <p><b>Date:</b> <?= $s['preferred_date']; ?></p>
                        <p><b>Status:</b><span class="status <?= strtolower(str_replace(' ', '-', $s['status'])); ?>"><?= $s['status']; ?></span></p>
                        <a href="my-booking-detail.php?type=sell&id=<?= $s['sell_id']; ?>" class="view-booking-btn">View Detail</a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>
/*button active*/
function showBookingTab(tab){

    /*obtain two content areas*/
    const activeTab = document.getElementById("activeBookingTab");
    const historyTab = document.getElementById("historyBookingTab");

    /*get all buttons*/
    const buttons = document.querySelectorAll(".booking-tab-btn");

    /*remove all Active styles*/
    buttons.forEach(btn => btn.classList.remove("active"));

    if(tab === "active"){
        activeTab.style.display = "block";
        historyTab.style.display = "none";
        buttons[0].classList.add("active");

    }else{
        activeTab.style.display = "none";
        historyTab.style.display = "block";
        buttons[1].classList.add("active");
    }
}
</script>
