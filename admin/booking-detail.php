<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}


if(!isset($_GET['type']) || !isset($_GET['id'])){
    header("Location: repairs.php");
    exit();
}

$type = $_GET['type'];
$id = (int)$_GET['id'];

if($type != "repair" && $type != "sell"){
    header("Location: repairs.php");
    exit();
}

/*update status*/

if(isset($_POST['update_status'])){

    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if($type == "repair"){

        mysqli_query(
            $conn,
            "UPDATE repair_bookings
            SET status='$status'
            WHERE booking_id='$id'"
        );

        /*get booking user*/

        $booking = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT * FROM repair_bookings
                WHERE booking_id='$id'"
            )
        );

        if($booking){

            $notify_user = $booking['user_id'];

            mysqli_query(
                $conn,
                "INSERT INTO notifications
                (user_id,title,message,type)
                VALUES
                (
                    '$notify_user',
                    'Repair Status Updated',
                    'Your repair booking #RB".$id." status was updated to ".$status." by admin.',
                    'repair'
                )"
            );

        }

    }else{

        mysqli_query(
            $conn,
            "UPDATE sell_requests
            SET status='$status'
            WHERE sell_id='$id'"
        );

        $booking = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT * FROM sell_requests
                WHERE sell_id='$id'"
            )
        );

        if($booking){

            $notify_user = $booking['user_id'];

            mysqli_query(
                $conn,
                "INSERT INTO notifications
                (user_id,title,message,type)
                VALUES
                (
                    '$notify_user',
                    'Sell Booking Updated',
                    'Your sell booking #SB".$id." status was updated to ".$status." by admin.',
                    'sell'
                )"
            );

        }

    }

    $_SESSION['status_success'] = "Booking status updated successfully.";

    header("Location: booking-detail.php?type=$type&id=$id");
    exit();

}


/*assign technician*/

if(isset($_POST['assign_technician']) && $type == "repair"){

    $technician_id = (int)$_POST['technician_id'];

    $oldBooking = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT technician_id
             FROM repair_bookings
             WHERE booking_id='$id'"
        )
    );

    $oldTechId = $oldBooking['technician_id'];

    mysqli_query(
        $conn,
        "UPDATE repair_bookings
         SET technician_id='$technician_id',
             status='Assigned'
         WHERE booking_id='$id'"
    );

    /*create notification*/

    $booking = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM repair_bookings
             WHERE booking_id='$id'"
        )
    );

    $tech = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT username FROM users
             WHERE user_id='$technician_id'"
        )
    );

    if($booking && $tech){

        //user notification
        if($oldTechId && $oldTechId != $technician_id){

            $title = "Technician Updated";
            $message = "The technician for your repair booking #RB".$id." has been changed to ".$tech['username'].".";

        }else{

            $title = "Technician Assigned";
            $message = "Technician ".$tech['username']." has been assigned to your repair booking #RB".$id.".";

        }

        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id,title,message,type)
            VALUES
            (
                '".$booking['user_id']."',
                '$title',
                '$message',
                'repair'
            )"
        );

        //notify old technician
        if($oldTechId && $oldTechId != $technician_id){

            mysqli_query(
                $conn,
                "INSERT INTO notifications
                (user_id,title,message,type)
                VALUES
                (
                    '$oldTechId',
                    'Repair Reassigned',
                    'Repair booking #RB".$id." has been reassigned to another technician.',
                    'repair'
                )"
            );

        }

        //notify new technician
        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id,title,message,type)
            VALUES
            (
                '$technician_id',
                'New Repair Assigned',
                'You have been assigned repair booking #RB".$id.".',
                'repair'
            )"
        );

    }

    $_SESSION['status_success'] = "Technician assigned successfully.";

    header("Location: booking-detail.php?type=$type&id=$id");
    exit();
}

/*technician list*/

$technicians = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE role='technician'"
);

/*get booking detail*/

if($type == "repair"){

    $query = mysqli_query(
        $conn,
        "SELECT r.*, 
                u.username, u.email, u.phone,
                t.username AS technician_name
         FROM repair_bookings r
         LEFT JOIN users u ON r.user_id = u.user_id
         LEFT JOIN users t ON r.technician_id = t.user_id
         WHERE r.booking_id='$id'"
    );

}else{

    $query = mysqli_query(
        $conn,
        "SELECT s.*, 
                u.username, u.email, u.phone
         FROM sell_requests s
         LEFT JOIN users u ON s.user_id = u.user_id
         WHERE s.sell_id='$id'"
    );
}

