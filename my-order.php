<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/*get current user info*/
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

/*check ongoing order*/
$orders = mysqli_query(
    $conn,
    "SELECT * FROM orders
     WHERE user_id='$user_id'

     /*Exclude Completed and Cancelled status*/
     AND status NOT IN ('Completed','Cancelled')
     ORDER BY order_id DESC"
);

/*Check if the user has submitted a cancellation order.*/
if(isset($_POST['cancel_order'])){

    /*read order id*/
    $order_id = (int)$_POST['order_id'];

    /*read and process cancel reason*/
    $cancel_reason = mysqli_real_escape_string($conn, $_POST['cancel_reason']);


    /*update order status*/
    mysqli_query(
        $conn,
        "UPDATE orders
         SET status='Cancelled',
            /*save cancel reason*/
             cancel_reason='$cancel_reason'
        /*Restrict order number and user ID*/
         WHERE order_id='$order_id'
         AND user_id='$user_id'"
    );

    $admin_id = 1;

    /*send notification to admin*/
    mysqli_query(
        $conn,
        "INSERT INTO notifications
        (user_id, title, message, type)
        VALUES
        (
            '$admin_id',
            'Order Cancelled by User',
            'User #$user_id $username cancelled order #ORD$order_id. Reason: $cancel_reason',
            'order'
        )"
    );

    /* cancel success message */
    $_SESSION['cancel_success'] = "Order cancelled successfully.";

    header("Location: my-order.php");
    exit();
}

/*check order history record*/
$history_orders = mysqli_query(
    $conn,
    "SELECT * FROM orders
     WHERE user_id='$user_id'
     AND status IN ('Completed','Cancelled')
     ORDER BY order_id DESC"
);
?>

<?php include("includes/header.php"); ?>

<link rel="stylesheet" href="assets/css/my-order.css">

<div class="my-order-page">

    <h1>My Orders</h1>
    <p>Track and manage your purchase history.</p>

    <!--switch button-->
    <div class="order-tabs">
        <button class="tab-btn active" onclick="showTab('active')">
            Active Orders
        </button>

        <button class="tab-btn" onclick="showTab('history')">
            Order History
        </button>
    </div>

    <div id="activeTab">

        <!--active order page-->
        <?php if(mysqli_num_rows($orders) > 0){ ?>

            <?php while($order = mysqli_fetch_assoc($orders)){ ?>

                <div class="order-card">

                    <div class="order-top">
                        <div>
                            <h2>Order #<?= $order['order_id']; ?></h2>
                            <p><?= $order['created_at']; ?></p>
                        </div>

                        <span class="order-status <?= strtolower($order['status']); ?>">
                            <?= $order['status']; ?>
                        </span>
                    </div>

                    <div class="order-info">
                        <div>
                            <b>Receiver</b>
                            <p><?= $order['full_name']; ?></p>
                        </div>

                        <div>
                            <b>Phone</b>
                            <p><?= $order['phone']; ?></p>
                        </div>

                        <div>
                            <b>Payment</b>
                            <p><?= $order['payment_method']; ?></p>
                        </div>

                        <div>
                            <b>Total</b>
                            <p class="total-price">
                                RM<?= number_format($order['total_amount'], 2); ?>
                            </p>
                        </div>
                    </div>

                    <div class="order-address">
                        <b>Delivery Address</b>
                        <p><?= $order['address']; ?></p>
                    </div>

                    <div class="order-items">

                        <?php
                        $items = mysqli_query(
                            $conn,
                            "SELECT oi.*, p.product_name, p.brand, p.condition_type
                             FROM order_items oi
                             JOIN products p ON oi.product_id = p.product_id
                             WHERE oi.order_id='".$order['order_id']."'"
                        );

                        while($item = mysqli_fetch_assoc($items)){
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

                            <div class="order-item">

                                <img src="<?= $image_path; ?>">

                                <div>
                                    <h3><?= $item['product_name']; ?></h3>
                                    <p><?= $item['brand']; ?> • <?= $item['condition_type']; ?></p>
                                    <span>Qty: <?= $item['quantity']; ?></span>
                                </div>

                                <b>
                                    RM<?= number_format($item['price'] * $item['quantity'], 2); ?>
                                </b>

                            </div>

                        <?php } ?>

                    </div>

                    <div class="order-actions">
                        <a href="track-order.php?id=<?= $order['order_id']; ?>" class="track-btn">
                            Track Order
                        </a>

                        <a href="buy-again.php?id=<?= $order['order_id']; ?>" class="shop-btn">
                            Buy Again
                        </a>

                        <button type="button" class="cancel-order-btn" onclick="openCancelModal(<?= $order['order_id']; ?>)">
                            Cancel Order
                        </button>
                    </div>

                </div>

            <?php } ?>

        <?php }else{ ?>
            <!--display when no active order-->
            <div class="empty-orders">
                <i class="fa-solid fa-box-open"></i>
                <h2>No active orders</h2>
                <p>You don't have any active orders.</p>
                <a href="marketplace.php">Start Shopping</a>
            </div>

        <?php } ?>

    </div>

    <!--history page-->
    <div id="historyTab" style="display:none;">

        <?php if(mysqli_num_rows($history_orders) > 0){ ?>

            <?php while($order = mysqli_fetch_assoc($history_orders)){ ?>

                <div class="order-card">

                    <div class="order-top">
                        <div>
                            <h2>Order #<?= $order['order_id']; ?></h2>
                            <p><?= $order['created_at']; ?></p>
                        </div>

                        <span class="order-status <?= strtolower($order['status']); ?>">
                            <?= $order['status']; ?>
                        </span>
                    </div>

                    <div class="order-info">
                        <div>
                            <b>Receiver</b>
                            <p><?= $order['full_name']; ?></p>
                        </div>

                        <div>
                            <b>Phone</b>
                            <p><?= $order['phone']; ?></p>
                        </div>

                        <div>
                            <b>Payment</b>
                            <p><?= $order['payment_method']; ?></p>
                        </div>

                        <div>
                            <b>Total</b>
                            <p class="total-price">
                                RM<?= number_format($order['total_amount'], 2); ?>
                            </p>
                        </div>
                    </div>

                    <div class="order-address">
                        <b>Delivery Address</b>
                        <p><?= $order['address']; ?></p>
                    </div>

                    <div class="order-actions">
                        <a href="buy-again.php?id=<?= $order['order_id']; ?>" class="shop-btn">
                            Buy Again
                        </a>
                    </div>

                </div>

            <?php } ?>

        <?php }else{ ?>

            <!--display when no order history-->
            <div class="empty-orders">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <h2>No order history</h2>
                <p>Completed or cancelled orders will appear here.</p>
            </div>

        <?php } ?>

    </div>

