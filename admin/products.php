<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$products = mysqli_query(
    $conn,
    "SELECT * FROM products 
     WHERE status='published'
     ORDER BY product_id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Listings - ReTech Hub</title>

    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-products.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="admin-layout">

    <!--side and top bar-->
    <aside class="sidebar">

        <img src="../assets/images/logo.png" class="admin-logo">

        <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
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

        <div class="admin-top">

            <form class="admin-search" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search user ID, product, booking ID, order ID..."required>

                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <div class="admin-icons">

                <a href="messages.php" class="top-icon badge-wrap">

                    <i class="fa-solid fa-message"></i><span>Message</span>
                    <span id="messageBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="notifications.php" class="top-icon badge-wrap">

                    <i class="fa-solid fa-bell"></i><span>Notification</span>

                    <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span></a>

                <a href="../profile.php" class="admin-profile">
                    <img
                    src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default-avatar.png'; ?>"
                    class="admin-avatar">

                    <span>
                        <?= $_SESSION['username']; ?>
                    </span>
                </a>

            </div>

        </div>

        <!--page bar-->
        <div class="products-header">

            <div>
                <h1>Product Listings</h1>
                <p>Manage all published products in the marketplace.</p>
            </div>

            <a href="upload.php" class="add-product-btn">
                <i class="fa-solid fa-plus"></i>
                Add Product
            </a>

        </div>

        <div class="products-table-card">

            <!--product table-->
            <table>

                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Condition</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <!--product data-->
                <?php if(mysqli_num_rows($products) > 0){ ?>

                    <?php while($p = mysqli_fetch_assoc($products)){ ?>

                    <?php
                    $img_query = mysqli_query(
                        $conn,
                        "SELECT * FROM product_images
                         WHERE product_id='".$p['product_id']."'
                         LIMIT 1"
                    );

                    $img = mysqli_fetch_assoc($img_query);

                    $image_path = $img
                        ? $img['image_path']
                        : "assets/images/products/default-product.png";
                    ?>

                    <tr>

                        <td>
                            <img
                            src="../<?= $image_path; ?>"
                            class="product-thumb">
                        </td>

                        <td>
                            <b><?= $p['product_name']; ?></b>
                            <br>
                            <small>ID: #<?= $p['product_id']; ?></small>
                        </td>

                        <td><?= $p['category']; ?></td>

                        <td><?= $p['brand']; ?></td>

                        <td>
                            <span class="condition-tag <?= strtolower(str_replace(' ','-',$p['condition_type'])); ?>">
                                <?= $p['condition_type']; ?>
                            </span>
                        </td>

                        <td>
                            <b>RM<?= number_format($p['price'], 2); ?></b>
                        </td>

                        <td><?= $p['stock']; ?></td>

                        <td>
                            <span class="status published">
                                Published
                            </span>
                        </td>

                        <!--action button-->
                        <td>
                            <div class="action-buttons">

                                <a href="view-product.php?id=<?= $p['product_id']; ?>" class="view-btn">View</a>
                                <a href="edit-product.php?id=<?= $p['product_id']; ?>" class="edit-btn">Edit</a>
                                <a
                                    href="javascript:void(0);"
                                    class="small-btn delete"
                                    onclick="confirmDeleteProduct(
                                    <?= (int)$p['product_id']; ?>,
                                    <?= htmlspecialchars(
                                        json_encode($p['product_name']),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                )"
                                >
                                    Delete
                                </a>

                            </div>
                        </td>

                    </tr>

                    <?php } ?>

                <?php }else{ ?>

                    <!--display when no product found-->
                    <tr>
                        <td colspan="9" class="empty-row">
                            No published products found.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </main>

</div>

<?php include("../includes/live-badges.php"); ?>

<!--delete confirmation window-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDeleteProduct(productId, productName){

    Swal.fire({
        title: "Delete Product?",
        html:
            "Are you sure you want to delete <b>" +
            productName +
            "</b>?<br><br>" +
            "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e53935",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",

    }).then((result) => {

        if(result.isConfirmed){

            window.location.href =
                "delete-product.php?id=" + productId;
        }

    });

}
</script>

<!-- Publish Success -->
<?php if(isset($_SESSION['publish_success'])){ ?>
<script>
Swal.fire({
    icon: "success",
    title: "Product Published",
    text: <?= json_encode($_SESSION['publish_success']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>
<?php
unset($_SESSION['publish_success']);
}
?>

<!-- Delete Success -->
<?php if(isset($_SESSION['delete_product_success'])){ ?>
<script>
Swal.fire({
    icon: "success",
    title: "Success",
    text: <?= json_encode($_SESSION['delete_product_success']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#d32f2f"
});
</script>
<?php
unset($_SESSION['delete_product_success']);
}
?>

<!-- Delete Error -->
<?php if(isset($_SESSION['delete_product_error'])){ ?>
<script>
Swal.fire({
    icon: "error",
    title: "Delete Failed",
    text: <?= json_encode($_SESSION['delete_product_error']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#d32f2f"
});
</script>
<?php
unset($_SESSION['delete_product_error']);
}
?>
</body>
</html>