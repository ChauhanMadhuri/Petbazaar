<?php

session_start();

include("conn.php");

/* =========================================
   SUCCESS POPUP CHECK
========================================= */

$show_success = false;
$success_bill_id = 0;

if (
    isset($_SESSION['bill_success']) &&
    $_SESSION['bill_success'] == true &&
    isset($_SESSION['bill_id'])
) {
    $show_success = true;
    $success_bill_id = (int)$_SESSION['bill_id'];

    unset($_SESSION['bill_success']);
    unset($_SESSION['bill_id']);
}


/* =========================================
   IF ORDER SUCCESS
========================================= */

if ($show_success && $success_bill_id > 0) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<script>

Swal.fire({
    title: 'Order Successful!',
    text: 'Your order has been placed successfully.',
    icon: 'success',
    confirmButtonText: 'OK',
    confirmButtonColor: '#146c2e',
    allowOutsideClick: false,
    allowEscapeKey: false

}).then(function(result) {

    if (result.isConfirmed) {

        window.location.replace(
            'bill.php?o_id=<?php echo $success_bill_id; ?>'
        );

    }

});

</script>

</body>
</html>

<?php
exit();
}


/* =========================================
   LOGIN CHECK
========================================= */

if (!isset($_SESSION['loggedinuser']) || $_SESSION['loggedinuser'] !== true || !isset($_SESSION['userid']) || empty($_SESSION['userid'])) {

    header("Location: login.php");
    exit();

}

$userid = (int)$_SESSION['userid'];

$user_profile_q = mysqli_query($conn, "SELECT * FROM ragistration WHERE Id='$userid' LIMIT 1");
$user_data = ($user_profile_q && mysqli_num_rows($user_profile_q) > 0) ? mysqli_fetch_assoc($user_profile_q) : [];


/* =========================================
   CHECK CART
========================================= */

$cart_check = mysqli_query(
    $conn,
    "SELECT * FROM addtocart WHERE u_id='$userid'"
);

if (!$cart_check || mysqli_num_rows($cart_check) == 0) {

    echo "<script>
            alert('Your cart is empty.');
            window.location='cart.php';
          </script>";

    exit();
}


/* =========================================
   FORM SUBMIT
========================================= */

