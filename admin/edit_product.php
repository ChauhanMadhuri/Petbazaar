<?php
include("auth_check.php");
include("conn.php");

/* =========================
   GET PRODUCT ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['admin_msg'] = "Invalid Product ID.";
    $_SESSION['admin_msg_type'] = "danger";
    header("Location: manage_product.php");
    exit();
}

$id = (int)$_GET['id'];

/* =========================
   FETCH CURRENT PRODUCT
========================= */
$result = mysqli_query($conn, "SELECT * FROM product WHERE id='$id' LIMIT 1");
if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['admin_msg'] = "Product not found.";
    $_SESSION['admin_msg_type'] = "warning";
    header("Location: manage_product.php");
    exit();
}

$product = mysqli_fetch_assoc($result);
$error_msg = "";

/* =========================
   UPDATE PRODUCT LOGIC
========================= */
if (isset($_POST['update'])) {
    $category     = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $sub_category = mysqli_real_escape_string($conn, trim($_POST['sub_category'] ?? ''));
    $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name'] ?? ''));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price        = (float)($_POST['price'] ?? 0);
    $qty          = (int)($_POST['qty'] ?? 0);

    if (empty($category) || empty($sub_category) || empty($product_name) || $price < 0 || $qty < 0) {
        $error_msg = "Please fill in all required fields with valid values.";
    } else {
        $upload_dir = "../User/upload/";
        if (!file_exists($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        // Check if new image is uploaded
        if (isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $orig_name = basename($_FILES['image']['name']);
            $file_ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (!in_array($file_ext, $allowed_exts)) {
                $error_msg = "Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP, GIF.";
            } else {
                $new_image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $orig_name);
                $destination = $upload_dir . $new_image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    // Delete old image if it exists and differs
                    $old_image = $product['image'];
                    if (!empty($old_image) && file_exists($upload_dir . $old_image) && is_file($upload_dir . $old_image)) {
                        @unlink($upload_dir . $old_image);
                    }

                    $update_query = "UPDATE product SET
                                        category='$category',
                                        sub_category='$sub_category',
                                        product_name='$product_name',
                                        description='$description',
                                        price='$price',
                                        qty='$qty',
                                        image='$new_image_name'
                                     WHERE id='$id'";
                } else {
                    $error_msg = "Failed to upload the image file. Please check folder permissions.";
                }
            }
        } else {
            // Update without changing image
            $update_query = "UPDATE product SET
                                category='$category',
                                sub_category='$sub_category',
                                product_name='$product_name',
                                description='$description',
                                price='$price',
                                qty='$qty'
                             WHERE id='$id'";
        }

        if (empty($error_msg)) {
            if (mysqli_query($conn, $update_query)) {
                $_SESSION['admin_msg'] = "Product '" . htmlspecialchars($product_name) . "' updated successfully!";
                $_SESSION['admin_msg_type'] = "success";
                header("Location: manage_product.php");
                exit();
            } else {
                $error_msg = "Database Update Error: " . mysqli_error($conn);
            }
        }
    }

    // Refresh current product data from POST values to keep user changes in case of error
    $product['category']     = $_POST['category'] ?? $product['category'];
    $product['sub_category'] = $_POST['sub_category'] ?? $product['sub_category'];
    $product['product_name'] = $_POST['product_name'] ?? $product['product_name'];
    $product['description']  = $_POST['description'] ?? $product['description'];
    $product['price']        = $_POST['price'] ?? $product['price'];
    $product['qty']          = $_POST['qty'] ?? $product['qty'];
}

/* =========================
   FETCH CATEGORIES & SUB-CATEGORIES
========================= */
$categories_res = mysqli_query($conn, "SELECT * FROM category ORDER BY category ASC");

