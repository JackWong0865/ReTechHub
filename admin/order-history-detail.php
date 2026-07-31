<?php
session_start();
include("../includes/db.php");

/*check admin permission*/
if(
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
){
    header("Location: ../login.php");
    exit();
}

/*check order id*/
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: order-history.php");
    exit();
}

/*get order id*/
$order_id = (int)$_GET['id'];

/*update order status*/
if(isset($_POST['update_status'])){

    /*get submitted order information*/
    $submitted_order_id = isset($_POST['order_id'])
        ? (int)$_POST['order_id']
        : 0;

    $new_status = trim($_POST['status'] ?? '');

    /*allowed order statuses*/
    $allowed_statuses = [
        'Pending',
        'Processing',
        'Shipped',
        'Completed',
        'Cancelled'
    ];

    /*validate order id*/
    if($submitted_order_id <= 0){

        $_SESSION['history_status_error'] =
            "Invalid order ID.";

        header("Location: order-history.php");
        exit();
    }

    /*validate selected status*/
    if(!in_array($new_status, $allowed_statuses, true)){

        $_SESSION['history_status_error'] =
            "Invalid order status selected.";

        header(
            "Location: order-history-detail.php?id=" .
            $submitted_order_id
        );
        exit();
    }

    /*get existing order information*/
    $existing_stmt = mysqli_prepare(
        $conn,
        "SELECT user_id, status, cancel_reason
         FROM orders
         WHERE order_id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $existing_stmt,
        "i",
        $submitted_order_id
    );

    mysqli_stmt_execute($existing_stmt);

    $existing_result =
        mysqli_stmt_get_result($existing_stmt);

    $existing_order =
        mysqli_fetch_assoc($existing_result);

    mysqli_stmt_close($existing_stmt);

    /*check whether order exists*/
    if(!$existing_order){

        $_SESSION['history_status_error'] =
            "Order not found.";

        header("Location: order-history.php");
        exit();
    }

    /*
    When admin changes the status to Cancelled,
    cancel_reason is cleared.

    A cancelled order without cancel_reason will be
    displayed as Cancelled By: Admin.
    */
    if($new_status === 'Cancelled'){

        $update_stmt = mysqli_prepare(
            $conn,
            "UPDATE orders
             SET status = ?,
                 cancel_reason = NULL
             WHERE order_id = ?"
        );

    }else{

        /*
        Clear cancellation reason when the order is
        changed back to another status.
        */
        $update_stmt = mysqli_prepare(
            $conn,
            "UPDATE orders
             SET status = ?,
                 cancel_reason = NULL
             WHERE order_id = ?"
        );
    }

    mysqli_stmt_bind_param(
        $update_stmt,
        "si",
        $new_status,
        $submitted_order_id
    );

    $updated =
        mysqli_stmt_execute($update_stmt);

    mysqli_stmt_close($update_stmt);

    /*continue when status update succeeds*/
    if($updated){

        /*get notification recipient*/
        $notify_user =
            (int)$existing_order['user_id'];

        /*prepare notification information*/
        $notification_title =
            "Order Status Updated";

        $notification_message =
            "Your order #ORD" .
            $submitted_order_id .
            " status has been updated to " .
            $new_status .
            ".";

        /*insert notification for customer*/
        $notification_stmt = mysqli_prepare(
            $conn,
            "INSERT INTO notifications
            (user_id, title, message, type)
            VALUES (?, ?, ?, 'order')"
        );

        mysqli_stmt_bind_param(
            $notification_stmt,
            "iss",
            $notify_user,
            $notification_title,
            $notification_message
        );

        mysqli_stmt_execute($notification_stmt);

        mysqli_stmt_close($notification_stmt);

        /*
        Active statuses belong to orders.php.
        */
        if(
            $new_status === 'Pending' ||
            $new_status === 'Processing' ||
            $new_status === 'Shipped'
        ){

            $_SESSION['order_status_success'] =
                "Order #ORD" .
                $submitted_order_id .
                " status updated to " .
                $new_status .
                " successfully.";

            header("Location: orders.php");
            exit();
        }

        /*
        Completed and Cancelled orders remain
        inside order history.
        */
        $_SESSION['history_status_success'] =
            "Order #ORD" .
            $submitted_order_id .
            " status updated to " .
            $new_status .
            " successfully.";

        header(
            "Location: order-history-detail.php?id=" .
            $submitted_order_id
        );
        exit();

    }else{

        /*save error message*/
        $_SESSION['history_status_error'] =
            "Unable to update the order status.";

        header(
            "Location: order-history-detail.php?id=" .
            $submitted_order_id
        );
        exit();
    }
}

