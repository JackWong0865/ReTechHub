<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/*check cart id*/
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    $_SESSION['remove_cart_error'] =
        "Invalid cart item.";

    header("Location: cart.php");
    exit();
}

/*get cart id*/
$cart_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

/*delete cart item*/
$result = mysqli_query(
    $conn,
    "DELETE FROM cart
     WHERE cart_id='$cart_id'
     AND user_id='$user_id'"
);

/*check delete result*/
if($result && mysqli_affected_rows($conn) > 0){

    $_SESSION['remove_cart_success'] =
        "Item removed from your cart successfully.";

}else{

    $_SESSION['remove_cart_error'] =
        "Unable to remove the item from your cart.";
}

/*return to cart page*/
header("Location: cart.php");
exit();
?>