<?php

session_start();
include("conn.php");


/* ==============================
   PRODUCT ID CHECK
============================== */

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = (int)$_GET['id'];
$buy_now = isset($_GET['buy_now']) && $_GET['buy_now'] == '1';


/* ==============================
   LOGIN CHECK
============================== */

if (!isset($_SESSION['loggedinuser']) || $_SESSION['loggedinuser'] !== true || !isset($_SESSION['userid']) || empty($_SESSION['userid'])) {

    $qty_param = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (isset($_GET['qty']) ? (int)$_GET['qty'] : 1);
    $_SESSION['redirect_after_login'] = "add_to_cart.php?id=" . $product_id . ($buy_now ? "&buy_now=1" : "") . "&qty=" . $qty_param;

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Please Login First - PetBazaar</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f3f4f6;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .modal-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                padding: 20px;
            }
            .modal-box {
                background: #ffffff;
                width: 100%;
                max-width: 380px;
                border-radius: 16px;
                padding: 32px 26px;
                text-align: center;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                animation: popIn 0.3s ease-out;
            }
            @keyframes popIn {
                0% { transform: scale(0.85); opacity: 0; }
                100% { transform: scale(1); opacity: 1; }
            }
            .modal-icon {
                width: 70px;
                height: 70px;
                background: #fff7ed;
                color: #ea580c;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                margin-bottom: 20px;
            }
            .modal-title {
                font-size: 22px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 10px;
            }
            .modal-text {
                font-size: 14px;
                color: #4b5563;
                margin-bottom: 25px;
                line-height: 1.5;
            }
            .btn-ok {
                display: block;
                width: 100%;
                padding: 13px 20px;
                background: #e3a33b;
                color: #ffffff;
                font-size: 16px;
                font-weight: 600;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                text-decoration: none;
                box-sizing: border-box;
                box-shadow: 0 4px 12px rgba(227, 163, 59, 0.35);
                transition: all 0.2s;
            }
            .btn-ok:hover {
                background: #cc8a22;
                color: #ffffff;
                transform: translateY(-1px);
            }
            .btn-ok:active {
                transform: translateY(0);
            }
        </style>
    </head>
    <body>
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-icon">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h3 class="modal-title">Please Login First</h3>
                <p class="modal-text">You must be logged in to add products to your cart. Please log in to proceed.</p>
                <a href="login.php" class="btn-ok" id="btnOk">OK</a>
            </div>
        </div>
        <script>
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    window.location.href = 'login.php';
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit();
}


/* ==============================
   USER ID & EMAIL
============================== */

$userid = (int)$_SESSION['userid'];

$email = $_SESSION['email'] ?? '';
if (empty($email)) {
    $user_sql = mysqli_query($conn, "SELECT email FROM ragistration WHERE Id='$userid' LIMIT 1");
    if ($user_sql && mysqli_num_rows($user_sql) > 0) {
        $u_row = mysqli_fetch_assoc($user_sql);
        $email = $u_row['email'];
    }
}
$email = mysqli_real_escape_string($conn, $email);


/* ==============================
   FETCH PRODUCT & STOCK CHECK
============================== */

$product_sql = "SELECT * FROM product WHERE id='$product_id' LIMIT 1";
$product = mysqli_query($conn, $product_sql);

if (!$product || mysqli_num_rows($product) == 0) {
    echo "<script>alert('Product not found.'); window.location='index.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($product);
$available_stock = (int)$row['qty'];

if ($available_stock <= 0) {
    echo "<script>alert('Sorry, this product is currently Out of Stock.'); window.location='single_product.php?id=$product_id';</script>";
    exit();
}


/* ==============================
   PRODUCT DETAILS & QUANTITY
============================== */

$p_img   = mysqli_real_escape_string($conn, $row['image']);
$p_name  = mysqli_real_escape_string($conn, $row['product_name']);
$p_price = (float)$row['price'];

$p_qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (isset($_GET['qty']) ? (int)$_GET['qty'] : 1);

if ($p_qty < 1) {
    $p_qty = 1;
}

if ($p_qty > $available_stock) {
    $p_qty = $available_stock;
}


/* ==============================
   CHECK EXISTING CART ITEM
============================== */

$existing_cart_query = mysqli_query($conn, "
    SELECT * FROM addtocart 
    WHERE u_id='$userid' 
    AND (p_id='$product_id' OR p_name='$p_name') 
    LIMIT 1
");

if ($existing_cart_query && mysqli_num_rows($existing_cart_query) > 0) {
    $existing = mysqli_fetch_assoc($existing_cart_query);
    $c_id = (int)$existing['c_id'];
    
    if ($buy_now) {
        $final_qty = $p_qty;
    } else {
        $final_qty = (int)$existing['p_qty'] + $p_qty;
    }

    if ($final_qty > $available_stock) {
        $final_qty = $available_stock;
    }

    $update_cart = mysqli_query($conn, "
        UPDATE addtocart 
        SET p_qty='$final_qty', p_price='$p_price', p_img='$p_img', p_id='$product_id' 
        WHERE c_id='$c_id'
    ");
} else {
    $insert_cart = mysqli_query($conn, "
        INSERT INTO addtocart
        (u_id, p_id, p_img, p_name, p_qty, p_price, email)
        VALUES
        ('$userid', '$product_id', '$p_img', '$p_name', '$p_qty', '$p_price', '$email')
    ");
}

/* ==============================
   REDIRECT
============================== */

if ($buy_now) {
    header("Location: checkout.php");
    exit();
} else {
    header("Location: cart.php");
    exit();
}
?>

