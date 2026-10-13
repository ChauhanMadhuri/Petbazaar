<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Bazaar</title>

    <link rel="stylesheet" href="style.css">
</head>
<style>
    *{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

/*==============================
VIDEO
===============================*/

.video-banner{

width:100%;

height:100vh;

overflow:hidden;

}

.video-banner video{

width:100%;

height:100%;

object-fit:cover;

display:block;

}


/*==============================
TITLE
===============================*/

.category-section{

padding:80px 8%;

background:#fffdf7;

}

.title{

text-align:center;

margin-bottom:60px;

}

.title h2{

font-size:40px;

color:#E3A33B;

margin-bottom:10px;

}

.title p{

font-size:18px;

color:#777;

}


/*==============================
ROW
===============================*/

.category-row{

display:flex;

justify-content:space-between;

gap:25px;

flex-wrap:wrap;

}


/*==============================
CARD
===============================*/

.card{

width:23%;

background:#fff;

border-radius:20px;

overflow:hidden;

box-shadow:0 8px 25px rgba(0,0,0,.12);

transition:.4s;

cursor:pointer;

}

.card:hover{

transform:translateY(-10px);

}

.card img{

width:100%;

height:250px;

object-fit:cover;

display:block;

}

.card h3{

padding:20px;

text-align:center;

font-size:25px;

color:#444;

}
@media(max-width:992px){

.card{

width:48%;

}

}

@media(max-width:576px){

.card{

width:100%;

}

.title h2{

font-size:32px;

}

.video-banner{

height:60vh;

}

}
</style>
<body>

<!-- Header -->
<?php include("header.php"); ?>


<!-- ================= VIDEO ================= -->

<section class="video-banner">

    <video autoplay muted loop>
        <source src="images/dog.mp4" type="video/mp4">
    </video>

</section>



<!-- ================= CATEGORY ================= -->

<section class="category-section">

    <div class="title">

        <h2>Shop By Pet</h2>

        <p>Choose Your Favourite Pet</p>

    </div>


    <div class="category-row">

        <div class="card" onclick="window.location.href='subcategory.php?category=Dog&sub=Bad';">

            <img src="images/dog_bed.jfif" alt="Bed">

            <h3>Bed</h3>

        </div>

        <div class="card" onclick="window.location.href='subcategory.php?category=Dog&sub=Cloth';">

            <img src="images/dog_cloth.jfif" alt="Cloth">

            <h3>Cloth</h3>

        </div>

        <div class="card" onclick="window.location.href='subcategory.php?category=Dog&sub=Food';">

            <img src="images/dog_food.jfif" alt="Food">

            <h3>Food</h3>

        </div>

        <div class="card" onclick="window.location.href='subcategory.php?category=Dog&sub=Grooming';">

            <img src="images/dog_grroming.jfif" alt="Grooming">

            <h3>Grooming</h3>

        </div>

    </div>

</section>


<?php include("footer.php"); ?>

</body>
</html>