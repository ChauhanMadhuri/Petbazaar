<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
 
include("conn.php"); 
 
/* India Time */ 
date_default_timezone_set('Asia/Kolkata'); 
 
/* ========================================= 
   LOGIN CHECK 
   ========================================= */ 
if (!isset($_SESSION['loggedinuser']) || $_SESSION['loggedinuser'] !== true || !isset($_SESSION['userid']) || empty($_SESSION['userid'])) { 
    $_SESSION['redirect_after_login'] = "my_orders.php"; 
    header("Location: login.php"); 
    exit(); 
} 
 
$userid = (int)$_SESSION['userid']; 
 
/* ========================================= 
   FETCH ALL USER ORDERS
   ========================================= */ 
$orders_query = mysqli_query( 
    $conn, 
    "SELECT * FROM bill 
     WHERE u_id='$userid'
     ORDER BY id DESC" 
); 

$cancel_success = $_SESSION['cancel_success'] ?? '';
$cancel_error = $_SESSION['cancel_error'] ?? '';
unset($_SESSION['cancel_success'], $_SESSION['cancel_error']);

include("header.php"); 
?> 
 
<style> 
.orders-page-wrapper { 
    background-color: #f6f8fb; 
    padding: 30px 15px 70px; 
    min-height: 85vh; 
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
} 

.orders-container { 
    max-width: 1000px; 
    margin: 0 auto; 
} 

.orders-header { 
    margin-bottom: 25px; 
} 

.orders-header h1 { 
    font-size: 28px; 
    font-weight: 800; 
    color: #1f2937; 
    margin: 0 0 6px; 
} 

.orders-header p { 
    color: #6b7280; 
    font-size: 15px; 
    margin: 0; 
} 

.order-card { 
    background: #ffffff; 
    border-radius: 14px; 
    border: 1px solid #e5e7eb; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.03); 
    margin-bottom: 22px; 
    overflow: hidden; 
    transition: all 0.2s ease; 
} 

.order-card:hover { 
    box-shadow: 0 6px 20px rgba(0,0,0,0.07); 
    border-color: #d1d5db; 
} 

.order-card-header { 
    background: #fafafa; 
    border-bottom: 1px solid #f3f4f6; 
    padding: 16px 22px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    flex-wrap: wrap; 
    gap: 15px; 
} 

.order-meta-left { 
    display: flex; 
    gap: 25px; 
    flex-wrap: wrap; 
} 

.meta-field { 
    font-size: 13px; 
    color: #6b7280; 
} 

.meta-field strong { 
    color: #1f2937; 
    display: block; 
    font-size: 14px; 
    margin-top: 2px; 
} 

.order-card-body { 
    padding: 22px; 
} 

.order-items-preview { 
    display: flex; 
    gap: 15px; 
    align-items: center; 
    flex-wrap: wrap; 
    margin-bottom: 18px; 
} 

.item-thumb-box { 
    width: 65px; 
    height: 65px; 
    border-radius: 10px; 
    border: 1px solid #e5e7eb; 
    overflow: hidden; 
    background: #f9fafb; 
    position: relative; 
    flex-shrink: 0; 
} 

.item-thumb-box img { 
    width: 100%; 
    height: 100%; 
    object-fit: contain; 
    padding: 4px; 
} 

.item-count-badge { 
    position: absolute; 
    bottom: 2px; 
    right: 2px; 
    background: #1f2937; 
    color: #fff; 
    font-size: 10px; 
    font-weight: 700; 
    padding: 1px 5px; 
    border-radius: 6px; 
} 

.order-card-footer { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    flex-wrap: wrap; 
    gap: 15px; 
    padding-top: 15px; 
    border-top: 1px solid #f3f4f6; 
} 

.status-badge { 
    display: inline-flex; 
    align-items: center; 
    gap: 6px; 
    padding: 5px 14px; 
    border-radius: 20px; 
    font-size: 12px; 
    font-weight: 700; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
} 

.badge-placed,
.badge-pending { 
    background: #fef3c7; 
    color: #b45309; 
} 

.badge-processing { 
    background: #e0e7ff; 
    color: #4338ca; 
} 

.badge-shipped { 
    background: #ccfbf1; 
    color: #0f766e; 
} 

.badge-delivered { 
    background: #d1fae5; 
    color: #065f46; 
} 

.badge-cancelled { 
    background: #fee2e2; 
    color: #b91c1c; 
} 

.btn-view-invoice { 
    background: #E39A2E; 
    color: #ffffff; 
    padding: 9px 18px; 
    border-radius: 8px; 
    font-size: 13px; 
    font-weight: 600; 
    text-decoration: none; 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    transition: all 0.2s; 
    border: none; 
} 

