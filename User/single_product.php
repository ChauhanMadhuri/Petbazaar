<?php
include("conn.php");
include("header.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location='subcategory.php';</script>";
    exit();
}

$id = (int)$_GET['id'];

$product = mysqli_query($conn,"SELECT * FROM product WHERE id='$id' LIMIT 1");
if (!$product || mysqli_num_rows($product) == 0) {
    echo "<script>alert('Product not found.'); window.location='subcategory.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($product);
$stock = (int)$row['qty'];
$is_out_of_stock = ($stock <= 0);
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars($row['product_name']); ?> - PetBazaar</title>

<style>
body{
    background:#f8f8f8;
    font-family:Arial, Helvetica, sans-serif;
}
.back-btn{
    display:inline-block;
    margin:20px;
    padding:10px 20px;
    background:#E39A2E;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-size:16px;
    transition: 0.2s;
}

.back-btn:hover{
    background:#c67d13;
    color: #fff;
}

.single-product{
    width:90%;
    max-width: 1200px;
    margin:30px auto 50px;
    display:flex;
    gap:50px;
    background:#fff;
    padding:35px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.left{
    width:45%;
    text-align:center;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fafafa;
    border-radius: 12px;
    border: 1px solid #eee;
    padding: 20px;
}

.left img{
    width:100%;
    max-height:420px;
    object-fit:contain;
}

.right{
    width:55%;
}

.right h1{
    font-size:32px;
    margin-bottom:10px;
    color:#222;
}

.rating{
    font-size:18px;
    color:#f4b400;
    margin:10px 0;
}

.rating span{
    color:#777;
    font-size:14px;
}

.price{
    font-size:32px;
    color:#ff6600;
    font-weight:bold;
    margin:15px 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.old-price{
    color:#999;
    text-decoration:line-through;
    font-size:20px;
}

.off{
    color:green;
    font-size:18px;
    background: #e8f5e9;
    padding: 2px 8px;
    border-radius: 4px;
}

.des{
    color:#555;
    line-height:28px;
    margin:15px 0 20px;
}

/* Stock Status */
.stock-badge-box {
    margin-bottom: 20px;
    font-size: 17px;
    font-weight: 700;
}

.stock-in {
    color: #067D62;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.stock-low {
    color: #b45309;
    background: #fef3c7;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.stock-out {
    color: #b91c1c;
    background: #fee2e2;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.delivery{
    background:#f9f9f9;
    border-radius:10px;
    padding:15px;
    line-height:30px;
    margin-bottom:20px;
    font-size:15px;
    border: 1px solid #eee;
}

.qty-box{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:25px;
}

.qty-box button{
    width:38px;
    height:38px;
    border:none;
    background:#E39A2E;
    color:#fff;
    font-size:20px;
    cursor:pointer;
    border-radius:6px;
    transition: 0.2s;
}

.qty-box button:hover:not(:disabled){
    background:#c67d13;
}

.qty-box button:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.qty-box input{
    width:60px;
    height:38px;
    text-align:center;
    border:1px solid #ccc;
    font-size:16px;
    border-radius:6px;
    background: #fff;
    font-weight: bold;
}

.btns{
    display:flex;
    gap:15px;
    flex-wrap: wrap;
}

.btn-cart-action{
    padding:13px 28px;
    text-decoration:none;
    color:#fff;
    background:#E39A2E;
    border-radius:8px;
    font-size:16px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}

.btn-cart-action:hover:not(:disabled){
    background:#c67d13;
    color: #fff;
}

.btn-cart-action:disabled {
    background: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

.btn-buy-now{
    background:#ff5722;
}

.btn-buy-now:hover:not(:disabled){
    background:#e64a19;
    color: #fff;
}

/* Login Required Popup Modal */
.login-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 20px;
    backdrop-filter: blur(2px);
}

.login-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 390px;
    border-radius: 16px;
    padding: 32px 28px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    animation: popModal 0.28s ease-out;
}

@keyframes popModal {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.login-modal-icon {
    width: 68px;
    height: 68px;
    background: #fff7ed;
    color: #ea580c;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    margin-bottom: 18px;
}

.login-modal-title {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.login-modal-text {
    font-size: 14.5px;
    color: #4b5563;
    margin-bottom: 24px;
    line-height: 1.5;
}

.btn-login-ok {
    display: block;
    width: 100%;
    padding: 13px 20px;
    background: #E39A2E;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(227, 154, 46, 0.35);
    transition: all 0.2s;
}

.btn-login-ok:hover {
    background: #c67d13;
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(227, 154, 46, 0.45);
}

.btn-login-ok:active {
    transform: translateY(0);
}

.spec{
    margin-top:35px;
    border-top:1px solid #ddd;
    padding-top:20px;
}

.spec h3{
    margin-bottom:15px;
    font-size: 20px;
}

.spec table{
    width:100%;
    border-collapse:collapse;
}

.spec table td{
    border:1px solid #ddd;
    padding:12px;
    font-size: 14px;
}

@media(max-width:992px){
    .single-product{
        flex-direction:column;
    }
    .left, .right{
        width:100%;
    }
    .left img{
        max-height:300px;
    }
}
</style>
</head>

<body>
<a href="subcategory.php?category=<?php echo urlencode($row['category']); ?>&sub=<?php echo urlencode($row['sub_category']); ?>" class="back-btn">
    ← Back to Products
</a>

<div class="single-product">

    <div class="left">
        <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" 
             alt="<?php echo htmlspecialchars($row['product_name']); ?>"
             onerror="this.src='images/item1.jpg'">
    </div>

    <div class="right">

        <h1><?php echo htmlspecialchars($row['product_name']); ?></h1>

        <div class="rating">
            ★★★★★
            <span>(4.8 | 128 Reviews)</span>
        </div>

        <div class="price">
            ₹ <?php echo number_format((float)$row['price'], 2); ?>
            <span class="old-price">
                ₹ <?php echo number_format((float)$row['price'] + 150, 2); ?>
            </span>
            <span class="off">20% OFF</span>
        </div>

        <p class="des">
            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
        </p>

        <!-- Stock Status Badge -->
        <div class="stock-badge-box">
            <?php if ($is_out_of_stock): ?>
                <span class="stock-out">
                    <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                </span>
            <?php elseif ($stock <= 5): ?>
                <span class="stock-low">
                    <i class="fa-solid fa-clock"></i> Only <?php echo $stock; ?> left in stock - Order soon!
                </span>
            <?php else: ?>
                <span class="stock-in">
                    <i class="fa-solid fa-check"></i> In Stock (<?php echo $stock; ?> Available)
                </span>
            <?php endif; ?>
        </div>

        <div class="delivery">
            🚚 Free Delivery on orders above ₹499 <br>
            🔒 Secure Payment Guaranteed <br>
            🔄 7 Days Easy Return Policy
        </div>

        <!-- Quantity Selector -->
        <div class="qty-box">
            <label><b>Quantity:</b></label>
            <button type="button" onClick="minus()" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>-</button>
            <input type="text" id="qty" name="quantity" value="<?php echo $is_out_of_stock ? '0' : '1'; ?>" readonly>
            <button type="button" onClick="plus(<?php echo $stock; ?>)" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>+</button>
        </div>

        <!-- Action Buttons -->
        <div class="btns">
            <!-- Add to Cart -->
            <form id="addToCartForm" method="POST" action="add_to_cart.php?id=<?php echo $row['id']; ?>" style="margin:0;">
                <input type="hidden" name="quantity" id="cart_qty" value="<?php echo $is_out_of_stock ? '0' : '1'; ?>">
                <button type="submit" class="btn-cart-action" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-cart-shopping"></i> Add To Cart
                </button>
            </form>

            <!-- Buy Now -->
            <form id="buyNowForm" method="POST" action="add_to_cart.php?id=<?php echo $row['id']; ?>&buy_now=1" style="margin:0;">
                <input type="hidden" name="quantity" id="buy_now_qty" value="<?php echo $is_out_of_stock ? '0' : '1'; ?>">
                <button type="submit" class="btn-cart-action btn-buy-now" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-bolt"></i> Buy Now
                </button>
            </form>
        </div>

        <!-- Login Required Modal Popup -->
        <div id="loginRequiredModal" class="login-modal-overlay" style="display: none;">
            <div class="login-modal-box">
                <div class="login-modal-icon">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h3 class="login-modal-title">Please Login First</h3>
                <p class="login-modal-text">You must be logged in to add products to your cart. Please log in to proceed.</p>
                <button type="button" class="btn-login-ok" id="loginModalOkBtn">OK</button>
            </div>
        </div>

        <div class="spec">
            <h3>Product Details</h3>
            <table>
                <tr>
                    <td style="width: 35%; font-weight: 600; color: #555;">Category</td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #555;">Sub Category</td>
                    <td><?php echo htmlspecialchars($row['sub_category']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #555;">Availability</td>
                    <td><?php echo $is_out_of_stock ? '<span class="text-danger fw-bold">Out of Stock</span>' : '<span class="text-success fw-bold">In Stock</span>'; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600; color: #555;">Stock Quantity</td>
                    <td><?php echo $stock; ?> units available</td>
                </tr>
            </table>
        </div>

    </div>

</div>

<!-- Related Products -->
<div style="width:90%; max-width: 1200px; margin:40px auto 60px;">
    <h2 style="margin-bottom:25px; font-weight: 700;">Related Products</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:25px;">
        <?php
        $cat = mysqli_real_escape_string($conn, $row['category']);
        $sub = mysqli_real_escape_string($conn, $row['sub_category']);

        $related = mysqli_query($conn,"
            SELECT * FROM product
            WHERE category='$cat'
            AND sub_category='$sub'
            AND id!='$id'
            LIMIT 4
        ");

        if ($related && mysqli_num_rows($related) > 0) {
            while($r = mysqli_fetch_assoc($related)) {
                $r_stock = (int)$r['qty'];
        ?>
        <div style="background:#fff;padding:18px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.06);text-align:center;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <a href="single_product.php?id=<?php echo $r['id']; ?>">
                    <img src="upload/<?php echo htmlspecialchars($r['image']); ?>"
                         style="width:100%;height:160px;object-fit:contain;margin-bottom:10px;"
                         onerror="this.src='images/item1.jpg'">
                </a>
                <h4 style="font-size:16px;margin:8px 0;font-weight:600;"><?php echo htmlspecialchars($r['product_name']); ?></h4>
                <div style="color:#ff6600;font-size:18px;font-weight:bold;margin-bottom:8px;">
                    ₹ <?php echo number_format((float)$r['price'], 2); ?>
                </div>
                <div style="font-size:12px;margin-bottom:12px;">
                    <?php if ($r_stock <= 0): ?>
                        <span style="color:#dc2626;font-weight:bold;">Out of Stock</span>
                    <?php else: ?>
                        <span style="color:#059669;font-weight:bold;">In Stock (<?php echo $r_stock; ?> left)</span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="single_product.php?id=<?php echo $r['id']; ?>"
               style="display:block;padding:9px 15px;background:#E39A2E;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;font-weight:600;">
                View Product
            </a>
        </div>
        <?php
            }
        } else {
            echo "<p style='color:#777;'>No other products in this category.</p>";
        }
        ?>
    </div>
</div>

<script>
var isLoggedIn = <?php echo (isset($_SESSION['loggedinuser']) && $_SESSION['loggedinuser'] === true) ? 'true' : 'false'; ?>;
var redirectTargetUrl = "login.php";

function showLoginModal(redirectUrl) {
    redirectTargetUrl = redirectUrl || "login.php";
    var modal = document.getElementById("loginRequiredModal");
    if (modal) {
        modal.style.display = "flex";
    }
}

var okBtn = document.getElementById("loginModalOkBtn");
if (okBtn) {
    okBtn.addEventListener("click", function() {
        window.location.href = redirectTargetUrl;
    });
}

// Close modal if clicking on overlay background
var modalOverlay = document.getElementById("loginRequiredModal");
if (modalOverlay) {
    modalOverlay.addEventListener("click", function(e) {
        if (e.target === modalOverlay) {
            modalOverlay.style.display = "none";
        }
    });
}

// Intercept Add to Cart form submission if not logged in
var addToCartForm = document.getElementById("addToCartForm");
if (addToCartForm) {
    addToCartForm.addEventListener("submit", function(e) {
        if (!isLoggedIn) {
            e.preventDefault();
            var currentQty = document.getElementById("qty").value || 1;
            var target = "login.php?redirect=" + encodeURIComponent("add_to_cart.php?id=<?php echo $row['id']; ?>&qty=" + currentQty);
            showLoginModal(target);
        }
    });
}

// Intercept Buy Now form submission if not logged in
var buyNowForm = document.getElementById("buyNowForm");
if (buyNowForm) {
    buyNowForm.addEventListener("submit", function(e) {
        if (!isLoggedIn) {
            e.preventDefault();
            var currentQty = document.getElementById("qty").value || 1;
            var target = "login.php?redirect=" + encodeURIComponent("add_to_cart.php?id=<?php echo $row['id']; ?>&buy_now=1&qty=" + currentQty);
            showLoginModal(target);
        }
    });
}

function plus(maxStock){
    let qty = document.getElementById("qty");
    let cartQty = document.getElementById("cart_qty");
    let buyNowQty = document.getElementById("buy_now_qty");

    let currentVal = parseInt(qty.value) || 1;
    if (currentVal >= maxStock) {
        alert("Maximum available stock is " + maxStock + " units.");
        return;
    }

    let newVal = currentVal + 1;
    qty.value = newVal;
    cartQty.value = newVal;
    buyNowQty.value = newVal;
}

function minus(){
    let qty = document.getElementById("qty");
    let cartQty = document.getElementById("cart_qty");
    let buyNowQty = document.getElementById("buy_now_qty");

    let currentVal = parseInt(qty.value) || 1;
    if(currentVal > 1){
        let newVal = currentVal - 1;
        qty.value = newVal;
        cartQty.value = newVal;
        buyNowQty.value = newVal;
    }
}
</script>

<?php include("footer.php"); ?>
</body>
</html>
