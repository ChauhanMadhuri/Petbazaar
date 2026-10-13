<?php

/* ==============================
   DATABASE CONNECTION
   ============================== */

if (!isset($conn)) {
    include_once("conn.php");
}


/* ==============================
   SESSION START
   ============================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* ==============================
   CART COUNT
   ============================== */

$cart_count = 0;

if (isset($_SESSION['userid'])) {
    $userid = (int)$_SESSION['userid'];

    $result = mysqli_query($conn, 
        "SELECT COALESCE(SUM(p_qty), 0) AS count 
         FROM addtocart 
         WHERE u_id = '$userid'"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $cart_count = (int)$row['count'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Petbazar</title>

    <meta property="og:title" content="petbazaar">

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="format-detection" content="telephone=no">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="author" content="RSKWORLD">

    <meta name="keywords"
        content="pet shop, online store, e-commerce, pet products, animal care, pet supplies">

    <meta name="description"
        content="RSKWORLD - Comprehensive Online Platform for Pet Lovers and E-commerce Solutions">

    <meta name="robots" content="index, follow">

    <meta name="revisit-after" content="7 days">

    <meta property="og:description"
        content="Discover premium pet products and services at RSKWORLD">

    <meta property="og:type" content="website">

    <meta name="HandheldFriendly" content="true">

    <meta name="MobileOptimized" content="320">


    <!-- Preload -->

    <link rel="preload" href="style.css" as="style">

    <link rel="preload" href="js/script.js" as="script">


    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


    <!-- Swiper -->

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">


    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
        crossorigin="anonymous">


    <!-- CSS -->

    <link rel="stylesheet" type="text/css" href="css/vendor.css">

    <link rel="stylesheet" type="text/css" href="style.css">


    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css2?family=Chilanka&family=Montserrat:wght@300;400;500&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@300;400;500;700&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">


    <!-- Favicon -->

    <link rel="icon" type="image/png" href="images/favicon.png">


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<style>

/* =========================================
   MAIN LOGO
   ========================================= */

.main-logo {
    margin: 0;
    padding: 0 !important;
}


/* =========================================
   STICKY HEADER
   ========================================= */

header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}


/* Space for fixed header */

body {
    padding-top: 150px;
}


/* =========================================
   TOP HEADER SPACE
   ========================================= */

header .container.py-2 {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

header .row.py-4 {
    padding-top: 15px !important;
    padding-bottom: 10px !important;
}


/* =========================================
   LOGO
   ========================================= */

header img {
    max-height: 150px;
    width: auto;
}


/* =========================================
   SEARCH
   ========================================= */

.support-box {
    margin-top: 0 !important;
}

.search-bar {
    height: 40px !important;
    padding: 0 12px !important;
}

.search-bar input {
    height: 38px !important;
    padding: 5px 10px !important;
    font-size: 14px;
}

.search-bar svg,
.search-bar iconify-icon {
    width: 18px;
    height: 18px;
}


/* =========================================
   MENU
   ========================================= */

header .container-fluid hr {
    margin: 0 !important;
}

.main-menu {
    margin: 0 !important;
    padding: 0 !important;
}

.navbar {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.navbar-nav .nav-link {
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}


/* =========================================
   USER ICON
   ========================================= */

.user-icon {
    position: relative;
    display: inline-block;
}

.user-icon i {
    font-size: 22px;
    color: #222;
}

.status {
    position: absolute;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    bottom: 0;
    right: -2px;
    border: 2px solid #fff;
}

.online {
    background: green;
}

.offline {
    background: red;
}


/* =========================================
   WELCOME USER
   ========================================= */

.welcome-user {
    color: #E3A33B;
    font-size: 20px;
    font-weight: 400;
    white-space: nowrap;
    margin-right: 15px;
}


/* =========================================
   CART ICON
   ========================================= */

.cart-icon {
    position: relative;
    display: inline-block;
    text-decoration: none;
}

.cart-icon i {
    font-size: 20px;
}

.cart-icon span {
    position: absolute;
    top: -10px;
    right: -12px;

    min-width: 20px;
    height: 20px;

    padding: 2px 5px;

    border-radius: 50%;

    background: #E3A33B;
    color: white;

    font-size: 11px;
    font-weight: bold;

    display: flex;
    align-items: center;
    justify-content: center;
}


/* =========================================
   CATEGORY MENU
   ========================================= */

.category-menu {
    position: relative;
    list-style: none;
}

.category-menu > a {
    display: flex;
    align-items: center;
    gap: 6px;
}


/* =========================================
   FIRST DROPDOWN
   ========================================= */

.category-dropdown {
    position: absolute;

    top: 100%;
    left: 0;

    width: 230px;

    background: #fff;

    list-style: none;

    padding: 8px 0;
    margin: 0;

    display: none;

    border-radius: 10px;

    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);

    z-index: 9999;
}


/* Show category dropdown */

.category-menu:hover .category-dropdown {
    display: block;
}


/* =========================================
   CATEGORY
   ========================================= */

.sub-menu {
    position: relative;
}

.sub-menu > a {
    display: flex;

    justify-content: space-between;
    align-items: center;

    padding: 12px 18px;

    color: #333;

    text-decoration: none;

    transition: 0.3s;

    font-size: 15px;
}

.sub-menu > a:hover {
    background: #E3A33B;
    color: #fff;
}


/* =========================================
   SECOND DROPDOWN
   ========================================= */

.sub-dropdown {
    position: absolute;

    top: 0;
    left: 100%;

    width: 220px;

    background: #fff;

    list-style: none;

    padding: 8px 0;
    margin: 0;

    display: none;

    border-radius: 10px;

    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
}


/* Show sub category */

.sub-menu:hover .sub-dropdown {
    display: block;
}


/* =========================================
   SUB CATEGORY
   ========================================= */

.sub-dropdown li {
    list-style: none;
}

.sub-dropdown li a {
    display: block;

    padding: 12px 18px;

    color: #333;

    text-decoration: none;

    transition: 0.3s;

    font-size: 15px;
}

.sub-dropdown li a:hover {
    background: #E3A33B;
    color: #fff;
}


/* =========================================
   ARROW
   ========================================= */

.sub-menu i {
    font-size: 12px;
}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 991px) {

    body {
        padding-top: 110px;
    }

    header img {
        max-height: 100px;
    }

    .category-dropdown {
        position: static;
        width: 100%;
        box-shadow: none;
        border-radius: 0;
    }

    .sub-dropdown {
        position: static;
        width: 100%;
        box-shadow: none;
        border-radius: 0;
        padding-left: 15px;
    }

}

</style>


<body>


<!-- =========================================
     SVG ICONS
     ========================================= -->

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">

    <defs>

        <symbol id="cart" viewBox="0 0 24 24">

            <path fill="currentColor"
                d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />

        </symbol>

    </defs>

</svg>


<!-- =========================================
     CART OFFCANVAS
     ========================================= -->

<div class="offcanvas offcanvas-end"
    data-bs-scroll="true"
    tabindex="-1"
    id="offcanvasCart"
    aria-labelledby="My Cart">

    <div class="offcanvas-header justify-content-center">

        <button type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Close">
        </button>

    </div>


    <div class="offcanvas-body">

        <div class="order-md-last">

            <h4 class="d-flex justify-content-between align-items-center mb-3">

                <span class="text-primary">
                    Your cart
                </span>

                <span class="badge bg-primary rounded-circle pt-2">
                    <?php echo $cart_count; ?>
                </span>

            </h4>


            <ul class="list-group mb-3">

                <li class="list-group-item d-flex justify-content-between lh-sm">

                    <div>

                        <h6 class="my-0">
                            Your Cart
                        </h6>

                        <small class="text-body-secondary">
                            <?php echo $cart_count; ?> item(s) in your cart
                        </small>

                    </div>

                </li>

            </ul>


            <a href="cart.php"
                class="w-100 btn btn-primary btn-lg">

                View Cart

            </a>

        </div>

    </div>

</div>


<!-- =========================================
     SEARCH OFFCANVAS
     ========================================= -->

<div class="offcanvas offcanvas-end"
    data-bs-scroll="true"
    tabindex="-1"
    id="offcanvasSearch"
    aria-labelledby="Search">

    <div class="offcanvas-header justify-content-center">

        <button type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Close">
        </button>

    </div>


    <div class="offcanvas-body">

        <div class="order-md-last">

            <h4 class="text-primary text-uppercase mb-3">
                Search
            </h4>


            <div class="search-bar border rounded-2 border-dark-subtle">

                <form id="mobile-search-form"
                    class="text-center d-flex align-items-center"
                    action="search.php"
                    method="get">

                    <input type="text"
                        name="search"
                        class="form-control border-0 bg-transparent"
                        placeholder="Search your product"
                        required>


                    <button type="submit" style="border:0; background:none;">
                        <i class="fa-solid fa-magnifying-glass me-3"></i>
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     HEADER
     ========================================= -->

<header>


    <!-- TOP HEADER -->

    <div class="container py-2">

        <div class="row py-4 pb-0 pb-sm-4 align-items-center">


            <!-- LOGO -->

            <div class="col-sm-4 col-lg-3 text-center text-sm-start">

                <div class="main-logo">

                    <a href="index.php">

                        <img src="images/img.png"
                            alt="Petbazar Logo"
                            class="img-fluid">

                    </a>

                </div>

            </div>


            <!-- SEARCH -->

            <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">

                <div class="search-bar border rounded-2 px-3 border-dark-subtle">

                    <form id="search-form"
                        class="text-center d-flex align-items-center"
                        action="search.php"
                        method="get">

                        <input type="text"
                            name="search"
                            class="form-control border-0 bg-transparent"
                            placeholder="Search your product">


                        <button type="submit" style="border:0; background:none;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                    </form>

                </div>

            </div>


            <!-- USER -->

            <div class="col-sm-8 col-lg-4 d-flex justify-content-end align-items-center">


                <?php

if (isset($_SESSION['loggedinuser'])) {

?>

    <div class="text-end me-3 d-flex align-items-center justify-content-end gap-3">

        <span style="
            font-size:15px;
            font-weight:400;
            color:#E3A33B;
        ">

            👋 Welcome,
            <?php
            echo htmlspecialchars($_SESSION['email'] ?? '');
            ?>

        </span>

        <a href="my_orders.php" class="btn btn-sm btn-outline-warning text-dark border-warning rounded-pill px-3 py-1 fw-semibold" style="font-size: 13px;">
            <i class="fa-solid fa-box-archive text-warning me-1"></i> My Orders
        </a>

    </div>

<?php

}

?>

            </div>

        </div>

    </div>


    <!-- HR -->

    <div class="container-fluid">

        <hr class="m-0">

    </div>


    <!-- =========================================
         NAVBAR
         ========================================= -->

    <div class="container">

        <nav class="main-menu d-flex navbar navbar-expand-lg">


            <!-- MOBILE ICONS -->

            <div class="d-flex d-lg-none align-items-end mt-3">

                <ul class="d-flex justify-content-end list-unstyled m-0">


                    <!-- USER -->

                    <li>

                        <a href="<?php
                            echo isset($_SESSION['loggedinuser'])
                                ? 'logout.php'
                                : 'login.php';
                        ?>"
                            class="mx-3">

                            <i class="fa-solid fa-user fs-4"></i>

                        </a>

                    </li>

                    <?php if (isset($_SESSION['loggedinuser'])): ?>
                    <!-- MY ORDERS -->
                    <li>
                        <a href="my_orders.php" class="mx-3" title="My Orders">
                            <i class="fa-solid fa-box-archive fs-4"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- WISHLIST 

                    <li>

                        <a href="wishlist.php"
                            class="mx-3">

                            <i class="fa-solid fa-heart fs-4"></i>

                        </a>

                    </li> --->
 

                    <!-- CART -->

                    <li>

                        <a href="cart.php"
                            class="mx-3 position-relative">

                            <i class="fa-solid fa-cart-shopping fs-4"></i>

                            <span class="position-absolute
                                top-0
                                start-100
                                translate-middle
                                badge
                                rounded-circle
                                bg-primary">

                                <?php echo $cart_count; ?>

                            </span>

                        </a>

                    </li>


                    <!-- SEARCH -->

                    <li>

                        <a href="#"
                            class="mx-3"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasSearch"
                            aria-controls="offcanvasSearch">

                            <i class="fa-solid fa-magnifying-glass fs-4"></i>

                        </a>

                    </li>


                </ul>

            </div>


            <!-- MOBILE TOGGLER -->

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">

                <span class="navbar-toggler-icon"></span>

            </button>


            <!-- =========================================
                 NAV OFFCANVAS
                 ========================================= -->

            <div class="offcanvas offcanvas-end"
                tabindex="-1"
                id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">


                <!-- CLOSE -->

                <div class="offcanvas-header justify-content-center">

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close">
                    </button>

                </div>


                <!-- MENU -->

                <div class="offcanvas-body justify-content-between">


                    <ul class="navbar-nav menu-list list-unstyled d-flex gap-md-3 mb-0">


                        <!-- HOME -->

                        <li class="nav-item">

                            <a href="index.php"
                                class="nav-link active">

                                Home

                            </a>

                        </li>


                        <!-- ABOUT -->

                        <li class="nav-item">

                            <a href="about.php"
                                class="nav-link">

                                About us

                            </a>

                        </li>


                        <!-- =================================
                             CATEGORY
                             ================================= -->

                        <li class="nav-item category-menu">

                            <a href="#"
                                class="nav-link">

                                Category

                                <i class="fa-solid fa-angle-down"></i>

                            </a>


                            <!-- CATEGORY DROPDOWN -->

                            <ul class="category-dropdown">


                                <?php

                                $catqry = mysqli_query(
                                    $conn,
                                    "SELECT * FROM category ORDER BY category ASC"
                                );


                                if ($catqry) {

                                    while ($cat = mysqli_fetch_assoc($catqry)) {

                                        $category = $cat['category'];


                                        /*
                                         * Default page
                                         */

                                        $page = "subcategory.php";


                                        /*
                                         * Category page
                                         */

                                        if ($category == "Cat") {

                                            $page = "cat.php";

                                        } elseif ($category == "Dog") {

                                            $page = "dog.php";

                                        } elseif ($category == "Fish") {

                                            $page = "fish.php";

                                        } elseif ($category == "Bird") {

                                            $page = "bird.php";

                                        }

                                ?>


                                <!-- CATEGORY ITEM -->

                                <li class="sub-menu">


                                    <a href="<?php
                                        echo $page;
                                    ?>?catname=<?php
                                        echo urlencode($category);
                                    ?>">

                                        <?php
                                        echo htmlspecialchars($category);
                                        ?>


                                        <i class="fa-solid fa-angle-right"></i>

                                    </a>


                                    <!-- SUB CATEGORY -->

                                    <ul class="sub-dropdown">


                                        <?php

                                        $safe_category = mysqli_real_escape_string(
                                            $conn,
                                            $category
                                        );


                                        $subqry = mysqli_query(
                                            $conn,
                                            "SELECT *
                                             FROM sub_cat
                                             WHERE category_name='$safe_category'
                                             ORDER BY sub_cat ASC"
                                        );


                                        if ($subqry) {

                                            while ($sub = mysqli_fetch_assoc($subqry)) {

                                        ?>


                                        <li>

                                            <a href="subcategory.php?category=<?php
                                                echo urlencode($category);
                                            ?>&sub=<?php
                                                echo urlencode($sub['sub_cat']);
                                            ?>">

                                                <?php
                                                echo htmlspecialchars($sub['sub_cat']);
                                                ?>

                                            </a>

                                        </li>


                                        <?php

                                            }

                                        }

                                        ?>


                                    </ul>

                                </li>


                                <?php

                                    }

                                }

                                ?>


                            </ul>

                        </li>


                        <!-- CONTACT -->

                        <li class="nav-item">

                            <a href="contact.php"
                                class="nav-link">

                                Contact

                            </a>

                        </li>


                        <!-- SERVICE -->

                        <li class="nav-item">

                            <a href="feedback.php"
                                class="nav-link">

                                Feedback

                            </a>

                        </li>


                        <!-- REGISTRATION / MY ORDERS -->

                        <?php

                        if (!isset($_SESSION['loggedinuser'])) {

                        ?>

                            <li class="nav-item">

                                <a href="reg.php"
                                    class="nav-link">

                                    Registration

                                </a>

                            </li>

                        <?php

                        } else {

                        ?>

                            <li class="nav-item d-lg-none">

                                <a href="my_orders.php"
                                    class="nav-link">

                                    <i class="fa-solid fa-box-archive text-warning me-1"></i> My Orders

                                </a>

                            </li>

                        <?php

                        }

                        ?>


                    </ul>


                    <!-- =================================
                         RIGHT SIDE ICONS
                         ================================= -->

                    <div class="d-none d-lg-flex align-items-end">


                        <ul class="d-flex justify-content-end list-unstyled m-0">


                            <!-- USER LOGIN / LOGOUT -->

                            <li>

                                <div class="user-icon">

                                    <a href="<?php

                                        echo isset($_SESSION['loggedinuser'])
                                            ? 'logout.php'
                                            : 'login.php';

                                    ?>">

                                        <i class="fa-solid fa-user"></i>


                                        <?php

                                        if (isset($_SESSION['loggedinuser'])) {

                                            echo '<span class="status online"></span>';

                                        } else {

                                            echo '<span class="status offline"></span>';

                                        }

                                        ?>

                                    </a>

                                </div>

                            </li>


                            <?php if (isset($_SESSION['loggedinuser'])): ?>
                            <!-- MY ORDERS
                            <li>
                                <a href="my_orders.php" class="mx-3" title="My Orders">
                                    <i class="fa-solid fa-box-archive fs-4"></i>
                                </a>
                            </li> --->
                            <?php endif; ?> 


                            <!-- WISHLIST
                            <li>

                                <a href="wishlist.php"
                                    class="mx-3">

                                    <i class="fa-solid fa-heart fs-4"></i>

                                </a>

                            </li>  -->



                            <!-- CART -->

                            <li>

                                <a href="cart.php"
                                    class="cart-icon mx-3">

                                    <i class="fa-solid fa-cart-shopping"></i>

                                    <span>

                                        <?php
                                        echo $cart_count;
                                        ?>

                                    </span>

                                </a>

                            </li>


                        </ul>

                    </div>


                </div>

            </div>

        </nav>

    </div>

</header>


<!-- =========================================
     JAVASCRIPT
     ========================================= -->


<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Iconify -->

<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>


<!-- Swiper -->

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>