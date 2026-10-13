<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conn.php';

/* ==============================
   LOGIN CHECK
============================== */
if (!isset($_SESSION['loggedinuser']) || $_SESSION['loggedinuser'] !== true || !isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    $_SESSION['redirect_after_login'] = "cart.php";
    header("Location: login.php");
    exit();
}

$userid = (int)$_SESSION['userid'];

/* ==============================
   HANDLE QUANTITY UPDATE
============================== */
$cart_alert = "";
if (isset($_SESSION['cart_alert'])) {
    $cart_alert = $_SESSION['cart_alert'];
    unset($_SESSION['cart_alert']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_qty') {
        $c_id = (int)$_POST['c_id'];
        $requested_qty = (int)$_POST['quantity'];

        // Get cart item & matching product
        $item_q = mysqli_query($conn, "SELECT c.*, p.qty AS stock_qty, p.id AS product_id, p.product_name 
                                       FROM addtocart c 
                                       LEFT JOIN product p ON (c.p_id = p.id OR c.p_name = p.product_name)
                                       WHERE c.c_id = '$c_id' AND c.u_id = '$userid' LIMIT 1");
        if ($item_q && mysqli_num_rows($item_q) > 0) {
            $item_data = mysqli_fetch_assoc($item_q);
            $stock = (int)($item_data['stock_qty'] ?? 0);

            if ($requested_qty <= 0) {
                mysqli_query($conn, "DELETE FROM addtocart WHERE c_id = '$c_id' AND u_id = '$userid'");
                $_SESSION['cart_alert'] = "Item removed from cart.";
            } elseif ($requested_qty > $stock) {
                mysqli_query($conn, "UPDATE addtocart SET p_qty = '$stock' WHERE c_id = '$c_id' AND u_id = '$userid'");
                $_SESSION['cart_alert'] = "Quantity capped to maximum available stock ($stock items).";
            } else {
                mysqli_query($conn, "UPDATE addtocart SET p_qty = '$requested_qty' WHERE c_id = '$c_id' AND u_id = '$userid'");
            }
        }
        header("Location: cart.php");
        exit();
    }
}

/* ==============================
   FETCH CART PRODUCTS WITH LIVE STOCK
============================== */
$sql = "SELECT c.*, p.id AS p_real_id, p.qty AS live_stock, p.product_name AS current_name, p.image AS current_img, p.price AS current_price
        FROM addtocart c
        LEFT JOIN product p ON (c.p_id = p.id OR c.p_name = p.product_name)
        WHERE c.u_id = '$userid'
        ORDER BY c.c_id DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Cart Error: " . mysqli_error($conn));
}

$grand_total = 0;
$total_items = 0;
$has_out_of_stock = false;
$cart_items_list = [];

while ($row = mysqli_fetch_assoc($result)) {
    $price = (float)$row['p_price'];
    $qty = (int)$row['p_qty'];
    $stock = isset($row['live_stock']) ? (int)$row['live_stock'] : 0;
    
    // Check stock issues
    $is_out_of_stock = ($stock <= 0);
    $exceeds_stock = ($qty > $stock && $stock > 0);
    
    if ($is_out_of_stock || $exceeds_stock) {
        $has_out_of_stock = true;
    }

    $item_total = $price * $qty;
    $grand_total += $item_total;
    $total_items += $qty;

    $row['calculated_item_total'] = $item_total;
    $row['is_out_of_stock'] = $is_out_of_stock;
    $row['exceeds_stock'] = $exceeds_stock;
    $row['actual_stock'] = $stock;
    
    $cart_items_list[] = $row;
}

$shipping = ($grand_total >= 499 || $grand_total == 0) ? 0 : 200;
$final_total = $grand_total + $shipping;

include 'header.php';
?>

<style>
.pb-cart-wrapper {
    background-color: #f3f4f6;
    padding: 30px 15px 70px;
    min-height: 85vh;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #111827;
}

.pb-cart-container {
    max-width: 1280px;
    margin: 0 auto;
}

.cart-header-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 20px;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-layout-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 25px;
    align-items: flex-start;
}

.cart-items-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    padding: 24px;
}

.cart-items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 10px;
}

.cart-items-header h2 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    color: #111827;
}

