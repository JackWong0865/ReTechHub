<?php
include("includes/db.php");
include("includes/header.php");

if(!isset($_GET['id'])){
    header("Location: marketplace.php");
    exit();
}

$product_id = (int)$_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE product_id='$product_id'"
);

if(mysqli_num_rows($query) == 0){
    echo "Product not found";
    exit();
}

$product = mysqli_fetch_assoc($query);

/*product image*/

$images = mysqli_query(
    $conn,
    "SELECT * FROM product_images
     WHERE product_id='$product_id'"
);

$total_images = mysqli_num_rows($images);

if($total_images == 0){
    $total_images = 1;
}

/*main image*/

$main_image_query = mysqli_query(
    $conn,
    "SELECT * FROM product_images
     WHERE product_id='$product_id'
     LIMIT 1"
);

$main_image = mysqli_fetch_assoc($main_image_query);

$main_image_path = $main_image
    ? $main_image['image_path']
    : 'assets/images/products/default-product.png';
?>

<link rel="stylesheet" href="assets/css/product-detail.css">

<div class="product-detail-page">

    <a href="marketplace.php" class="back-link">
        &lt; Back to select
    </a>

    <div class="breadcrumb">
        Home > Marketplace > <?= $product['category']; ?> > <?= $product['brand']; ?> > <?= $product['product_name']; ?>
    </div>

    <div class="product-detail-layout">

        <!--left image-->
        <div class="product-gallery">

            <div class="thumb-list">
                
                <!--display the page of the picture-->
                <?php
                $count = 0;

                while($img = mysqli_fetch_assoc($images)){

                    $active = $count == 0 ? 'active' : '';

                    echo '
                    <img src="'.$img['image_path'].'" class="thumb '.$active.'">';

                    $count++;
                }
                ?>

            </div>

            <div class="main-image-box">
                
                <img src="<?= $main_image_path; ?>" class="main-product-img">

                <div class="image-count">
                    1 / <?= $total_images; ?>
                </div>
            </div>

        </div>

        <!--middle info-->
        <div class="product-info-detail">

            <?php
            $condition_class = strtolower(str_replace(' ', '-', $product['condition_type']));
            ?>

            <span class="condition-badge <?= $condition_class; ?>">
                <?= $product['condition_type']; ?>
            </span>

            <h1>
                <?= $product['product_name']; ?>
            </h1>

            <div class="price-row-detail">
                <h2>RM<?= number_format($product['price'], 2); ?></h2>

                <?php if(!empty($product['badge'])){ ?>
                    <span class="discount-badge">
                        <?= $product['badge']; ?>
                    </span>
                <?php } ?>
            </div>

            <div class="warranty-box">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                    <b>7-Day Warranty</b>
                    <p>Shop with confidence. 7-day return or replacement.</p>
                </div>
            </div>

            <div class="condition-section">

                <div class="condition-title">
                    <b>Condition</b>
                    <button type="button" class="how-btn" onclick="openConditionModal()">
                        How it works?
                    </button>
                </div>

                <div class="condition-options">

                    <!--condition-->
                    <?php
                    $conditions = ["Like New", "Good", "Fair", "Used"];

                    foreach($conditions as $condition){
                        $active = $product['condition_type'] == $condition ? "active" : "";
                    ?>

                        <button class="<?= $active; ?>" type="button">
                            <?= $condition; ?>
                        </button>

                    <?php } ?>

                </div>

                <!--condition description-->
                <p class="condition-desc">

                <?php
                if($product['condition_type'] == "Like New"){
                    echo "The item is in excellent condition with minimal to no signs of wear.";
                }
                elseif($product['condition_type'] == "Good"){
                    echo "Minor scratches may be visible but fully functional.";
                }
                elseif($product['condition_type'] == "Fair"){
                    echo "Visible wear and scratches but still works properly.";
                }
                else{
                    echo "Heavy signs of usage but still usable.";
                }
                ?>

