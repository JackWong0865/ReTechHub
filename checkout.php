<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/*check current login user id*/
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

/*set default delivery method*/
$delivery_fee = 8.00;
$delivery_method = "Standard Delivery";

/*check cart product*/
$cart_items = mysqli_query(
    $conn,
    "SELECT c.*, p.*
     FROM cart c
     JOIN products p ON c.product_id = p.product_id
     WHERE c.user_id='$user_id'"
);

/*check if the shopping cart is empty*/
if(mysqli_num_rows($cart_items) == 0){
    header("Location: cart.php");
    exit();
}

/*check product subtotal*/
$subtotal = 0;
$items = [];

while($item = mysqli_fetch_assoc($cart_items)){
    $item_total = $item['price'] * $item['quantity'];
    $subtotal += $item_total;
    $items[] = $item;
}

/*calculate product total*/
$total = $subtotal + $delivery_fee;

if(isset($_POST['place_order'])){

    /*get delivery method*/
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);

    /*determine shipping costs*/
    if($delivery_method == "Self Pickup"){
        $delivery_fee = 0.00;
    }else{
        $delivery_fee = 8.00;
    }

    $total = $subtotal + $delivery_fee;

    /*get customer info*/
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    if(!preg_match('/^[0-9]{10,11}$/', $phone)){
        die("Phone number must contain only numbers.");
    }

    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);


    mysqli_query(
        $conn,
        "INSERT INTO orders
        (user_id, full_name, phone, address, payment_method, delivery_method, subtotal, delivery_fee, total_amount, status)
        VALUES
        ('$user_id','$full_name','$phone','$address','$payment_method','$delivery_method','$subtotal','$delivery_fee','$total','Pending')"
    );

    /*get order id*/
    $order_id = mysqli_insert_id($conn);

    /*notify admin*/
    $admin_id = 1;

        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id, title, message, type)
            VALUES
            (
                '$admin_id',
                'New Order Placed',
                'User #$user_id  $username placed a new order #ORD$order_id.',
                'order'
            )"
        );

    foreach($items as $item){
        mysqli_query(
            $conn,
            "INSERT INTO order_items
            (order_id, product_id, quantity, price)
            VALUES
            ('$order_id','".$item['product_id']."','".$item['quantity']."','".$item['price']."')"
        );
    }

    /*clear cart after checkout*/
    mysqli_query(
        $conn,
        "DELETE FROM cart WHERE user_id='$user_id'"
    );

    /*success message*/
    $_SESSION['order_success'] =
        "Order placed successfully.";

    /*redirect to My Order*/
    header("Location: my-order.php");
    exit();
}
?>

<?php include("includes/header.php"); ?>

<link rel="stylesheet" href="assets/css/checkout.css">

<div class="checkout-page">

    <!--page code-->
    <h1>Checkout</h1>
    <p>Complete your order details and payment method.</p>

    <form method="POST" class="checkout-layout">

        <div class="checkout-form-card">

            <h2>Shipping Details</h2>

            <label>Full Name</label>
            <input type="text" name="full_name" required>

            <label>Phone Number</label>
            <input type="tel" name="phone" maxlength="11" pattern="[0-9]{10,11}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>

            <label>Delivery Address</label>
            <textarea name="address" required></textarea>

            <h2>Delivery Method</h2>

            <div class="payment-options">

                <label class="payment-card">
                    <input type="radio" name="delivery_method" value="Standard Delivery" checked>
                    <i class="fa-solid fa-truck"></i>
                    <span>Standard Delivery</span>
                    <small>RM8.00</small>
                </label>

                <label class="payment-card">
                    <input type="radio" name="delivery_method" value="Self Pickup">
                    <i class="fa-solid fa-store"></i>
                    <span>Self Pickup</span>
                    <small>Free</small>
                </label>

            </div>

            <h2>Payment Method</h2>

            <div class="payment-options">

                <label class="payment-card">
                    <input type="radio" name="payment_method" value="Cash On Delivery" checked>
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Cash On Delivery</span>
                </label>

                <label class="payment-card">
                    <input type="radio" name="payment_method" value="Online Banking">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Online Banking</span>
                </label>

                <label class="payment-card">
                    <input type="radio" name="payment_method" value="Credit Card">
                    <i class="fa-regular fa-credit-card"></i>
                    <span>Credit Card</span>
                </label>

            </div>

        </div>

        <div class="order-summary-card">

            <h2>Order Summary</h2>

            <?php foreach($items as $item){ ?>

                <?php
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

                <div class="summary-item">

                    <img src="<?= $image_path; ?>">

                    <div>
                        <h4><?= $item['product_name']; ?></h4>
                        <p>Qty: <?= $item['quantity']; ?></p>
                    </div>

                    <b>RM<?= number_format($item['price'] * $item['quantity'], 2); ?></b>

                </div>

            <?php } ?>

            <hr>

            <div class="summary-row">
                <span>Subtotal</span>
                <b>RM<?= number_format($subtotal, 2); ?></b>
            </div>

            <div class="summary-row">
                <span>Delivery Fee</span>
                <b id="deliveryFeeText">RM<?= number_format($delivery_fee, 2); ?></b>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <b id="totalText">RM<?= number_format($total, 2); ?></b>
            </div>

            <button type="submit" name="place_order" class="place-order-btn">
                Place Order
            </button>

            <a href="cart.php" class="back-cart-btn">
                Back to Cart
            </a>

        </div>

    </form>

</div>

<script>

/*get subtotal*/
const subtotal = <?= $subtotal; ?>;

/*get delivery method*/
const deliveryRadios = document.querySelectorAll("input[name='delivery_method']");
const deliveryFeeText = document.getElementById("deliveryFeeText");
const totalText = document.getElementById("totalText");

deliveryRadios.forEach(radio => {
    radio.addEventListener("change", function(){

        /*determine delivery method*/
        let deliveryFee = this.value === "Self Pickup" ? 0 : 8;
        /*recalculate total*/
        let total = subtotal + deliveryFee;

        /*update delivery fee*/
        deliveryFeeText.innerText = "RM" + deliveryFee.toFixed(2);
        /*update total*/
        totalText.innerText = "RM" + total.toFixed(2);
    });
});
</script>