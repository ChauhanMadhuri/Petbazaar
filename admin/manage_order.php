<?php
include("auth_check.php");
include("conn.php");
include("slide_bar.php");

/* =====================================================
   UPDATE ORDER STATUS
===================================================== */
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    // Check previous status
    $prev_q = mysqli_query($conn, "SELECT order_status, cancelled_by FROM bill WHERE id = $order_id LIMIT 1");
    $prev_data = ($prev_q && mysqli_num_rows($prev_q) > 0) ? mysqli_fetch_assoc($prev_q) : null;
    $prev_status = $prev_data['order_status'] ?? '';

    $cancel_sql_part = "";
    if ($order_status === 'Cancelled' && $prev_status !== 'Cancelled') {
        $cancel_sql_part = ", cancelled_by = 'Admin', cancelled_at = NOW()";

        // Restore stock
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
    }

    $update_query = mysqli_query($conn, "
        UPDATE bill 
        SET order_status = '$order_status',
            payment_status = '$payment_status'
            $cancel_sql_part
        WHERE id = $order_id
    ");

    if ($update_query) {
        echo "<script>
                alert('Order #PB-INV-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " status updated successfully!');
                window.location='manage_order.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Failed to update status: " . addslashes(mysqli_error($conn)) . "');
                window.location='manage_order.php';
              </script>";
        exit();
    }
}

/* =====================================================
   DELETE ORDER (CASCADING)
===================================================== */
if (isset($_POST['delete_order'])) {
    $order_id = (int)$_POST['order_id'];

    mysqli_query($conn, "DELETE FROM orders WHERE bill_id = $order_id");
    $delete = mysqli_query($conn, "DELETE FROM bill WHERE id = $order_id");

    if ($delete) {
        echo "<script>
                alert('Order deleted successfully');
                window.location='manage_order.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Delete failed: " . addslashes(mysqli_error($conn)) . "');
                window.location='manage_order.php';
              </script>";
        exit();
    }
}