.btn-view-invoice:hover { 
    background: #c8831e; 
    color: #ffffff; 
    box-shadow: 0 4px 12px rgba(227,154,46,0.3); 
} 

.btn-cancel-order { 
    background: #dc3545; 
    color: white; 
    border: none; 
    padding: 9px 18px; 
    border-radius: 8px; 
    font-size: 13px; 
    font-weight: 600; 
    cursor: pointer; 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    transition: 0.2s; 
} 

.btn-cancel-order:hover { 
    background: #b02a37; 
    color: white; 
} 

.expired-text { 
    color: #8b95a5; 
    font-size: 13px; 
    font-weight: 600; 
    display: inline-flex; 
    align-items: center; 
    gap: 6px; 
} 

.cancelled-text { 
    color: #dc3545; 
    font-size: 13px; 
    font-weight: 600; 
    display: inline-flex; 
    align-items: center; 
    gap: 6px; 
} 

.empty-orders { 
    background: #ffffff; 
    border-radius: 16px; 
    border: 1px solid #e5e7eb; 
    padding: 60px 20px; 
    text-align: center; 
    max-width: 600px; 
    margin: 40px auto; 
} 

@media (max-width: 768px) {
    .orders-page-wrapper {
        padding: 20px 10px 50px;
    }
    .order-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .order-meta-left {
        gap: 15px;
        width: 100%;
        justify-content: space-between;
    }
    .order-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .order-card-footer > div:last-child {
        width: 100%;
        justify-content: flex-start;
    }
}
</style> 

