<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/*get current login user id*/
$user_id = $_SESSION['user_id'];

/*check cart items*/
$cart_items = mysqli_query(
    $conn,
    "SELECT c.*, p.*
     FROM cart c
     JOIN products p ON c.product_id = p.product_id
     WHERE c.user_id='$user_id'
     ORDER BY c.cart_id DESC"
);

/*initialize total amount*/
$total = 0;
?>

<link rel="stylesheet" href="assets/css/cart.css">

<!--page code-->
<div class="cart-page">

    <h1>My Cart</h1>
    <p>Review your selected products before checkout.</p>

    <div class="cart-layout">

        <!--items card-->
        <div class="cart-items">

            <?php if(mysqli_num_rows($cart_items) > 0){ ?>

                <?php while($item = mysqli_fetch_assoc($cart_items)){ ?>

                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;

                    $img_query = mysqli_query(
                        $conn,
                        "SELECT image_path FROM product_images 
                         WHERE product_id='".$item['product_id']."' 
                         LIMIT 1"
                    );

                    $img = mysqli_fetch_assoc($img_query);

                    $image_path = $img
                        ? $img['image_path']
                        : "assets/images/products/default-product.png";
                    ?>

                    <div class="cart-card">

                        <img src="<?= $image_path; ?>" class="cart-img">

                        <div class="cart-info">
                            <h3><?= $item['product_name']; ?></h3>
                            <p><?= $item['brand']; ?> • <?= $item['condition_type']; ?></p>
                            <span>RM<?= number_format($item['price'], 2); ?></span>
                        </div>

                        <div class="cart-qty">
                            <a href="update-cart.php?id=<?= $item['cart_id']; ?>&action=minus">-</a>
                            <b><?= $item['quantity']; ?></b>
                            <a href="update-cart.php?id=<?= $item['cart_id']; ?>&action=plus">+</a>
                        </div>

                        <div class="cart-subtotal">
                            RM<?= number_format($subtotal, 2); ?>
                        </div>

                        <a href="remove-cart.php?id=<?= $item['cart_id']; ?>" class="remove-btn remove-cart">
                            <i class="fa-solid fa-trash"></i>
                        </a>

                    </div>

                <?php } ?>

            <?php }else{ ?>

                <!--empty cart-->
                <div class="empty-cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h2>Your cart is empty</h2>
                    <a href="marketplace.php">Continue Shopping</a>
                </div>

            <?php } ?>

        </div>

        <!--summary card-->
        <div class="cart-summary">

            <h2>Order Summary</h2>

            <div class="summary-row">
                <span>Subtotal</span>
                <b>RM<?= number_format($total, 2); ?></b>
            </div>

            <div class="summary-row">
                <span>Delivery Fee</span>
                <b>RM8.00</b>
            </div>

            <hr>

            <div class="summary-row total">
                <span>Total</span>
                <b>RM<?= number_format($total + 8, 2); ?></b>
            </div>

            <a href="checkout.php" class="checkout-btn">
                Proceed to Checkout
            </a>

            <a href="marketplace.php" class="continue-btn">
                Continue Shopping
            </a>

        </div>

    </div>

</div>

<!--remove confirmation window-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll(".remove-cart").forEach(function(button){

    button.addEventListener("click", function(e){

        e.preventDefault();

        const url = this.href;

        Swal.fire({
            title: "Remove Item?",
            text: "This product will be removed from your cart.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Remove",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d32f2f",
            cancelButtonColor: "#6c757d"
        }).then(function(result){

            if(result.isConfirmed){
                window.location.href = url;
            }

        });

    });

});
</script>

<!--success delete window-->
<?php if(isset($_SESSION['remove_cart_success'])){ ?>
<script>
Swal.fire({
    icon: "success",
    title: "Success",
    text: <?= json_encode($_SESSION['remove_cart_success']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#d32f2f"
});
</script>

<!--fail delete window-->
<?php
unset($_SESSION['remove_cart_success']);
}
?>

<?php if(isset($_SESSION['remove_cart_error'])){ ?>
<script>
Swal.fire({
    icon: "error",
    title: "Error",
    text: <?= json_encode($_SESSION['remove_cart_error']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#d32f2f"
});
</script>
<?php
unset($_SESSION['remove_cart_error']);
}
?>