<?php
include("auth_check.php");
include("conn.php");


/* =====================================================
   ADD SUB CATEGORY
===================================================== */

if (isset($_POST['add_sub_catagory'])) {

    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );

    $subcat = mysqli_real_escape_string(
        $conn,
        $_POST['sub_cat']
    );


    /* DUPLICATE CHECK */

    $check = mysqli_query(
        $conn,
        "SELECT * FROM sub_cat
         WHERE category_name='$category'
         AND sub_cat='$subcat'"
    );


    if (mysqli_num_rows($check) > 0) {

        $error = "Sub Category Already Exists!";

    } else {

        $qry = "INSERT INTO sub_cat
                (category_name, sub_cat)
                VALUES
                ('$category','$subcat')";


        if (mysqli_query($conn, $qry)) {

            header("Location: add_sub_catagory.php");
            exit();

        } else {

            $error = "Something Went Wrong!";

        }

    }

}


/* =====================================================
   DELETE SUB CATEGORY
===================================================== */

if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];


    mysqli_query(
        $conn,
        "DELETE FROM sub_cat
         WHERE id='$id'"
    );


    header("Location: add_sub_catagory.php");
    exit();

}


/* =====================================================
   EDIT SUB CATEGORY
===================================================== */

$edit_id = "";
$edit_category = "";
$edit_subcat = "";


if (isset($_GET['edit'])) {

    $edit_id = (int)$_GET['edit'];


    $query = mysqli_query(
        $conn,
        "SELECT * FROM sub_cat
         WHERE id='$edit_id'"
    );


    if ($query && mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);

        $edit_category = $row['category_name'];
        $edit_subcat = $row['sub_cat'];

    } else {

        $edit_id = "";

    }

}


/* =====================================================
   UPDATE SUB CATEGORY
===================================================== */

if (isset($_POST['update_sub_category'])) {

    $id = (int)$_POST['edit_id'];


    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );


    $subcat = mysqli_real_escape_string(
        $conn,
        $_POST['sub_cat']
    );


    /* DUPLICATE CHECK */

    $check = mysqli_query(
        $conn,
        "SELECT * FROM sub_cat
         WHERE category_name='$category'
         AND sub_cat='$subcat'
         AND id!='$id'"
    );


    if (mysqli_num_rows($check) > 0) {

        $error = "Sub Category Already Exists!";

    } else {


        $query = "UPDATE sub_cat SET
                    category_name='$category',
                    sub_cat='$subcat'
                  WHERE id='$id'";


        if (mysqli_query($conn, $query)) {

            header("Location: add_sub_catagory.php");
            exit();

        } else {

            $error = "Something Went Wrong!";

        }

    }

}


/* =====================================================
   FETCH ALL SUB CATEGORY
===================================================== */

$result = mysqli_query(
    $conn,
    "SELECT * FROM sub_cat
     ORDER BY id DESC"
);


/* =====================================================
   SIDEBAR
===================================================== */

include("slide_bar.php");

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Manage Sub Category</title>


<link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
>


<style>

/* =====================================================
   BASIC
===================================================== */

* {

    margin: 0;
    padding: 0;
    box-sizing: border-box;

    font-family: 'Poppins', sans-serif;

}


body {

    background: #f8f8f8;

    min-height: 100vh;

}


/* =====================================================
   MAIN
===================================================== */

.main {

    margin-left: 236px;

    padding: 35px;

}


/* =====================================================
   HEADER
===================================================== */

.page-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;

}


.page-header h2 {

    font-size: 30px;

    color: #333;

}


.page-header p {

    color: #777;

    margin-top: 5px;

}


/* =====================================================
   ADD BUTTON
===================================================== */

.add-sub-btn {

    background: #d89b3d;

    color: white;

    border: none;

    padding: 12px 20px;

    border-radius: 8px;

    font-size: 15px;

    font-weight: 600;

    cursor: pointer;

    transition: .3s;

}


.add-sub-btn:hover {

    background: #bf8427;

    transform: translateY(-2px);

}


/* =====================================================
   FORM BOX
===================================================== */

.add-box {

    background: white;

    padding: 25px;

    border-radius: 15px;

    margin-bottom: 25px;

    box-shadow: 0 5px 20px rgba(0,0,0,.08);

    display: none;

}


.add-box.show {

    display: block;

}


.add-box h3 {

    color: #4b3a25;

    margin-bottom: 20px;

    font-size: 22px;

}