</div>

<!--cancel function-->
<div class="cancel-modal" id="cancelModal">

    <div class="cancel-modal-box">

        <h2>Cancel Order</h2>
        <p>Please select a reason for cancellation.</p>

        <form method="POST">

            <input type="hidden" name="order_id" id="cancelOrderId">

            <label>Reason</label>

            <select name="cancel_reason" required>
                <option value="">Select reason</option>
                <option value="Changed my mind">Changed my mind</option>
                <option value="Found a better price">Found a better price</option>
                <option value="Ordered by mistake">Ordered by mistake</option>
                <option value="Delivery takes too long">Delivery takes too long</option>
                <option value="Payment issue">Payment issue</option>
                <option value="Prefer not to answer">Prefer not to answer</option>
            </select>

            <div class="cancel-modal-actions">
                <button type="button" onclick="closeCancelModal()" class="modal-close-btn">
                    Close
                </button>

                <button type="submit" name="cancel_order" class="modal-cancel-btn">
                    Confirm Cancel
                </button>
            </div>

        </form>

    </div>

</div>

<script>
function showTab(tab){

    /*get page content*/
    const activeTab = document.getElementById("activeTab");
    const historyTab = document.getElementById("historyTab");
    /*get all page button*/
    const buttons = document.querySelectorAll(".tab-btn");

    buttons.forEach(btn => {
        btn.classList.remove("active");
    });

    /*shows current order*/
    if(tab === "active"){
        activeTab.style.display = "block";
        historyTab.style.display = "none";
        buttons[0].classList.add("active");

    /*shows order history*/
    }else{
        activeTab.style.display = "none";
        historyTab.style.display = "block";
        buttons[1].classList.add("active");
    }
}

/*Open Cancel Modal confirmation window*/
function openCancelModal(orderId){
    document.getElementById("cancelOrderId").value = orderId;
    document.getElementById("cancelModal").style.display = "flex";
}

/*close Cancel Modal confirmation window.*/
function closeCancelModal(){
    document.getElementById("cancelModal").style.display = "none";
}
</script>

<!--cancel success window-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['cancel_success'])){ ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Cancelled',
    text: '<?= htmlspecialchars($_SESSION['cancel_success']); ?>',
    confirmButtonColor: '#e53935'
});
</script>

<?php
unset($_SESSION['cancel_success']);
}
?>

<!--checkout success window-->
<?php if(isset($_SESSION['order_success'])){ ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Order Placed',
    text: '<?= htmlspecialchars($_SESSION['order_success']); ?>',
    confirmButtonColor: '#e53935'
});
</script>

<?php
unset($_SESSION['order_success']);
}
?>