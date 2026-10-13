<?php
include("auth_check.php");
include("conn.php");


/* =====================================================
   DELETE CATEGORY
===================================================== */

if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $delete = mysqli_query(
        $conn,
        "DELETE FROM category WHERE id='$id'"
    );

    header("Location: add_catagory.php");
    exit();
}


/* =====================================================
   ADD CATEGORY
===================================================== */

if (isset($_POST['add_catagory'])) {

    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );

    $query = "INSERT INTO category(category)
              VALUES('$category')";

    if (mysqli_query($conn, $query)) {

        header("Location: add_catagory.php");
        exit();

    } else {

        $error = "Something Went Wrong.";

    }
}


/* =====================================================
   EDIT CATEGORY
===================================================== */

$edit_id = "";
$edit_category = "";


if (isset($_GET['edit'])) {

    $edit_id = (int)$_GET['edit'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM category WHERE id='$edit_id'"
    );

    if ($query && mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);

        $edit_category = $row['category'];

    } else {

        $edit_id = "";

    }
}


/* =====================================================
   UPDATE CATEGORY
===================================================== */

if (isset($_POST['update_category'])) {

    $id = (int)$_POST['edit_id'];

    $category = mysqli_real_escape_string(
        $conn,
        $_POST['category']
    );

    $query = "UPDATE category
              SET category='$category'
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {

        header("Location: add_catagory.php");
        exit();

    } else {

        $error = "Category Update Failed.";

    }
}


/* =====================================================
   FETCH CATEGORY
===================================================== */

$result = mysqli_query(
    $conn,
    "SELECT * FROM category ORDER BY id DESC"
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

<title>Manage Category</title>


<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet"
>


<style>


*{

    margin:0;
    padding:0;
    box-sizing:border-box;

    font-family:'Poppins',sans-serif;

}


body{

    background:#f8f8f8;

}


/* =========================
   MAIN
========================= */

.main{

    margin-left:236px;

    padding:35px;

}


/* =========================
   HEADER
========================= */

.page-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:25px;

}


.page-header h2{

    font-size:30px;

    color:#333;

}


.page-header p{

    color:#777;

    margin-top:5px;

}


/* =========================
   ADD BUTTON
========================= */

.add-category-btn{

    background:#d89b3d;

    color:white;

    border:none;

    padding:12px 20px;

    border-radius:8px;

    font-size:15px;

    font-weight:600;

    cursor:pointer;

    transition:.3s;

}


.add-category-btn:hover{

    background:#bf8427;

    transform:translateY(-2px);

}


/* =========================
   FORM
========================= */

.add-box{

    background:white;

    padding:25px;

    border-radius:15px;

    margin-bottom:25px;

    box-shadow:0 5px 20px rgba(0,0,0,.08);

    display:none;

}


.add-box.show{

    display:block;

}


.add-box h3{

    color:#4b3a25;

    margin-bottom:18px;

    font-size:22px;

}


.form-row{

    display:flex;

    gap:15px;

}


.form-row input{

    flex:1;

    height:48px;

    padding:0 15px;

    border:2px solid #ddb87b;

    border-radius:9px;

    outline:none;

    font-size:15px;

}


.form-row input:focus{

    border-color:#d89b3d;

    box-shadow:0 0 8px
    rgba(216,155,61,.25);

}


/* =========================
   FORM BUTTONS
========================= */

.save-btn{

    background:#d89b3d;

    color:white;

    border:none;

    padding:0 25px;

    border-radius:9px;

    font-weight:600;

    cursor:pointer;

}


.save-btn:hover{

    background:#bf8427;

}


.cancel-btn{

    background:#7b5b32;

    color:white;

    border:none;

    padding:0 22px;

    border-radius:9px;

    cursor:pointer;

    font-weight:600;

}


.cancel-btn:hover{

    background:#604521;

}


/* =========================
   TABLE
========================= */

.table-box{

    background:white;

    padding:20px;

    border-radius:15px;

    box-shadow:0 5px 20px rgba(0,0,0,.08);

    overflow-x:auto;

}


table{

    width:100%;

    border-collapse:collapse;

}


th{

    background:#d89b3d;

    color:white;

    padding:15px;

    text-align:left;

}


td{

    padding:15px;

    border-bottom:1px solid #eee;

}


tr:hover td{

    background:#fffaf2;

}


/* =========================
   ID
========================= */