/* =====================================================
   FORM ROW
===================================================== */

.form-row {

    display: flex;

    gap: 15px;

}


.form-row select,
.form-row input {

    flex: 1;

    height: 48px;

    padding: 0 15px;

    border: 2px solid #ddb87b;

    border-radius: 9px;

    outline: none;

    font-size: 15px;

    background: white;

}


.form-row select:focus,
.form-row input:focus {

    border-color: #d89b3d;

    box-shadow: 0 0 8px rgba(216,155,61,.25);

}


/* =====================================================
   BUTTONS
===================================================== */

.save-btn {

    background: #d89b3d;

    color: white;

    border: none;

    padding: 0 25px;

    border-radius: 9px;

    font-weight: 600;

    cursor: pointer;

}


.save-btn:hover {

    background: #bf8427;

}


.cancel-btn {

    background: #7b5b32;

    color: white;

    border: none;

    padding: 0 22px;

    border-radius: 9px;

    cursor: pointer;

    font-weight: 600;

}


.cancel-btn:hover {

    background: #604521;

}


/* =====================================================
   TABLE BOX
===================================================== */

.table-box {

    background: white;

    padding: 20px;

    border-radius: 15px;

    box-shadow: 0 5px 20px rgba(0,0,0,.08);

    overflow-x: auto;

}


/* =====================================================
   TABLE
===================================================== */

table {

    width: 100%;

    border-collapse: collapse;

}


th {

    background: #d89b3d;

    color: white;

    padding: 15px;

    text-align: left;

    font-size: 15px;

}


td {

    padding: 15px;

    border-bottom: 1px solid #eee;

    color: #444;

}


tr:hover td {

    background: #fffaf2;

}


/* =====================================================
   ID
===================================================== */

.id-circle {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    background: #fff1d9;

    color: #c17f20;

    display: flex;

    justify-content: center;

    align-items: center;

    font-weight: 600;

}


/* =====================================================
   CATEGORY
===================================================== */

.category-name {

    font-weight: 600;

    color: #555;

}


/* =====================================================
   SUB CATEGORY
===================================================== */

.sub-category-name {

    font-weight: 600;

    color: #333;

}


/* =====================================================
   ACTION
===================================================== */

.edit-btn,
.delete-btn {

    display: inline-block;

    padding: 7px 13px;

    border-radius: 7px;

    color: white;

    text-decoration: none;

    font-size: 13px;

    margin-right: 5px;

}


.edit-btn {

    background: #198754;

}


.edit-btn:hover {

    background: #157347;

}


.delete-btn {

    background: #dc3545;

}


.delete-btn:hover {

    background: #bb2d3b;

}


/* =====================================================
   ERROR
===================================================== */

.error {

    margin-top: 15px;

    padding: 12px;

    background: #f8d7da;

    color: #721c24;

    border-radius: 8px;

    font-weight: 600;

}


/* =====================================================
   EMPTY
===================================================== */

