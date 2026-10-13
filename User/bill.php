<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conn.php");

/* =========================================
   1. VALIDATE ORDER / BILL ID
========================================= */
if (!isset($_GET['o_id']) || empty($_GET['o_id'])) {
    echo "<script>
            alert('Bill ID not found!');
            window.location.href='index.php';
          </script>";
    exit();
}

$bill_id = (int)$_GET['o_id'];

/* =========================================
   2. FETCH BILL DETAILS
========================================= */
$bill_query = mysqli_query($conn, "SELECT * FROM bill WHERE id='$bill_id' LIMIT 1");

if (!$bill_query || mysqli_num_rows($bill_query) == 0) {
    echo "<script>
            alert('Order / Bill not found!');
            window.location.href='index.php';
          </script>";
    exit();
}

$bill = mysqli_fetch_assoc($bill_query);

/* =========================================
   3. SECURITY & ACCESS CONTROL
========================================= */
$is_admin = (isset($_SESSION['loggedinadmin']) && $_SESSION['loggedinadmin'] === true) || 
            (isset($_SESSION['utype']) && strtolower($_SESSION['utype']) === 'admin');

$logged_userid = isset($_SESSION['userid']) ? (int)$_SESSION['userid'] : 0;

// Allow if admin or if the bill belongs to the logged-in user
if (!$is_admin) {
    if ($logged_userid === 0) {
        $_SESSION['redirect_after_login'] = "bill.php?o_id=" . $bill_id;
        echo "<script>
                alert('Please login to view your order invoice.');
                window.location.href='login.php';
              </script>";
        exit();
    } elseif ($logged_userid !== (int)$bill['u_id']) {
        echo "<script>
                alert('Access Denied: You are not authorized to view this invoice.');
                window.location.href='index.php';
              </script>";
        exit();
    }
}

/* =========================================
   4. EXTRACT BILL DATA
========================================= */
$name           = $bill['name'] ?? '';
$email          = $bill['email'] ?? '';
$phone          = $bill['phone'] ?? '';
$address        = $bill['address'] ?? '';
$city           = $bill['city'] ?? '';
$state          = $bill['state'] ?? '';
$pincode        = $bill['pincode'] ?? '';
$subtotal       = (float)($bill['subtotal'] ?? 0);
$shipping       = (float)($bill['shipping'] ?? 0);
$total          = (float)($bill['total_amount'] ?? 0);
$order_date_raw = $bill['order_date'] ?? '';
$payment        = $bill['payment'] ?? 'Cash on Delivery';
$order_status   = $bill['order_status'] ?? 'Pending';
$payment_status = $bill['payment_status'] ?? 'Pending';
$cancelled_by   = $bill['cancelled_by'] ?? '';
$cancelled_at   = !empty($bill['cancelled_at']) ? date("d M Y, h:i A", strtotime($bill['cancelled_at'])) : '';

// Normalize payment method label
if ($payment === '0' || empty($payment)) {
    $payment = 'Cash on Delivery';
} elseif ($payment === '1') {
    $payment = 'Online Payment';
}

// Format date safely
if (!empty($order_date_raw) && strpos($order_date_raw, '0000-00-00') === false) {
    $formatted_date = date("d M Y, h:i A", strtotime($order_date_raw));
} else {
    $formatted_date = date("d M Y");
}

