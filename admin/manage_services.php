<?php
include "auth_check.php";
include "conn.php";


// ==========================================
// DELETE SERVICE
// ==========================================

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];


    // First get image name

    $image_query = mysqli_query(
        $conn,
        "SELECT image FROM services WHERE id=$id"
    );


    if ($image_query && mysqli_num_rows($image_query) > 0) {

        $image_data = mysqli_fetch_assoc($image_query);

        $image_name = $image_data['image'];


        // Delete image from folder

        if (!empty($image_name)) {

            $image_path = "../uploads/services/" . $image_name;

            if (file_exists($image_path)) {

                unlink($image_path);

            }

        }

    }


    // Delete service

    mysqli_query(
        $conn,
        "DELETE FROM services WHERE id=$id"
    );


    header("Location: manage_services.php?msg=deleted");

    exit();

}


// ==========================================
// ADD / UPDATE SERVICE
// ==========================================

$edit_id = 0;

$edit_service_name = "";
$edit_category = "";
$edit_description = "";
$edit_price = "";
$edit_image = "";


// ==========================================
// EDIT BUTTON CLICK
// ==========================================

if (isset($_GET['edit'])) {

    $edit_id = (int) $_GET['edit'];


    $edit_query = mysqli_query(
        $conn,
        "SELECT * FROM services WHERE id=$edit_id"
    );


    if ($edit_query && mysqli_num_rows($edit_query) > 0) {

        $edit_data = mysqli_fetch_assoc($edit_query);


        $edit_service_name =
            $edit_data['service_name'];

        $edit_category =
            $edit_data['category'];

        $edit_description =
            $edit_data['description'];

        $edit_price =
            $edit_data['price'];

        $edit_image =
            $edit_data['image'];

    }

}


// ==========================================
// FORM SUBMIT
// ==========================================

if (isset($_POST['save_service'])) {


    $service_name =
        mysqli_real_escape_string(
            $conn,
            $_POST['service_name']
        );


    $category =
        mysqli_real_escape_string(
            $conn,
            $_POST['category']
        );


    $description =
        mysqli_real_escape_string(
            $conn,
            $_POST['description']
        );


    $price =
        mysqli_real_escape_string(
            $conn,
            $_POST['price']
        );


    $service_id =
        (int) $_POST['service_id'];


    // ======================================
    // IMAGE UPLOAD
    // ======================================

    $new_image = "";


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] == 0
    ) {


        $image_name =
            $_FILES['image']['name'];


        $tmp_name =
            $_FILES['image']['tmp_name'];


        $file_ext =
            strtolower(
                pathinfo(
                    $image_name,
                    PATHINFO_EXTENSION
                )
            );


        $allowed_ext = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];


        if (in_array($file_ext, $allowed_ext)) {


            $new_image =
                time() . "_" .
                rand(1000,9999) .
                "." . $file_ext;


            $upload_folder =
                "../uploads/services/";


            // Create folder if not exists

            if (!is_dir($upload_folder)) {

                mkdir(
                    $upload_folder,
                    0777,
                    true
                );

            }


            move_uploaded_file(
                $tmp_name,
                $upload_folder . $new_image
            );

        }

    }


    // ======================================
    // UPDATE
    // ======================================

    if ($service_id > 0) {


        if (!empty($new_image)) {


            // Delete old image

            $old_query = mysqli_query(
                $conn,
                "SELECT image
                 FROM services
                 WHERE id=$service_id"
            );


            if (
                $old_query &&
                mysqli_num_rows($old_query) > 0
            ) {

                $old_data =
                    mysqli_fetch_assoc($old_query);


                if (!empty($old_data['image'])) {


                    $old_path =
                        "../uploads/services/" .
                        $old_data['image'];


                    if (file_exists($old_path)) {

                        unlink($old_path);

                    }

                }

            }


            // Update with new image

            $sql = "
                UPDATE services SET

                service_name='$service_name',

                category='$category',

                description='$description',

                price='$price',

                image='$new_image'

                WHERE id=$service_id
            ";


        } else {


            // Update without image

            $sql = "
                UPDATE services SET

                service_name='$service_name',

                category='$category',

                description='$description',

                price='$price'

                WHERE id=$service_id
            ";

        }


        mysqli_query($conn, $sql);


        header(
            "Location: manage_services.php?msg=updated"
        );

        exit();

    }


    // ======================================
    // INSERT NEW SERVICE
    // ======================================

    else {


        $sql = "
            INSERT INTO services
            (
                service_name,
                category,
                description,
                price,
                image
            )

            VALUES
            (
                '$service_name',
                '$category',
                '$description',
                '$price',
                '$new_image'
            )
        ";


        mysqli_query($conn, $sql);


        header(
            "Location: manage_services.php?msg=added"
        );

        exit();

    }

}


