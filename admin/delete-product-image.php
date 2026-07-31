<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['product_id'])){

    $image_id = (int)$_GET['id'];
    $product_id = (int)$_GET['product_id'];

    mysqli_query(
        $conn,
        "DELETE FROM product_images
         WHERE image_id='$image_id'"
    );

    header("Location: edit-product.php?id=".$product_id);
    exit();
}

header("Location: products.php");
exit();
?>