/* =========================================
   5. FETCH ORDER ITEMS WITH PRODUCT DETAILS
========================================= */
$items_query = mysqli_query($conn, "
    SELECT 
        o.*, 
        p.image AS product_image, 
        p.category, 
        p.sub_category
    FROM orders o
    LEFT JOIN product p ON o.product_id = p.id
    WHERE o.bill_id = '$bill_id'
    ORDER BY o.id ASC
");

$order_items = [];
$calc_subtotal = 0;

if ($items_query) {
    while ($item = mysqli_fetch_assoc($items_query)) {
        $item_subtotal = (float)$item['subtotal'];
        if ($item_subtotal <= 0) {
            $item_subtotal = (float)$item['product_price'] * (int)$item['quantity'];
        }
        $calc_subtotal += $item_subtotal;
        $order_items[] = $item;
    }
}

// Fallback if subtotal was 0 in database
if ($subtotal <= 0) {
    $subtotal = $calc_subtotal;
    if ($total <= 0) {
        $total = $subtotal + $shipping;
    }
}

include("header.php");
?>

<!-- Custom Modern Invoice Styling -->
<style>
/* Base Page Layout */
.invoice-page-wrapper {
    background-color: #f6f8fb;
    padding: 130px 15px 70px;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #2b3445;
}

.invoice-container {
    max-width: 960px;
    margin: 0 auto;
}

/* Breadcrumb */
.invoice-breadcrumb {
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6b7280;
}

.invoice-breadcrumb a {
    color: #E39A2E;
    text-decoration: none;
    font-weight: 500;
}

.invoice-breadcrumb a:hover {
    text-decoration: underline;
}

/* Action Bar */
.invoice-top-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.invoice-top-actions .title-group h1 {
    font-size: 26px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.invoice-top-actions .title-group p {
    font-size: 14px;
    color: #6b7280;
    margin: 4px 0 0;
}

.btn-invoice-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-invoice-print {
    background: #E39A2E;
    color: #ffffff;
}

.btn-invoice-print:hover {
    background: #c8831e;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(227, 154, 46, 0.35);
}

.btn-invoice-outline {
    background: #ffffff;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-invoice-outline:hover {
    background: #f3f4f6;
    color: #111827;
}

/* Order Stepper (Status Tracker) */
.order-stepper-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px 30px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #e5e7eb;
}

.stepper-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 0;
    padding: 0;
    list-style: none;
}

.stepper-steps::before {
    content: '';
    position: absolute;
    top: 18px;
    left: 40px;
    right: 40px;
    height: 3px;
    background: #e5e7eb;
    z-index: 1;
}

.stepper-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
}

.stepper-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #e5e7eb;
    color: #9ca3af;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.stepper-label {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    display: block;
}

/* Stepper Active & Completed States */
.stepper-step.active .stepper-icon {
    border-color: #E39A2E;
    background: #E39A2E;
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(227, 154, 46, 0.2);
}

.stepper-step.active .stepper-label {
    color: #E39A2E;
}

.stepper-step.completed .stepper-icon {
    border-color: #10b981;
    background: #10b981;
    color: #ffffff;
}

.stepper-step.completed .stepper-label {
    color: #065f46;
}

/* Main Invoice Paper Card */
.invoice-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 45px 50px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    position: relative;
    overflow: hidden;
}

/* Card Decorative Top Bar */
.invoice-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #E39A2E 0%, #f3ba5f 100%);
}

/* Header inside Invoice */
.inv-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 25px;
    border-bottom: 2px solid #f3f4f6;
    margin-bottom: 30px;
}

.inv-brand-logo {
    font-size: 28px;
    font-weight: 800;
    color: #E39A2E;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1;
}

.inv-brand-tagline {
    font-size: 13px;
    color: #6b7280;
    margin-top: 6px;
    font-weight: 500;
}

.inv-company-details {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 10px;
    line-height: 1.5;
}

.inv-meta-block {
    text-align: right;
}

.inv-meta-block h2 {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #1f2937;
    margin: 0 0 6px;
}

.inv-number {
    font-size: 15px;
    font-weight: 700;
    color: #4b5563;
}

.inv-number span {
    color: #E39A2E;
}

