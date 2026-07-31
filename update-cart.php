<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$cart_id = (int)$_GET['id'];
$action = $_GET['action'];
$user_id = $_SESSION['user_id'];

$item = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT * FROM cart 
     WHERE cart_id='$cart_id' 
     AND user_id='$user_id'"
));

if($item){

    if($action == "plus"){
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE cart_id='$cart_id'");
    }

    if($action == "minus"){
        if($item['quantity'] > 1){
            mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE cart_id='$cart_id'");
        }
    }
}

header("Location: cart.php");
exit();
?>