.cart-items-header span {
    font-size: 14px;
    color: #6b7280;
}

.cart-item-row {
    display: flex;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #f3f4f6;
}

.cart-item-row:last-child {
    border-bottom: none;
    padding-bottom: 5px;
}

.cart-item-img-box {
    width: 130px;
    height: 130px;
    background: #fafafa;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    padding: 8px;
}

.cart-item-img-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.cart-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.item-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 6px;
    text-decoration: none;
    display: inline-block;
}

.item-title:hover {
    color: #E39A2E;
}

.item-price-tag {
    font-size: 17px;
    font-weight: 700;
    color: #B12704;
    margin-bottom: 8px;
}

.stock-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 12px;
}

.stock-in {
    color: #067D62;
}

.stock-low {
    color: #b45309;
}

.stock-out {
    color: #dc2626;
    background: #fee2e2;
    padding: 4px 10px;
    border-radius: 6px;
}

.item-actions-bar {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.qty-control-group {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #f9fafb;
    overflow: hidden;
}

.qty-btn {
    background: #f3f4f6;
    border: none;
    width: 32px;
    height: 32px;
    font-size: 16px;
    font-weight: bold;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease;
}

.qty-btn:hover:not(:disabled) {
    background: #e5e7eb;
    color: #111827;
}

.qty-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.qty-display {
    width: 42px;
    height: 32px;
    text-align: center;
    border: none;
    border-left: 1px solid #d1d5db;
    border-right: 1px solid #d1d5db;
    background: #fff;
    font-weight: 600;
    font-size: 14px;
    color: #111827;
}

.btn-delete-item {
    color: #007185;
    background: none;
    border: none;
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
    padding: 0;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-delete-item:hover {
    color: #c45500;
    text-decoration: underline;
}

.item-subtotal-display {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    text-align: right;
}

.summary-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    padding: 24px;
    position: sticky;
    top: 90px;
}

.summary-card h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 20px;
    color: #111827;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.free-delivery-badge {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    color: #065f46;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 15px;
    margin-bottom: 12px;
    color: #4b5563;
}

.summary-total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 20px;
    font-weight: 800;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
    color: #B12704;
}

.btn-proceed-checkout {
    display: block;
    width: 100%;
    background: #FFD814;
    border: 1px solid #FCD200;
    color: #0F1111;
    padding: 14px;
    border-radius: 20px;
    font-size: 15px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(213,217,217,.5);
    margin-top: 20px;
    transition: all 0.2s ease;
}

.btn-proceed-checkout:hover:not(.disabled) {
    background: #F7CA00;
    border-color: #F2C200;
    color: #0F1111;
}

.btn-proceed-checkout.disabled {
    background: #e5e7eb;
    border-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
    box-shadow: none;
}

.empty-cart-view {
    background: #ffffff;
    border-radius: 12px;
    padding: 60px 20px;
    text-align: center;
    border: 1px solid #e5e7eb;
    max-width: 700px;
    margin: 40px auto;
}

.empty-cart-view i {
    font-size: 60px;
    color: #E39A2E;
    margin-bottom: 20px;
}

.continue-shopping-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #E39A2E;
    color: #fff;
    padding: 12px 26px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    margin-top: 20px;
    transition: background 0.2s;
}

.continue-shopping-btn:hover {
    background: #c8831e;
    color: #fff;
}

