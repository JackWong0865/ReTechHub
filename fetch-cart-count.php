<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    echo 0;
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT SUM(quantity) AS total
     FROM cart
     WHERE user_id='$user_id'"
);

$data = mysqli_fetch_assoc($query);

echo $data['total'] ?? 0;