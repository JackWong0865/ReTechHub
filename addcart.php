<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){

    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

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
             SET quantity = quantity + 1
             WHERE user_id='$user_id'
             AND product_id='$product_id'"
        );

        $_SESSION['cart_message'] = "Product quantity updated in cart.";

    }else{

        mysqli_query(
            $conn,
            "INSERT INTO cart(user_id, product_id, quantity)
             VALUES('$user_id','$product_id',1)"
        );

        $_SESSION['cart_message'] = "Product added to cart successfully.";
    }
}

header("Location: marketplace.php");
exit();
?>