@media(max-width: 900px) {
    .cart-layout-grid {
        grid-template-columns: 1fr;
    }
    .summary-card {
        position: static;
    }
}
@media(max-width: 600px) {
    .cart-item-row {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .item-actions-bar {
        justify-content: center;
    }
    .item-subtotal-display {
        text-align: center;
    }
}
</style>

<div class="pb-cart-wrapper">
    <div class="pb-cart-container">

        <h1 class="cart-header-title">
            <i class="fa-solid fa-cart-shopping text-warning"></i> Shopping Cart
        </h1>

        <?php if (!empty($cart_alert)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="fa-solid fa-circle-info me-2"></i> <?php echo htmlspecialchars($cart_alert); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if ($has_out_of_stock): ?>
        <div class="alert alert-danger" role="alert" style="border-radius: 10px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Some products in your cart are currently out of stock or exceed available quantity. Please adjust quantities or remove them before proceeding to checkout.
        </div>
        <?php endif; ?>

        <?php if (count($cart_items_list) > 0): ?>

        <div class="cart-layout-grid">

            <!-- LEFT: Cart Items -->
            <div class="cart-items-card">

                <div class="cart-items-header">
                    <h2>Items in Cart</h2>
                    <span>Price</span>
                </div>

                <?php foreach ($cart_items_list as $item): 
                    $c_id = (int)$item['c_id'];
                    $p_name = $item['p_name'];
                    $p_img = !empty($item['p_img']) ? 'upload/' . $item['p_img'] : 'images/item1.jpg';
                    $p_price = (float)$item['p_price'];
                    $p_qty = (int)$item['p_qty'];
                    $stock = (int)$item['actual_stock'];
                    $subtot = $item['calculated_item_total'];
                    $is_out = $item['is_out_of_stock'];
                    $exceeds = $item['exceeds_stock'];
                    $real_p_id = (int)($item['p_real_id'] ?? $item['p_id']);
                ?>

                <div class="cart-item-row">

                    <!-- Image -->
                    <div class="cart-item-img-box">
                        <img src="<?php echo htmlspecialchars($p_img); ?>" 
                             alt="<?php echo htmlspecialchars($p_name); ?>"
                             onerror="this.src='images/item1.jpg'">
                    </div>

                    <!-- Details -->
                    <div class="cart-item-info">

                        <div>
                            <?php if ($real_p_id > 0): ?>
                            <a href="single_product.php?id=<?php echo $real_p_id; ?>" class="item-title">
                                <?php echo htmlspecialchars($p_name); ?>
                            </a>
                            <?php else: ?>
                            <h4 class="item-title"><?php echo htmlspecialchars($p_name); ?></h4>
                            <?php endif; ?>

                            <!-- Stock Status -->
                            <div>
                                <?php if ($is_out): ?>
                                    <span class="stock-pill stock-out">
                                        <i class="fa-solid fa-circle-xmark"></i> Currently Out of Stock
                                    </span>
                                <?php elseif ($exceeds): ?>
                                    <span class="stock-pill stock-low">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Only <?php echo $stock; ?> available in stock!
                                    </span>
                                <?php elseif ($stock <= 5): ?>
                                    <span class="stock-pill stock-low">
                                        <i class="fa-solid fa-clock"></i> Only <?php echo $stock; ?> left in stock - Order soon
                                    </span>
                                <?php else: ?>
                                    <span class="stock-pill stock-in">
                                        <i class="fa-solid fa-check"></i> In Stock (<?php echo $stock; ?> Available)
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="item-price-tag">
                                ₹<?php echo number_format($p_price, 2); ?>
                            </div>
                        </div>

                        <!-- Actions & Quantity -->
                        <div class="item-actions-bar">

                            <!-- Qty Control -->
                            <form method="POST" action="cart.php" style="margin: 0; display: inline-flex; align-items: center;" id="form_qty_<?php echo $c_id; ?>">
                                <input type="hidden" name="action" value="update_qty">
                                <input type="hidden" name="c_id" value="<?php echo $c_id; ?>">

                                <div class="qty-control-group">
                                    <button type="button" 
                                            class="qty-btn" 
                                            onclick="changeQty(<?php echo $c_id; ?>, -1, <?php echo $stock; ?>)">-</button>

                                    <input type="number" 
                                           name="quantity" 
                                           id="qty_input_<?php echo $c_id; ?>" 
                                           value="<?php echo $p_qty; ?>" 
                                           min="1" 
                                           max="<?php echo max(1, $stock); ?>" 
                                           class="qty-display"
                                           onchange="document.getElementById('form_qty_<?php echo $c_id; ?>').submit();">

                                    <button type="button" 
                                            class="qty-btn" 
                                            <?php echo ($p_qty >= $stock || $stock <= 0) ? 'disabled' : ''; ?>
                                            onclick="changeQty(<?php echo $c_id; ?>, 1, <?php echo $stock; ?>)">+</button>
                                </div>
                            </form>

                            <!-- Delete -->
                            <a href="delete_cart.php?id=<?php echo $c_id; ?>" 
                               class="btn-delete-item" 
                               onclick="return confirm('Remove <?php echo addslashes($p_name); ?> from your cart?');">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>

                        </div>

                    </div>

                    <!-- Item Subtotal -->
                    <div class="item-subtotal-display">
                        ₹<?php echo number_format($subtot, 2); ?>
                    </div>

                </div>

                <?php endforeach; ?>

                <div class="text-end pt-3">
                    <span style="font-size: 18px; font-weight: 600;">
                        Subtotal (<?php echo $total_items; ?> items): 
                        <strong style="color: #B12704; font-size: 20px;">₹<?php echo number_format($grand_total, 2); ?></strong>
                    </span>
                </div>

            </div>


            <!-- RIGHT: Order Summary -->
            <div class="summary-card">

                <h3>Order Summary</h3>

                <?php if ($grand_total >= 499): ?>
                <div class="free-delivery-badge">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>Your order qualifies for <strong>FREE Delivery</strong>!</span>
                </div>
                <?php else: ?>
                <div class="free-delivery-badge" style="background:#fffbeb; border-color:#fde68a; color:#92400e;">
                    <i class="fa-solid fa-truck"></i>
                    <span>Add <strong>₹<?php echo number_format(499 - $grand_total, 2); ?></strong> more for FREE Delivery!</span>
                </div>
                <?php endif; ?>

                <div class="summary-line">
                    <span>Items Total (<?php echo $total_items; ?>)</span>
                    <span>₹<?php echo number_format($grand_total, 2); ?></span>
                </div>

                <div class="summary-line">
                    <span>Delivery Charges</span>
                    <span>
                        <?php if ($shipping == 0): ?>
                            <strong style="color: #067D62;">FREE</strong>
                        <?php else: ?>
                            ₹<?php echo number_format($shipping, 2); ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="summary-total-line">
                    <span>Order Total:</span>
                    <span>₹<?php echo number_format($final_total, 2); ?></span>
                </div>

                <?php if ($has_out_of_stock): ?>
                    <button type="button" class="btn-proceed-checkout disabled" disabled title="Please remove out of stock items to proceed">
                        <i class="fa-solid fa-lock me-1"></i> Proceed to Buy (Fix Items)
                    </button>
                    <small class="text-danger d-block text-center mt-2">
                        Adjust or remove unavailable items to continue.
                    </small>
                <?php else: ?>
                    <a href="checkout.php" class="btn-proceed-checkout">
                        <i class="fa-solid fa-lock me-1"></i> Proceed to Buy (<?php echo $total_items; ?> items)
                    </a>
                <?php endif; ?>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <i class="fa-solid fa-shield-halved text-success me-1"></i> Safe and Secure Payments. 100% Authentic Products.
                </div>

            </div>

        </div>

        <?php else: ?>

        <!-- Empty Cart -->
        <div class="empty-cart-view">
            <i class="fa-solid fa-basket-shopping"></i>
            <h2 style="font-weight: 700; color: #1f2937; margin-bottom: 8px;">Your PetBazaar Cart is Empty</h2>
            <p style="color: #6b7280; font-size: 15px; margin-bottom: 25px;">Explore our categories and find healthy food, toys, beds, and grooming items for your pets!</p>
            <a href="subcategory.php" class="continue-shopping-btn">
                <i class="fa-solid fa-arrow-left"></i> Start Shopping
            </a>
        </div>

        <?php endif; ?>

    </div>
</div>

<script>
function changeQty(cId, delta, maxStock) {
    const input = document.getElementById('qty_input_' + cId);
    let currentVal = parseInt(input.value) || 1;
    let newVal = currentVal + delta;

    if (newVal < 1) {
        if (confirm('Do you want to remove this product from your cart?')) {
            input.value = 0;
            document.getElementById('form_qty_' + cId).submit();
        }
        return;
    }

    if (maxStock > 0 && newVal > maxStock) {
        alert('Cannot add more. Only ' + maxStock + ' units in stock.');
        newVal = maxStock;
    }

    input.value = newVal;
    document.getElementById('form_qty_' + cId).submit();
}
</script>

<?php include 'footer.php'; ?>
