<?php 
session_start(); 
include("../includes/db.php"); 

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){ 
    header("Location: ../login.php"); 
    exit(); 
} 

if(!isset($_GET['id'])){ 
    header("Location: assigned-repairs.php"); 
    exit(); 
} 

$tech_id = $_SESSION['user_id']; 
$booking_id = (int)$_GET['id']; 
$from = $_GET['from'] ?? 'assigned'; 

if(isset($_POST['update_status'])){ 
    $status = mysqli_real_escape_string($conn, $_POST['status']); 
    mysqli_query( 
        $conn, 
        "UPDATE repair_bookings 
        SET status='$status' 
        WHERE booking_id='$booking_id' 
        AND technician_id='$tech_id'" 
    ); 
        
    $booking = mysqli_fetch_assoc( 
        mysqli_query( 
            $conn, 
            "SELECT user_id 
            FROM repair_bookings 
            WHERE booking_id='$booking_id'" 
        ) 
    ); 
    
    $tech_name = $_SESSION['username']; 
    $admin_id = 1; 
            
    if($booking){ 
                
        /*notify user*/ 
        mysqli_query( 
            $conn, 
            "INSERT INTO notifications 
            (user_id, title, message, type) 
            VALUES 
            ( 
            '".$booking['user_id']."', 
            'Repair Status Updated', 
            'Your repair booking #RB".$booking_id." status was updated to ".$status.".', 
            'repair' 
            )" 
        ); 
                    
        /*notify admin*/ 
        mysqli_query( 
            $conn, 
            "INSERT INTO notifications 
            (user_id, title, message, type) 
            VALUES 
            ( 
            '$admin_id', 
            'Technician Updated Repair Status', 
            'Technician ".$tech_name." updated repair booking #RB".$booking_id." to ".$status.".', 
            'repair' 
            )"
        ); 
    } 
                
    $_SESSION['status_success'] = "Repair status updated successfully.";

    header("Location: repair-detail.php?id=$booking_id&from=$from"); 
    exit(); 
} 
            
$query = mysqli_query( 
    $conn, 
    "SELECT r.*, 
        u.username, 
        u.email, 
        u.phone, 
        u.profile_image 
    FROM repair_bookings r
    LEFT JOIN users u 
    ON r.user_id = u.user_id 
    WHERE r.booking_id='$booking_id' 
    AND r.technician_id='$tech_id'" 
); 

if(!$query || mysqli_num_rows($query) == 0){ 
    die("Repair booking not found or not assigned to you."); 
} 

$data = mysqli_fetch_assoc($query); 
?> 

<!DOCTYPE html> 
<html> 
    <head> 
        <title>Repair Detail</title> 
    
        <link rel="stylesheet" href="../assets/css/technician.css"> 
        <link rel="stylesheet" href="../assets/css/assigned-repairs.css"> 
        <link rel="stylesheet" href="../assets/css/repair-detail.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> 
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
                
                <a href="assigned-repairs.php" class="<?= $from == 'assigned' ? 'active' : ''; ?>"> 
                    <i class="fa-solid fa-screwdriver-wrench"></i> 
                    Assigned Repairs 
                </a> 
                
                <a href="repair-requests.php"><i class="fa-solid fa-clipboard-list"></i> Repair Requests </a> 
                
                <a href="repair-history.php" class="<?= $from == 'history' ? 'active' : ''; ?>"> 
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
                <div class="repair-detail-card"> 
                    <a href="<?= $from == 'history' ? 'repair-history.php' : 'assigned-repairs.php'; ?>" class="back-link">
                        &lt; Back to Assigned Repairs 
                    </a> 
                            
                    <h1>Repair Booking Detail</h1> 
                    <div class="detail-grid"> 
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
                                
                        <div class="detail-box"> 
                            <h3>Repair Information</h3> 
                            <p><b>Booking ID:</b> #RB<?= $data['booking_id']; ?></p> 
                            <p><b>Device:</b> <?= $data['device_type']; ?></p> 
                            <p><b>Repair Type:</b> <?= $data['repair_type']; ?></p> 
                            <p><b>Issue:</b> <?= $data['issue_description']; ?></p> 
                            <p><b>Preferred Date:</b> <?= $data['preferred_date']; ?></p> 
                            <p><b>Address:</b> <?= $data['address']; ?></p> 
                            <p><b>Status:</b> <span class="status <?= strtolower(str_replace(' ', '-', $data['status'])); ?>"> <?= $data['status']; ?></span></p>
                        </div> 
                                
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

                            <?php }else{ ?>

                                <p>No image uploaded.</p>

                            <?php } ?>
                        </div> 
                                
                        <div class="detail-box full"> 
                            <h3>Update Repair Status</h3> 
                            <form method="POST" class="status-form"> 
                                <select name="status"> 
                                    <option value="Assigned" 
                                        <?= $data['status']=='Assigned'?'selected':''; ?>> 
                                        Assigned 
                                    </option> 

                                    <option value="In Progress" 
                                        <?= $data['status']=='In Progress'?'selected':''; ?>> 
                                        In Progress 
                                    </option> 

                                    <option value="Workshop Repair" 
                                        <?= $data['status']=='Workshop Repair'?'selected':''; ?>> 
                                        Workshop Repair 
                                    </option> 
                                            
                                    <option value="Completed" 
                                        <?= $data['status']=='Completed'?'selected':''; ?>> 
                                        Completed 
                                    </option> 
                                            
                                    <option value="Cancelled" 
                                        <?= $data['status']=='Cancelled'?'selected':''; ?>> 
                                        Cancelled 
                                    </option> 
                                        
                                </select> 
                                        
                                <button type="submit" name="update_status"> 
                                    Update Status 
                                </button> 
                                    
                            </form> 
                                
                        </div> 
                            
                    </div> 
                        
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