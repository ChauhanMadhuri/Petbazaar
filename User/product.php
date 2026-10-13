<?php
include("conn.php");
include("header.php");

$product = mysqli_query($conn,"SELECT * FROM product ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Products - PetBazaar</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
body{
    background:#f8f8f8;
    font-family:Arial,sans-serif;
}

.product-page-container {
    max-width: 1300px;
    margin: 40px auto 70px;
    padding: 0 20px;
}

.product-page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #222;
}

.product-box{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(270px,1fr));
    gap:30px;
}

.card{
    background:#fff;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:.3s;
    border: 1px solid #eee;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 25px rgba(0,0,0,.12);
}

.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    padding: 15px;
    background: #fafafa;
}

.details{
    padding:20px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.details h2{
    font-size:20px;
    margin-bottom:8px;
    color:#222;
    font-weight: 600;
}

.details p{
    font-size:14px;
    color:#666;
    line-height:20px;
    height:60px;
    overflow:hidden;
    margin-bottom: 12px;
}

.price{
    font-size:24px;
    color:#E39A2E;
    font-weight:bold;
    margin-bottom: 10px;
}

.btn-card{
    display: block;
    width:100%;
    padding:12px;
    background:#E39A2E;
    border:none;
    border-radius:8px;
    color:#fff;
    font-size:15px;
    font-weight: 600;
    cursor:pointer;
    text-align: center;
    text-decoration: none;
    transition:.2s;
}

.btn-card:hover{
    background:#c57f18;
    color: #fff;
}
</style>

</head>

<body>

<div class="product-page-container">

    <h1 class="product-page-title">Featured Pet Products</h1>

    <div class="product-box">

    <?php
    if ($product && mysqli_num_rows($product) > 0) {
        while($row=mysqli_fetch_assoc($product))
        {
            $stock = (int)$row['qty'];
            $is_out = ($stock <= 0);
    ?>

    <div class="card">

        <a href="single_product.php?id=<?php echo $row['id']; ?>">
            <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" 
                 alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                 onerror="this.src='images/item1.jpg'">
        </a>

        <div class="details">

            <div>
                <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            </div>

            <div>
                <div class="price">
                    ₹ <?php echo number_format((float)$row['price'], 2); ?>
                </div>

                <div style="font-size: 13px; font-weight: 600; margin-bottom: 14px;">
                    <?php if ($is_out): ?>
                        <span style="color: #dc2626;">● Out of Stock</span>
                    <?php elseif ($stock <= 5): ?>
                        <span style="color: #b45309;">● Only <?php echo $stock; ?> left</span>
                    <?php else: ?>
                        <span style="color: #059669;">● In Stock (<?php echo $stock; ?> Available)</span>
                    <?php endif; ?>
                </div>

                <a href="single_product.php?id=<?php echo $row['id']; ?>" class="btn-card">
                    View Product
                </a>
            </div>

        </div>

    </div>

    <?php
        }
    } else {
        echo "<p style='color:#777;'>No products found.</p>";
    }
    ?>

    </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>
