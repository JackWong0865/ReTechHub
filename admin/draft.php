<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*get all products with the status draft*/
$drafts = mysqli_query($conn, "SELECT * FROM products WHERE status='draft' ORDER BY product_id DESC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Draft Products</title>
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <!-- top n side bar -->
        <div class="admin-layout">

            <aside class="sidebar">
                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php" class="active"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
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

                            <i class="fa-solid fa-message"></i>
                            <span>Message</span>

                            <span id="messageBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">

                            <i class="fa-solid fa-bell"></i>
                            <span>Notification</span>

                            <span id="notificationBadge" class="live-badge" style="display:none;">
                                0
                            </span>

                        </a>

                        <a href="../profile.php" class="admin-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default-avatar.png'; ?>" class="admin-avatar">
                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <h1>Draft Products</h1>
                <p class="welcome">Products saved as draft but not published yet.</p>

                <div class="table-card">
                    <table>
                        <tr>
                            
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>

                        <?php if(mysqli_num_rows($drafts) > 0){ ?>

                            <?php while($d = mysqli_fetch_assoc($drafts)){ ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($d['product_name']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($d['category'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        RM<?= number_format(
                                            (float)($d['price'] ?? 0),
                                            2
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= (int)($d['stock'] ?? 0); ?>
                                    </td>

                                    <td>

                                        <a href="edit-product.php?id=<?= (int)$d['product_id']; ?>"
                                            class="small-btn">
                                            Edit
                                        </a>

                                        <a
                                            href="javascript:void(0);"
                                            class="small-btn red"
                                            onclick="confirmPublishDraft(
                                                <?= (int)$d['product_id']; ?>,
                                                <?= htmlspecialchars(
                                                    json_encode($d['product_name']),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>
                                            )">
                                            Publish
                                        </a>

                                        <a
                                            href="javascript:void(0);"
                                            class="small-btn delete"
                                            onclick="confirmDeleteDraft(
                                                <?= (int)$d['product_id']; ?>,
                                                <?= htmlspecialchars(
                                                    json_encode($d['product_name']),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>
                                            )">
                                            Delete
                                        </a>

                                    <td>

                                </tr>

                            <?php } ?>

                        <?php }else{ ?>

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:center; padding:30px;"
                                >
                                    No draft products found.
                                </td>

                            </tr>

                        <?php } ?>
                    </table>
                </div>

            </main>

        </div>

        <!--draft save success window-->
        <?php include("../includes/live-badges.php"); ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        /*confirm before publishing draft*/
        function confirmPublishDraft(productId, productName){

            Swal.fire({
                icon: "question",
                title: "Publish Product?",
                html:
                    "Are you sure you want to publish <b>" +
                    productName +
                    "</b>?<br><br>" +
                    "The system will check whether all required information has been completed.",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e53935",
                cancelButtonColor: "#6c757d",         
            }).then((result) => {

                if(result.isConfirmed){

                    window.location.href =
                        "publish-product.php?id=" + productId;
                }

            });
        }

        /*confirm before deleting draft*/
        function confirmDeleteDraft(productId, productName, page){

            Swal.fire({
                icon: "warning",
                title: "Delete Draft?",
                html:
                    "Are you sure you want to delete <b>" +
                    productName +
                    "</b>?",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d"
            }).then((result)=>{

                if(result.isConfirmed){

                    window.location.href =
                        "delete-draft.php?id=" +
                        productId +
                        "&return=" +
                        page;
                }

            });

        }
        </script>

        <!--display draft save success message-->
        <?php if(isset($_SESSION['draft_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "Draft Saved",
            text: <?= json_encode($_SESSION['draft_success']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['draft_success']);
        }
        ?>

        <!--display draft publish success message-->
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

        <!--display draft publish error message-->
        <?php if(isset($_SESSION['publish_error'])){ ?>

        <script>
        Swal.fire({
            icon: "error",
            title: "Unable to Publish Product",
            html: <?= json_encode($_SESSION['publish_error']); ?>,
            confirmButtonText: "Edit Product",
            confirmButtonColor: "#e53935"
        }).then(() => {

            <?php if(isset($_SESSION['publish_product_id'])){ ?>

            window.location.href =
                "edit-product.php?id=<?= (int)$_SESSION['publish_product_id']; ?>";

            <?php } ?>

        });
        </script>

        <?php
        unset($_SESSION['publish_error']);
        unset($_SESSION['publish_product_id']);
        }
        ?>

        <!--display draft delete success message-->
        <?php if(isset($_SESSION['delete_draft_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "Draft Deleted",
            text: <?= json_encode($_SESSION['delete_draft_success']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['delete_draft_success']);
        }
        ?>

        <!--display draft delete error message-->
        <?php if(isset($_SESSION['delete_draft_error'])){ ?>

        <script>
        Swal.fire({
            icon: "error",
            title: "Unable to Delete Draft",
            text: <?= json_encode($_SESSION['delete_draft_error']); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php
        unset($_SESSION['delete_draft_error']);
        }
        ?>

    </body>
</html>