if (isset($_POST['checkout'])) {

    /* CUSTOMER DETAILS */

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['name']
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST['phone']
    );

    $pin = mysqli_real_escape_string(
        $conn,
        $_POST['pin']
    );

    $city = mysqli_real_escape_string(
        $conn,
        $_POST['city']
    );

    $state = mysqli_real_escape_string(
        $conn,
        $_POST['state']
    );

    $address = mysqli_real_escape_string(
        $conn,
        $_POST['address']
    );

    $payment = mysqli_real_escape_string(
        $conn,
        $_POST['payment']
    );


    /* GET EMAIL */

    $email = "";

    $user_email_q = mysqli_query(
        $conn,
        "SELECT email
         FROM ragistration
         WHERE Id='$userid'
         LIMIT 1"
    );

    if ($user_email_q && mysqli_num_rows($user_email_q) > 0) {
        $user_email_row = mysqli_fetch_assoc($user_email_q);
        $email = $user_email_row['email'];
    }

    if (empty($email) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    }

    $email = mysqli_real_escape_string($conn, $email);


    /* GET CART ITEMS */

    $cart_items = [];

    $cart_query = mysqli_query(
        $conn,
        "SELECT *
         FROM addtocart
         WHERE u_id='$userid'"
    );

    if (!$cart_query) {

        echo "<script>
                alert('Cart Error: " . mysqli_error($conn) . "');
                window.location='cart.php';
              </script>";

        exit();
    }


    /* CALCULATE SUBTOTAL */

    $subtotal = 0;

    while ($cart = mysqli_fetch_assoc($cart_query)) {

        $cart_items[] = $cart;

        $price = (float)$cart['p_price'];

        $quantity = (int)$cart['p_qty'];

        $subtotal += $price * $quantity;
    }

    /* SHIPPING: FREE above 499, otherwise 200 */
    $shipping = ($subtotal >= 499) ? 0 : 200;

    $total = $subtotal + $shipping;


    /* START TRANSACTION */

    mysqli_begin_transaction($conn);

    try {

        /* CHECK STOCK */

        foreach ($cart_items as $cart) {

            $product_name = mysqli_real_escape_string(
                $conn,
                $cart['p_name']
            );

            $quantity = (int)$cart['p_qty'];

            $product_query = mysqli_query(
                $conn,
                "SELECT id, product_name, qty
                 FROM product
                 WHERE product_name='$product_name'
                 LIMIT 1
                 FOR UPDATE"
            );

            if (!$product_query) {

                throw new Exception(
                    "Product Query Error: " .
                    mysqli_error($conn)
                );
            }

            if (mysqli_num_rows($product_query) == 0) {

                throw new Exception(
                    "Product not found: " .
                    $product_name
                );
            }

            $product = mysqli_fetch_assoc($product_query);

            $available_stock = (int)$product['qty'];

            if ($available_stock < $quantity) {

                throw new Exception(
                    "Not enough stock for " .
                    $product_name .
                    ". Available stock: " .
                    $available_stock
                );
            }
        }


        $payment_status_val = ($payment === 'Online Payment' || $payment === '1') ? 'Paid' : 'Pending';

        $bill_sql = "INSERT INTO bill
        (
            u_id,
            name,
            email,
            phone,
            pincode,
            city,
            state,
            address,
            subtotal,
            shipping,
            total_amount,
            payment,
            order_status,
            payment_status,
            order_date
        )
        VALUES
        (
            '$userid',
            '$name',
            '$email',
            '$phone',
            '$pin',
            '$city',
            '$state',
            '$address',
            '$subtotal',
            '$shipping',
            '$total',
            '$payment',
            'Pending',
            '$payment_status_val',
            NOW()
        )";


        if (!mysqli_query($conn, $bill_sql)) {

            throw new Exception(
                "Bill Insert Error: " .
                mysqli_error($conn)
            );
        }


        /* GET BILL ID */

        $bill_id = mysqli_insert_id($conn);


        /* INSERT ORDERS + UPDATE STOCK */

        foreach ($cart_items as $cart) {

            $product_name = mysqli_real_escape_string(
                $conn,
                $cart['p_name']
            );

            $quantity = (int)$cart['p_qty'];

            $price = (float)$cart['p_price'];

            $item_subtotal = $price * $quantity;


            /* GET PRODUCT */

            $product_query = mysqli_query(
                $conn,
                "SELECT id, qty
                 FROM product
                 WHERE product_name='$product_name'
                 LIMIT 1
                 FOR UPDATE"
            );

            if (!$product_query) {

                throw new Exception(
                    "Product Query Error: " .
                    mysqli_error($conn)
                );
            }

            if (mysqli_num_rows($product_query) == 0) {

                throw new Exception(
                    "Product not found: " .
                    $product_name
                );
            }

            $product = mysqli_fetch_assoc($product_query);

            $product_id = (int)$product['id'];

            $available_stock = (int)$product['qty'];


            /* FINAL STOCK CHECK */

            if ($available_stock < $quantity) {

                throw new Exception(
                    "Stock not available for " .
                    $product_name
                );
            }


            /* INSERT ORDER */

            $order_sql = "INSERT INTO orders
            (
                bill_id,
                u_id,
                product_id,
                product_name,
                product_price,
                quantity,
                subtotal
            )
            VALUES
            (
                '$bill_id',
                '$userid',
                '$product_id',
                '$product_name',
                '$price',
                '$quantity',
                '$item_subtotal'
            )";


            if (!mysqli_query($conn, $order_sql)) {

                throw new Exception(
                    "Order Insert Error: " .
                    mysqli_error($conn)
                );
            }


            /* UPDATE STOCK */

            $stock_sql = "UPDATE product
                          SET qty = CAST(qty AS SIGNED) - $quantity
                          WHERE id='$product_id'
                          AND CAST(qty AS SIGNED) >= $quantity";


            if (!mysqli_query($conn, $stock_sql)) {

                throw new Exception(
                    "Stock Update Error: " .
                    mysqli_error($conn)
                );
            }


            if (mysqli_affected_rows($conn) == 0) {

                throw new Exception(
                    "Stock update failed for " .
                    $product_name
                );
            }
        }


        /* CLEAR USER CART */

        $clear_cart = mysqli_query(
            $conn,
            "DELETE FROM addtocart WHERE u_id='$userid'"
        );

        if (!$clear_cart) {

            throw new Exception(
                "Clear Cart Error: " .
                mysqli_error($conn)
            );
        }


        /* COMMIT */

        mysqli_commit($conn);


        /* SUCCESS SESSION */

        $_SESSION['bill_success'] = true;

        $_SESSION['bill_id'] = $bill_id;


        header("Location: checkout.php");

        exit();


    } catch (Exception $e) {

        mysqli_rollback($conn);

        $error_message = addslashes(
            $e->getMessage()
        );

        echo "<script>

                alert('Order Failed: $error_message');

                window.location='cart.php';

              </script>";

        exit();
    }
}


/* =========================================
   INCLUDE HEADER
========================================= */

include("header.php");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Checkout</title>


<!-- BOOTSTRAP -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>

/* ================================
   BODY
================================ */

body {
    margin: 0;
    padding: 0;
    background: #f5f5f5;
}


