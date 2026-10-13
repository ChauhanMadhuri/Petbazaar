<?php

include("conn.php");
include("header.php");

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search - Petbazar</title>

    <style>

        body {
            background: #f8f8f8;
        }

        /* =========================
           SEARCH PAGE
        ========================= */

        .search-page {
            padding: 40px 40px 60px;
        }

        .search-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-title h2 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* =========================
           SEARCH BOX
        ========================= */

        .search-box {
            max-width: 650px;
            margin: 0 auto 45px;
        }

        .search-box form {
            display: flex;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            height: 48px;
            padding: 0 18px;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 8px 0 0 8px;
            outline: none;
            font-size: 15px;
            background: #fff;
        }

        .search-box input:focus {
            border-color: #d89b3d;
        }

        .search-box button {
            width: 110px;
            height: 48px;
            border: none;
            background: #d89b3d;
            color: white;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
        }

        .search-box button:hover {
            background: #c88a2d;
        }

        /* =========================
           RESULT TITLE
        ========================= */

        .section-title {
            margin: 20px 0 25px;
            font-weight: 700;
            font-size: 24px;
        }

        /* =========================
           PRODUCT CARD
        ========================= */

        .product-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        /* =========================
           PRODUCT IMAGE
        ========================= */

        .product-image {
            width: 100%;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* =========================
           PRODUCT DETAILS
        ========================= */

        .product-card h5 {
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .product-card a {
            text-decoration: none;
        }

        .product-card p {
            margin-bottom: 5px;
            font-size: 14px;
            color: #666;
        }

        .product-card strong {
            display: block;
            margin-top: 12px;
            font-size: 19px;
            color: #d89b3d;
        }

        /* =========================
           NO RESULT
        ========================= */

        .no-result {
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .no-result h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .no-result p {
            color: #777;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 768px) {

            .search-page {
                padding: 30px 15px 50px;
            }

            .search-title h2 {
                font-size: 25px;
            }

            .search-box {
                margin-bottom: 30px;
            }

            .product-image {
                height: 200px;
            }

        }

    </style>

</head>


<body>


<div class="search-page">


    <!-- =========================
         TITLE
    ========================= -

    <div class="search-title">

        <h2>Search Products</h2>

    </div>


     =========================
         SEARCH BOX
    ========================= 

    <div class="search-box">

        <form action="search.php" method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search product, category or sub category..."
                value="<?php echo htmlspecialchars($search); ?>"
                required
            >

            <button type="submit">
                Search
            </button>

        </form>

    </div> -!>


<?php


if ($search != "") {


    /* =========================
       SAFE SEARCH
    ========================= */

    $search_safe = mysqli_real_escape_string($conn, $search);


    /* =========================
       PRODUCT SEARCH
    ========================= */

    $query = "

        SELECT *

        FROM product

        WHERE

            product_name LIKE '%$search_safe%'

            OR category LIKE '%$search_safe%'

            OR sub_category LIKE '%$search_safe%'

            OR description LIKE '%$search_safe%'

        ORDER BY id DESC

    ";


    $result = mysqli_query($conn, $query);

?>


    <!-- =========================
         RESULT TITLE
    ========================= 

    <h3 class="section-title">

        Search Result for:
        "<?php echo htmlspecialchars($search); ?>"

    </h3>-->


    <div class="row">


<?php


    if ($result && mysqli_num_rows($result) > 0) {


        while ($product = mysqli_fetch_assoc($result)) {


            /* =========================
               IMAGE PATH CHECK
            ========================= */

            $image = $product['image'];

            if (file_exists("uploads/" . $image)) {

                $image_path = "uploads/" . $image;

            } elseif (file_exists("upload/" . $image)) {

                $image_path = "upload/" . $image;

            } else {

                $image_path = "images/no-image.png";

            }


?>


            <!-- =========================
                 PRODUCT
            ========================= -->

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">


                <div class="product-card">


                    <a href="single_product.php?id=<?php echo $product['id']; ?>">


                        <!-- IMAGE -->

                        <div class="product-image">

                            <img
                                src="<?php echo htmlspecialchars($image_path); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                onerror="this.src='images/item1.jpg'"
                            >

                        </div>


                        <!-- PRODUCT NAME -->

                        <h5>

                            <?php
                            echo htmlspecialchars($product['product_name']);
                            ?>

                        </h5>


                    </a>


                    <!-- CATEGORY -->

                    <p>

                        <strong style="
                            display:inline;
                            font-size:14px;
                            color:#333;
                        ">
                            Category:
                        </strong>

                        <?php
                        echo htmlspecialchars($product['category']);
                        ?>

                    </p>


                    <!-- SUB CATEGORY -->

                    <p>

                        <strong style="
                            display:inline;
                            font-size:14px;
                            color:#333;
                        ">
                            Sub Category:
                        </strong>

                        <?php
                        echo htmlspecialchars($product['sub_category']);
                        ?>

                    </p>


                    <!-- PRICE -->

                    <?php if (isset($product['price'])) { ?>

                        <strong>

                            ₹<?php
                            echo htmlspecialchars($product['price']);
                            ?>

                        </strong>

                    <?php } ?>


                </div>


            </div>


<?php

        }


    } else {


?>


        <!-- NO RESULT -->

        <div class="col-12">

            <div class="no-result">

                <h4>
                    No Product Found
                </h4>

                <p>
                    Try another product, category or sub category.
                </p>

            </div>

        </div>


<?php

    }

?>


    </div>


<?php

}

?>


</div>


</body>

</html>