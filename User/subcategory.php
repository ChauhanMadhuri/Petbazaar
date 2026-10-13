<?php

include("conn.php");
include("header.php");

$category = $_GET['category'] ?? '';
$sub = $_GET['sub'] ?? '';


$product = mysqli_query($conn, "
    SELECT * FROM product
    WHERE category='$category'
    AND sub_category='$sub'
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title><?php echo htmlspecialchars($sub); ?> Products</title>

<style>

body{
    background:#f8f8f8;
    font-family:Arial,Helvetica,sans-serif;
    margin-top:90px;
}


/* PRODUCT CONTAINER */

.product-container{
    width:90%;
    margin:auto;

    display:grid;

    grid-template-columns:
        repeat(auto-fit,minmax(280px,1fr));

    gap:30px;

    padding-bottom:60px;
}


/* PRODUCT CARD */

.product-card{
    background:#fff;

    border-radius:15px;

    overflow:hidden;

    box-shadow:
        0 8px 20px rgba(0,0,0,.1);

    transition:.3s;
}


.product-card:hover{
    transform:translateY(-10px);
}


/* IMAGE BOX */

.product-card .product-image{

    width:100%;

    height:260px;

    display:flex;

    justify-content:center;

    align-items:center;

    overflow:hidden;

    background:#fff;
}


/* IMAGE */

.product-card .product-image img{

    width:100%;

    height:100%;

    object-fit:contain;

    display:block;
}


/* PRODUCT INFO */

.product-info{

    padding:18px;
}


/* PRODUCT NAME */

.product-info h3{

    font-size:24px;

    color:#222;

    margin-bottom:10px;
}


/* DESCRIPTION */

.product-info p{

    color:#666;

    line-height:24px;

    height:70px;

    overflow:hidden;
}


/* PRICE */

.price{

    color:#E39A2E;

    font-size:28px;

    font-weight:bold;

    margin:15px 0;
}


/* BUTTON */

.btn{

    display:block;

    text-align:center;

    text-decoration:none;

    background:#E39A2E;

    color:#fff;

    padding:13px;

    border-radius:8px;

    transition:.3s;
}


.btn:hover{

    background:#c67d13;
}


/* NO PRODUCT */

.no-product{

    width:90%;

    margin:50px auto;

    text-align:center;

    font-size:22px;

    color:#777;
}

</style>

</head>


<body>


<div class="product-container">


<?php

if(mysqli_num_rows($product) > 0)
{

    while($row = mysqli_fetch_assoc($product))
    {

        /*
        Database માં image:
        example:
        1750000000_abc123.png

        Actual image:
        petbazaar/upload/1750000000_abc123.png
        */

        $image = trim($row['image']);

        $image_path = "upload/" . $image;

?>



<div class="product-card">


    <!-- IMAGE -->

    <div class="product-image">

        <?php
        $disp_img = (!empty($image) && file_exists($image_path)) ? $image_path : ((!empty($image) && file_exists("images/" . $image)) ? "images/" . $image : "images/item1.jpg");
        ?>
        <img
            src="<?php echo htmlspecialchars($disp_img); ?>"
            alt="<?php echo htmlspecialchars($row['product_name']); ?>"
            onerror="this.src='images/item1.jpg'"
        >

    </div>



    <!-- PRODUCT INFO -->

    <div class="product-info">


        <h3>

            <?php

            echo htmlspecialchars(
                $row['product_name']
            );

            ?>

        </h3>



        <p>

            <?php

            echo htmlspecialchars(
                $row['description']
            );

            ?>

        </p>



        <div class="price">

            ₹ <?php echo htmlspecialchars($row['price']); ?>

        </div>

        <?php $p_stock = (int)$row['qty']; ?>
        <div style="font-size: 13px; font-weight: 600; margin-bottom: 12px;">
            <?php if ($p_stock <= 0): ?>
                <span style="color: #dc2626; font-weight: 700;">● Out of Stock</span>
            <?php elseif ($p_stock <= 5): ?>
                <span style="color: #b45309;">● Only <?php echo $p_stock; ?> left</span>
            <?php else: ?>
                <span style="color: #059669;">● In Stock (<?php echo $p_stock; ?> available)</span>
            <?php endif; ?>
        </div>

        <a
            href="single_product.php?id=<?php echo $row['id']; ?>"
            class="btn"
        >

            View Product

        </a>


    </div>


</div>


<?php

    }

}
else
{

?>

    <div class="no-product">

        No products found.

    </div>

<?php

}

?>


</div>


<?php

include("footer.php");

?>

</body>

</html>