/* ================================
   CHECKOUT MAIN
================================ */

.checkout-container {
    width: 100%;
    max-width: 1150px;

    /* Header થી proper gap */
    margin: 180px auto 50px auto;

    padding: 0 20px 50px;
}


/* ================================
   ROW
================================ */

.checkout-container .row {
    align-items: flex-start;
}


/* ================================
   LEFT CHECKOUT BOX
================================ */

.checkout-box {
    background: #ffffff;

    padding: 30px;

    border-radius: 12px;

    box-shadow: 0 5px 25px rgba(0,0,0,0.10);

    width: 100%;
}


/* ================================
   CHECKOUT TITLE
================================ */

.checkout-title {
    font-size: 28px;

    font-weight: 700;

    color: #333;

    margin-bottom: 25px;

    padding-bottom: 15px;

    border-bottom: 2px solid #E3A33B;
}


/* ================================
   LABEL
================================ */

.form-label {
    font-weight: 600;

    color: #333;

    margin-bottom: 7px;
}


/* ================================
   INPUT BOX
================================ */

.checkout-box .form-control {

    width: 100%;

    height: 48px;

    padding: 10px 14px;

    border: 1px solid #d5d5d5;

    border-radius: 7px;

    font-size: 15px;

    color: #333;

    background: #fff;

    box-sizing: border-box;
}


/* ================================
   INPUT FOCUS
================================ */

.checkout-box .form-control:focus {

    border-color: #E3A33B;

    box-shadow: 0 0 0 3px rgba(227,163,59,0.15);

    outline: none;
}


/* ================================
   ADDRESS
================================ */

.checkout-box textarea.form-control {

    height: 110px;

    min-height: 110px;

    resize: vertical;
}


/* ================================
   FORM SPACE
================================ */

.checkout-box .mb-3 {

    margin-bottom: 18px !important;
}


/* ================================
   PAYMENT BOX
================================ */

.payment-section {

    margin-top: 5px;

    margin-bottom: 25px;

    padding: 16px 18px;

    border: 1px solid #ddd;

    border-radius: 8px;

    background: #fafafa;
}


.payment-title {

    font-size: 16px;

    font-weight: 600;

    color: #333;

    margin-bottom: 12px;
}


.form-check {

    margin-bottom: 8px;
}


.form-check-input {

    cursor: pointer;
}


.form-check-input:checked {

    background-color: #E3A33B;

    border-color: #E3A33B;
}


.form-check-label {

    cursor: pointer;

    color: #444;
}


/* ================================
   PLACE ORDER BUTTON
================================ */

.checkout-btn {

    width: 100%;

    height: 50px;

    background: #E3A33B;

    border: none;

    border-radius: 7px;

    color: white;

    font-size: 17px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.3s;
}


.checkout-btn:hover {

    background: #c98920;

    transform: translateY(-1px);
}


/* ================================
   ORDER SUMMARY
================================ */

.summary-box {

    background: #ffffff;

    padding: 25px;

    border-radius: 12px;

    box-shadow: 0 5px 25px rgba(0,0,0,0.10);

    width: 100%;

    box-sizing: border-box;
}


.summary-box h4 {

    font-size: 22px;

    font-weight: 700;

    color: #333;

    margin-bottom: 20px;
}


/* ================================
   SUMMARY ROW
================================ */

.summary-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    margin-bottom: 15px;

    font-size: 15px;

    color: #444;
}


.summary-row span:last-child {

    white-space: nowrap;

    font-weight: 500;
}


/* ================================
   TOTAL
================================ */

.total-row {

    border-top: 1px solid #ddd;

    padding-top: 15px;

    margin-top: 10px;

    font-size: 20px;

    font-weight: 700;

    color: #222;
}


/* ================================
   TABLET
================================ */

@media (max-width: 991px) {

    .checkout-container {

        margin-top: 170px;

        padding: 0 15px 40px;
    }

    .checkout-box {

        padding: 25px;
    }

    .summary-box {

        padding: 25px;
    }
}


/* ================================
   MOBILE
================================ */

@media (max-width: 767px) {

    .checkout-container {

        margin-top: 150px;

        padding: 0 12px 40px;
    }

    .checkout-box {

        padding: 20px;
    }

    .summary-box {

        padding: 20px;
    }

    .checkout-title {

        font-size: 24px;
    }

    .checkout-box .form-control {

        height: 45px;
    }

    .checkout-box textarea.form-control {

        height: 100px;
    }
}


/* ================================
   SMALL MOBILE
================================ */

@media (max-width: 575px) {

    .checkout-container {

        margin-top: 145px;
    }

    .checkout-box {

        padding: 17px;
    }

    .summary-box {

        padding: 17px;
    }

    .checkout-title {

        font-size: 22px;
    }

}


