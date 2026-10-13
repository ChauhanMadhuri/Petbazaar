<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conn.php");

date_default_timezone_set('Asia/Kolkata');

/* LOGIN CHECK */
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = (int)$_SESSION['userid'];

/* ONLY POST REQUEST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_orders.php");
    exit();
}

/* ORDER ID */
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

if ($order_id <= 0) {
    $_SESSION['cancel_error'] = "Invalid order.";
    header("Location: my_orders.php");
    exit();
}


/* GET ORDER */
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, u_id, order_status, order_date
     FROM bill
     WHERE id = ? AND u_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "ii", $order_id, $userid);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* ORDER NOT FOUND */
if (!$order) {
    $_SESSION['cancel_error'] = "Order not found.";
    header("Location: my_orders.php");
    exit();
}


/* ORDER STATUS */
$order_status = !empty($order['order_status'])
    ? $order['order_status']
    : 'Placed';


/* ALREADY CANCELLED */
if ($order_status === 'Cancelled') {
    $_SESSION['cancel_error'] = "This order is already cancelled.";
    header("Location: my_orders.php");
    exit();
}


/* SHIPPED / DELIVERED CANNOT BE CANCELLED */
if ($order_status === 'Shipped' || $order_status === 'Delivered') {
    $_SESSION['cancel_error'] = "This order cannot be cancelled now.";
    header("Location: my_orders.php");
    exit();
}


/* ORDER DATE */
$order_timestamp = !empty($order['order_date'])
    ? strtotime($order['order_date'])
    : 0;


/* INVALID DATE */
if ($order_timestamp <= 0) {
    $_SESSION['cancel_error'] = "Order date is invalid.";
    header("Location: my_orders.php");
    exit();
}


/* 48 HOURS DEADLINE */
$cancel_deadline = $order_timestamp + (48 * 60 * 60);

$current_timestamp = time();


/* 48 HOURS EXPIRED */
if ($current_timestamp > $cancel_deadline) {
    $_SESSION['cancel_error'] =
        "Cancellation period has expired. You can cancel only within 48 hours.";

    header("Location: my_orders.php");
    exit();
}


/* CANCEL ORDER */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE bill
     SET order_status = 'Cancelled',
         cancelled_by = 'User',
         cancelled_at = NOW()
     WHERE id = ?
     AND u_id = ?
     AND order_status NOT IN ('Cancelled', 'Shipped', 'Delivered')"
);

mysqli_stmt_bind_param($stmt, "ii", $order_id, $userid);

if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        // Restore product quantities to inventory
        $items_res = mysqli_query($conn, "SELECT product_id, product_name, quantity FROM orders WHERE bill_id = '$order_id'");
        if ($items_res) {
            while ($it = mysqli_fetch_assoc($items_res)) {
                $pid = (int)$it['product_id'];
                $pname = mysqli_real_escape_string($conn, $it['product_name']);
                $qty = (int)$it['quantity'];

                if ($pid > 0) {
                    mysqli_query($conn, "UPDATE product SET qty = CAST(qty AS SIGNED) + $qty WHERE id = '$pid'");
                } else {
                    mysqli_query($conn, "UPDATE product SET qty = CAST(qty AS SIGNED) + $qty WHERE product_name = '$pname'");
                }
            }
        }

        $_SESSION['cancel_success'] =
            "Order #PB-INV-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " cancelled successfully.";

    } else {

        $_SESSION['cancel_error'] =
            "Order could not be cancelled.";
    }

} else {

    $_SESSION['cancel_error'] =
        "Something went wrong while cancelling the order.";
}

mysqli_stmt_close($stmt);


/* BACK TO MY ORDERS */
header("Location: my_orders.php");
exit();

?>