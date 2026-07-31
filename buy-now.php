<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: marketplace.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_GET['id'];

$check = mysqli_query(
    $conn,
    "SELECT * FROM cart
     WHERE user_id='$user_id'
     AND product_id='$product_id'"
);

if(mysqli_num_rows($check) > 0){
    mysqli_query(
        $conn,
        "UPDATE cart
         SET quantity=1
         WHERE user_id='$user_id'
         AND product_id='$product_id'"
    );
}else{
    mysqli_query(
        $conn,
        "INSERT INTO cart
        (user_id, product_id, quantity)
        VALUES
        ('$user_id','$product_id',1)"
    );
}

header("Location: checkout.php");
exit();
?>