.inv-date {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* Badges */
.inv-badge-wrap {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-top: 10px;
}

.inv-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-placed {
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

.badge-paid {
    background: #d1fae5;
    color: #065f46;
}

.badge-pending {
    background: #fef3c7;
    color: #92400e;
}

/* Info Grid (Billed To / Shipped To) */
.inv-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    background: #f9fafb;
    border-radius: 12px;
    padding: 22px 25px;
    margin-bottom: 35px;
    border: 1px solid #edf2f7;
}

.inv-info-col h4 {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 700;
    color: #9ca3af;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.inv-info-col h4 i {
    color: #E39A2E;
    font-size: 14px;
}

.inv-info-col .name {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 6px;
}

.inv-info-col .detail-item {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.6;
    display: flex;
    align-items: center;
    gap: 8px;
}

.inv-info-col .detail-item i {
    color: #9ca3af;
    width: 16px;
    font-size: 13px;
}

/* Items Table */
.inv-table-wrap {
    margin-bottom: 35px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.inv-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.inv-table thead {
    background: #fafafa;
    border-bottom: 1px solid #e5e7eb;
}

.inv-table th {
    padding: 14px 18px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #6b7280;
    text-align: left;
}

.inv-table th.text-end,
.inv-table td.text-end {
    text-align: right;
}

.inv-table th.text-center,
.inv-table td.text-center {
    text-align: center;
}

.inv-table td {
    padding: 16px 18px;
    font-size: 14px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.inv-table tbody tr:last-child td {
    border-bottom: none;
}

.inv-table tbody tr:hover {
    background: #fdfaf6;
}

/* Product Row Display */
.product-cell {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-thumb {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    object-fit: cover;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.product-meta .p-title {
    font-weight: 600;
    color: #1f2937;
    display: block;
    margin-bottom: 3px;
}

.product-meta .p-cat {
    font-size: 12px;
    color: #9ca3af;
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
}

/* Bottom Grid (Payment + Totals) */
.inv-bottom-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 35px;
    align-items: start;
    padding-top: 10px;
}

.payment-note-card {
    background: #fffbf2;
    border: 1px dashed #E39A2E;
    border-radius: 12px;
    padding: 20px 22px;
}

.payment-note-card h5 {
    font-size: 14px;
    font-weight: 700;
    color: #92400e;
    margin: 0 0 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.payment-meta-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-bottom: 6px;
    color: #78350f;
}

.payment-meta-row strong {
    color: #1f2937;
}

.inv-thank-msg {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 16px;
    line-height: 1.5;
}

/* Summary Calculation Box */
.inv-summary-box {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 24px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: #6b7280;
}

.summary-item .amount {
    font-weight: 600;
    color: #374151;
}

.summary-item.grand-total-row {
    border-top: 2px solid #E39A2E;
    margin-top: 10px;
    padding-top: 14px;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}

.summary-item.grand-total-row .amount {
    color: #E39A2E;
    font-size: 22px;
}

/* Footer note */
.inv-card-footer {
    text-align: center;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #f3f4f6;
    font-size: 12px;
    color: #9ca3af;
}

/* PRINT MEDIA STYLESHEET */
@media print {
    /* Hide non-printable web chrome */
    body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    header,
    nav,
    footer,
    #footer,
    .navbar,
    .main-menu,
    .invoice-breadcrumb,
    .invoice-top-actions,
    .order-stepper-card,
    .no-print {
        display: none !important;
    }

    .invoice-page-wrapper {
        padding: 0 !important;
        background: #ffffff !important;
        min-height: auto !important;
    }

    .invoice-container {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .invoice-card {
        box-shadow: none !important;
        border: none !important;
        padding: 10px 0 !important;
        border-radius: 0 !important;
    }

    .invoice-card::before {
        display: none !important;
    }

    .inv-table th {
        background: #f3f4f6 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .payment-note-card {
        background: #ffffff !important;
        border: 1px solid #ccc !important;
    }

    .inv-table tr {
        page-break-inside: avoid;
    }

    @page {
        size: A4;
        margin: 15mm;
    }
}

/* Responsive Styles */
@media (max-width: 768px) {
    .invoice-page-wrapper {
        padding-top: 110px;
    }

    .invoice-card {
        padding: 25px 20px;
    }

    .inv-header {
        flex-direction: column;
        gap: 20px;
    }

    .inv-meta-block {
        text-align: left;
    }

    .inv-badge-wrap {
        justify-content: flex-start;
    }

    .inv-info-grid {
        grid-template-columns: 1fr;
        gap: 18px;
        padding: 18px;
    }

    .inv-bottom-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .order-stepper-card {
        display: none; /* Hide stepper on very narrow mobile to save vertical space */
    }
}
</style>

<div class="invoice-page-wrapper">
    <div class="invoice-container">

        <!-- Breadcrumb Navigation -->
        <div class="invoice-breadcrumb no-print">
            <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <a href="my_orders.php">My Orders</a>
            <span>/</span>
            <span>Invoice #PB-<?php echo $bill_id; ?></span>
        </div>

        <!-- Top Action Bar -->
        <div class="invoice-top-actions no-print">
            <div class="title-group">
                <h1>Order Invoice</h1>
                <p>Thank you for choosing PetBazaar! Your invoice is ready.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button onclick="window.print()" class="btn-invoice-action btn-invoice-print">
                    <i class="fa-solid fa-print"></i> Print Bill
                </button>
                <a href="my_orders.php" class="btn-invoice-action btn-invoice-outline">
                    <i class="fa-solid fa-bag-shopping"></i> My Orders
                </a>
                <a href="index.php" class="btn-invoice-action btn-invoice-outline">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>

        <?php if ($order_status !== 'Cancelled'): 
            $step_pending = in_array($order_status, ['Pending', 'Placed', 'Processing', 'Shipped', 'Delivered']);
            $step_proc    = in_array($order_status, ['Processing', 'Shipped', 'Delivered']);
            $step_ship    = in_array($order_status, ['Shipped', 'Delivered']);
            $step_deliv   = ($order_status === 'Delivered');
        ?>
        <!-- Order Progress Stepper -->
        <div class="order-stepper-card no-print">
            <ul class="stepper-steps">
                <li class="stepper-step <?php echo $step_proc ? 'completed' : ($step_pending ? 'active' : ''); ?>">
                    <div class="stepper-icon">
                        <i class="fa-solid <?php echo $step_proc ? 'fa-check' : 'fa-clipboard-check'; ?>"></i>
                    </div>
                    <span class="stepper-label">Pending</span>
                </li>

                <li class="stepper-step <?php echo $step_ship ? 'completed' : ($step_proc ? 'active' : ''); ?>">
                    <div class="stepper-icon">
                        <i class="fa-solid <?php echo $step_ship ? 'fa-check' : 'fa-box-open'; ?>"></i>
                    </div>
                    <span class="stepper-label">Processing</span>
                </li>

                <li class="stepper-step <?php echo $step_deliv ? 'completed' : ($step_ship ? 'active' : ''); ?>">
                    <div class="stepper-icon">
                        <i class="fa-solid <?php echo $step_deliv ? 'fa-check' : 'fa-truck-fast'; ?>"></i>
                    </div>
                    <span class="stepper-label">Shipped</span>
                </li>

                <li class="stepper-step <?php echo $step_deliv ? 'completed' : ''; ?>">
                    <div class="stepper-icon">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <span class="stepper-label">Delivered</span>
                </li>
            </ul>
        </div>
        <?php else: ?>
        <div class="alert alert-danger rounded-3 mb-4 d-flex align-items-center gap-3 no-print">
            <i class="fa-solid fa-circle-xmark fs-4"></i>
            <div>
                <strong>This Order is Cancelled</strong> <?php echo !empty($cancelled_by) ? 'by ' . htmlspecialchars($cancelled_by) : ''; ?><?php echo !empty($cancelled_at) ? ' on ' . htmlspecialchars($cancelled_at) : ''; ?>.
                <div class="small mt-1 text-muted">Any deducted payments or refunds will be handled according to policy. For assistance, contact support@petbazaar.com.</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Printable Invoice Paper Card -->
        <div class="invoice-card" id="printableInvoice">
            
            <!-- Invoice Header -->
            <div class="inv-header">
                <div>
                    <div class="inv-brand-logo">
                        <span>🐾</span> PetBazaar
                    </div>
                    <div class="inv-brand-tagline">
                        Everything Your Pet Deserves &bull; Quality & Care
                    </div>
                    <div class="inv-company-details">
                        PetBazaar Retail Pvt. Ltd.<br>
                        Support: support@petbazaar.com | +91 98765 43210<br>
                        GSTIN: 24AAACP1234F1Z5 &bull; Gujarat, India
                    </div>
                </div>

                <div class="inv-meta-block">
                    <h2>TAX INVOICE</h2>
                    <div class="inv-number">
                        Invoice No: <span>#PB-INV-<?php echo str_pad($bill_id, 5, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="inv-date">
                        <i class="fa-regular fa-calendar-check me-1"></i> <?php echo $formatted_date; ?>
                    </div>

                    <!-- Status Badges -->
                    <div class="inv-badge-wrap">
                        <?php
                        $badge_class = 'badge-placed';
                        $status_icon = 'fa-clock';
                        if ($order_status === 'Processing') {
                            $badge_class = 'badge-processing';
                            $status_icon = 'fa-arrows-rotate fa-spin';
                        } elseif ($order_status === 'Shipped') {
                            $badge_class = 'badge-shipped';
                            $status_icon = 'fa-truck-fast';
                        } elseif ($order_status === 'Delivered') {
                            $badge_class = 'badge-delivered';
                            $status_icon = 'fa-circle-check';
                        } elseif ($order_status === 'Cancelled') {
                            $badge_class = 'badge-cancelled';
                            $status_icon = 'fa-circle-xmark';
                        }
                        ?>
                        <span class="inv-badge <?php echo $badge_class; ?>">
                            <i class="fa-solid <?php echo $status_icon; ?>"></i> <?php echo htmlspecialchars($order_status); ?>
                        </span>

                        <span class="inv-badge <?php echo ($payment_status === 'Paid') ? 'badge-paid' : 'badge-pending'; ?>">
                            <i class="fa-solid fa-credit-card"></i> <?php echo htmlspecialchars($payment_status); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Details Grid -->
            <div class="inv-info-grid">
                <!-- Billed To -->
                <div class="inv-info-col">
                    <h4><i class="fa-solid fa-user"></i> Billed To</h4>
                    <div class="name"><?php echo htmlspecialchars($name); ?></div>
                    <div class="detail-item">
                        <i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($email); ?>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($phone); ?>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="inv-info-col">
                    <h4><i class="fa-solid fa-location-dot"></i> Shipping Details</h4>
                    <div class="detail-item" style="margin-bottom: 4px;">
                        <i class="fa-solid fa-map-pin"></i> 
                        <span><?php echo nl2br(htmlspecialchars($address)); ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fa-solid fa-city"></i>
                        <span>
                            <?php 
                            $loc = [];
                            if (!empty($city)) $loc[] = $city;
                            if (!empty($state)) $loc[] = $state;
                            if (!empty($pincode)) $loc[] = "PIN: " . $pincode;
                            echo htmlspecialchars(implode(', ', $loc));
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ordered Items Table -->
            <div class="inv-table-wrap">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Product Description</th>
                            <th class="text-end" style="width: 140px;">Unit Price</th>
                            <th class="text-center" style="width: 90px;">Qty</th>
                            <th class="text-end" style="width: 150px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($order_items)):
                            $count = 1;
                            foreach ($order_items as $item):
                                $item_img = !empty($item['product_image']) ? 'upload/' . $item['product_image'] : 'images/item1.jpg';
                                $item_price = (float)$item['product_price'];
                                $item_qty   = (int)$item['quantity'];
                                $item_sub   = (float)$item['subtotal'];
                                if ($item_sub <= 0) $item_sub = $item_price * $item_qty;
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?php echo $count++; ?></td>
                            <td>
                                <div class="product-cell">
                                    <img 
                                        src="<?php echo htmlspecialchars($item_img); ?>" 
                                        alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                        class="product-thumb"
                                        onerror="this.src='images/item1.jpg'"
                                    >
                                    <div class="product-meta">
                                        <span class="p-title"><?php echo htmlspecialchars(trim($item['product_name'])); ?></span>
                                        <?php if (!empty($item['category'])): ?>
                                            <span class="p-cat"><?php echo htmlspecialchars($item['category']); ?><?php echo !empty($item['sub_category']) ? ' &bull; ' . htmlspecialchars($item['sub_category']) : ''; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end fw-semibold">₹<?php echo number_format($item_price, 2); ?></td>
                            <td class="text-center fw-bold"><?php echo $item_qty; ?></td>
                            <td class="text-end fw-bold text-dark">₹<?php echo number_format($item_sub, 2); ?></td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No items recorded for this bill.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Section: Payment Info & Order Totals -->
            <div class="inv-bottom-grid">
                <!-- Payment Information & Customer Support Notes -->
                <div>
                    <div class="payment-note-card">
                        <h5>
                            <i class="fa-solid fa-shield-halved"></i> Payment Information
                        </h5>
                        <div class="payment-meta-row">
                            <span>Method:</span>
                            <strong><?php echo htmlspecialchars($payment); ?></strong>
                        </div>
                        <div class="payment-meta-row">
                            <span>Status:</span>
                            <strong><?php echo htmlspecialchars($payment_status); ?></strong>
                        </div>
                        <div class="payment-meta-row">
                            <span>Currency:</span>
                            <strong>INR (₹)</strong>
                        </div>
                    </div>

                    <div class="inv-thank-msg">
                        <strong>Thank you for loving animals!</strong><br>
                        We ensure our products meet strict quality standards for your pets' health and happiness. For returns, replacements, or support, write to <em>support@petbazaar.com</em> within 7 days of delivery.
                    </div>
                </div>

                <!-- Financial Summary Box -->
                <div class="inv-summary-box">
                    <div class="summary-item">
                        <span>Items Subtotal</span>
                        <span class="amount">₹<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <div class="summary-item">
                        <span>Delivery Fee</span>
                        <span>
                            <?php if ($shipping > 0): ?>
                                <strong class="amount">₹<?php echo number_format($shipping, 2); ?></strong>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">FREE</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="summary-item grand-total-row">
                        <span>Total Payable</span>
                        <span class="amount">₹<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="inv-card-footer">
                This is a computer-generated tax invoice. No signature is required. &bull; PetBazaar India &bull; Made with 🐾 for pet lovers.
            </div>

        </div> <!-- End Invoice Card -->

    </div>
</div>

<?php
include("footer.php");
?>