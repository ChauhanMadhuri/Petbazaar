<?php
include("auth_check.php");
include("conn.php");

/* =========================
   CHECK PRODUCT ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['admin_msg'] = "Invalid Product ID.";
    $_SESSION['admin_msg_type'] = "danger";
    header("Location: manage_product.php");
    exit();
}

$id = (int)$_GET['id'];

/* =========================
   FETCH PRODUCT
========================= */
$get_product = mysqli_query(
    $conn,
    "SELECT * FROM product WHERE id='$id' LIMIT 1"
);

if (!$get_product || mysqli_num_rows($get_product) == 0) {
    $_SESSION['admin_msg'] = "Product not found or already deleted.";
    $_SESSION['admin_msg_type'] = "warning";
    header("Location: manage_product.php");
    exit();
}

$product = mysqli_fetch_assoc($get_product);
$image = $product['image'];
$product_name = $product['product_name'];

/* =========================
   DELETE PRODUCT FROM DATABASE
========================= */
$delete_query = mysqli_query(
    $conn,
    "DELETE FROM product WHERE id='$id'"
);

if ($delete_query) {
    // Delete image file from upload directory if exists
    if (!empty($image)) {
        $img_path = "../User/upload/" . $image;
        if (file_exists($img_path) && is_file($img_path)) {
            @unlink($img_path);
        }
    }

    // Clean up related items in cart if any
    @mysqli_query($conn, "DELETE FROM addtocart WHERE p_id='$id'");

    $_SESSION['admin_msg'] = "Product '" . htmlspecialchars($product_name) . "' deleted successfully.";
    $_SESSION['admin_msg_type'] = "success";
} else {
    $_SESSION['admin_msg'] = "Failed to delete product: " . mysqli_error($conn);
    $_SESSION['admin_msg_type'] = "danger";
}

/* =========================
   REDIRECT TO MANAGE PRODUCT
========================= */
header("Location: manage_product.php");
exit();
?>