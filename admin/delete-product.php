<?php
session_start();
include("../includes/db.php");

/*check admin permission*/
if(
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
){
    header("Location: ../login.php");
    exit();
}

/*check product id*/
if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){

    $_SESSION['delete_product_error'] =
        "Invalid product ID.";

    header("Location: products.php");
    exit();
}

/*get product id*/
$product_id = (int)$_GET['id'];

/*get product information*/
$product_stmt = mysqli_prepare(
    $conn,
    "SELECT product_name
     FROM products
     WHERE product_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $product_stmt,
    "i",
    $product_id
);

mysqli_stmt_execute($product_stmt);

$product_result =
    mysqli_stmt_get_result($product_stmt);

$product =
    mysqli_fetch_assoc($product_result);

mysqli_stmt_close($product_stmt);

/*check whether product exists*/
if(!$product){

    $_SESSION['delete_product_error'] =
        "Product not found.";

    header("Location: products.php");
    exit();
}

/*get product image paths*/
$image_stmt = mysqli_prepare(
    $conn,
    "SELECT image_path
     FROM product_images
     WHERE product_id = ?"
);

mysqli_stmt_bind_param(
    $image_stmt,
    "i",
    $product_id
);

mysqli_stmt_execute($image_stmt);

$image_result =
    mysqli_stmt_get_result($image_stmt);

/*store image paths before deleting database records*/
$image_paths = [];

while($image = mysqli_fetch_assoc($image_result)){

    if(!empty($image['image_path'])){

        $image_paths[] =
            $image['image_path'];
    }
}

mysqli_stmt_close($image_stmt);

/*start database transaction*/
mysqli_begin_transaction($conn);

try{

    /*delete product images from database*/
    $delete_images_stmt = mysqli_prepare(
        $conn,
        "DELETE FROM product_images
         WHERE product_id = ?"
    );

    mysqli_stmt_bind_param(
        $delete_images_stmt,
        "i",
        $product_id
    );

    if(!mysqli_stmt_execute($delete_images_stmt)){

        throw new Exception(
            mysqli_stmt_error(
                $delete_images_stmt
            )
        );
    }

    mysqli_stmt_close(
        $delete_images_stmt
    );

    /*delete product*/
    $delete_product_stmt = mysqli_prepare(
        $conn,
        "DELETE FROM products
         WHERE product_id = ?"
    );

    mysqli_stmt_bind_param(
        $delete_product_stmt,
        "i",
        $product_id
    );

    if(!mysqli_stmt_execute($delete_product_stmt)){

        throw new Exception(
            mysqli_stmt_error(
                $delete_product_stmt
            )
        );
    }

    $affected_rows =
        mysqli_stmt_affected_rows(
            $delete_product_stmt
        );

    mysqli_stmt_close(
        $delete_product_stmt
    );

    /*check whether product was deleted*/
    if($affected_rows <= 0){

        throw new Exception(
            "The product could not be deleted."
        );
    }

    /*commit database changes*/
    mysqli_commit($conn);

    /*delete physical image files*/
    foreach($image_paths as $image_path){

        $full_path =
            "../" . ltrim(
                $image_path,
                "/"
            );

        if(is_file($full_path)){

            unlink($full_path);
        }
    }

    /*save delete success message*/
    $_SESSION['delete_product_success'] =
        $product['product_name'] .
        " has been deleted successfully.";

}catch(Exception $e){

    /*rollback database changes*/
    mysqli_rollback($conn);

    /*save delete error message*/
    $_SESSION['delete_product_error'] =
        "Unable to delete the product: " .
        $e->getMessage();
}

/*return to products page*/


header("Location: products.php");
exit();
?>