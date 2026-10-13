<?php
include "auth_check.php";


/* ==============================
   DATABASE CONNECTION
   ============================== */

include "conn.php";


/* ==============================
   SIDEBAR
   ============================== */

include "slide_bar.php";


/* ==============================
   TOTAL USERS
   ============================== */

$total_users = 0;

$sql_users = "SELECT COUNT(*) AS total_users FROM ragistration";

$result_users = mysqli_query($conn, $sql_users);

if ($result_users) {

    $row_users = mysqli_fetch_assoc($result_users);

    $total_users = $row_users['total_users'];
}


/* ==============================
   TOTAL ORDERS
   ============================== */

$total_orders = 0;

$sql_orders = "SELECT COUNT(*) AS total_orders FROM bill";

$result_orders = mysqli_query($conn, $sql_orders);

if ($result_orders) {

    $row_orders = mysqli_fetch_assoc($result_orders);

    $total_orders = $row_orders['total_orders'];
}


/* ==============================
   TOTAL PRODUCTS
   ============================== */

$total_products = 0;

$sql_products = "SELECT COUNT(*) AS total_products FROM product";

$result_products = mysqli_query($conn, $sql_products);

if ($result_products) {

    $row_products = mysqli_fetch_assoc($result_products);

    $total_products = $row_products['total_products'];
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>PetBazaar Dashboard</title>


    <!-- =========================
         BOOTSTRAP
         ========================= -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- =========================
         FONT AWESOME
         ========================= -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>

        /* =====================================
           BASIC
           ===================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        body {

            background: #f5f6fa;

            font-family: Arial, sans-serif;

            overflow-x: hidden;

        }


        /* =====================================
           SIDEBAR - DESKTOP
           ===================================== */

        .sidebar {

            width: 253px !important;

            height: 100vh;

            position: fixed !important;

            top: 0;

            left: 0;

            z-index: 1050;

            background: #dda33c;

            overflow-y: auto;

            transition: 0.3s;

        }


        /* =====================================
           SIDEBAR LOGO
           ===================================== */

        .sidebar .logo {

            height: 150px;

            display: flex;

            align-items: center;

            padding-left: 30px;

        }

        .sidebar .logo h2 {

            color: white;

            font-size: 26px;

        }


        /* =====================================
           SIDEBAR MENU
           ===================================== */

        .sidebar ul {

            list-style: none;

            padding: 0;

            margin: 0;

        }

        .sidebar ul li {

            width: 100%;

        }

        .sidebar ul li a {

            display: flex;

            align-items: center;

            width: 100%;

            min-height: 58px;

            padding: 10px 25px;

            color: white;

            text-decoration: none;

            font-size: 18px;

        }

        .sidebar ul li a i {

            width: 35px;

            font-size: 18px;

            flex-shrink: 0;

        }

        .sidebar ul li a:hover {

            background: rgba(0, 0, 0, 0.10);

        }


        /* =====================================
           MAIN
           ===================================== */

        .dashboard-main {

            margin-left: 253px;

            width: calc(100% - 253px);

            min-height: 100vh;

            padding: 40px;

        }


        /* =====================================
           MOBILE MENU BUTTON
           ===================================== */

        .mobile-menu-btn {

            display: none;

            border: none;

            background: #dda33c;

            color: white;

            width: 45px;

            height: 45px;

            border-radius: 8px;

            font-size: 22px;

            align-items: center;

            justify-content: center;

        }


        /* =====================================
           OVERLAY
           ===================================== */

        .sidebar-overlay {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, 0.45);

            z-index: 1040;

        }


        /* =====================================
           WELCOME
           ===================================== */

        .welcome-title {

            font-size: 32px;

            font-weight: 700;

            color: #111;

            margin-bottom: 8px;

        }

        .welcome-subtitle {

            font-size: 22px;

            font-weight: 600;

            color: #666;

        }


        /* =====================================
           DASHBOARD CARDS
           ===================================== */

        .dashboard-card {

            background: white;

            border: none;

            border-radius: 18px;

            min-height: 150px;

            padding: 25px;

            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.08);

            transition: 0.3s;

        }

        .dashboard-card:hover {

            transform: translateY(-4px);

        }


        /* =====================================
           ICON
           ===================================== */

        .dashboard-icon {

            width: 75px;

            height: 75px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            font-size: 32px;

            flex-shrink: 0;

        }


        .users-icon {

            background: #3498db;

        }

        .orders-icon {

            background: #f39c12;

        }

        .products-icon {

            background: #27ae60;

        }


        /* =====================================
           CARD NUMBER
           ===================================== */

        .card-number {

            font-size: 40px;

            font-weight: 700;

            line-height: 1;

            color: #222;

        }

        .card-title {

            font-size: 20px;

            color: #666;

            margin-top: 8px;

        }


        /* =====================================
           TABLET
           ===================================== */

        @media (max-width: 991px) {

            .sidebar {

                width: 220px !important;

            }

            .dashboard-main {

                margin-left: 220px;

                width: calc(100% - 220px);

                padding: 30px;

            }

            .sidebar ul li a {

                font-size: 16px;

                padding-left: 20px;

            }

            .welcome-title {

                font-size: 28px;

            }

            .welcome-subtitle {

                font-size: 20px;

            }

            .dashboard-icon {

                width: 65px;

                height: 65px;

                font-size: 28px;

            }

            .card-number {

                font-size: 34px;

            }

            .card-title {

                font-size: 18px;

            }

        }


        /* =====================================
           MOBILE
           ===================================== */

        @media (max-width: 767px) {

            /* Sidebar hidden */

            .sidebar {

                width: 260px !important;

                left: -260px !important;

                transition: left 0.3s ease;

            }


            /* Sidebar open */

            .sidebar.mobile-show {

                left: 0 !important;

            }


            /* Main full width */

            .dashboard-main {

                margin-left: 0;

                width: 100%;

                min-height: 100vh;

                padding: 20px 15px;

            }


            /* Menu button */

            .mobile-menu-btn {

                display: flex;

            }


            /* Overlay */

            .sidebar-overlay.show {

                display: block;

            }


            /* Header */

            .mobile-header {

                display: flex;

                align-items: center;

                justify-content: space-between;

                margin-bottom: 25px;

            }


            /* Welcome */

            .welcome-title {

                font-size: 24px;

                line-height: 1.3;

            }

            .welcome-subtitle {

                font-size: 18px;

            }


            /* Card */

            .dashboard-card {

                min-height: 125px;

                padding: 20px;

            }


            .dashboard-icon {

                width: 60px;

                height: 60px;

                font-size: 25px;

            }


            .card-number {

                font-size: 30px;

            }


            .card-title {

                font-size: 17px;

            }

        }


        /* =====================================
           SMALL MOBILE
           ===================================== */

        @media (max-width: 400px) {

            .dashboard-main {

                padding: 15px 12px;

            }


            .welcome-title {

                font-size: 21px;

            }


            .welcome-subtitle {

                font-size: 16px;

            }


            .dashboard-card {

                padding: 16px;

                min-height: 115px;

            }


            .dashboard-icon {

                width: 52px;

                height: 52px;

                font-size: 22px;

            }


            .card-number {

                font-size: 27px;

            }


            .card-title {

                font-size: 15px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================
     MOBILE OVERLAY
     ===================================== -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay">
</div>


<!-- =====================================
     MAIN
     ===================================== -->

<main class="dashboard-main">


    <!-- =================================
         MOBILE HEADER
         ================================= -->

    <div class="mobile-header d-md-none">

        <button
            type="button"
            class="mobile-menu-btn"
            id="menuBtn">

            <i class="fa-solid fa-bars"></i>

        </button>


        <h5 class="m-0 fw-bold">

            Admin Dashboard

        </h5>


        <div style="width:45px;"></div>

    </div>


    <!-- =================================
         WELCOME
         ================================= -->

    <div class="mb-4">

        <h1 class="welcome-title">

            Welcome,

            <?php

            echo htmlspecialchars(
                $_SESSION['email'] ?? 'Administrator'
            );

            ?>

            👋

        </h1>


        <p class="welcome-subtitle mb-0">

            Administrator

        </p>

    </div>


    <!-- =================================
         CARDS
         ================================= -->

    <div class="row g-4">


        <!-- ===============================
             USERS
             =============================== -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div
                class="dashboard-card
                       d-flex
                       align-items-center
                       justify-content-between
                       gap-3">

                <div class="dashboard-icon users-icon">

                    <i class="fa-solid fa-users"></i>

                </div>


                <div class="text-end">

                    <div class="card-number">

                        <?php
                        echo $total_users;
                        ?>

                    </div>

                    <div class="card-title">

                        Users

                    </div>

                </div>

            </div>

        </div>


        <!-- ===============================
             ORDERS
             =============================== -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div
                class="dashboard-card
                       d-flex
                       align-items-center
                       justify-content-between
                       gap-3">

                <div class="dashboard-icon orders-icon">

                    <i class="fa-solid fa-cart-shopping"></i>

                </div>


                <div class="text-end">

                    <div class="card-number">

                        <?php
                        echo $total_orders;
                        ?>

                    </div>

                    <div class="card-title">

                        Orders

                    </div>

                </div>

            </div>

        </div>


        <!-- ===============================
             PRODUCTS
             =============================== -->

        <div class="col-12 col-sm-6 col-lg-4">

            <div
                class="dashboard-card
                       d-flex
                       align-items-center
                       justify-content-between
                       gap-3">

                <div class="dashboard-icon products-icon">

                    <i class="fa-solid fa-paw"></i>

                </div>


                <div class="text-end">

                    <div class="card-number">

                        <?php
                        echo $total_products;
                        ?>

                    </div>

                    <div class="card-title">

                        Products

                    </div>

                </div>

            </div>

        </div>


    </div>


</main>


<!-- =====================================
     JAVASCRIPT
     ===================================== -->

<script>

    const menuBtn =
        document.getElementById("menuBtn");

    const sidebar =
        document.querySelector(".sidebar");

    const overlay =
        document.getElementById("sidebarOverlay");


    /* ==============================
       OPEN SIDEBAR
       ============================== */

    if (menuBtn) {

        menuBtn.addEventListener("click", function () {

            sidebar.classList.add("mobile-show");

            overlay.classList.add("show");

        });

    }


    /* ==============================
       CLOSE SIDEBAR
       ============================== */

    if (overlay) {

        overlay.addEventListener("click", function () {

            sidebar.classList.remove("mobile-show");

            overlay.classList.remove("show");

        });

    }

</script>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>