// ==========================================
// FETCH ALL SERVICES
// ==========================================

$services_query = mysqli_query(
    $conn,
    "SELECT *
     FROM services
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Services</title>


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

    background: #f5f6f8;

    font-family: Arial, sans-serif;

}


/* ======================================
   MAIN AREA
====================================== */

.main-content {

    margin-left: 236px;

    padding: 30px;

}


/* ======================================
   TITLE
====================================== */

.page-title {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;

}


.page-title h2 {

    margin: 0;

    font-weight: 700;

}


/* ======================================
   FORM CARD
====================================== */

.form-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 3px 15px rgba(0,0,0,0.08);

    margin-bottom: 30px;

}


.form-card h4 {

    margin-bottom: 20px;

    font-weight: 600;

}


/* ======================================
   INPUT
====================================== */

.form-label {

    font-weight: 600;

}


.form-control,
.form-select {

    min-height: 45px;

}


/* ======================================
   BUTTON
====================================== */

.save-btn {

    background: #d89b3d;

    color: white;

    border: none;

    padding: 10px 25px;

    border-radius: 6px;

}


.save-btn:hover {

    background: #b87920;

    color: white;

}


/* ======================================
   TABLE CARD
====================================== */

.table-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 3px 15px rgba(0,0,0,0.08);

}


.table-card h4 {

    margin-bottom: 20px;

}


/* ======================================
   TABLE
====================================== */

.table th {

    background: #d89b3d;

    color: white;

    white-space: nowrap;

}


.table td {

    vertical-align: middle;

}


.service-image {

    width: 80px;

    height: 60px;

    object-fit: cover;

    border-radius: 7px;

}


/* ======================================
   CATEGORY BADGE
====================================== */

.category-badge {

    padding: 6px 12px;

    border-radius: 20px;

    background: #f3e0bd;

    color: #8b5b15;

    font-size: 13px;

}


/* ======================================
   ACTION BUTTONS
====================================== */

.edit-btn {

    background: #198754;

    color: white;

    border: none;

    padding: 7px 12px;

    border-radius: 5px;

    text-decoration: none;

}


.edit-btn:hover {

    background: #146c43;

    color: white;

}


.delete-btn {

    background: #dc3545;

    color: white;

    border: none;

    padding: 7px 12px;

    border-radius: 5px;

    text-decoration: none;

}


.delete-btn:hover {

    background: #bb2d3b;

    color: white;

}


/* ======================================
   CURRENT IMAGE
====================================== */

.current-image {

    width: 100px;

    height: 70px;

    object-fit: cover;

    border-radius: 7px;

    margin-top: 8px;

}


/* ======================================
   MOBILE
====================================== */

@media(max-width:900px) {

    .main-content {

        margin-left: 0;

        padding: 20px;

    }

}


</style>

</head>


<body>


<!-- =====================================
     MAIN CONTENT
====================================== -->