/*get completed or cancelled order information*/
$order_stmt = mysqli_prepare(
    $conn,
    "SELECT
        o.*,
        u.username,
        u.email AS user_email
     FROM orders o
     LEFT JOIN users u
        ON o.user_id = u.user_id
     WHERE o.order_id = ?
     AND o.status IN ('Completed', 'Cancelled')
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $order_stmt,
    "i",
    $order_id
);

mysqli_stmt_execute($order_stmt);

$order_result =
    mysqli_stmt_get_result($order_stmt);

$order =
    mysqli_fetch_assoc($order_result);

mysqli_stmt_close($order_stmt);

/*check whether history order exists*/
if(!$order){
    header("Location: order-history.php");
    exit();
}

/*get purchased items*/
$items_stmt = mysqli_prepare(
    $conn,
    "SELECT
        oi.*,
        p.product_name,
        p.brand,
        p.condition_type
     FROM order_items oi
     LEFT JOIN products p
        ON oi.product_id = p.product_id
     WHERE oi.order_id = ?"
);

mysqli_stmt_bind_param(
    $items_stmt,
    "i",
    $order_id
);

mysqli_stmt_execute($items_stmt);

$items =
    mysqli_stmt_get_result($items_stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Order History Detail - ReTech Hub
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/admin.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/admin-orders.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>

<body>

<div class="admin-layout">

    <!--sidebar-->
    <aside class="sidebar">

        <img src="../assets/images/logo.png" class="admin-logo">

        <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
        <a href="products.php"><i class="fa-solid fa-box"></i> Listings</a>
        <a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders</a>
        <a href="order-history.php" class="active"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
        <a href="upload.php"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Product</a>
        <a href="draft.php"><i class="fa-solid fa-file-lines"></i> Draft Products</a>
        <a href="repairs.php"><i class="fa-solid fa-clipboard-list"></i> Booking Details</a>
        <a href="technician-workload.php"><i class="fa-solid fa-user-gear"></i>Technician Workload</a>
        <a href="messages.php"><i class="fa-solid fa-message"></i> Messages</a>
        <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

    </aside>

    <!--main content-->
    <main class="admin-main">

        <!--order detail card-->
        <div class="order-detail-card">

            <a
                href="order-history.php"
                class="back-link"
            >
                &lt; Back to Order History
            </a>

            <h1>
                Order #ORD<?= (int)$order['order_id']; ?>
            </h1>

            <div class="detail-grid">

                <!--customer information-->
                <div class="detail-box">

                    <h3>Customer Info</h3>

                    <p>
                        <b>Username:</b>

                        <?= htmlspecialchars(
                            $order['username']
                            ?? 'Unknown User'
                        ); ?>
                    </p>

                    <p>
                        <b>Name:</b>

                        <?= htmlspecialchars(
                            $order['full_name']
                            ?? '-'
                        ); ?>
                    </p>

                    <p>
                        <b>Email:</b>

                        <?= htmlspecialchars(
                            $order['user_email']
                            ?? $order['email']
                            ?? '-'
                        ); ?>
                    </p>

                    <p>
                        <b>Phone:</b>

                        <?= htmlspecialchars(
                            $order['phone']
                            ?? '-'
                        ); ?>
                    </p>

                </div>

                <!--order information-->
                <div class="detail-box">

                    <h3>Order Info</h3>

                    <p>
                        <b>Status:</b>

                        <span class="order-status <?= strtolower(
                            htmlspecialchars($order['status'])
                        ); ?>">

                            <?= htmlspecialchars(
                                $order['status']
                            ); ?>

                        </span>
                    </p>

                    <p>
                        <b>Payment:</b>

                        <?= htmlspecialchars(
                            $order['payment_method']
                            ?? '-'
                        ); ?>
                    </p>

                    <p>
                        <b>Subtotal:</b>

                        RM<?= number_format(
                            (float)($order['subtotal'] ?? 0),
                            2
                        ); ?>
                    </p>

                    <p>
                        <b>Delivery Fee:</b>

                        RM<?= number_format(
                            (float)($order['delivery_fee'] ?? 0),
                            2
                        ); ?>
                    </p>

                    <p>
                        <b>Total:</b>

                        RM<?= number_format(
                            (float)($order['total_amount'] ?? 0),
                            2
                        ); ?>
                    </p>

                    <p>
                        <b>Order Date:</b>

                        <?= htmlspecialchars(
                            $order['created_at']
                            ?? '-'
                        ); ?>
                    </p>

                </div>

                <!--delivery address-->
                <div class="detail-box full">

                    <h3>Delivery Address</h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars(
                                $order['address']
                                ?? 'No delivery address provided.'
                            )
                        ); ?>
                    </p>

                </div>

                <!--cancellation information-->
                <?php if($order['status'] === 'Cancelled'){ ?>

                    <div class="detail-box full">

                        <h3>
                            Cancellation Information
                        </h3>

                        <?php if(!empty($order['cancel_reason'])){ ?>

                            <!--customer cancellation-->
                            <p>
                                <b>Cancelled By:</b>
                                Customer
                            </p>

                            <p>
                                <b>Reason:</b>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $order['cancel_reason']
                                    )
                                ); ?>
                            </p>

                        <?php }else{ ?>

                            <!--admin cancellation-->
                            <p>
                                <b>Cancelled By:</b>
                                Admin
                            </p>

                        <?php } ?>

                    </div>

                <?php } ?>

                <!--update order status-->
                <div class="detail-box full">

                    <h3>
                        Update Order Status
                    </h3>

                    <form method="POST" id="statusUpdateForm" class="history-status-form">

                        <input type="hidden"name="order_id"value="<?= (int)$order['order_id']; ?>">

                        <!--ensure PHP receives update_status-->
                        <input type="hidden"name="update_status"value="1">

                        <select name="status" id="historyStatus" required>

                            <option
                                value="Pending"
                                <?= $order['status'] === 'Pending'
                                    ? 'selected'
                                    : ''; ?>
                            >
                                Pending
                            </option>

                            <option
                                value="Processing"
                                <?= $order['status'] === 'Processing'
                                    ? 'selected'
                                    : ''; ?>
                            >
                                Processing
                            </option>

                            <option
                                value="Shipped"
                                <?= $order['status'] === 'Shipped'
                                    ? 'selected'
                                    : ''; ?>
                            >
                                Shipped
                            </option>

                            <option
                                value="Completed"
                                <?= $order['status'] === 'Completed'
                                    ? 'selected'
                                    : ''; ?>
                            >
                                Completed
                            </option>

                            <option
                                value="Cancelled"
                                <?= $order['status'] === 'Cancelled'
                                    ? 'selected'
                                    : ''; ?>
                            >
                                Cancelled
                            </option>

                        </select>

                        <button type="submit">
                            Update Status
                        </button>

                    </form>

                    <small>
                        Changing the status to Pending,
                        Processing or Shipped will return
                        the order to Order Management.
                    </small>

                </div>

                <!--order items-->
                <div class="detail-box full">

                    <h3>Order Items</h3>

                    <?php if(mysqli_num_rows($items) > 0){ ?>

                        <?php while(
                            $item = mysqli_fetch_assoc($items)
                        ){ ?>

                            <?php
                            /*get the first product image*/
                            $image_stmt = mysqli_prepare(
                                $conn,
                                "SELECT image_path
                                 FROM product_images
                                 WHERE product_id = ?
                                 LIMIT 1"
                            );

                            $product_id =
                                (int)$item['product_id'];

                            mysqli_stmt_bind_param(
                                $image_stmt,
                                "i",
                                $product_id
                            );

                            mysqli_stmt_execute($image_stmt);

                            $image_result =
                                mysqli_stmt_get_result($image_stmt);

                            $image =
                                mysqli_fetch_assoc($image_result);

                            mysqli_stmt_close($image_stmt);

                            /*set product image path*/
                            $image_path = $image
                                ? "../" . $image['image_path']
                                : "../assets/images/products/default-product.png";

                            /*support quantity or qty column*/
                            $quantity = isset($item['quantity'])
                                ? (int)$item['quantity']
                                : (int)($item['qty'] ?? 1);

                            /*calculate item total*/
                            $item_total =
                                (float)$item['price'] *
                                $quantity;
                            ?>

                            <div class="order-item-row">

                                <img
                                    src="<?= htmlspecialchars(
                                        $image_path
                                    ); ?>"
                                    alt="Product image"
                                >

                                <div>

                                    <h4>
                                        <?= htmlspecialchars(
                                            $item['product_name']
                                            ?? 'Unknown Product'
                                        ); ?>
                                    </h4>

                                    <p>
                                        <?= htmlspecialchars(
                                            $item['brand']
                                            ?? '-'
                                        ); ?>

                                        •

                                        <?= htmlspecialchars(
                                            $item['condition_type']
                                            ?? '-'
                                        ); ?>
                                    </p>

                                    <span>
                                        Qty: <?= $quantity; ?>
                                    </span>

                                </div>

                                <b>
                                    RM<?= number_format(
                                        $item_total,
                                        2
                                    ); ?>
                                </b>

                            </div>

                        <?php } ?>

                    <?php }else{ ?>

                        <p>No order items found.</p>

                    <?php } ?>

                </div>

            </div>

        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/*confirm order status update*/