if(!$query || mysqli_num_rows($query) == 0){
    die("Booking not found.");
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Booking Detail</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin-repairs.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="admin-layout">

            <aside class="sidebar">
                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
                <a href="repairs.php" class="active"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
                <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
                <a href="messages.php"><i class="fa-solid fa-message"></i>Messages</a>
                <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </aside>

            <main class="admin-main">

                <div class="booking-detail-card">

                    <a href="repairs.php" class="back-link"> &lt; Back to Booking Details </a>

                    <h1> <?= ucfirst($type); ?> Booking Detail </h1>

                    <div class="detail-grid">

                        <div class="detail-box">
                            <h3>Customer Information</h3>

                            <p><b>Name:</b> <?= $data['username']; ?></p>
                            <p><b>Email:</b> <?= $data['email']; ?></p>
                            <p><b>Phone:</b> <?= $data['phone']; ?></p>
                        </div>

                        <div class="detail-box">
                            <h3>Booking Information</h3>

                            <p><b>Device:</b> <?= $data['device_type']; ?></p>

                            <?php if($type == "repair"){ ?>

                                <p><b>Repair Type:</b> <?= $data['repair_type']; ?></p>
                                <p><b>Issue:</b> <?= $data['issue_description']; ?></p>

                            <?php }else{ ?>

                                <p><b>Brand:</b> <?= $data['brand']; ?></p>
                                <p><b>Model:</b> <?= $data['model']; ?></p>
                                <p><b>Condition:</b> <?= $data['condition_type']; ?></p>
                                <p><b>Description:</b> <?= $data['description']; ?></p>

                            <?php } ?>

                            <p><b>Preferred Date:</b> <?= $data['preferred_date']; ?></p>
                            <p><b>Address:</b> <?= $data['address']; ?></p>
                            <p><b>Status:</b>
                                <span class="repair-status <?= strtolower(str_replace(' ', '-', $data['status'])); ?>">
                                    <?= $data['status']; ?>
                                </span>
                            </p>

                            <?php if($type == "repair"){ ?>
                                <p>
                                    <b>Current Technician:</b>
                                    <?= !empty($data['technician_name']) ? $data['technician_name'] : 'Not Assigned'; ?>
                                </p>
                            <?php } ?>

                            <div class="admin-action-box">

                                <h3>Admin Actions</h3>

                                <!-- Assign Technician -->

                                <?php if($type == "repair"){ ?>

                                    <form method="POST" class="detail-action-form">

                                        <label>Assign Technician</label>

                                        <select name="technician_id" required>
                                            <option value="">Select Technician</option>

                                            <?php
                                            mysqli_data_seek($technicians, 0);
                                            while($tech = mysqli_fetch_assoc($technicians)){
                                            ?>
                                                <option
                                                value="<?= $tech['user_id']; ?>"
                                                <?= isset($data['technician_id']) && $data['technician_id'] == $tech['user_id'] ? 'selected' : ''; ?>>

                                                    <?= $tech['username']; ?>

                                                </option>
                                            <?php } ?>

                                        </select>

                                        <button type="submit" name="assign_technician">
                                            Assign Technician
                                        </button>

                                    </form>

                                <?php } ?>

                                <!-- Update Status -->

                                <form method="POST" class="detail-action-form">

                                    <label>Update Status</label>

                                    <select name="status">
                                        <?php if($type == "repair"){ ?>
                                            <option value="Pending" <?= $data['status']=='Pending'?'selected':''; ?>>
                                                Pending
                                            </option>

                                            <option value="Assigned" <?= $data['status']=='Assigned'?'selected':''; ?>>
                                            Assigned
                                            </option>

                                            <option value="In Progress" <?= $data['status']=='In Progress'?'selected':''; ?>>
                                                In Progress
                                            </option>

                                            <option value="Workshop Repair" <?= $data['status']=='Workshop Repair'?'selected':''; ?>> 
                                                Workshop Repair 
                                            </option> 

                                            <option value="Completed" <?= $data['status']=='Completed'?'selected':''; ?>>
                                                Completed
                                            </option>

                                            <option value="Cancelled" <?= $data['status']=='Cancelled'?'selected':''; ?>>
                                                Cancelled
                                            </option>

                                        <?php }else{ ?>
                                            <option value="Pending" <?= $data['status']=='Pending'?'selected':''; ?>>
                                                Pending
                                            </option>

                                            <option value="Approved" <?= $data['status']=='Approved'?'selected':''; ?>>
                                                Approved
                                            </option>

                                            <option value="Completed" <?= $data['status']=='Completed'?'selected':''; ?>>
                                                Completed
                                            </option>

                                            <option value="Rejected" <?= $data['status']=='Rejected'?'selected':''; ?>>
                                                Rejected
                                            </option>

                                        <?php } ?>

                                    </select>

                                    <button type="submit" name="update_status">
                                        Update Status
                                    </button>

                                </form>

                            </div>
                        </div>

                        <div class="detail-box full">
                            <h3>User Uploaded Photo</h3>

                            <?php
                            $booking_images = mysqli_query(
                                $conn,
                                "SELECT * FROM booking_images
                                WHERE booking_type='$type'
                                AND booking_id='$id'
                                ORDER BY image_id ASC"
                            );
                            ?>

                            <?php if(mysqli_num_rows($booking_images) > 0){ ?>

                                <div class="booking-image-grid">

                                    <?php while($img = mysqli_fetch_assoc($booking_images)){ ?>

                                        <a href="../<?= $img['image_path']; ?>" target="_blank">
                                            <img
                                            src="../<?= $img['image_path']; ?>"
                                            class="booking-image">
                                        </a>

                                    <?php } ?>

                                </div>

                            <?php }else{ ?>

                                <p>No image uploaded.</p>

                            <?php } ?>
                        </div>

                    </div>

                </div>

            </main>

        </div>
        <!--success message window-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php if(isset($_SESSION['status_success'])){ ?>

        <script>

        Swal.fire({
            icon: "success",
            title: "Success",
            text: "<?= $_SESSION['status_success']; ?>",
            confirmButtonText: "OK",
            confirmButtonColor: "#d32f2f"
        });

        </script>

        <?php unset($_SESSION['status_success']); } ?>

    </body>
</html>