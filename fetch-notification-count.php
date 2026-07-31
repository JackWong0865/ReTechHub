<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    echo 0;
    exit();
}

$user_id = $_SESSION['user_id'];

$count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM notifications
         WHERE user_id='$user_id'
         AND is_read=0"
    )
);

echo $count['total'];
?>