.id-circle{

    width:38px;

    height:38px;

    border-radius:50%;

    background:#fff1d9;

    color:#c17f20;

    display:flex;

    justify-content:center;

    align-items:center;

    font-weight:600;

}


/* =========================
   CATEGORY
========================= */

.category-name{

    font-weight:600;

    color:#333;

}


/* =========================
   ACTION
========================= */

.edit-btn,
.delete-btn{

    display:inline-block;

    padding:8px 14px;

    border-radius:7px;

    color:white;

    text-decoration:none;

    font-size:13px;

    margin-right:5px;

    border:none;

    cursor:pointer;

}


.edit-btn{

    background:#198754;

}


.edit-btn:hover{

    background:#157347;

}


.delete-btn{

    background:#dc3545;

}


.delete-btn:hover{

    background:#bb2d3b;

}


/* =========================
   ERROR
========================= */

.error{

    margin-top:15px;

    padding:12px;

    background:#f8d7da;

    color:#721c24;

    border-radius:8px;

    text-align:center;

}


/* =========================
   MOBILE
========================= */

@media(max-width:768px){

    .main{

        margin-left:0;

        padding:20px;

    }

    .page-header{

        flex-direction:column;

        align-items:flex-start;

        gap:15px;

    }

    .form-row{

        flex-direction:column;

    }

    .save-btn,
    .cancel-btn{

        height:48px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =========================
     HEADER
========================= -->

<div class="page-header">


    <div>

        <h2>Manage Category</h2>

        <p>
            Add, Edit and Delete Categories
        </p>

    </div>


    <button
        type="button"
        class="add-category-btn"
        onclick="openCategoryForm()"
    >

        + Add Category

    </button>


</div>



<!-- =========================
     ADD / EDIT FORM
========================= -->

<div
    class="add-box
    <?php

    if (!empty($edit_id)) {

        echo 'show';

    }

    ?>"
    id="categoryForm"
>


<h3>

<?php

if (!empty($edit_id)) {

    echo "Edit Category";

} else {

    echo "Add New Category";

}

?>

</h3>



<?php

/* =========================
   EDIT FORM
========================= */

if (!empty($edit_id)) {

?>


<form method="POST">


<div class="form-row">


    <input
        type="text"
        name="category"
        value="<?php

        echo htmlspecialchars(
            $edit_category
        );

        ?>"
        placeholder="Enter Category Name"
        required
    >


    <input
        type="hidden"
        name="edit_id"
        value="<?php

        echo $edit_id;

        ?>"
    >


    <button
        type="submit"
        name="update_category"
        class="save-btn"
    >

        Update Category

    </button>


    <button
        type="button"
        class="cancel-btn"
        onclick="window.location='add_catagory.php'"
    >

        Cancel

    </button>


</div>


</form>


<?php

} else {


/* =========================
   ADD FORM
========================= */

?>


<form method="POST">


<div class="form-row">


    <input
        type="text"
        name="category"
        placeholder="Enter Category Name"
        required
    >


    <button
        type="submit"
        name="add_catagory"
        class="save-btn"
    >

        Add Category

    </button>


    <button
        type="button"
        class="cancel-btn"
        onclick="closeCategoryForm()"
    >

        Cancel

    </button>


</div>


</form>


<?php

}


if (isset($error)) {

    echo "<div class='error'>$error</div>";

}

?>


</div>



<!-- =========================
     CATEGORY TABLE
========================= -->

<div class="table-box">


<table>


<thead>

<tr>

    <th>ID</th>

    <th>Category Name</th>

    <th>Action</th>

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
            $row['category']
        );

        ?>

    </div>

</td>



<!-- ACTION -->

<td>


<!-- EDIT -->

<a
    href="add_catagory.php?edit=<?php
        echo $row['id'];
    ?>"
    class="edit-btn"
>

    Edit

</a>



<!-- DELETE -->

<a
    href="add_catagory.php?delete=<?php
        echo $row['id'];
    ?>"
    class="delete-btn"

    onclick="
        return confirm(
            'Are you sure you want to delete this category?'
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
    colspan="3"
    style="
        text-align:center;
        padding:30px;
        color:#777;
    "
>

    No Category Found

</td>

</tr>


<?php

}

?>


</tbody>

</table>


</div>


</div>



<script>


function openCategoryForm(){

    document
        .getElementById("categoryForm")
        .classList.add("show");

}


function closeCategoryForm(){

    document
        .getElementById("categoryForm")
        .classList.remove("show");

}


</script>


</body>

</html>