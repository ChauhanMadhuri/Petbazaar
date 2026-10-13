<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Sidebar</title>


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>

        /* =====================================
           BASIC
           ===================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;
        }


        /* =====================================
           SIDEBAR
           ===================================== */

        body .sidebar {

            width: 236px;

            height: 100vh;

            background: #d89b3d;

            position: fixed;

            left: 0;

            top: 0;

            padding-top: 28px;

            z-index: 9999;

            overflow-y: auto;

            transition: left 0.3s ease;

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

            gap: 18px;

            width: 100%;

            padding: 18px 27px;

            color: white !important;

            text-decoration: none;

            font-size: 17px;

            transition: 0.3s;

        }


        .sidebar ul li a i {

            width: 20px;

            text-align: center;

            font-size: 17px;

            flex-shrink: 0;

        }


        .sidebar ul li a:hover {

            background: #2c3047;

            color: white !important;

        }


        /* =====================================
           MOBILE TOGGLE BUTTON
           ===================================== */

        .sidebar-toggle {

            display: none;

            position: fixed;

            top: 15px;

            left: 15px;

            width: 45px;

            height: 45px;

            border: none;

            border-radius: 8px;

            background: #d89b3d;

            color: white;

            font-size: 22px;

            cursor: pointer;

            z-index: 10001;

            align-items: center;

            justify-content: center;

            box-shadow:
                0 4px 10px rgba(0, 0, 0, 0.15);

        }


        /* =====================================
           CLOSE BUTTON
           ===================================== */

        .sidebar-close {

            display: none;

            position: absolute;

            top: 15px;

            right: 15px;

            width: 35px;

            height: 35px;

            border: none;

            background: transparent;

            color: white;

            font-size: 22px;

            cursor: pointer;

        }


        /* =====================================
           OVERLAY
           ===================================== */

        .sidebar-overlay {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, 0.45);

            z-index: 9998;

        }


        /* =====================================
           DESKTOP
           992px AND ABOVE
           ===================================== */

        @media (min-width: 992px) {

            .sidebar {

                left: 0;

            }

            .sidebar-toggle {

                display: none;

            }

        }


        /* =====================================
           TABLET + MOBILE
           BELOW 992px
           ===================================== */

        @media (max-width: 991px) {

            /* Sidebar hidden */

            body .sidebar {

                left: -236px;

            }


            /* Sidebar open */

            body .sidebar.sidebar-open {

                left: 0;

            }


            /* Toggle button visible */

            .sidebar-toggle {

                display: flex;

            }


            /* Close button */

            .sidebar-close {

                display: block;

            }


            /* Overlay */

            .sidebar-overlay.show {

                display: block;

            }

        }


        /* =====================================
           SMALL MOBILE
           ===================================== */

        @media (max-width: 575px) {

            body .sidebar {

                width: 236px;

                left: -236px;

            }


            body .sidebar.sidebar-open {

                left: 0;

            }


            .sidebar-toggle {

                top: 12px;

                left: 12px;

                width: 42px;

                height: 42px;

                font-size: 20px;

            }


            .sidebar ul li a {

                padding: 17px 25px;

                font-size: 16px;

            }

        }

    </style>

</head>



<body>


    <!-- =====================================
         MOBILE TOGGLE BUTTON
         ===================================== -->

    <button
        type="button"
        class="sidebar-toggle"
        id="sidebarToggle">

        <i class="fas fa-bars"></i>

    </button>


    <!-- =====================================
         OVERLAY
         ===================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay">
    </div>


    <!-- =====================================
         SIDEBAR
         ===================================== -->

    <div class="sidebar" id="adminSidebar">


        <!-- CLOSE BUTTON -->

        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose">

            <i class="fas fa-xmark"></i>

        </button>


        <!-- MENU -->

        <ul>


            <!-- DASHBOARD -->

            <li>

                <a href="dashbord.php">

                    <i class="fas fa-home"></i>

                    Dashboard

                </a>

            </li>


            <!-- CATEGORY -->

            <li>

                <a href="add_catagory.php">

                    <i class="fas fa-list"></i>

                    Category

                </a>

            </li>


            <!-- SUB CATEGORY -->

            <li>

                <a href="add_sub_catagory.php">

                    <i class="fas fa-list"></i>

                    Sub Category

                </a>

            </li>


            <!-- PRODUCTS -->

            <li>

                <a href="add_product.php">

                    <i class="fas fa-box"></i>

                    Add Products

                </a>

            </li>


            <!-- MANAGE PRODUCTS -->

            <li>

                <a href="manage_product.php">

                    <i class="fas fa-eye"></i>

                    Manage Products

                </a>

            </li>


            <!-- MANAGE ORDERS -->

            <li>

                <a href="manage_order.php">

                    <i class="fas fa-shopping-cart"></i>

                    Manage Orders

                </a>

            </li>


            <!-- CONTACT -->

            <li>

                <a href="view_contact.php">

                    <i class="fas fa-envelope"></i>

                    Contact

                </a>

            </li>


            <!-- VIEW REGISTER -->

            <li>

                <a href="view_reg.php">

                    <i class="fas fa-user"></i>

                    View Register

                </a>

            </li>

         <li>

                <a href="view_feedback.php">

                    <i class="fas fa-user"></i>
                      View Feedback

                </a>

            </li>
            

            <!-- LOGOUT -->

            <li>

                <a href="logout.php">

                    <i class="fas fa-sign-out-alt"></i>

                    Logout

                </a>

            </li>


        </ul>

    </div>


    <!-- =====================================
         JAVASCRIPT
         ===================================== -->

    <script>

        const sidebar =
            document.getElementById("adminSidebar");

        const toggleButton =
            document.getElementById("sidebarToggle");

        const closeButton =
            document.getElementById("sidebarClose");

        const overlay =
            document.getElementById("sidebarOverlay");


        /* =================================
           OPEN SIDEBAR
           ================================= */

        toggleButton.addEventListener(
            "click",
            function () {

                sidebar.classList.add("sidebar-open");

                overlay.classList.add("show");

            }
        );


        /* =================================
           CLOSE SIDEBAR
           ================================= */

        closeButton.addEventListener(
            "click",
            function () {

                sidebar.classList.remove("sidebar-open");

                overlay.classList.remove("show");

            }
        );


        /* =================================
           CLOSE ON OVERLAY CLICK
           ================================= */

        overlay.addEventListener(
            "click",
            function () {

                sidebar.classList.remove("sidebar-open");

                overlay.classList.remove("show");

            }
        );


        /* =================================
           CLOSE AFTER MENU CLICK
           MOBILE ONLY
           ================================= */

        const menuLinks =
            sidebar.querySelectorAll("a");


        menuLinks.forEach(function (link) {

            link.addEventListener(
                "click",
                function () {

                    if (window.innerWidth < 992) {

                        sidebar.classList.remove(
                            "sidebar-open"
                        );

                        overlay.classList.remove(
                            "show"
                        );

                    }

                }
            );

        });


        /* =================================
           WINDOW RESIZE
           ================================= */

        window.addEventListener(
            "resize",
            function () {

                if (window.innerWidth >= 992) {

                    sidebar.classList.remove(
                        "sidebar-open"
                    );

                    overlay.classList.remove(
                        "show"
                    );

                }

            }
        );

    </script>


</body>

</html>