</p>

            </div>

            <!--product info-->
            <div class="spec-list">

            <?php if(!empty($product['storage'])){ ?>
                <div><span>Storage</span><b><?= $product['storage']; ?></b></div>
            <?php } ?>

            <?php if(!empty($product['color'])){ ?>
                <div><span>Color</span><b><?= $product['color']; ?></b></div>
            <?php } ?>

            <?php if(!empty($product['battery_health'])){ ?>
                <div><span>Battery Health</span><b><?= $product['battery_health']; ?></b></div>
            <?php } ?>

            <?php if(!empty($product['sim'])){ ?>
                <div><span>SIM</span><b><?= $product['sim']; ?></b></div>
            <?php } ?>

            <?php if(!empty($product['network'])){ ?>
                <div><span>Network</span><b><?= $product['network']; ?></b></div>
            <?php } ?>

            </div>

        </div>

        <!--right checkout-->
        <div class="checkout-card">

            <div class="delivery-box">
                <h3>Delivery Options</h3>

                <div class="delivery-option">
                    <span>Standard Delivery<br><small>2 - 4 working days</small></span>
                    <b>RM8.00</b>
                </div>

                <div class="delivery-option">
                    <span>Self Pickup<br><small>Pick up at seller location</small></span>
                    <b>Free</b>
                </div>
            </div>

            <div class="total-box">
                <span>Total Price</span>
                <h2>RM<?= number_format($product['price'] + 8, 2); ?></h2>
                <small>Include<br>delivery<br>fee</small>
            </div>

            <a href="buy-now.php?id=<?= $product['product_id']; ?>" class="buy-btn">
                Buy Now
            </a>

            <a href="addcart.php?id=<?= $product['product_id']; ?>" class="add-cart-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                Add to Cart
            </a>

            <div class="safe-box">
                <i class="fa-solid fa-lock"></i>
                <p>
                    <b>Safe & Secure Payments</b><br>
                    Your payment information is protected.
                </p>
            </div>

        </div>

    </div>

    <div class="bottom-layout">

        <!--description-->
        <div class="description-card">

            <div class="tab-menu">
                <span class="active">Description</span>
            </div>

            <p>
                <?= !empty($product['description']) ? $product['description'] : "No description available."; ?>
            </p>

        </div>


        <div class="recommend-card">

            <!--recommend product-->
            <div class="recommend-title">
                <h3>You May Also Like</h3>
                <a href="marketplace.php?category=<?= urlencode($product['category']); ?>">View All</a>
            </div>

            <div class="recommend-grid">

                <?php

                /*get current product categories*/
                $current_category = mysqli_real_escape_string(
                    $conn,
                    $product['category']
                );

                /*Search related recommended products*/
                $recommend = mysqli_query(
                    $conn,
                    "SELECT *
                     FROM products

                     /*search same categories*/
                     WHERE category='$current_category'

                     /*Exclude current products*/
                     AND product_id != '$product_id'

                      /* Only display active products */
                     AND status='Published'

                     /*Random sorting*/
                     ORDER BY RAND()

                     /*maximum show 4 product*/
                     LIMIT 4"
                );

                while($r = mysqli_fetch_assoc($recommend)){

                    /*search product picture*/
                    $rec_img_query = mysqli_query(
                        $conn,
                        "SELECT image_path 
                         FROM product_images
                         WHERE product_id='".$r['product_id']."'
                         LIMIT 1"
                    );

                    /*get picture info*/
                    $rec_img = mysqli_fetch_assoc($rec_img_query);

                    /*Determine if there are product images*/
                    $rec_image_path = $rec_img
                        ? $rec_img['image_path']
                        : "assets/images/products/default-product.png";
                ?>

                <a href="product-detail.php?id=<?= (int)$r['product_id']; ?>" class="recommend-item">

                    <?php
                    $recommend_condition_class = strtolower(
                        str_replace(' ', '-', $r['condition_type'])
                    );
                    ?>

                    <span class="recommend-condition-badge <?= htmlspecialchars($recommend_condition_class); ?>">
                        <?= htmlspecialchars($r['condition_type']); ?>
                    </span>

                    <?php if(!empty($r['badge'])){ ?>

                        <span class="recommend-badge">
                            <?= htmlspecialchars($r['badge']); ?>
                        </span>

                    <?php } ?>

                    <img
                        src="<?= htmlspecialchars($rec_image_path); ?>"
                        alt="<?= htmlspecialchars($r['product_name']); ?>"
                    >

                    <h4>
                        <?= htmlspecialchars($r['product_name']); ?>
                    </h4>

                    <p class="recommend-price">
                        RM<?= number_format((float)$r['price'], 2); ?>
                    </p>

                </a>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<!--how it work page-->
<div class="condition-modal" id="conditionModal">
    <div class="condition-modal-box">

        <button class="condition-close" onclick="closeConditionModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2>Condition Guide</h2>

        <div class="condition-item">

            <div class="condition-icon icon-like">
                <i class="fa-solid fa-star"></i>
            </div>

            <div>
                <h4>Like New</h4>
                <p>
                    Device is in excellent condition with almost no scratches or signs of use.
                    All functions work perfectly.
                </p>
            </div>

        </div>

        <div class="condition-item">

            <div class="condition-icon icon-good">
                <i class="fa-solid fa-thumbs-up"></i>
            </div>

            <div>
                <h4>Good</h4>
                <p>
                    Minor cosmetic scratches or marks,
                    but the device is fully functional.
                </p>
            </div>

        </div>

        <div class="condition-item">

            <div class="condition-icon icon-fair">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <div>
                <h4>Fair</h4>
                <p>
                    Noticeable scratches or wear,
                    but still works properly.
                </p>
            </div>

        </div>

        <div class="condition-item">

            <div class="condition-icon icon-used">
                <i class="fa-solid fa-box-open"></i>
            </div>

            <div>
                <h4>Used</h4>
                <p>
                    Heavy signs of use.
                    Device is still usable but may have cosmetic defects.
                </p>
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/*Product image switching*/
const thumbs = document.querySelectorAll(".thumb");
const mainImg = document.querySelector(".main-product-img");
const imageCount = document.querySelector(".image-count");

/*Add a click event to each thumbnail.*/
thumbs.forEach((thumb, index) => {
    thumb.addEventListener("click", function(){

        /*Switch main image*/
        mainImg.src = this.src;

        /*Update thumbnail selection status*/
        thumbs.forEach(t => t.classList.remove("active"));
        this.classList.add("active");

        /*Update image number*/
        imageCount.innerText = (index + 1) + " / " + thumbs.length;
    });
});
</script>

<!--Condition Modal-->
<script>
function openConditionModal(){
    document.getElementById("conditionModal").style.display = "flex";
}

function closeConditionModal(){
    document.getElementById("conditionModal").style.display = "none";
}
</script>

<!--add cart successful message-->
<?php if(isset($_SESSION['cart_message'])){ ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Added to Cart',
    text: '<?= $_SESSION['cart_message']; ?>',
    confirmButtonColor: '#ff0000',
    background: '#111',
    color: '#ff0000'
});
</script>

<?php unset($_SESSION['cart_message']); } ?>