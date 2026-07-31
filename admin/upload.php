<?php
session_start();
include("../includes/db.php");

/* Check admin login first */
if(
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
){
    header("Location: ../login.php");
    exit();
}

$error = "";

if(isset($_POST['save_draft']) || isset($_POST['publish'])){

    /* Read form data */
    $product_name = trim($_POST['product_name'] ?? "");
    $category = trim($_POST['category'] ?? "");
    $brand = trim($_POST['brand'] ?? "");
    $condition = trim($_POST['condition_type'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $stock = trim($_POST['stock'] ?? "");
    $location = trim($_POST['location'] ?? "");
    $badge = trim($_POST['badge'] ?? "");
    $storage = trim($_POST['storage'] ?? "");
    $color = trim($_POST['color'] ?? "");
    $battery_health = trim($_POST['battery_health'] ?? "");
    $sim = trim($_POST['sim'] ?? "");
    $network = trim($_POST['network'] ?? "");

    $is_draft = isset($_POST['save_draft']);
    $is_publish = isset($_POST['publish']);

    /* Check whether an image was selected */
    $has_image =
        isset($_FILES['images']['name']) &&
        is_array($_FILES['images']['name']) &&
        count(array_filter($_FILES['images']['name'])) > 0;

    /*
       PUBLISH VALIDATION
       All important product information is required.
    */
    if($is_publish){

        if(
            $product_name === "" ||
            $category === "" ||
            $brand === "" ||
            $condition === "" ||
            $description === "" ||
            $price === "" ||
            $stock === "" ||
            $location === ""
        ){
            $error = "Please complete all required product details before publishing.";
        }
        elseif(!is_numeric($price) || (float)$price <= 0){

            $error = "Product price must be greater than RM0.";

        }
        elseif(
            filter_var(
                $stock,
                FILTER_VALIDATE_INT
            ) === false ||
            (int)$stock < 1
        ){

            $error = "Product stock must be at least 1.";

        }
        elseif(!$has_image){

            $error ="Please upload at least one product image before publishing.";
        }
    }

    /* DRAFT VALIDATION
       Product name is required and at least
       two additional fields must be completed. */
    if($is_draft){

        $draft_fields = [
            $category,
            $brand,
            $condition,
            $description,
            $price,
            $stock,
            $location,
            $badge,
            $storage,
            $color
        ];

        $completed_fields = 0;

        foreach($draft_fields as $field){

            if(trim((string)$field) !== ""){
                $completed_fields++;
            }
        }

        if($product_name === ""){

            $error = "Please enter a product name before saving the draft.";

        }
        elseif($completed_fields < 2){

            $error = "Please complete at least two additional product fields before saving the draft.";
        }
    }

    /* Continue only when there is no validation error */
    if($error === ""){

        /* Escape data after validation */
        $product_name_db = mysqli_real_escape_string($conn, $product_name);

        $category_db = mysqli_real_escape_string($conn, $category);

        $brand_db = mysqli_real_escape_string($conn, $brand);

        $condition_db = mysqli_real_escape_string($conn, $condition);

        $description_db = mysqli_real_escape_string($conn, $description);

        $location_db = mysqli_real_escape_string($conn, $location);

        $badge_db = mysqli_real_escape_string($conn, $badge);

        $storage_db = mysqli_real_escape_string($conn, $storage);

        $color_db = mysqli_real_escape_string($conn, $color);

        $battery_health_db = mysqli_real_escape_string( $conn, $battery_health );

        $sim_db = mysqli_real_escape_string($conn, $sim);

        $network_db = mysqli_real_escape_string($conn, $network);

        /* Draft fields may be incomplete.
           Use 0 when price or stock is empty. */
        $price_db = $price !== "" ? (float)$price : 0;

        $stock_db = $stock !== "" ? (int)$stock : 0;

        $status = $is_draft ? "draft" : "published";

        /* Upload images only after validation */
        $uploaded_images = [];

        if(
            isset($_FILES['images']['tmp_name']) &&
            is_array($_FILES['images']['tmp_name'])
        ){

            foreach(
                $_FILES['images']['tmp_name']
                as $key => $tmp_name
            ){

                if(
                    empty($_FILES['images']['name'][$key]) ||
                    $_FILES['images']['error'][$key] !== UPLOAD_ERR_OK
                ){
                    continue;
                }

                /* Maximum 4 images */
                if(count($uploaded_images) >= 4){
                    break;
                }

                $original_name =
                    basename(
                        $_FILES['images']['name'][$key]
                    );

                $extension =
                    strtolower(
                        pathinfo(
                            $original_name,
                            PATHINFO_EXTENSION
                        )
                    );

                $allowed_extensions = [
                    "jpg",
                    "jpeg",
                    "png",
                    "webp"
                ];

                if(!in_array(
                    $extension,
                    $allowed_extensions,
                    true
                )){
                    continue;
                }

                $filename =
                    time() . "_" .
                    bin2hex(random_bytes(4)) .
                    "." . $extension;

                $folder =
                    "../assets/images/products/" .
                    $filename;

                if(move_uploaded_file(
                    $tmp_name,
                    $folder
                )){
                    $uploaded_images[] =
                        "assets/images/products/" .
                        $filename;
                }
            }
        }

        /* Insert product */
        $insert_product = mysqli_query(
            $conn,
            "INSERT INTO products
            (
                product_name,
                category,
                brand,
                condition_type,
                price,
                location,
                description,
                stock,
                badge,
                status,
                storage,
                color,
                battery_health,
                sim,
                network
            )
            VALUES
            (
                '$product_name_db',
                '$category_db',
                '$brand_db',
                '$condition_db',
                '$price_db',
                '$location_db',
                '$description_db',
                '$stock_db',
                '$badge_db',
                '$status',
                '$storage_db',
                '$color_db',
                '$battery_health_db',
                '$sim_db',
                '$network_db'
            )"
        );

        if($insert_product){

            $product_id = mysqli_insert_id($conn);

            foreach($uploaded_images as $img){

                $img_db =
                    mysqli_real_escape_string(
                        $conn,
                        $img
                    );

                mysqli_query(
                    $conn,
                    "INSERT INTO product_images
                    (
                        product_id,
                        image_path
                    )
                    VALUES
                    (
                        '$product_id',
                        '$img_db'
                    )"
                );
            }

            if($status === "draft"){

                $_SESSION['draft_success'] =
                    "Product saved as draft successfully.";

                header("Location: draft.php");

            }else{

                $_SESSION['publish_success'] =
                    "Product published successfully.";

                header("Location: products.php");
            }

            exit();

        }else{

            $error = "Unable to save the product. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Upload Product - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/upload.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="admin-layout">

            <!--side bar-->
            <aside class="sidebar">
                <img src="../assets/images/logo.png" class="admin-logo">

                <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
                <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
                <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
                <a href="order-history.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
                <a href="upload.php" class="active"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
                <a href="draft.php"><i class="fa-solid fa-file-lines"></i>Draft Products</a>
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
                            <i class="fa-solid fa-message"></i><span>Message</span>
                            <span id="messageBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="notifications.php" class="top-icon badge-wrap">
                            <i class="fa-solid fa-bell"></i><span>Notification</span>
                            <span id="notificationBadge" class="live-badge" style="display:none;"> 0</span>
                        </a>

                        <a href="../profile.php" class="admin-profile">
                            <img src="../<?= $_SESSION['profile_image'] ?? 'uploads/profile/default-avatar.png'; ?>" class="admin-avatar">
                            <span><?= $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </div>

                <!--page code-->
                <div class="upload-header">
                    <div>
                        <h1>Upload New Product</h1>
                        <p>Add a new product to the marketplace. Fill in the details below.</p>
                    </div>

                    <div class="header-buttons">
                        <button type="submit" name="save_draft" form="productForm" class="draft-btn">Save as Draft</button>
                        <button type="submit" name="publish" form="productForm" class="publish-btn">Publish</button>
                    </div>
                </div>

        

                <form id="productForm" class="upload-layout" method="POST" enctype="multipart/form-data">

                    <div class="product-form-card">

                        <div class="section-title">
                            <i class="fa-solid fa-clipboard-list"></i>
                            <div>
                                <h2>Product Details</h2>
                                <p>Provide basic information about the product.</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div>
                                <label>Category *</label>
                                <select name="category">
                                    <option value="">Select category</option>
                                    <option value="Smartphone">Smartphone</option>
                                    <option value="Laptop">Laptop</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Gaming">Gaming</option>
                                    <option value="Accessories">Accessories</option>
                                    <option value="Camera">Camera</option>
                                </select>
                            </div>

                            <div>
                                <label>Brand *</label>
                                <input type="text" name="brand" placeholder="Ex: Graphite">
                            </div>

                            <div class="full">
                                <label>Product Name *</label>
                                <input type="text" name="product_name" placeholder="Ex: iPhone 13 Pro 128GB">
                            </div>

                            <div>
                                <label>Condition *</label>
                                <select name="condition_type">
                                    <option value="">Select condition</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Used">Used</option>
                                </select>
                            </div>

                            <div>
                                <label>Storage / Variant</label>
                                <input type="text" name="storage" placeholder="Ex: 128GB, 256GB">
                            </div>

                            <div>
                                <label>Color</label>
                                <input type="text" name="color" placeholder="Ex: Graphite">
                            </div>

                            <div>
                                <label>Battery Health</label>
                                <input type="text" name="battery_health" placeholder="Ex: 95%">
                            </div>

                            <div>
                                <label>SIM</label>
                                <select name="sim">
                                    <option value="">Select SIM Type</option>
                                    <option value="Single SIM">Single SIM</option>
                                    <option value="Dual SIM">Dual SIM</option>
                                    <option value="eSIM">eSIM</option>
                                    <option value="Dual SIM + eSIM">Dual SIM + eSIM</option>
                                </select>
                            </div>

                            <div>
                                <label>Network</label>
                                <select name="network">
                                    <option value="">Select Network</option>
                                    <option value="4G">4G</option>
                                    <option value="5G">5G</option>
                                </select>
                            </div>

                            <div class="full">
                                <label>Description *</label>
                                <textarea name="description" placeholder="Write a detailed description..."></textarea>
                            </div>

                            <div>
                                <label>Price *</label>
                                <input type="number" name="price" placeholder="Ex: 2699">
                            </div>

                            <div>
                                <label>Stock *</label>
                                <input type="number" name="stock" placeholder="Ex: 10">
                            </div>

                            <div>
                                <label>Location</label>
                                <input type="text" name="location" placeholder="Ex: Kuala Lumpur">
                            </div>

                            <div>
                                <label>Badge</label>
                                <input type="text" name="badge" placeholder="Ex: NEW / -10%">
                            </div>
                        </div>

                    </div>

                    <div class="image-card">

                        <div class="section-title">
                            <i class="fa-solid fa-image"></i>
                            <div>
                                <h2>Product Images</h2>
                                <p>Upload clear photos of the product.</p>
                            </div>
                        </div>

                        <div class="upload-wrapper">

                            <!-- MAIN UPLOAD -->

                            <label for="imageInput" class="upload-box">

                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <h2>Drag & Drop Your Images Here</h2>
                                <p>or click to browse</p>
                                <span>JPG, PNG up to 10MB each</span>

                            </label>

                            <input type="file" id="imageInput" name="images[]" accept="image/*" multiple hidden>

                            <!-- IMAGE PREVIEW -->

                            <div class="preview-grid" id="previewGrid">

                                <div class="preview-item empty">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Add Photo</span>
                                </div>

                                <div class="preview-item empty">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Add Photo</span>
                                </div>

                                <div class="preview-item empty">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Add Photo</span>
                                </div>

                                <div class="preview-item empty">
                                    <i class="fa-regular fa-image"></i>
                                    <span>Add Photo</span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tips-area">

                        <div class="tips-card">
                            <h3><i class="fa-regular fa-lightbulb"></i> Tips for Better Listings</h3>

                            <div class="tip"><b>Add Clear Photos</b><p>Upload clear, well-lit photos from multiple angles.</p></div>
                            <div class="tip"><b>Write Honest Description</b><p>Provide accurate details about condition and features.</p></div>
                            <div class="tip"><b>Set a Fair Price</b><p>Research similar items to set a competitive price.</p></div>
                            <div class="tip"><b>Choose Right Category</b><p>Select the most relevant category for better visibility.</p></div>
                        </div>

                        <div class="tips-card">
                            <h3>Product Condition Guide</h3>
                            <p><span class="dot green"></span> <b>Like New</b><br>Almost no signs of wear.</p>
                            <p><span class="dot blue"></span> <b>Good</b><br>Light signs of use.</p>
                            <p><span class="dot orange"></span> <b>Fair</b><br>Visible signs but functional.</p>
                            <p><span class="dot red"></span> <b>Used</b><br>Heavily used, may have defects.</p>
                        </div>

                    </div>

                </form>

            </main>

        </div>

        <script>
        const imageInput = document.getElementById("imageInput");
        const previewGrid = document.getElementById("previewGrid");

        /*create image array*/
        let selectedFiles = [];

        imageInput.addEventListener("change", function(){

            /*get new picture*/
            const newFiles = Array.from(imageInput.files);

            newFiles.forEach(file => {
                /*maximum 4 picture*/
                if(selectedFiles.length < 4){
                    selectedFiles.push(file);
                }
            });

            /*update input*/
            updateInputFiles();
            
            /*update preview*/
            renderPreview();
        });

        function renderPreview(){

            previewGrid.innerHTML = "";

            selectedFiles.forEach((file, index) => {

                const reader = new FileReader();

                reader.onload = function(e){

                    const item = document.createElement("div");
                    item.className = "preview-item";

                    item.innerHTML = `
                        <button type="button" class="delete-btn" onclick="removeImage(${index})">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        <img src="${e.target.result}">
                    `;

                    previewGrid.appendChild(item);
                };

                reader.readAsDataURL(file);
            });

            for(let i = selectedFiles.length; i < 4; i++){
                const empty = document.createElement("div");
                empty.className = "preview-item empty";

                empty.innerHTML = `
                    <i class="fa-regular fa-image"></i>
                    <span>Add Photo</span>
                `;

                previewGrid.appendChild(empty);
            }
        }

        function removeImage(index){
            selectedFiles.splice(index, 1);
            updateInputFiles();
            renderPreview();
        }

        function updateInputFiles(){

            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            imageInput.files = dataTransfer.files;
        }
        </script>

        <!--unable window-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php if($error !== ""){ ?>

        <script>
        Swal.fire({
            icon: "error",
            title: <?= json_encode(
                isset($_POST['publish'])
                    ? "Unable to Publish Product"
                    : "Unable to Save Draft"
            ); ?>,
            text: <?= json_encode($error); ?>,
            confirmButtonText: "OK",
            confirmButtonColor: "#e53935"
        });
        </script>

        <?php } ?>

        <?php include("../includes/live-badges.php"); ?>

    </body>
</html>