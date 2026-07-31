<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*check product id*/
if(!isset($_GET['id'])){
    header("Location: products.php");
    exit();
}

/*get product id*/
$product_id = (int)$_GET['id'];

/*check product info*/
$query = mysqli_query(
    $conn,
    "SELECT * FROM products
     WHERE product_id='$product_id'"
);

/*check if the product exists*/
if(!$query || mysqli_num_rows($query) == 0){
    die("Product not found.");
}

/*get product info*/
$product = mysqli_fetch_assoc($query);

/*check product picture*/
$images = mysqli_query(
    $conn,
    "SELECT * FROM product_images
     WHERE product_id='$product_id'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Product - Admin</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="admin-layout">
    <!--side bar-->
    <aside class="sidebar">
        <img src="../assets/images/logo.png" class="admin-logo">

        <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="users.php"><i class="fa-solid fa-users"></i>Users</a>
        <a href="products.php" class="active"><i class="fa-solid fa-box"></i> Listings</a>
        <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
        <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
        <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
        <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
        <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
        <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
        <a href="messages.php"><i class="fa-solid fa-message"></i>Messages</a>
        <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </aside>

    <main class="admin-main">
        <!--top bar-->
        <div class="admin-top">

            <form class="admin-search" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search user ID, product, booking ID, order ID..."required>

                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <div class="admin-icons">

                <a href="messages.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-message"></i>
                    <span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;">0</span>
                </a>

                <a href="notifications.php" class="top-icon badge-wrap">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notification</span>
                    <span id="notificationBadge" class="live-badge" style="display:none;">0</span>
                </a>

                <a href="../profile.php" class="admin-profile">
                    <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default-avatar.png'; ?>" class="admin-avatar">
                    <span><?= $_SESSION['username']; ?></span>
                </a>

            </div>

        </div>

        <!--page code-->
        <div class="product-view-card">

            <a href="products.php" class="back-link">
                &lt; Back to Listings
            </a>

            <h1><?= $product['product_name']; ?></h1>

            <div class="product-view-layout">

                <div class="product-view-images">

                    <?php if(mysqli_num_rows($images) > 0){ ?>

                        <?php while($img = mysqli_fetch_assoc($images)){ ?>

                            <img src="../<?= $img['image_path']; ?>">

                        <?php } ?>

                    <?php }else{ ?>

                        <img src="../assets/images/products/default-product.png">

                    <?php } ?>

                </div>

                <!--product card-->
                <div class="product-view-info">

                    <p><b>Product ID:</b> #<?= $product['product_id']; ?></p>
                    <p><b>Category:</b> <?= $product['category']; ?></p>
                    <p><b>Brand:</b> <?= $product['brand']; ?></p>
                    <p><b>Condition:</b> <?= $product['condition_type']; ?></p>
                    <p><b>Price:</b> RM<?= number_format($product['price'], 2); ?></p>
                    <p><b>Stock:</b> <?= $product['stock']; ?></p>
                    <p><b>Status:</b> <?= $product['status']; ?></p>

                    <div class="product-desc-box">
                        <h3>Description</h3>
                        <p><?= $product['description']; ?></p>
                    </div>

                    <div class="product-view-actions">

                        <a href="edit-product.php?id=<?= $product['product_id']; ?>" class="edit-btn">
                            Edit Product
                        </a>

                        <a
                        href="delete-product.php?id=<?= $product['product_id']; ?>"
                        class="delete-btn delete-product">
                            Delete Product
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<?php include("../includes/live-badges.php"); ?>

<!--confirm delete window-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelector(".delete-product").addEventListener("click", function(e){

    e.preventDefault();

    const url = this.href;

    Swal.fire({
        title: "Delete Product?",
        text: "This product will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#d32f2f",
        cancelButtonColor: "#6c757d"
    }).then(function(result){

        if(result.isConfirmed){
            window.location.href = url;
        }

    });

});
</script>

</body>
</html>