.empty {

    text-align: center;

    padding: 35px;

    color: #777;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:768px) {

    .main {

        margin-left: 0;

        padding: 20px;

    }


    .page-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }


    .form-row {

        flex-direction: column;

    }


    .save-btn,
    .cancel-btn {

        height: 48px;

        padding: 0 20px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     HEADER
===================================================== -->

<div class="page-header">

    <div>

        <h2>
            Manage Sub Category
        </h2>

        <p>
            Add, Edit and Delete PetBazaar Sub Categories
        </p>

    </div>


    <button
        type="button"
        class="add-sub-btn"
        onclick="openSubCategoryForm()"
    >

        + Add Sub Category

    </button>

</div>


<!-- =====================================================
     ADD / EDIT FORM
===================================================== -->

<div
    class="add-box <?php

        if (!empty($edit_id)) {

            echo 'show';

        }

    ?>"
    id="subCategoryForm"
>


<h3>

<?php

if (!empty($edit_id)) {

    echo "Edit Sub Category";

} else {

    echo "Add New Sub Category";

}

?>

</h3>


<?php

/* =====================================================
   EDIT FORM
===================================================== */

if (!empty($edit_id)) {

?>


<form method="POST">


<div class="form-row">


<!-- CATEGORY -->

<select
    name="category"
    required
>

    <option value="">
        Choose Category
    </option>


    <?php

    $categories = mysqli_query(
        $conn,
        "SELECT * FROM category
         ORDER BY category ASC"
    );


    while ($cat = mysqli_fetch_assoc($categories)) {

    ?>

        <option
            value="<?php
                echo htmlspecialchars(
                    $cat['category']
                );
            ?>"
            <?php

            if (
                $cat['category']
                ==
                $edit_category
            ) {

                echo "selected";

            }

            ?>
        >

            <?php

            echo htmlspecialchars(
                $cat['category']
            );

            ?>

        </option>

    <?php

    }

    ?>

</select>


<!-- SUB CATEGORY -->

<input
    type="text"
    name="sub_cat"
    value="<?php

        echo htmlspecialchars(
            $edit_subcat
        );

    ?>"
    placeholder="Enter Sub Category Name"
    required
>


<!-- ID -->

<input
    type="hidden"
    name="edit_id"
    value="<?php
        echo $edit_id;
    ?>"
>


<!-- UPDATE -->

<button
    type="submit"
    name="update_sub_category"
    class="save-btn"
>

    Update

</button>


<!-- CANCEL -->

<button
    type="button"
    class="cancel-btn"
    onclick="window.location='add_sub_catagory.php'"
>

    Cancel

</button>


</div>

</form>


<?php

} else {


/* =====================================================
   ADD FORM
===================================================== */

?>


<form method="POST">


<div class="form-row">


<!-- CATEGORY -->

<select
    name="category"
    required
>

    <option value="">
        Choose Category
    </option>


    <?php

    $categories = mysqli_query(
        $conn,
        "SELECT * FROM category
         ORDER BY category ASC"
    );


    while ($cat = mysqli_fetch_assoc($categories)) {

    ?>

        <option
            value="<?php
                echo htmlspecialchars(
                    $cat['category']
                );
            ?>"
        >

            <?php

            echo htmlspecialchars(
                $cat['category']
            );

            ?>

        </option>

    <?php

    }

    ?>

</select>


<!-- SUB CATEGORY -->

<input
    type="text"
    name="sub_cat"
    placeholder="Enter Sub Category Name"
    required
>


<!-- ADD -->

<button
    type="submit"
    name="add_sub_catagory"
    class="save-btn"
>

    Add

</button>


<!-- CANCEL -->

<button
    type="button"
    class="cancel-btn"
    onclick="closeSubCategoryForm()"
>

    Cancel

</button>


</div>

</form>


<?php

}


if (isset($error)) {

    echo "<div class='error'>";
    echo htmlspecialchars($error);
    echo "</div>";

}

?>


</div>


<!-- =====================================================
     SUB CATEGORY TABLE
===================================================== -->

<div class="table-box">


<table>


<thead>

<tr>

    <th>
        ID
    </th>

    <th>
        Category
    </th>

    <th>
        Sub Category
    </th>

    <th>
        Action
    </th>

</tr>

</thead>


<tbody>


<?php

if (
    $result &&
    mysqli_num_rows($result) > 0
) {

    while (
        $row = mysqli_fetch_assoc($result)
    ) {

?>


<tr>


<!-- ID -->

<td>

    <div class="id-circle">

        <?php

        echo $row['id'];

        ?>

    </div>

</td>


<!-- CATEGORY -->

<td>

    <div class="category-name">

        <?php

        echo htmlspecialchars(
            $row['category_name']
        );

        ?>

    </div>

</td>


<!-- SUB CATEGORY -->

<td>

    <div class="sub-category-name">

        <?php

        echo htmlspecialchars(
            $row['sub_cat']
        );

        ?>

    </div>

</td>


<!-- ACTION -->

<td>


<!-- EDIT -->

<a
    href="add_sub_catagory.php?edit=<?php
        echo $row['id'];
    ?>"
    class="edit-btn"
>

    Edit

</a>


<!-- DELETE -->

<a
    href="add_sub_catagory.php?delete=<?php
        echo $row['id'];
    ?>"
    class="delete-btn"
    onclick="
        return confirm(
            'Are you sure you want to delete this sub category?'
        );
    "
>

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
        colspan="4"
        class="empty"
    >

        No Sub Category Found

    </td>

</tr>


<?php

}

?>



</tbody>

</table>


</div>


</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

function openSubCategoryForm() {

    document
        .getElementById("subCategoryForm")
        .classList.add("show");

}


function closeSubCategoryForm() {

    document
        .getElementById("subCategoryForm")
        .classList.remove("show");

}

</script>


</body>

</html>