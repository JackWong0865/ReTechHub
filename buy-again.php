<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
    header("Location: my-order.php");
    exit();
}

$order_id = (int)$_GET['id'];

/* confirm this order belongs to this user */
$order = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE order_id='$order_id'
     AND user_id='$user_id'"
);

if(!$order || mysqli_num_rows($order) == 0){
    header("Location: my-order.php");
    exit();
}

/* get old order items */
$items = mysqli_query(
    $conn,
    "SELECT *
     FROM order_items
     WHERE order_id='$order_id'"
);

while($item = mysqli_fetch_assoc($items)){

    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    /* check if product already in cart */
    $check = mysqli_query(
        $conn,
        "SELECT *
         FROM cart
         WHERE user_id='$user_id'
         AND product_id='$product_id'"
    );

    if(mysqli_num_rows($check) > 0){

        mysqli_query(
            $conn,
            "UPDATE cart
             SET quantity = quantity + '$quantity'
             WHERE user_id='$user_id'
             AND product_id='$product_id'"
        );

    }else{

        mysqli_query(
            $conn,
            "INSERT INTO cart
             (user_id, product_id, quantity)
             VALUES
             ('$user_id','$product_id','$quantity')"
        );

    }
}

/* go checkout, user re-enter details */
header("Location: checkout.php?buy_again=1");
exit();
?>