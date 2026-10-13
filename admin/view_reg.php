<?php
include "auth_check.php";
include "conn.php";
include "slide_bar.php";

/* Registered Users */
$sql = mysqli_query($conn, "SELECT * FROM ragistration");
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registered Users</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Sidebar -->
    <link rel="stylesheet" href="sidebar.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        /* Main content */
        .main {
            margin-left: 236px;
            width: calc(100% - 236px);
            min-height: 100vh;
            padding: 45px 35px;
        }

        /* Page box */
        .users-box {
            width: 100%;
            background: white;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
            overflow: hidden;
        }

        /* Heading */
        .page-title {
            text-align: center;
            padding: 25px 20px;
            font-size: 30px;
            color: #ff9800;
            font-weight: 700;
        }

        /* Table */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #ff9800;
            color: white;
            padding: 15px 12px;
            font-size: 16px;
            text-align: center;
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 15px;
            color: #333;
        }

        tr:nth-child(even) {
            background: #fff8e8;
        }

        tr:hover {
            background: #fff0cc;
        }

        /* Responsive */
        @media(max-width: 768px) {

            .main {
                margin-left: 236px;
                width: calc(100% - 236px);
                padding: 25px 15px;
            }

            .page-title {
                font-size: 25px;
            }

        }

    </style>

</head>

<body>

    <!-- Main Content -->
    <div class="main">

        <div class="users-box">

            <div class="page-title">
                Registered Users
            </div>

            <div class="table-container">

                <table>

                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>City</th>
                            <th>Address</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                   
$sql = mysqli_query($conn, "SELECT * FROM  ragistration");

if (!$sql) {
    die("Query Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($sql)) {

                    ?>

                        <tr>

                            <td><?php echo $row['fullname']; ?></td>

                            <td><?php echo $row['email']; ?></td>

                            <td><?php echo $row['password']; ?></td>

                            <td><?php echo $row['phone']; ?></td>

                            <td><?php echo $row['gender']; ?></td>

                            <td><?php echo $row['city']; ?></td>

                            <td><?php echo $row['address']; ?></td>

                        </tr>

                    <?php
                    }
                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>