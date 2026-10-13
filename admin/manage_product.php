<?php
include("auth_check.php");
include("conn.php");
include("slide_bar.php");

// Fetch products
$result = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
$total_products = $result ? mysqli_num_rows($result) : 0;

$admin_msg = $_SESSION['admin_msg'] ?? '';
$admin_msg_type = $_SESSION['admin_msg_type'] ?? 'info';
unset($_SESSION['admin_msg'], $_SESSION['admin_msg_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - PetBazaar Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .main {
            margin-left: 236px;
            padding: 35px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h2 {
            margin: 0;
            color: #333;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header h2 i {
            color: #d89b3d;
        }

        .page-header p {
            color: #777;
            margin-top: 5px;
            font-size: 14px;
        }

        .btn-add-product {
            background: #d89b3d;
            color: white;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(216, 155, 61, 0.3);
            transition: all 0.2s;
        }

        .btn-add-product:hover {
            background: #bf8427;
            transform: translateY(-1px);
        }

        /* Alert notifications */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .alert-info {
            background: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-alert {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
        }

        .close-alert:hover {
            opacity: 1;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #d89b3d;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
        }

        th:first-child {
            border-top-left-radius: 8px;
            text-align: center;
            width: 60px;
        }

        th:nth-child(2) {
            text-align: center;
            width: 90px;
        }

        th:last-child {
            border-top-right-radius: 8px;
            text-align: center;
            width: 150px;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #333;
            vertical-align: middle;
        }

        tr:hover td {
            background: #fffdf9;
        }

        .prod-id {
            font-weight: 700;
            color: #888;
            text-align: center;
        }

        .img-cell {
            text-align: center;
        }

        .prod-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 4px;
        }

        .prod-name {
            font-weight: 600;
            color: #111;
            font-size: 15px;
            margin-bottom: 3px;
        }

        .category-tag {
            display: inline-block;
            background: #fff8eb;
            color: #b45309;
            border: 1px solid #fde68a;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .sub-tag {
            display: inline-block;
            background: #f3f4f6;
            color: #4b5563;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 4px;
        }

        .desc-text {
            max-width: 250px;
            color: #666;
            font-size: 13px;
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .price-text {
            font-weight: 700;
            color: #E39A2E;
            font-size: 15px;
        }

        /* Stock Pill */
        .stock-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .stock-in {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-out {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Action Buttons */
        .actions-cell {
            text-align: center;
            white-space: nowrap;
        }

        .edit-btn,
        .delete-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            font-weight: 600;
            margin: 2px;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
        }

        .edit-btn {
            background: #198754;
        }

        .edit-btn:hover {
            background: #157347;
            transform: translateY(-1px);
        }

        .delete-btn {
            background: #dc3545;
        }

        .delete-btn:hover {
            background: #bb2d3b;
            transform: translateY(-1px);
        }

        .empty-view {
            text-align: center;
            padding: 50px 20px;
            color: #777;
        }

        .empty-view i {
            font-size: 50px;
            color: #d89b3d;
            margin-bottom: 15px;
            display: block;
        }

        @media (max-width: 991px) {
            .main {
                margin-left: 0;
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>

<div class="main">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h2>
                <i class="fa-solid fa-boxes-stacked"></i> Manage Products
            </h2>
            <p>Total Products in Store: <strong><?php echo $total_products; ?></strong></p>
        </div>

        <a href="add_product.php" class="btn-add-product">
            <i class="fa-solid fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (!empty($admin_msg)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($admin_msg_type); ?>" id="flashAlert">
            <div class="alert-content">
                <?php if ($admin_msg_type === 'success'): ?>
                    <i class="fa-solid fa-circle-check"></i>
                <?php elseif ($admin_msg_type === 'danger'): ?>
                    <i class="fa-solid fa-circle-xmark"></i>
                <?php else: ?>
                    <i class="fa-solid fa-circle-info"></i>
                <?php endif; ?>
                <span><?php echo $admin_msg; ?></span>
            </div>
            <button type="button" class="close-alert" onclick="document.getElementById('flashAlert').style.display='none';">&times;</button>
        </div>
    <?php endif; ?>

    <!-- PRODUCTS TABLE -->
    <div class="table-card">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $p_id = (int)$row['id'];
                    $img = $row['image'];
                    $stock = (int)$row['qty'];
                    $img_src = (!empty($img) && file_exists("../User/upload/" . $img)) ? "../User/upload/" . htmlspecialchars($img) : "../User/images/item1.jpg";
                ?>
                <tr>
                    <!-- ID -->
                    <td class="prod-id">#<?php echo $p_id; ?></td>

                    <!-- Image -->
                    <td class="img-cell">
                        <img 
                            src="<?php echo $img_src; ?>" 
                            alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                            class="prod-img"
                            onerror="this.src='../User/images/item1.jpg'"
                        >
                    </td>

                    <!-- Product Name -->
                    <td>
                        <div class="prod-name"><?php echo htmlspecialchars($row['product_name']); ?></div>
                    </td>

                    <!-- Category & Sub Category -->
                    <td>
                        <div class="category-tag">
                            <?php echo htmlspecialchars($row['category']); ?>
                        </div>
                        <?php if (!empty($row['sub_category'])): ?>
                            <div>
                                <span class="sub-tag">
                                    <?php echo htmlspecialchars($row['sub_category']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- Description -->
                    <td>
                        <div class="desc-text" title="<?php echo htmlspecialchars($row['description']); ?>">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </div>
                    </td>

                    <!-- Price -->
                    <td>
                        <div class="price-text">
                            ₹<?php echo number_format((float)$row['price'], 2); ?>
                        </div>
                    </td>

                    <!-- Stock / Quantity -->
                    <td>
                        <?php if ($stock <= 0): ?>
                            <span class="stock-pill stock-out">
                                <i class="fa-solid fa-circle-xmark"></i> Out of Stock
                            </span>
                        <?php elseif ($stock <= 5): ?>
                            <span class="stock-pill stock-low">
                                <i class="fa-solid fa-triangle-exclamation"></i> Low: <?php echo $stock; ?>
                            </span>
                        <?php else: ?>
                            <span class="stock-pill stock-in">
                                <i class="fa-solid fa-check"></i> <?php echo $stock; ?> Qty
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Actions -->
                    <td class="actions-cell">
                        <a href="edit_product.php?id=<?php echo $p_id; ?>" class="edit-btn" title="Edit Product">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        <a 
                            href="delete_product.php?id=<?php echo $p_id; ?>" 
                            class="delete-btn" 
                            title="Delete Product"
                            onclick="return confirm('Are you sure you want to delete \'<?php echo addslashes($row['product_name']); ?>\'? This action cannot be undone.');"
                        >
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php else: ?>

        <div class="empty-view">
            <i class="fa-solid fa-box-open"></i>
            <h3 style="color:#444; margin-bottom:8px;">No Products Found</h3>
            <p style="color:#888; margin-bottom: 20px;">You haven't added any products yet.</p>
            <a href="add_product.php" class="btn-add-product">
                <i class="fa-solid fa-plus"></i> Add First Product
            </a>
        </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>