<div class="orders-page-wrapper"> 
    <div class="orders-container"> 

        <!-- HEADER --> 
        <div class="orders-header"> 
            <h1> 
                <i class="fa-solid fa-box-archive text-warning me-2"></i> My Orders 
            </h1> 
            <p> 
                Track your pet purchases, manage active orders, and view or print your bills anytime. 
            </p> 
        </div> 

        <?php if (!empty($cancel_success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($cancel_success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($cancel_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($cancel_error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php 
        if ($orders_query && mysqli_num_rows($orders_query) > 0): 
            while ($b = mysqli_fetch_assoc($orders_query)): 
                $b_id = (int)$b['id']; 
                $b_status = !empty($b['order_status']) ? $b['order_status'] : 'Pending'; 
                $b_total = (float)$b['total_amount']; 
                $b_date = !empty($b['order_date']) ? date("d M Y, h:i A", strtotime($b['order_date'])) : 'Recent'; 
                $cancelled_by = $b['cancelled_by'] ?? '';
                $cancelled_at = !empty($b['cancelled_at']) ? date("d M Y, h:i A", strtotime($b['cancelled_at'])) : '';

                $order_timestamp = !empty($b['order_date']) ? strtotime($b['order_date']) : 0; 
                $current_timestamp = time(); 
                $cancel_deadline = $order_timestamp + (48 * 60 * 60); 

                $can_cancel = false; 
                if ( 
                    $order_timestamp > 0 && 
                    $current_timestamp <= $cancel_deadline && 
                    $b_status !== 'Cancelled' && 
                    $b_status !== 'Delivered' && 
                    $b_status !== 'Shipped' 
                ) { 
                    $can_cancel = true; 
                } 

                // Fetch items
                $items_res = mysqli_query($conn, " 
                    SELECT o.*, p.image 
                    FROM orders o 
                    LEFT JOIN product p ON o.product_id = p.id 
                    WHERE o.bill_id = $b_id 
                "); 
                $items = []; 
                if ($items_res) { 
                    while ($it = mysqli_fetch_assoc($items_res)) { 
                        $items[] = $it; 
                    } 
                } 

                $badge_cls = 'badge-pending'; 
                if ($b_status === 'Processing') $badge_cls = 'badge-processing'; 
                elseif ($b_status === 'Shipped') $badge_cls = 'badge-shipped'; 
                elseif ($b_status === 'Delivered') $badge_cls = 'badge-delivered'; 
                elseif ($b_status === 'Cancelled') $badge_cls = 'badge-cancelled'; 
        ?> 

        <!-- ORDER CARD --> 
        <div class="order-card"> 

            <div class="order-card-header"> 
                <div class="order-meta-left"> 
                    <div class="meta-field"> 
                        ORDER PLACED 
                        <strong><?php echo htmlspecialchars($b_date); ?></strong> 
                    </div> 

                    <div class="meta-field"> 
                        TOTAL AMOUNT 
                        <strong style="color: #B12704;">₹<?php echo number_format($b_total, 2); ?></strong> 
                    </div> 

                    <div class="meta-field"> 
                        SHIP TO 
                        <strong><?php echo htmlspecialchars($b['name']); ?></strong> 
                    </div> 
                </div> 

                <div class="text-end"> 
                    <div class="meta-field"> 
                        INVOICE NO. 
                        <strong class="text-warning">#PB-INV-<?php echo str_pad($b_id, 5, '0', STR_PAD_LEFT); ?></strong> 
                    </div> 
                </div> 
            </div> 

            <div class="order-card-body"> 

                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2"> 
                    <div> 
                        <span class="status-badge <?php echo $badge_cls; ?>"> 
                            <i class="fa-solid fa-circle-dot"></i> 
                            <?php echo htmlspecialchars($b_status); ?> 
                        </span> 

                        <span class="text-muted small ms-2"> 
                            Payment: <strong><?php echo htmlspecialchars($b['payment']); ?></strong>
                            (<?php echo htmlspecialchars($b['payment_status'] ?? 'Pending'); ?>)
                        </span> 

                        <?php if ($b_status === 'Cancelled'): ?>
                            <span class="badge bg-danger ms-2" style="font-size: 11px;">
                                Cancelled by <?php echo htmlspecialchars($cancelled_by ?: 'Customer'); ?>
                                <?php if (!empty($cancelled_at)) echo ' on ' . $cancelled_at; ?>
                            </span>
                        <?php endif; ?>
                    </div> 

                    <div class="small text-muted"> 
                        <?php echo count($items); ?> Item(s) in this order 
                    </div> 
                </div> 

                <!-- Items Preview --> 
                <div class="order-items-preview"> 
                    <?php 
                    foreach ($items as $item): 
                        $img = !empty($item['image']) ? 'upload/' . $item['image'] : 'images/item1.jpg'; 
                    ?> 
                    <div class="item-thumb-box" title="<?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo (int)$item['quantity']; ?>)"> 
                        <img src="<?php echo htmlspecialchars($img); ?>" 
                             alt="item" 
                             onerror="this.src='images/item1.jpg'"> 
                        <span class="item-count-badge">&times;<?php echo (int)$item['quantity']; ?></span> 
                    </div> 
                    <?php endforeach; ?> 
                </div> 

                <!-- Card Footer --> 
                <div class="order-card-footer"> 
                    <div class="small text-muted"> 
                        Need help with this order? Email <a href="mailto:support@petbazaar.com" class="text-warning text-decoration-none">support@petbazaar.com</a> 
                    </div> 

                    <div class="d-flex align-items-center gap-2 flex-wrap"> 

                        <!-- Cancellation / Status Message --> 
                        <?php if ($can_cancel): ?> 
                            <form method="POST" action="cancel_order.php" onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');" style="margin: 0;"> 
                                <input type="hidden" name="order_id" value="<?php echo $b_id; ?>"> 
                                <button type="submit" class="btn-cancel-order"> 
                                    <i class="fa-solid fa-xmark"></i> Cancel Order 
                                </button> 
                            </form> 
                        <?php elseif ($b_status === 'Cancelled'): ?> 
                            <span class="cancelled-text"> 
                                <i class="fa-solid fa-circle-xmark"></i> Order Cancelled 
                            </span> 
                        <?php elseif ($b_status === 'Shipped'): ?> 
                            <span class="text-info fw-semibold small"> 
                                <i class="fa-solid fa-truck-fast"></i> Order in transit 
                            </span> 
                        <?php elseif ($b_status === 'Delivered'): ?> 
                            <span class="text-success fw-semibold small"> 
                                <i class="fa-solid fa-circle-check"></i> Package Delivered 
                            </span> 
                        <?php elseif ($current_timestamp > $cancel_deadline): ?> 
                            <span class="expired-text" title="Orders can only be cancelled within 48 hours of placing"> 
                                <i class="fa-solid fa-clock"></i> Cancellation window expired (48h) 
                            </span> 
                        <?php endif; ?> 

                        <!-- Invoice Link --> 
                        <a href="bill.php?o_id=<?php echo $b_id; ?>" class="btn-view-invoice"> 
                            <i class="fa-solid fa-file-invoice"></i> View Invoice 
                        </a> 

                    </div> 
                </div> 

            </div> 
        </div> 

        <?php 
            endwhile; 
        else: 
        ?> 

        <div class="empty-orders"> 
            <i class="fa-solid fa-box-open" style="font-size: 55px; color: #E39A2E; margin-bottom: 20px;"></i> 
            <h3 style="font-weight: 700; color: #1f2937; margin-bottom: 10px;">No Orders Placed Yet</h3> 
            <p style="color: #6b7280; margin-bottom: 25px;">You haven't ordered any items yet. Start shopping and pamper your lovely pets!</p> 
            <a href="subcategory.php" class="btn-view-invoice" style="padding: 12px 25px; font-size: 15px;"> 
                <i class="fa-solid fa-cart-shopping"></i> Browse Products 
            </a> 
        </div> 

        <?php endif; ?> 

    </div> 
</div> 

<?php include("footer.php"); ?>
