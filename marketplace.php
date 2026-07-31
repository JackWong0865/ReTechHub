<?php
include("includes/db.php");
include("includes/header.php");

$limit = 10;

$where = "WHERE status='published'";

$search = $_GET['search'] ?? '';

if(!empty($search)){

    $search = mysqli_real_escape_string($conn, $search);

    $where .= " AND (
        product_name LIKE '%$search%'
        OR brand LIKE '%$search%'
        OR category LIKE '%$search%'
    )";
}

if(!empty($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $where .= " AND category='$category'";
}

if(!empty($_GET['condition'])){
    $condition = mysqli_real_escape_string($conn, $_GET['condition']);
    $where .= " AND condition_type='$condition'";
}

if(!empty($_GET['location'])){
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $where .= " AND location='$location'";
}

if(!empty($_GET['brand'])){
    $brand = mysqli_real_escape_string($conn, $_GET['brand']);
    $where .= " AND brand='$brand'";
}

if(!empty($_GET['max_price'])){
    $max_price = (int)$_GET['max_price'];
    $where .= " AND price <= $max_price";
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $limit;

/*total product*/

$total_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM products $where"
);

$total_row = mysqli_fetch_assoc($total_query);

$total_products = $total_row['total'];

$total_pages = ceil($total_products / $limit);

/*filter URL*/

$query_string = $_GET;

unset($query_string['page']);

$filter_url = http_build_query($query_string);

/*product query*/

$product_query = mysqli_query(
    $conn,
    "SELECT * FROM products $where
     ORDER BY product_id DESC
     LIMIT $limit OFFSET $offset"
);

/*showing*/

$start_product = $total_products > 0
    ? $offset + 1
    : 0;

$end_product = min(
    $offset + $limit,
    $total_products
);
?>

<link rel="stylesheet"
href="assets/css/marketplace.css">

<div class="marketplace-page">

    <div class="market-title">
        <div>
            <h1>Marketplace</h1>
            <p>Buy and sell pre-owned tech devices at great prices.</p>
        </div>

    
    </div>

    <div class="market-content">

        <div class="products-area">

            <p class="showing">
                Showing <?= $start_product; ?> - <?= $end_product; ?> of <?= $total_products; ?> products
            </p>

            <div class="product-grid">
                
                <?php while($row = mysqli_fetch_assoc($product_query)){ ?>
                
                <div class="product-card">

                    <?php if(!empty($row['badge'])){ ?>
                        <span class="badge-red">
                            <?= $row['badge']; ?>
                        </span>
                    <?php } ?>

                    <?php
                    $image_query = mysqli_query(
                        $conn,
                        "SELECT * FROM product_images
                        WHERE product_id='".$row['product_id']."'
                         LIMIT 1"
                    );

                    $product_image = mysqli_fetch_assoc($image_query);

                    $image_path = $product_image
                        ? $product_image['image_path']
                        : 'assets/images/products/default-product.png';
                    ?>

                    <img
                    src="<?= $image_path; ?>"
                    class="product-img">

                    <h4>
                        <span class="product-name">
                            <?= $row['product_name']; ?>
                        </span>
                    </h4>

                    <span class="condition <?= strtolower(str_replace(' ', '-', $row['condition_type'])); ?>">
                        <?= $row['condition_type']; ?>
                    </span>

                    <h3>
                        RM<?= number_format($row['price'], 2); ?>
                    </h3>

                    <div class="product-bottom">

                        <a
                            href="product-detail.php?id=<?= $row['product_id']; ?>"
                            class="view-btn">
                                View product
                        </a>

                        <a
                        href="addcart.php?id=<?= $row['product_id']; ?>"
                        class="cart-small">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>

                    </div>

                </div>

                <?php } ?>
            </div>

            <!--page button-->
            <div class="pagination-box">

                <?php if($page > 1){ ?>
                <a href="marketplace.php?page=1<?= $filter_url ? '&'.$filter_url : ''; ?>">
                    &lt;&lt;
                </a>

                <?php } ?>

                <!--previous-->

                <?php if($page > 1){ ?>
                    <a href="marketplace.php?page=<?= $page - 1; ?><?= $filter_url ? '&'.$filter_url : ''; ?>">
                        &lt;
                    </a>
                <?php } ?>

                <!--page numbers-->

                <?php for($i = 1; $i <= $total_pages; $i++){ ?>
                    <a
                    href="marketplace.php?page=<?= $i; ?><?= $filter_url ? '&'.$filter_url : ''; ?>"
                    class="<?= $i == $page ? 'active' : ''; ?>">

                        <?= $i; ?>

                    </a>
                <?php } ?>

                <!--next-->

                <?php if($page < $total_pages){ ?>
                    <a href="marketplace.php?page=<?= $page + 1; ?><?= $filter_url ? '&'.$filter_url : ''; ?>">
                        &gt;
                    </a>
                    <a href="marketplace.php?page=<?= $total_pages; ?><?= $filter_url ? '&'.$filter_url : ''; ?>">
                        &gt;&gt;
                    </a>
                <?php } ?>

            </div>

            
        </div>

        <!--filter-->

        <form class="filter-box" method="GET" action="marketplace.php">

            <div class="filter-top">
                <h3>Filters</h3>
                <a href="marketplace.php">Reset All <i class="fa-solid fa-rotate-right"></i></a>
            </div>

            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                <option value="Smartphone" <?= ($_GET['category'] ?? '') == 'Smartphone' ? 'selected' : ''; ?>>Smartphones</option>
                <option value="Laptop" <?= ($_GET['category'] ?? '') == 'Laptop' ? 'selected' : ''; ?>>Laptops & PCs</option>
                <option value="Tablet" <?= ($_GET['category'] ?? '') == 'Tablet' ? 'selected' : ''; ?>>Tablets</option>
                <option value="Gaming" <?= ($_GET['category'] ?? '') == 'Gaming' ? 'selected' : ''; ?>>Gaming</option>
                <option value="Accessories" <?= ($_GET['category'] ?? '') == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                <option value="Camera" <?= ($_GET['category'] ?? '') == 'Camera' ? 'selected' : ''; ?>>Cameras</option>
            </select>

            <label>Price Range</label>
            <input
                type="range"
                name="max_price"
                min="0"
                max="10000"
                value="<?= $_GET['max_price'] ?? 10000; ?>">

            <div class="price-row">
                <span>RM 0</span>
                <span>RM <?= $_GET['max_price'] ?? 10000; ?></span>
            </div>

            <label>Condition</label>
            <div class="check">
                <input type="radio" name="condition" value="Like New" <?= ($_GET['condition'] ?? '') == 'Like New' ? 'checked' : ''; ?>>
                Like New
            </div>

            <div class="check">
                <input type="radio" name="condition" value="Good" <?= ($_GET['condition'] ?? '') == 'Good' ? 'checked' : ''; ?>>
                Good
            </div>

            <div class="check">
                <input type="radio" name="condition" value="Fair" <?= ($_GET['condition'] ?? '') == 'Fair' ? 'checked' : ''; ?>>
                Fair
            </div>

            <div class="check">
               <input type="radio" name="condition" value="Used" <?= ($_GET['condition'] ?? '') == 'Used' ? 'checked' : ''; ?>>
                Used
            </div>

            <label>Location</label>
            <select name="location">
                <option value="">All Locations</option>
                <option value="Kuala Lumpur" <?= ($_GET['location'] ?? '') == 'Kuala Lumpur' ? 'selected' : ''; ?>>Kuala Lumpur</option>
                <option value="Selangor" <?= ($_GET['location'] ?? '') == 'Selangor' ? 'selected' : ''; ?>>Selangor</option>
                <option value="Penang" <?= ($_GET['location'] ?? '') == 'Penang' ? 'selected' : ''; ?>>Penang</option>
                <option value="Johor Bahru" <?= ($_GET['location'] ?? '') == 'Johor Bahru' ? 'selected' : ''; ?>>Johor Bahru</option>
                <option value="Melaka" <?= ($_GET['location'] ?? '') == 'Melaka' ? 'selected' : ''; ?>>Melaka</option>
            </select>
        
            <label>Brand</label>
            <select name="brand">
                <option value="">All Brands</option>
                <option value="Apple" <?= ($_GET['brand'] ?? '') == 'Apple' ? 'selected' : ''; ?>>Apple</option>
                <option value="Samsung" <?= ($_GET['brand'] ?? '') == 'Samsung' ? 'selected' : ''; ?>>Samsung</option>
                <option value="ASUS" <?= ($_GET['brand'] ?? '') == 'ASUS' ? 'selected' : ''; ?>>ASUS</option>
                <option value="Sony" <?= ($_GET['brand'] ?? '') == 'Sony' ? 'selected' : ''; ?>>Sony</option>
                <option value="Canon" <?= ($_GET['brand'] ?? '') == 'Canon' ? 'selected' : ''; ?>>Canon</option>
            </select>
        
            <button type="submit" class="apply-btn">
                Apply Filters
            </button>
        
        </form>

    </div>

    <div class="service-strip">
        <div><i class="fa-regular fa-credit-card"></i><span><b>Secure Payments</b><br>100% secure payment</span></div>
        <div><i class="fa-solid fa-rotate-left"></i><span><b>7-Day Return Policy</b><br>Return within 7 days</span></div>
        <div><i class="fa-solid fa-shield-halved"></i><span><b>Verified Sellers</b><br>All sellers verified</span></div>
        <div><i class="fa-solid fa-truck-fast"></i><span><b>Fast Delivery</b><br>Delivered quickly</span></div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['cart_message'])){ ?>

<!--add cart-->
<script>
Swal.fire({
    icon: 'success',
    title: 'Added to Cart',
    text: '<?= $_SESSION['cart_message']; ?>',
    confirmButtonColor: '#ff0000',
    background: '#f4f4f4',
    color: '#ff0000'
});
</script>

<?php unset($_SESSION['cart_message']); } ?>