</style>

</head>


<body>


<div class="checkout-container">

<div class="row g-4">


<!-- =================================
     CUSTOMER DETAILS
================================= -->

<div class="col-lg-7">

<div class="checkout-box">

<div class="checkout-title">
    Checkout
</div>


<form method="POST">


<!-- NAME -->

<div class="mb-3">

<label class="form-label">
    Name
</label>

<input
    type="text"
    name="name"
    class="form-control"
    placeholder="Enter your name"
    value="<?php echo htmlspecialchars($_POST['name'] ?? $user_data['fullname'] ?? ''); ?>"
    required>

</div>


<!-- PHONE -->

<div class="mb-3">

<label class="form-label">
    Phone
</label>

<input
    type="text"
    name="phone"
    class="form-control"
    placeholder="Enter phone number"
    value="<?php echo htmlspecialchars($_POST['phone'] ?? $user_data['phone'] ?? ''); ?>"
    required>

</div>


<!-- PIN -->

<div class="mb-3">

<label class="form-label">
    PIN Code
</label>

<input
    type="text"
    name="pin"
    class="form-control"
    placeholder="Enter PIN code"
    value="<?php echo htmlspecialchars($_POST['pin'] ?? ''); ?>"
    required>

</div>


<!-- CITY + STATE -->

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">
    City
</label>

<input
    type="text"
    name="city"
    class="form-control"
    placeholder="Enter city"
    value="<?php echo htmlspecialchars($_POST['city'] ?? $user_data['city'] ?? ''); ?>"
    required>

</div>


<div class="col-md-6 mb-3">

<label class="form-label">
    State
</label>

<input
    type="text"
    name="state"
    class="form-control"
    placeholder="Enter state"
    value="<?php echo htmlspecialchars($_POST['state'] ?? 'Gujarat'); ?>"
    required>

</div>

</div>


<!-- ADDRESS -->

<div class="mb-3">

<label class="form-label">
    Address
</label>

<textarea
    name="address"
    class="form-control"
    rows="4"
    placeholder="Enter your complete address"
    required><?php echo htmlspecialchars($_POST['address'] ?? $user_data['address'] ?? ''); ?></textarea>

</div>


<!-- PAYMENT -->

<div class="payment-section">

<div class="payment-title">
    Payment Method
</div>


<div class="form-check">

<input
    class="form-check-input"
    type="radio"
    name="payment"
    value="Cash on Delivery"
    id="cod"
    checked>

<label
    class="form-check-label"
    for="cod">

    Cash on Delivery

</label>

</div>
</div>


<!-- BUTTON -->

<button
    type="submit"
    name="checkout"
    class="checkout-btn">

    Place Order

</button>


</form>

</div>

</div>



<!-- =================================
     ORDER SUMMARY
================================= -->

<div class="col-lg-5">

<div class="summary-box">

<h4 class="mb-4">
    Order Summary
</h4>


<?php

$summary_query = mysqli_query(
    $conn,
    "SELECT *
     FROM addtocart
     WHERE u_id='$userid'"
);

$summary_subtotal = 0;


if (
    $summary_query &&
    mysqli_num_rows($summary_query) > 0
) {

    while (
        $item =
        mysqli_fetch_assoc($summary_query)
    ) {

        $item_price =
            (float)$item['p_price'];

        $item_qty =
            (int)$item['p_qty'];

        $item_total =
            $item_price * $item_qty;

        $summary_subtotal +=
            $item_total;

?>

<div class="summary-row">

<span>

<?php

echo htmlspecialchars(
    $item['p_name']
);

?>

× <?php echo $item_qty; ?>

</span>


<span>

₹<?php

echo number_format(
    $item_total,
    2
);

?>

</span>

</div>

<?php

    }

}

?>


<hr>


<!-- SUBTOTAL -->

<div class="summary-row">

<span>
    Subtotal
</span>

<span>

₹<?php

echo number_format(
    $summary_subtotal,
    2
);

?>

</span>

</div>


<!-- SHIPPING -->

<div class="summary-row">

<span>
    Shipping
</span>

<span>
<?php if ($summary_subtotal >= 499): ?>
    <span style="color: green; font-weight: 600;">FREE</span>
<?php else: ?>
    ₹200.00
<?php endif; ?>
</span>

</div>


<!-- TOTAL -->

<div class="summary-row total-row">

<span>
    Total
</span>

<span>

₹<?php

$ui_shipping = ($summary_subtotal >= 499) ? 0 : 200;

echo number_format(
    $summary_subtotal + $ui_shipping,
    2
);

?>

</span>

</div>


</div>

</div>


</div>

</div>


</body>

</html>
<?php

include 'footer.php'; 

?>