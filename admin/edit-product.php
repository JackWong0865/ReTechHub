<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/*check if the URL contains a product ID*/
if(!isset($_GET['id'])){
    header("Location: products.php");
    exit();
}

/*get product id*/
$product_id = (int)$_GET['id'];
$message = "";

/*check product info*/
$product_query = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE product_id='$product_id'"
);

/*check if the product exists*/
if(mysqli_num_rows($product_query) == 0){
    die("Product not found.");
}

/*convert product data into an array*/
$product = mysqli_fetch_assoc($product_query);

/*check the admin has submitted the update form*/
if(isset($_POST['update_product'])){

    /*get the modified data that modify by the admin*/
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $condition_type = mysqli_real_escape_string($conn, $_POST['condition_type']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $badge = mysqli_real_escape_string($conn, $_POST['badge']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    /*update products table*/
    mysqli_query(
        $conn,
        "UPDATE products SET
            product_name='$product_name',
            category='$category',
            brand='$brand',
            condition_type='$condition_type',
            price='$price',
            stock='$stock',
            location='$location',
            badge='$badge',
            description='$description'
         WHERE product_id='$product_id'"
    );

    /*check if any new images have been uploaded*/
    if(!empty($_FILES['images']['name'][0])){

        /*process multiple images in a loop*/
        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name){

            /*check again whether the image exists*/
            if(!empty($_FILES['images']['name'][$key])){

                /*generate a new file name*/
                $filename = time() . "_" . $_FILES['images']['name'][$key];

                /*set physical storage path*/
                $folder = "../assets/images/products/" . $filename;

                /*move the images to the product image folder*/
                move_uploaded_file($tmp_name, $folder);

                /*set up database image paths*/
                $image_path = "assets/images/products/" . $filename;

                /*insert into the product_images table*/
                mysqli_query(
                    $conn,
                    "INSERT INTO product_images
                    (product_id, image_path)
                    VALUES
                    ('$product_id', '$image_path')"
                );
            }
        }
    }

    /*save product update success message*/
    $_SESSION['product_update_success'] =
        "Product updated successfully.";

    /*redirect to prevent duplicate submission*/
    header("Location: edit-product.php?id=" . $product_id);
    exit();
}

$images = mysqli_query(
    $conn,
    "SELECT * FROM product_images WHERE product_id='$product_id'"
);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Product - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/upload.css">
        <link rel="stylesheet" href="../assets/css/edit-product.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="admin-layout">

            <!--side n top bar-->
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

                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <div class="upload-header">
                    <div>
                        <a href="products.php" class="back-btn">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Listings
                        </a>
                        <h1>Edit Product</h1>
                        <p>Update product details, images, stock and publishing status.</p>
                    </div>

                    <div class="bottom-actions">

                        <button type="submit" name="update_product" form="editProductForm" class="publish-btn">
                            Update Product
                        </button>

                    </div>
                </div>

                <form id="editProductForm" class="upload-layout" method="POST" enctype="multipart/form-data">

                    <div class="product-form-card">

                        <div class="section-title">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <div>
                                <h2>Product Information</h2>
                                <p>Edit basic information about the product.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div>
                                <label>Category *</label>
                                <select name="category" required>
                                    <option value="Smartphone" <?= $product['category']=="Smartphone" ? "selected" : ""; ?>>Smartphone</option>
                                    <option value="Laptop" <?= $product['category']=="Laptop" ? "selected" : ""; ?>>Laptops & PCs</option>
                                    <option value="Tablet" <?= $product['category']=="Tablet" ? "selected" : ""; ?>>Tablet</option>
                                    <option value="Gaming" <?= $product['category']=="Gaming" ? "selected" : ""; ?>>Gaming</option>
                                    <option value="Accessories" <?= $product['category']=="Accessories" ? "selected" : ""; ?>>Accessories</option>
                                    <option value="Camera" <?= $product['category']=="Camera" ? "selected" : ""; ?>>Camera</option>
                                </select>
                            </div>

                            <div>
                                <label>Brand *</label>
                                <input type="text" name="brand" value="<?= $product['brand']; ?>" required>
                            </div>

                            <div class="full">
                                <label>Product Name *</label>
                                <input type="text" name="product_name" value="<?= $product['product_name']; ?>" required>
                            </div>

                            <div>
                                <label>Condition *</label>
                                <select name="condition_type" required>
                                    <option value="Like New" <?= $product['condition_type']=="Like New" ? "selected" : ""; ?>>Like New</option>
                                    <option value="Good" <?= $product['condition_type']=="Good" ? "selected" : ""; ?>>Good</option>
                                    <option value="Fair" <?= $product['condition_type']=="Fair" ? "selected" : ""; ?>>Fair</option>
                                    <option value="Used" <?= $product['condition_type']=="Used" ? "selected" : ""; ?>>Used</option>
                                </select>
                            </div>

                            <div>
                                <label>Current Status</label>

                                <input type="text" value="<?= ucfirst($product['status']); ?>" readonly >
                            </div>

                            <div>
                                <label>Price *</label>
                                <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" required>
                            </div>

                            <div>
                                <label>Stock *</label>
                                <input type="number" name="stock" value="<?= $product['stock']; ?>" required>
                            </div>

                            <div>
                                <label>Location</label>
                                <input type="text" name="location" value="<?= $product['location']; ?>">
                            </div>

                            <div>
                                <label>Badge</label>
                                <input type="text" name="badge" value="<?= $product['badge']; ?>">
                            </div>

                            <div class="full">
                                <label>Description</label>
                                <textarea name="description"><?= $product['description']; ?></textarea>
                            </div>

                        </div>

                    </div>

                    <div class="image-card">

                        <div class="section-title">
                            <i class="fa-solid fa-image"></i>
                            <div>
                                <h2>Product Images</h2>
                                <p>Current images and upload new product photos.</p>
                            </div>
                        </div>

                        <div class="current-images">

                            <?php if(mysqli_num_rows($images) > 0){ ?>

                                <?php while($img = mysqli_fetch_assoc($images)){ ?>

                                    <div class="current-img-box">

                                        <img src="../<?= $img['image_path']; ?>">

                                        <a href="javascript:void(0);" onclick="confirmDeleteImage(
                                            <?= (int)$img['image_id']; ?>,
                                            <?= (int)$product_id; ?>
                                        )">

                                            <i class="fa-solid fa-trash"></i>

                                        </a>

                                    </div>

                                <?php } ?>

                            <?php }else{ ?>

                                <p>No images uploaded.</p>

                            <?php } ?>

                        </div>

                        <div class="upload-box edit-upload-box">

                            <input type="file" id="productImages" name="images[]" accept="image/*" multiple hidden>

                            <label for="productImages" class="upload-label">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <h3>Upload New Images</h3>
                                <span>Click to browse</span>
                                <p>PNG, JPG up to 10MB each</p>
                            </label>

                        </div>

                        <div class="preview-grid" id="previewGrid"></div>

                    </div>

                    <div class="tips-area">

                        <div class="tips-card">
                            <h3><i class="fa-regular fa-lightbulb"></i> Edit Tips</h3>

                            <div class="tip">
                                <b>Update Carefully</b>
                                <p>Make sure product details match the actual device.</p>
                            </div>

                            <div class="tip">
                                <b>Keep Price Updated</b>
                                <p>Adjust price based on product condition and demand.</p>
                            </div>

                            <div class="tip">
                                <b>Use Draft Status</b>
                                <p>Move incomplete products to draft before publishing.</p>
                            </div>
                        </div>

                    </div>

                    <div class="bottom-actions">
                    
                    </div>

                </form>

            </main>

        </div>


        <!--use for put multiple and delete picture-->
        <script>
        const imageInput = document.getElementById("productImages");
        const previewGrid = document.getElementById("previewGrid");

        let selectedFiles = [];

        imageInput.addEventListener("change", function(){

            const newFiles = Array.from(this.files);

            selectedFiles = [...selectedFiles, ...newFiles];

            const dt = new DataTransfer();

            selectedFiles.forEach(file=>{
                dt.items.add(file);
            });

            imageInput.files = dt.files;

            renderPreview();

        });

        function renderPreview(){

            previewGrid.innerHTML = "";

            selectedFiles.forEach((file,index)=>{

                const reader = new FileReader();

                reader.onload = function(e){

                    const box = document.createElement("div");
                    box.className = "preview-box";

                    box.innerHTML = `
                        <img src="${e.target.result}" class="preview-image">

                        <button
                            type="button"
                            class="remove-preview-btn"
                            onclick="removeImage(${index})">

                            <i class="fa-solid fa-xmark"></i>

                        </button>
                    `;

                    previewGrid.appendChild(box);

                };

                reader.readAsDataURL(file);

            });

        }

        function removeImage(index){

            selectedFiles.splice(index,1);

            const dt = new DataTransfer();

            selectedFiles.forEach(file=>dt.items.add(file));

            imageInput.files = dt.files;

            renderPreview();

        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        /*confirm before deleting product image*/
        function confirmDeleteImage(imageId, productId){

            Swal.fire({
                icon: "warning",
                title: "Delete Product Image?",
                text: "This image will be permanently deleted.",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
            }).then((result) => {

                if(result.isConfirmed){

                    window.location.href =
                        "delete-product-image.php?id=" +
                        imageId +
                        "&product_id=" +
                        productId;
                }

            });
        }
        </script>

        <!--display product update success message-->
        <?php if(isset($_SESSION['product_update_success'])){ ?>

        <script>
        Swal.fire({
            icon: "success",
            title: "Product Updated",
            text: <?= json_encode(
                $_SESSION['product_update_success']
            ); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#dc3545"
        });
        </script>

        <?php
        unset($_SESSION['product_update_success']);
        }
        ?>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>