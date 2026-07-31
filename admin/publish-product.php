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
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    $_SESSION['publish_error'] = "Invalid product ID.";

    header("Location: draft.php");
    exit();
}

/*get product id*/
$product_id = (int)$_GET['id'];

/*get draft product information*/
$product_stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM products
     WHERE product_id = ?
     AND status = 'draft'
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $product_stmt,
    "i",
    $product_id
);

mysqli_stmt_execute($product_stmt);

$product_result = mysqli_stmt_get_result($product_stmt);

$product = mysqli_fetch_assoc($product_result);

mysqli_stmt_close($product_stmt);

/*check whether draft exists*/
if(!$product){

    $_SESSION['publish_error'] = "Draft product not found or the product has already been published.";

    header("Location: draft.php");
    exit();
}

/*create missing information list*/
$missing_fields = [];

/*check product name*/
if(empty(trim($product['product_name'] ?? ''))){
    $missing_fields[] = "Product Name";
}

/*check category*/
if(empty(trim($product['category'] ?? ''))){
    $missing_fields[] = "Category";
}

/*check brand*/
if(empty(trim($product['brand'] ?? ''))){
    $missing_fields[] = "Brand";
}

/*check product condition*/
if(empty(trim($product['condition_type'] ?? ''))){
    $missing_fields[] = "Condition";
}

/*check description*/
if(empty(trim($product['description'] ?? ''))){
    $missing_fields[] = "Description";
}

/*check product price*/
if(
    !isset($product['price']) ||
    !is_numeric($product['price']) ||
    (float)$product['price'] <= 0
){
    $missing_fields[] = "Valid Price";
}

/*check product stock*/
if(
    !isset($product['stock']) ||
    !is_numeric($product['stock']) ||
    (int)$product['stock'] <= 0
){
    $missing_fields[] = "Valid Stock";
}

/*check location*/
if(empty(trim($product['location'] ?? ''))){
    $missing_fields[] = "Location";
}

/*check whether product has at least one image*/
$image_stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total_images
     FROM product_images
     WHERE product_id = ?"
);

mysqli_stmt_bind_param(
    $image_stmt,
    "i",
    $product_id
);

mysqli_stmt_execute($image_stmt);

$image_result = mysqli_stmt_get_result($image_stmt);

$image_data = mysqli_fetch_assoc($image_result);

mysqli_stmt_close($image_stmt);

$total_images = (int)($image_data['total_images'] ?? 0);

/*require at least one product image*/
if($total_images <= 0){ $missing_fields[] = "At Least One Product Image"; }

/*stop publishing when information is incomplete*/
if(!empty($missing_fields)){

    $missing_html = "Please complete the following information before publishing:<br><br>";

    foreach($missing_fields as $field){

        $missing_html .= "• " . htmlspecialchars($field) . "<br>";
    }

    $_SESSION['publish_error'] = $missing_html;

    $_SESSION['publish_product_id'] = $product_id;

    header("Location: draft.php");
    exit();
}

/*update product status to published*/
$publish_stmt = mysqli_prepare(
    $conn,
    "UPDATE products
     SET status = 'published'
     WHERE product_id = ?
     AND status = 'draft'"
);

mysqli_stmt_bind_param(
    $publish_stmt,
    "i",
    $product_id
);

$published = mysqli_stmt_execute($publish_stmt);

/*check affected rows*/
$affected_rows = mysqli_stmt_affected_rows($publish_stmt);

/*save database error before closing statement*/
$publish_error = mysqli_stmt_error($publish_stmt);

mysqli_stmt_close($publish_stmt);

/*display publish result*/
if($published && $affected_rows > 0){

    $_SESSION['publish_success'] =
        $product['product_name'] .
        " has been published successfully.";

}else{

    $_SESSION['publish_error'] =
        "Unable to publish the product. " .
        $publish_error;
}

/*return to draft page*/
header("Location: draft.php");
exit();
?>