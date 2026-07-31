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

    $_SESSION['delete_draft_error'] =
        "Invalid product ID.";

    header("Location: draft.php");
    exit();
}

/*get product id*/
$product_id = (int)$_GET['id'];

/*get draft product information*/
$product_stmt = mysqli_prepare(
    $conn,
    "SELECT product_name
     FROM products
     WHERE product_id = ?
     AND status = 'draft'
     LIMIT 1"
);

if(!$product_stmt){

    $_SESSION['delete_draft_error'] =
        "Unable to prepare product query: " .
        mysqli_error($conn);

    header("Location: draft.php");
    exit();
}

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

/*check whether draft exists*/
if(!$product){

    $_SESSION['delete_draft_error'] =
        "Draft product not found.";

    header("Location: draft.php");
    exit();
}

/*get product image paths*/
$image_stmt = mysqli_prepare(
    $conn,
    "SELECT image_path
     FROM product_images
     WHERE product_id = ?"
);

if(!$image_stmt){

    $_SESSION['delete_draft_error'] =
        "Unable to retrieve product images: " .
        mysqli_error($conn);

    header("Location: draft.php");
    exit();
}

mysqli_stmt_bind_param(
    $image_stmt,
    "i",
    $product_id
);

mysqli_stmt_execute($image_stmt);

$image_result =
    mysqli_stmt_get_result($image_stmt);

/*store image paths before deleting records*/
$image_paths = [];

while($image = mysqli_fetch_assoc($image_result)){

    if(!empty($image['image_path'])){

        $image_paths[] =
            $image['image_path'];
    }
}

mysqli_stmt_close($image_stmt);

/*start transaction*/
mysqli_begin_transaction($conn);

try{

    /*delete product image records*/
    $delete_images_stmt = mysqli_prepare(
        $conn,
        "DELETE FROM product_images
         WHERE product_id = ?"
    );

    if(!$delete_images_stmt){

        throw new Exception(
            mysqli_error($conn)
        );
    }

    mysqli_stmt_bind_param(
        $delete_images_stmt,
        "i",
        $product_id
    );

    if(!mysqli_stmt_execute($delete_images_stmt)){

        throw new Exception(
            mysqli_stmt_error($delete_images_stmt)
        );
    }

    mysqli_stmt_close($delete_images_stmt);

    /*delete draft product*/
    $delete_product_stmt = mysqli_prepare(
        $conn,
        "DELETE FROM products
         WHERE product_id = ?
         AND status = 'draft'"
    );

    if(!$delete_product_stmt){

        throw new Exception(
            mysqli_error($conn)
        );
    }

    mysqli_stmt_bind_param(
        $delete_product_stmt,
        "i",
        $product_id
    );

    if(!mysqli_stmt_execute($delete_product_stmt)){

        throw new Exception(
            mysqli_stmt_error($delete_product_stmt)
        );
    }

    $affected_rows =
        mysqli_stmt_affected_rows(
            $delete_product_stmt
        );

    mysqli_stmt_close($delete_product_stmt);

    /*make sure product was deleted*/
    if($affected_rows <= 0){

        throw new Exception(
            "The draft product could not be deleted."
        );
    }

    /*commit database changes*/
    mysqli_commit($conn);

    /*delete physical image files*/
    foreach($image_paths as $image_path){

        $full_path =
            "../" . ltrim($image_path, "/");

        if(is_file($full_path)){

            @unlink($full_path);
        }
    }

    /*save success message*/
    $_SESSION['delete_draft_success'] =
        $product['product_name'] .
        " has been deleted successfully.";

}catch(Throwable $e){

    /*rollback database changes*/
    mysqli_rollback($conn);

    $_SESSION['delete_draft_error'] =
        "Unable to delete the draft: " .
        $e->getMessage();
}

/*return to draft page*/
header("Location: draft.php");
exit();
?>