$current_cat_esc = mysqli_real_escape_string($conn, $product['category']);
$subcats_res = mysqli_query($conn, "SELECT * FROM sub_cat WHERE category_name='$current_cat_esc' ORDER BY sub_cat ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - PetBazaar Admin</title>

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

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h2 {
            margin: 0;
            color: #333;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title h2 i {
            color: #d89b3d;
        }

        .page-title p {
            color: #777;
            margin-top: 6px;
            font-size: 14px;
        }

        .edit-card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            max-width: 900px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            margin-bottom: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            background: #fff;
            color: #333;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: #d89b3d;
            box-shadow: 0 0 0 3px rgba(216, 155, 61, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 110px;
            line-height: 1.5;
        }

        /* Image section */
        .image-section {
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .image-preview-container {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-top: 12px;
        }

        .img-box {
            width: 130px;
            height: 130px;
            border-radius: 10px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            overflow: hidden;
            position: relative;
        }

        .img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .img-help {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }

        .button-area {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-update {
            background: #198754;
            color: white;
            border: none;
            padding: 13px 28px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-update:hover {
            background: #157347;
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #6c757d;
            color: white;
            padding: 13px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #565e64;
        }

        @media (max-width: 991px) {
            .main {
                margin-left: 0;
                padding: 20px 15px;
            }
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
            .image-preview-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<?php include("slide_bar.php"); ?>

<div class="main">

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2>
            <i class="fa-solid fa-pen-to-square"></i> Edit Product #<?php echo $product['id']; ?>
        </h2>
        <p>Update product specifications, category, inventory, and product image</p>
    </div>

    <!-- EDIT CARD -->
    <div class="edit-card">

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit_product.php?id=<?php echo $id; ?>" enctype="multipart/form-data">

            <!-- CATEGORY & SUB CATEGORY -->
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category <span style="color:#dc3545;">*</span></label>
                    <select name="category" id="category" required>
                        <option value="">Select Category</option>
                        <?php 
                        $has_selected_cat = false;
                        if ($categories_res && mysqli_num_rows($categories_res) > 0) {
                            while ($cat_row = mysqli_fetch_assoc($categories_res)) {
                                $cat_name = $cat_row['category'];
                                $sel = ($cat_name === $product['category']) ? 'selected' : '';
                                if ($sel) $has_selected_cat = true;
                                echo '<option value="' . htmlspecialchars($cat_name) . '" ' . $sel . '>' . htmlspecialchars($cat_name) . '</option>';
                            }
                        }
                        if (!$has_selected_cat && !empty($product['category'])) {
                            echo '<option value="' . htmlspecialchars($product['category']) . '" selected>' . htmlspecialchars($product['category']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sub_category">Sub Category <span style="color:#dc3545;">*</span></label>
                    <select name="sub_category" id="sub_category" required>
                        <option value="">Select Sub Category</option>
                        <?php 
                        $has_selected_sub = false;
                        if ($subcats_res && mysqli_num_rows($subcats_res) > 0) {
                            while ($sub_row = mysqli_fetch_assoc($subcats_res)) {
                                $sub_name = $sub_row['sub_cat'];
                                $sel = ($sub_name === $product['sub_category']) ? 'selected' : '';
                                if ($sel) $has_selected_sub = true;
                                echo '<option value="' . htmlspecialchars($sub_name) . '" ' . $sel . '>' . htmlspecialchars($sub_name) . '</option>';
                            }
                        }
                        if (!$has_selected_sub && !empty($product['sub_category'])) {
                            echo '<option value="' . htmlspecialchars($product['sub_category']) . '" selected>' . htmlspecialchars($product['sub_category']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- PRODUCT NAME -->
            <div class="form-group">
                <label for="product_name">Product Name <span style="color:#dc3545;">*</span></label>
                <input 
                    type="text" 
                    id="product_name"
                    name="product_name" 
                    value="<?php echo htmlspecialchars($product['product_name']); ?>" 
                    placeholder="e.g. Royal Canin Dog Food"
                    required
                >
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label for="description">Product Description <span style="color:#dc3545;">*</span></label>
                <textarea 
                    id="description"
                    name="description" 
                    rows="4" 
                    placeholder="Enter detailed description of the product..."
                    required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <!-- PRICE & QUANTITY -->
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price (₹) <span style="color:#dc3545;">*</span></label>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        id="price"
                        name="price" 
                        value="<?php echo htmlspecialchars($product['price']); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="qty">Quantity / Stock <span style="color:#dc3545;">*</span></label>
                    <input 
                        type="number" 
                        min="0" 
                        id="qty"
                        name="qty" 
                        value="<?php echo htmlspecialchars($product['qty']); ?>" 
                        required
                    >
                </div>
            </div>

            <!-- IMAGE SECTION -->
            <div class="image-section">
                <label style="margin-bottom: 4px;">Product Image</label>
                <p class="img-help">Upload a new image to replace the current one, or leave blank to keep existing.</p>

                <div class="image-preview-container">
                    <div class="img-box">
                        <?php 
                        $img_file = $product['image'];
                        $img_src = (!empty($img_file) && file_exists("../User/upload/" . $img_file)) ? "../User/upload/" . htmlspecialchars($img_file) : "../User/images/item1.jpg";
                        ?>
                        <img 
                            id="previewImg" 
                            src="<?php echo $img_src; ?>" 
                            alt="Product Image"
                            onerror="this.src='../User/images/item1.jpg'"
                        >
                    </div>

                    <div style="flex: 1;">
                        <input 
                            type="file" 
                            name="image" 
                            id="imageInput" 
                            accept="image/jpeg,image/png,image/webp,image/gif"
                        >
                        <p class="img-help" style="margin-top: 8px;">
                            Supported formats: <strong>JPG, PNG, WEBP, GIF</strong>. Recommended size: square or clean background.
                        </p>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="button-area">
                <button type="submit" name="update" class="btn-update">
                    <i class="fa-solid fa-floppy-disk"></i> Update Product
                </button>
                <a href="manage_product.php" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<!-- JavaScript for dynamic category loading and live image preview -->
<script>
document.getElementById("category").addEventListener("change", function() {
    var category = this.value;
    var subSelect = document.getElementById("sub_category");

    if (!category) {
        subSelect.innerHTML = '<option value="">Select Sub Category</option>';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "get_product.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            subSelect.innerHTML = xhr.responseText;
        }
    };

    xhr.send("category=" + encodeURIComponent(category));
});

// Live image preview
document.getElementById("imageInput").addEventListener("change", function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("previewImg").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>