<?php
include "auth_check.php";
include "conn.php";
include "slide_bar.php";



/* ===============================
   DELETE FEEDBACK
================================ */

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM feedback WHERE id = $id"
    );

    header("Location: view_feedback.php");
    exit();
}


/* ===============================
   FETCH FEEDBACK
================================ */

$result = mysqli_query(
    $conn,
    "SELECT id, name, email, rating, feedback, created_at
     FROM feedback
     ORDER BY id DESC"
);


if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>View Feedback - PetBazaar</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f0;
        }


        /* MAIN CONTENT */

        .main-content {
            margin-left: 236px;
            padding: 35px;
        }


        /* HEADER */

        .page-header {

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;
        }


        .page-header h2 {

            margin: 0;

            font-size: 30px;

            font-weight: 700;

            color: #333;
        }


        .page-header h2 i {

            color: #d89b3d;

            margin-right: 8px;
        }


        .page-header p {

            margin-top: 7px;

            margin-bottom: 0;

            color: #777;

            font-size: 15px;
        }


        /* COUNT */

        .feedback-count {

            background: white;

            padding: 12px 18px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.07);

            color: #555;
        }


        .feedback-count i {

            color: #d89b3d;

            margin-right: 7px;
        }


        /* CARD */

        .feedback-card {

            background: white;

            border-radius: 18px;

            padding: 25px;

            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }


        /* TABLE */

        .table {

            margin: 0;

            vertical-align: middle;
        }


        .table thead th {

            background: #d89b3d;

            color: white;

            border: none;

            padding: 16px;

            font-size: 15px;

            white-space: nowrap;
        }


        .table thead th:first-child {

            border-radius: 10px 0 0 10px;
        }


        .table thead th:last-child {

            border-radius: 0 10px 10px 0;
        }


        .table tbody td {

            padding: 17px 16px;

            border-bottom: 1px solid #eeeeee;
        }


        .table tbody tr {

            transition: 0.2s;
        }


        .table tbody tr:hover {

            background: #fffaf2;
        }


        /* ID */

        .id-circle {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            background: #fff1d9;

            color: #c17f20;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 700;
        }


        /* NAME */

        .name-box {

            font-weight: 600;

            color: #333;

            white-space: nowrap;
        }


        .name-box i {

            color: #d89b3d;

            margin-right: 7px;
        }


        /* EMAIL */

        .email {

            color: #666;

            white-space: nowrap;
        }


        .email i {

            color: #d89b3d;

            margin-right: 7px;
        }


        /* RATING */

        .rating {

            white-space: nowrap;
        }


        .rating i {

            color: #ffb400;

            font-size: 17px;

            margin-right: 2px;
        }


        /* FEEDBACK */

        .feedback-message {

            max-width: 350px;

            color: #555;

            line-height: 1.5;
        }


        /* DATE */

        .date-box {

            color: #777;

            white-space: nowrap;

            font-size: 14px;
        }


        .date-box i {

            color: #d89b3d;

            margin-right: 6px;
        }


        /* DELETE */

        .delete-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 40px;

            height: 40px;

            background: #fff0f0;

            color: #dc3545;

            border-radius: 10px;

            text-decoration: none;

            transition: 0.3s;
        }


        .delete-btn:hover {

            background: #dc3545;

            color: white;

            transform: translateY(-2px);
        }


        /* EMPTY */

        .empty-box {

            text-align: center;

            padding: 60px 20px;

            color: #777;
        }


        .empty-box i {

            font-size: 55px;

            color: #d89b3d;

            margin-bottom: 15px;
        }


        .empty-box h5 {

            color: #444;

            font-weight: 600;
        }


        /* MOBILE */

        @media(max-width: 991px) {

            .main-content {

                margin-left: 0;

                padding: 20px;
            }

        }


        @media(max-width: 600px) {

            .page-header {

                display: block;
            }


            .page-header h2 {

                font-size: 24px;
            }


            .feedback-count {

                display: inline-block;

                margin-top: 15px;
            }


            .feedback-card {

                padding: 15px;
            }

        }

    </style>

</head>


<body>


<div class="main-content">


    <!-- PAGE HEADER -->

    <div class="page-header">

        <div>

            <h2>

                <i class="fa-solid fa-comments"></i>

                View Feedback

            </h2>

            <p>

                Manage feedback received from PetBazaar users

            </p>

        </div>


        <?php

        $total_feedback = mysqli_num_rows($result);

        ?>

        <div class="feedback-count">

            <i class="fa-solid fa-star"></i>

            Total Feedback :

            <strong>

                <?php echo $total_feedback; ?>

            </strong>

        </div>

    </div>



    <!-- FEEDBACK CARD -->

    <div class="feedback-card">

        <div class="table-responsive">


            <table class="table">


                <!-- HEADER -->

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Rating</th>

                        <th>Feedback</th>

                        <th>Date</th>

                       

                    </tr>

                </thead>


                <!-- BODY -->

                <tbody>


                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <div class="id-circle">

                                <?php

                                echo htmlspecialchars($row['id']);

                                ?>

                            </div>

                        </td>



                        <!-- NAME -->

                        <td>

                            <div class="name-box">

                                <i class="fa-solid fa-user"></i>

                                <?php

                                echo htmlspecialchars($row['name']);

                                ?>

                            </div>

                        </td>



                        <!-- EMAIL -->

                        <td>

                            <div class="email">

                                <i class="fa-solid fa-envelope"></i>

                                <?php

                                echo htmlspecialchars($row['email']);

                                ?>

                            </div>

                        </td>



                        <!-- RATING -->

                        <td>

                            <div class="rating">

                                <?php

                                $rating = (int)$row['rating'];

                                for ($i = 1; $i <= 5; $i++) {

                                    if ($i <= $rating) {

                                        echo '<i class="fa-solid fa-star"></i>';

                                    } else {

                                        echo '<i class="fa-regular fa-star"
                                                  style="color:#ccc;"></i>';

                                    }

                                }

                                ?>

                            </div>

                        </td>



                        <!-- FEEDBACK -->

                        <td>

                            <div class="feedback-message">

                                <?php

                                echo nl2br(
                                    htmlspecialchars(
                                        $row['feedback']
                                    )
                                );

                                ?>

                            </div>

                        </td>



                        <!-- DATE -->

                        <td>

                            <div class="date-box">

                                <i class="fa-regular fa-calendar"></i>

                                <?php

                                echo date(
                                    "d-m-Y",
                                    strtotime($row['created_at'])
                                );

                                ?>

                            </div>

                        </td>



                     

                     
                    </tr>


                <?php

                    }

                } else {

                ?>


                    <!-- NO DATA -->

                    <tr>

                        <td colspan="7">


                            <div class="empty-box">

                                <i class="fa-regular fa-face-smile"></i>

                                <h5>
                                    No Feedback Found
                                </h5>

                                <p>
                                    There are no feedback messages yet.
                                </p>

                            </div>


                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>

            </table>


        </div>

    </div>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>