<div class="main-content">


    <!-- PAGE TITLE -->

    <div class="page-title">

        <h2>

            <i class="fa-solid fa-paw"></i>

            Manage Services

        </h2>

    </div>



    <!-- ==================================
         SUCCESS MESSAGE
    =================================== -->

    <?php if (isset($_GET['msg'])) { ?>


        <?php if ($_GET['msg'] == 'added') { ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-check"></i>

                Service added successfully.

            </div>

        <?php } ?>


        <?php if ($_GET['msg'] == 'updated') { ?>

            <div class="alert alert-success">

                <i class="fa-solid fa-check"></i>

                Service updated successfully.

            </div>

        <?php } ?>


        <?php if ($_GET['msg'] == 'deleted') { ?>

            <div class="alert alert-danger">

                <i class="fa-solid fa-trash"></i>

                Service deleted successfully.

            </div>

        <?php } ?>


    <?php } ?>



    <!-- ==================================
         ADD / UPDATE FORM
    =================================== -->

    <div class="form-card">


        <h4>

            <?php

            if ($edit_id > 0) {

                echo '<i class="fa-solid fa-pen"></i> Edit Service';

            } else {

                echo '<i class="fa-solid fa-plus"></i> Add Service';

            }

            ?>

        </h4>



        <form
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- Hidden ID -->

            <input
                type="hidden"
                name="service_id"
                value="<?php echo $edit_id; ?>"
            >


            <div class="row g-3">


                <!-- SERVICE NAME -->

                <div class="col-md-6">

                    <label class="form-label">

                        Service Name

                    </label>

                    <input
                        type="text"
                        name="service_name"
                        class="form-control"
                        placeholder="Enter service name"
                        value="<?php echo htmlspecialchars($edit_service_name); ?>"
                        required
                    >

                </div>



                <!-- CATEGORY -->

                <div class="col-md-6">

                    <label class="form-label">

                        Pet Category

                    </label>

                    <select
                        name="category"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>


                        <option
                            value="Dog"
                            <?php
                            if ($edit_category == "Dog")
                                echo "selected";
                            ?>
                        >
                            🐶 Dog
                        </option>


                        <option
                            value="Cat"
                            <?php
                            if ($edit_category == "Cat")
                                echo "selected";
                            ?>
                        >
                            🐱 Cat
                        </option>


                        <option
                            value="Bird"
                            <?php
                            if ($edit_category == "Bird")
                                echo "selected";
                            ?>
                        >
                            🐦 Bird
                        </option>


                        <option
                            value="Fish"
                            <?php
                            if ($edit_category == "Fish")
                                echo "selected";
                            ?>
                        >
                            🐟 Fish
                        </option>

                    </select>

                </div>



                <!-- PRICE -->

                <div class="col-md-6">

                    <label class="form-label">

                        Price

                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        placeholder="Enter price"
                        min="0"
                        step="0.01"
                        value="<?php echo htmlspecialchars($edit_price); ?>"
                        required
                    >

                </div>



                <!-- IMAGE -->

                <div class="col-md-6">

                    <label class="form-label">

                        Service Image

                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >


                    <?php

                    if (!empty($edit_image)) {

                    ?>

                        <img
                            src="../uploads/services/<?php echo htmlspecialchars($edit_image); ?>"
                            class="current-image"
                            alt="Current Image"
                        >

                    <?php

                    }

                    ?>

                </div>



                <!-- DESCRIPTION -->

                <div class="col-12">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Enter service description"
                        required
                    ><?php echo htmlspecialchars($edit_description); ?></textarea>

                </div>



                <!-- BUTTON -->

                <div class="col-12">


                    <button
                        type="submit"
                        name="save_service"
                        class="save-btn"
                    >


                        <?php

                        if ($edit_id > 0) {

                            ?>

                            <i class="fa-solid fa-rotate"></i>

                            Update Service

                            <?php

                        } else {

                            ?>

                            <i class="fa-solid fa-plus"></i>

                            Add Service

                            <?php

                        }

                        ?>


                    </button>


                    <?php

                    if ($edit_id > 0) {

                    ?>

                        <a
                            href="manage_services.php"
                            class="btn btn-secondary ms-2"
                        >

                            Cancel

                        </a>

                    <?php

                    }

                    ?>


                </div>


            </div>


        </form>


    </div>



    <!-- ==================================
         SERVICE LIST
    =================================== -->

    <div class="table-card">


        <h4>

            <i class="fa-solid fa-list"></i>

            All Services

        </h4>


        <div class="table-responsive">


            <table class="table table-bordered table-hover">


                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Image
                        </th>

                        <th>
                            Service Name
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (
                    $services_query &&
                    mysqli_num_rows($services_query) > 0
                ) {


                    while (
                        $service =
                        mysqli_fetch_assoc($services_query)
                    ) {

                ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?php
                            echo $service['id'];
                            ?>

                        </td>



                        <!-- IMAGE -->

                        <td>

                            <?php

                            if (!empty($service['image'])) {

                            ?>

                                <img
                                    src="../uploads/services/<?php echo htmlspecialchars($service['image']); ?>"
                                    class="service-image"
                                    alt="Service"
                                >

                            <?php

                            } else {

                                echo "No Image";

                            }

                            ?>

                        </td>



                        <!-- NAME -->

                        <td>

                            <strong>

                                <?php
                                echo htmlspecialchars(
                                    $service['service_name']
                                );
                                ?>

                            </strong>

                        </td>



                        <!-- CATEGORY -->

                        <td>

                            <span class="category-badge">

                                <?php
                                echo htmlspecialchars(
                                    $service['category']
                                );
                                ?>

                            </span>

                        </td>



                        <!-- PRICE -->

                        <td>

                            ₹<?php
                            echo number_format(
                                $service['price'],
                                2
                            );
                            ?>

                        </td>



                        <!-- DESCRIPTION -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $service['description']
                            );

                            ?>

                        </td>



                        <!-- ACTION -->

                        <td>

                            <a
                                href="manage_services.php?edit=<?php echo $service['id']; ?>"
                                class="edit-btn"
                            >

                                <i class="fa-solid fa-pen"></i>

                                Edit

                            </a>


                            <a
                                href="manage_services.php?delete=<?php echo $service['id']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this service?');"
                            >

                                <i class="fa-solid fa-trash"></i>

                                Delete

                            </a>

                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-4"
                        >

                            No services found.

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



<!-- Bootstrap JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>