const statusUpdateForm =
    document.getElementById("statusUpdateForm");

statusUpdateForm.addEventListener("submit", function(event){

    event.preventDefault();

    const selectedStatus =
        document.getElementById("historyStatus").value;

    Swal.fire({
        icon: "warning",
        title: "Update Order Status?",
        text:
            "Are you sure you want to change this order status to " +
            selectedStatus +
            "?",
        showCancelButton: true,
        confirmButtonText: "Yes, Update",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#e53935",
        cancelButtonColor: "#6c757d",
        reverseButtons: true
    }).then((result) => {

        if(result.isConfirmed){
            statusUpdateForm.submit();
        }

    });

});
</script>

<!--display status update success message-->
<?php if(isset($_SESSION['history_status_success'])){ ?>

<script>
Swal.fire({
    icon: "success",
    title: "Order Status Updated",
    text: <?= json_encode(
        $_SESSION['history_status_success']
    ); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>

<?php
unset($_SESSION['history_status_success']);
}
?>

<!--display status update error message-->
<?php if(isset($_SESSION['history_status_error'])){ ?>

<script>
Swal.fire({
    icon: "error",
    title: "Unable to Update Status",
    text: <?= json_encode(
        $_SESSION['history_status_error']
    ); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>

<?php
unset($_SESSION['history_status_error']);
}
?>

</body>
</html>

<?php
/*close items statement*/
mysqli_stmt_close($items_stmt);
?>