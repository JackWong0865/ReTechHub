<?php
include("includes/db.php");
include("includes/header.php");

$featured = mysqli_query(
    $conn,
    "SELECT * FROM products
     WHERE status='published'
     ORDER BY product_id DESC
     LIMIT 8"
);
?>

<link rel="stylesheet" href="assets/css/home.css">

<div class="home-page">

    <!--page code-->
    <!--hero-->
    <section class="hero-section">

        <div class="hero-text">
            <h1>
                BUY. SELL. REPAIR.<br>
                <span>ALL THINGS TECH.</span>
            </h1>

            <p>
                ReTech Hub is a one-stop platform for buying pre-owned devices,
                selling used electronics and booking repair services.
            </p>

            <div class="hero-buttons">
                <a href="marketplace.php" class="primary-btn">Shop Now</a>
                <a href="booking.php" class="outline-btn">Book Repair / Sell</a>
            </div>

            <div class="hero-features">
                <div>
                    <i class="fa-solid fa-shield-halved"></i>
                    <b>Trusted Devices</b>
                    <span>Quality checked</span>
                </div>

                <div>
                    <i class="fa-solid fa-recycle"></i>
                    <b>Sustainable</b>
                    <span>Give tech a second life</span>
                </div>

                <div>
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <b>Expert Repair</b>
                    <span>Professional service</span>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <img src="assets/images/hero-tech.jpg">
        </div>

    </section>

    <!--categories-->
    <section class="home-section">

        <div class="section-title">
            <h2>Popular <span>Categories</span></h2>
            <a href="marketplace.php">View All</a>
        </div>

        <div class="category-grid">

            <a href="marketplace.php?category=Smartphone" class="category-card">
                <i class="fa-solid fa-mobile-screen"></i>
                <h3>Smartphones</h3>
            </a>

            <a href="marketplace.php?category=Laptop" class="category-card">
                <i class="fa-solid fa-laptop"></i>
                <h3>Laptops</h3>
            </a>

            <a href="marketplace.php?category=Tablet" class="category-card">
                <i class="fa-solid fa-tablet-screen-button"></i>
                <h3>Tablets</h3>
            </a>

            <a href="marketplace.php?category=Gaming" class="category-card">
                <i class="fa-solid fa-gamepad"></i>
                <h3>Gaming</h3>
            </a>

            <a href="marketplace.php?category=Accessories" class="category-card">
                <i class="fa-solid fa-headphones"></i>
                <h3>Accessories</h3>
            </a>

            <a href="marketplace.php?category=Camera" class="category-card">
                <i class="fa-solid fa-camera"></i>
                <h3>Cameras</h3>
            </a>

        </div>

    </section>

    <!--featured product-->
    <section class="home-section">

        <div class="section-title">
            <h2>Featured <span>Products</span></h2>
            <a href="marketplace.php">Shop More</a>
        </div>

        <div class="featured-grid">

            <?php while($p = mysqli_fetch_assoc($featured)){ ?>

                <?php
                $img_query = mysqli_query(
                    $conn,
                    "SELECT image_path FROM product_images
                     WHERE product_id='".$p['product_id']."'
                     LIMIT 1"
                );

                $img = mysqli_fetch_assoc($img_query);

                $image_path = $img
                    ? $img['image_path']
                    : "assets/images/products/default-product.png";

                $condition_class = strtolower(str_replace(' ', '-', $p['condition_type']));
                ?>

                <div class="home-product-card">

                    <?php if(!empty($p['badge'])){ ?>
                        <span class="product-badge"><?= $p['badge']; ?></span>
                    <?php } ?>

                    <img src="<?= $image_path; ?>">

                    <span class="condition <?= $condition_class; ?>">
                        <?= $p['condition_type']; ?>
                    </span>

                    <h3><?= $p['product_name']; ?></h3>

                    <p><?= $p['brand']; ?></p>

                    <h2>RM<?= number_format($p['price'], 2); ?></h2>

                    <div class="product-actions">
                        <a href="product-detail.php?id=<?= $p['product_id']; ?>">View</a>
                        <a href="addcart.php?id=<?= $p['product_id']; ?>">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                    </div>

                </div>

            <?php } ?>

        </div>

    </section>

    <!--why choose us-->
    <section class="home-section">

        <div class="section-title center">
            <h2>Why Choose <span>ReTech Hub?</span></h2>
        </div>

        <div class="why-grid">

            <div class="why-card">
                <i class="fa-solid fa-shield-halved"></i>
                <h4>Trusted Platform</h4>
                <p>Safe and reliable platform for buy, sell and repair.</p>
            </div>

            <div class="why-card">
                <i class="fa-solid fa-clock"></i>
                <h4>Fast & Convenient</h4>
                <p>Easy booking and smooth process.</p>
            </div>

            <div class="why-card">
                <i class="fa-solid fa-tags"></i>
                <h4>Affordable Prices</h4>
                <p>Quality devices and competitive repair prices.</p>
            </div>

            <div class="why-card">
                <i class="fa-solid fa-leaf"></i>
                <h4>Sustainable Choice</h4>
                <p>Give devices a second life.</p>
            </div>

        </div>

    </section>

    <!--repair banner-->
    <section class="service-banner repair-banner">

        <div>
            <h2>Need Device Repair?</h2>
            <p>
                Book a repair service and let our technician help you inspect and repair your device.
            </p>
            <a href="booking.php">Book Repair</a>
        </div>

        <i class="fa-solid fa-screwdriver-wrench"></i>

    </section>

    <!--sell banner-->
    <section class="service-banner sell-banner">

        <i class="fa-solid fa-mobile-screen-button"></i>

        <div>
            <h2>Sell Your Old Device</h2>
            <p>
                Submit your device details, upload photos and wait for admin review.
            </p>
            <a href="booking.php">Sell Device</a>
        </div>

    </section>

</div>