/* =====================================================
   FETCH ORDERS
===================================================== */
$query = "SELECT * FROM bill ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$total_orders_count = $result ? mysqli_num_rows($result) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - PetBazaar Admin</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    body {
        margin: 0;
        background: #f7f5f1;
        font-family: Arial, Helvetica, sans-serif;
    }

    .main-content {
        margin-left: 236px;
        padding: 40px 30px;
    }

    .page-heading {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 5px;
    }

    .page-heading .icon {
        font-size: 30px;
        color: #d99a32;
    }

    .page-heading h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #222;
    }

    .subtitle {
        margin-left: 45px;
        color: #777;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .top-box {
        position: absolute;
        right: 35px;
        top: 45px;
        background: white;
        padding: 12px 22px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        color: #555;
        font-size: 14px;
        font-weight: 600;
    }

    .top-box i {
        color: #d99a32;
        margin-right: 8px;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.06);
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
    }

    .order-table th {
        background: #faf7f2;
        padding: 14px 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #555;
        border-bottom: 2px solid #ecd8bd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
        color: #333;
        vertical-align: middle;
    }

    .order-table tr:hover {
        background: #fffdf9;
    }

    .order-id-badge {
        font-weight: 700;
        color: #d99a32;
        background: #fff8eb;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        border: 1px solid #fae6c6;
    }

    .customer-info strong {
        color: #111;
        display: block;
        font-size: 14px;
        margin-bottom: 3px;
    }

    .customer-info span {
        display: block;
        color: #666;
        font-size: 12px;
        line-height: 1.4;
    }

    .customer-info span i {
        color: #d99a32;
        width: 14px;
    }

    .amount-total {
        font-weight: 800;
        color: #d99a32;
        font-size: 15px;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-placed, .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-processing { background: #e0e7ff; color: #4338ca; }
    .badge-shipped { background: #ccfbf1; color: #0f766e; }
    .badge-delivered { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #b91c1c; }

    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-pay-pending { background: #fef3c7; color: #92400e; }
    .badge-failed { background: #fee2e2; color: #991b1b; }

    .action-group {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-view-inv {
        background: #fff8eb;
        color: #d99a32;
        border: 1px solid #fae6c6;
    }

    .btn-view-inv:hover {
        background: #d99a32;
        color: white;
    }

    .btn-status-edit {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
    }

    .btn-status-edit:hover {
        background: #4f46e5;
        color: white;
    }

    .btn-delete-order {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .btn-delete-order:hover {
        background: #dc2626;
        color: white;
    }

    /* MODAL */
    .status-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .status-modal.active {
        display: flex;
    }

    .status-modal-content {
        background: white;
        border-radius: 14px;
        width: 440px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        color: #222;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #999;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
    }

    .form-group select {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #ccc;
        border-radius: 7px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .btn-save-status {
        background: #d99a32;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        width: 100%;
    }

    .btn-save-status:hover {
        background: #c28726;
    }

    .no-order {
        text-align: center;
        padding: 50px 20px;
        color: #888;
    }

    @media(max-width: 900px) {
        .main-content {
            margin-left: 0;
            padding: 20px 15px;
        }
        .top-box {
            position: static;
            margin-bottom: 20px;
            display: inline-block;
        }
    }
    </style>
</head>

<body>

<div class="main-content">

    <div class="page-heading">
        <div class="icon">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h1>Manage Orders & Invoices</h1>
    </div>

    <div class="subtitle">
        Review customer orders, update tracking status, monitor customer cancellations, and view/print invoices
    </div>

    <div class="top-box">
        <i class="fa-solid fa-box-archive"></i>
        Total Orders: <strong><?php echo $total_orders_count; ?></strong>
    </div>

    <div class="table-card">
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <table class="order-table">
            <thead>
                <tr>
                    <th style="width: 110px;">Invoice #</th>
                    <th style="width: 180px;">Customer Details</th>
                    <th style="width: 200px;">Shipping Address</th>
                    <th style="width: 120px;">Amount</th>
                    <th style="width: 130px;">Payment</th>
                    <th style="width: 160px;">Order Status</th>
                    <th style="width: 130px;">Order Date</th>
                    <th style="width: 120px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    $order_id = (int)$row['id'];
                    $o_status = $row['order_status'] ?? 'Pending';
                    $p_status = $row['payment_status'] ?? 'Pending';
                    $p_method = $row['payment'] ?? 'Cash on Delivery';
                    $phone    = !empty($row['phone']) ? $row['phone'] : 'N/A';
                    $total    = (float)($row['total_amount'] ?? 0);
                    $date     = !empty($row['order_date']) ? date("d M Y", strtotime($row['order_date'])) : 'N/A';
                    $cancelled_by = $row['cancelled_by'] ?? '';
                    $cancelled_at = !empty($row['cancelled_at']) ? date("d M Y, h:i A", strtotime($row['cancelled_at'])) : '';

                    $badge_class = 'badge-pending';
                    if ($o_status === 'Processing') $badge_class = 'badge-processing';
                    elseif ($o_status === 'Shipped') $badge_class = 'badge-shipped';
                    elseif ($o_status === 'Delivered') $badge_class = 'badge-delivered';
                    elseif ($o_status === 'Cancelled') $badge_class = 'badge-cancelled';
                ?>
                <tr>
                    <!-- Invoice # -->
                    <td>
                        <span class="order-id-badge">
                            #PB-<?php echo str_pad($order_id, 4, '0', STR_PAD_LEFT); ?>
                        </span>
                    </td>

                    <!-- Customer -->
                    <td>
                        <div class="customer-info">
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            <span><i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                            <span><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($phone); ?></span>
                        </div>
                    </td>

                    <!-- Shipping Address -->
                    <td>
                        <div class="customer-info">
                            <span><?php echo htmlspecialchars($row['address']); ?></span>
                            <span>
                                <?php 
                                $city_info = [];
                                if (!empty($row['city'])) $city_info[] = $row['city'];
                                if (!empty($row['state'])) $city_info[] = $row['state'];
                                if (!empty($row['pincode'])) $city_info[] = $row['pincode'];
                                echo htmlspecialchars(implode(', ', $city_info));
                                ?>
                            </span>
                        </div>
                    </td>

                    <!-- Total Amount -->
                    <td>
                        <span class="amount-total">
                            ₹<?php echo number_format($total, 2); ?>
                        </span>
                    </td>

                    <!-- Payment -->
                    <td>
                        <div><strong><?php echo htmlspecialchars($p_method); ?></strong></div>
                        <span class="badge <?php echo ($p_status === 'Paid') ? 'badge-paid' : (($p_status === 'Failed') ? 'badge-failed' : 'badge-pay-pending'); ?> mt-1">
                            <?php echo htmlspecialchars($p_status); ?>
                        </span>
                    </td>

                    <!-- Order Status -->
                    <td>
                        <span class="badge <?php echo $badge_class; ?>">
                            <?php echo htmlspecialchars($o_status); ?>
                        </span>

                        <?php if ($o_status === 'Cancelled'): ?>
                            <div style="font-size: 11px; color: #b91c1c; font-weight: bold; margin-top: 4px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                By <?php echo htmlspecialchars($cancelled_by ?: 'User'); ?>
                                <?php if (!empty($cancelled_at)): ?>
                                    <div style="color: #666; font-size: 10px; font-weight: normal;"><?php echo $cancelled_at; ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- Date -->
                    <td>
                        <div style="color: #666; font-size: 13px;">
                            <i class="fa-regular fa-calendar text-warning me-1"></i>
                            <?php echo $date; ?>
                        </div>
                    </td>

                    <!-- Actions -->
                    <td style="text-align: center;">
                        <div class="action-group" style="justify-content: center;">
                            <!-- View / Print Invoice -->
                            <a href="../User/bill.php?o_id=<?php echo $order_id; ?>" 
                               target="_blank" 
                               class="btn-action btn-view-inv" 
                               title="View & Print Invoice">
                                <i class="fa-solid fa-file-invoice"></i>
                            </a>

                            <!-- Edit Status -->
                            <button type="button" 
                                    class="btn-action btn-status-edit" 
                                    onclick="openStatusModal(<?php echo $order_id; ?>, '<?php echo addslashes($o_status); ?>', '<?php echo addslashes($p_status); ?>')" 
                                    title="Change Order Status">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Delete Order -->
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete Order #PB-<?php echo $order_id; ?>? All associated items will also be removed.');" style="margin: 0;">
                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                <button type="submit" name="delete_order" class="btn-action btn-delete-order" title="Delete Order">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php } else { ?>
            <div class="no-order">
                <i class="fa-solid fa-cart-shopping" style="font-size:45px;color:#d99a32;margin-bottom:15px;display:block;"></i>
                <h3 style="color:#444;margin:0 0 5px;">No Orders Recorded Yet</h3>
                <p style="color:#888;margin:0;">When customers place orders, they will appear here in real-time.</p>
            </div>
        <?php } ?>
    </div>

</div>

<!-- Modal for updating Order & Payment Status -->
<div id="statusModal" class="status-modal">
    <div class="status-modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Update Order Status</h3>
            <button type="button" class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>

        <form method="POST">
            <input type="hidden" name="order_id" id="modalOrderId">

            <div class="form-group">
                <label for="modalOrderStatus">Order Tracking Status</label>
                <select name="order_status" id="modalOrderStatus" required>
                    <option value="Pending">Pending (New Order)</option>
                    <option value="Processing">Processing (Packed & Ready)</option>
                    <option value="Shipped">Shipped (In Transit)</option>
                    <option value="Delivered">Delivered (Completed)</option>
                    <option value="Cancelled">Cancelled (Cancel Order & Restore Stock)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="modalPaymentStatus">Payment Status</label>
                <select name="payment_status" id="modalPaymentStatus" required>
                    <option value="Pending">Pending (Unpaid)</option>
                    <option value="Paid">Paid (Confirmed)</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>

            <button type="submit" name="update_status" class="btn-save-status">
                <i class="fa-solid fa-check me-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<script>
function openStatusModal(orderId, orderStatus, paymentStatus) {
    document.getElementById('modalOrderId').value = orderId;
    document.getElementById('modalTitle').innerText = 'Update Status - Order #PB-' + orderId;
    document.getElementById('modalOrderStatus').value = orderStatus;
    document.getElementById('modalPaymentStatus').value = paymentStatus;
    document.getElementById('statusModal').classList.add('active');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('active');
}

// Close modal when clicking backdrop
window.onclick = function(event) {
    var modal = document.getElementById('statusModal');
    if (event.target === modal) {
        closeStatusModal();
    }
}
</script>

</body>
</html>
