<?php
include("auth_check.php");
include("conn.php");
include("slide_bar.php");

if(isset($_POST['submit']))
{
    $category = mysqli_real_escape_string($conn,$_POST['category']);
    $sub_category = mysqli_real_escape_string($conn,$_POST['sub_category']);
    $product_name = mysqli_real_escape_string($conn,$_POST['product_name']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $price = mysqli_real_escape_string($conn,$_POST['price']);
    $qty = mysqli_real_escape_string($conn,$_POST['qty']);

    // Image Upload
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $folder = "../User/upload/" . $image;
    if (!file_exists("../User/upload/")) {
        @mkdir("../User/upload/", 0777, true);
    }

    move_uploaded_file($tmp, $folder);

    $insert = mysqli_query($conn,"INSERT INTO product
    (category,sub_category,product_name,description,price,qty,image)
    VALUES
    ('$category','$sub_category','$product_name','$description','$price','$qty','$image')");

    if($insert)
    {
        echo "<script>alert('Product Added Successfully');</script>";
    }
    else
    {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Product</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#f5f5f5;
}

.main-content{
margin-left:230px;
padding:40px;
}

.product-box{
max-width:900px;
margin:auto;
background:#fff;
padding:35px;
border-radius:15px;
box-shadow:0 8px 25px rgba(0,0,0,.1);
}

.product-box h2{
text-align:center;
margin-bottom:30px;
font-size:32px;
color:#E39A2E;
}

.row{
display:flex;
gap:20px;
}

.input-box{
width:100%;
margin-bottom:20px;
}

.input-box label{
display:block;
margin-bottom:8px;
font-weight:bold;
}

.input-box input,
.input-box select,
.input-box textarea{
width:100%;
padding:12px;
border:1px solid #ddd;
border-radius:8px;
outline:none;
font-size:15px;
}

.input-box textarea{
resize:none;
}

.preview{
width:220px;
height:220px;
margin:20px auto;
border:2px dashed #ccc;
border-radius:10px;
overflow:hidden;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
}

button{
width:100%;
padding:14px;
background:#E39A2E;
color:#fff;
border:none;
border-radius:8px;
font-size:18px;
cursor:pointer;
}

button:hover{
background:#c9821e;
}

@media(max-width:768px){

.main-content{
margin-left:0;
padding:20px;
}

.row{
flex-direction:column;
}

.preview{
width:180px;
height:180px;
}

}

</style>

</head>

<body>

<div class="main-content">

<div class="product-box">

<h2>Add New Product</h2>

<form action="" method="POST" enctype="multipart/form-data">

<div class="row">

<div class="input-box">

<label>Category</label>

<select name="category" id="category" required>

<option value="">Select Category</option>

<?php

$q=mysqli_query($conn,"SELECT * FROM category ORDER BY category ASC");

while($r=mysqli_fetch_assoc($q))
{

?>

<option value="<?php echo $r['category']; ?>">

<?php echo $r['category']; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="input-box">

<label>Sub Category</label>

<select name="sub_category" id="sub_category" required>

<option value="">Select Sub Category</option>

</select>

</div>

</div>

<div class="input-box">

<label>Product Name</label>

<input type="text" name="product_name" required>

</div>

<div class="input-box">

<label>Description</label>

<textarea name="description" rows="5" required></textarea>

</div>

<div class="row">

<div class="input-box">

<label>Price</label>

<input type="number" name="price" required>

</div>

<div class="input-box">

<label>Quantity</label>

<input type="number" name="qty" required>

</div>

</div>

<div class="input-box">

<label>Product Image</label>

<input type="file" name="image" id="image" required>

</div>

<div class="preview">

<img id="previewImg">

</div>

<button type="submit" name="submit">

Add Product

</button>

</form>

</div>

</div>

<script>

document.getElementById("category").addEventListener("change", function(){

    var category = this.value;

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "get_product.php", true);

    xhr.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
    );

    xhr.onreadystatechange = function(){

        if(xhr.readyState == 4 && xhr.status == 200)
        {
            document.getElementById("sub_category").innerHTML =
                xhr.responseText;
        }

    };

    xhr.send("category=" + encodeURIComponent(category));

});

</script>
</body>
</html>