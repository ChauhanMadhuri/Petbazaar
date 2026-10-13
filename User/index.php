<?php
 include 'header.php';
 
?>

  <!-- ================= HERO SLIDER ================= -->

<section class="hero-section">

    <div class="container">

        <div class="swiper main-swiper">

            <div class="swiper-wrapper">

                <!-- DOG -->
                <div class="swiper-slide">

                    <div class="hero-content">

                        <div class="hero-text">

                            <span class="hero-small">
                                🐾 LOVE • CARE • HAPPINESS
                            </span>

                            <h1>
                                Best destination for
                                <span>your pets</span>
                            </h1>

                            <p>
                                Everything your pet needs, all in one place.
                                <br>
                                Happy pets, happy life!
                            </p>

                            <a href="#" class="hero-btn">
                                Shop Now →
                            </a>

                        </div>

                        <div class="hero-image">

                            <img src="images/dog.png" alt="Dog">

                        </div>

                    </div>

                </div>


                <!-- CAT -->
                <div class="swiper-slide">

                    <div class="hero-content">

                        <div class="hero-text">

                            <span class="hero-small">
                                🐾 LOVE • CARE • HAPPINESS
                            </span>

                            <h1>
                                Everything special
                                <span>for your cat</span>
                            </h1>

                            <p>
                                Delicious food, playful toys and
                                everything your cat loves.
                            </p>

                            <a href="#" class="hero-btn">
                                Shop Now →
                            </a>

                        </div>

                        <div class="hero-image">

                            <img src="images/cat.png" alt="Cat">

                        </div>

                    </div>

                </div>


                <!-- BIRD -->
                <div class="swiper-slide">

                    <div class="hero-content">

                        <div class="hero-text">

                            <span class="hero-small">
                                🐾 LOVE • CARE • HAPPINESS
                            </span>

                            <h1>
                                Happy life for
                                <span>your birds</span>
                            </h1>

                            <p>
                                Premium food, toys and accessories
                                for your feathered friends.
                            </p>

                            <a href="#" class="hero-btn">
                                Shop Now →
                            </a>

                        </div>

                        <div class="hero-image">

                            <img src="images/bird.png" alt="Bird">

                        </div>

                    </div>

                </div>


                <!-- FISH -->
                <div class="swiper-slide">

                    <div class="hero-content">

                        <div class="hero-text">

                            <span class="hero-small">
                                🐾 LOVE • CARE • HAPPINESS
                            </span>

                            <h1>
                                Make your fish
                                <span>happy & healthy</span>
                            </h1>

                            <p>
                                Quality food, tanks and accessories
                                for your aquatic friends.
                            </p>

                            <a href="#" class="hero-btn">
                                Shop Now →
                            </a>

                        </div>

                        <div class="hero-image">

                            <img src="images/fish.png" alt="Fish">

                        </div>

                    </div>

                </div>

            </div>

            <div class="swiper-pagination"></div>

        </div>

    </div>

</section>



<!-- ================= CATEGORIES ================= -->

<section class="category-section">

    <div class="container">

        <div class="category-box">

            <!-- DOG -->

            <a href="dog.php" class="category-item">

                <div class="category-img">

                    <img src="images/dog.png" alt="Dog">

                </div>

                <div class="category-content">

                    <h3>Dog Shop</h3>

                    <p>
                        Food, Grooming
                        <br>
                        & Accessories
                    </p>

                </div>

                <span class="category-arrow">→</span>

            </a>


            <!-- CAT -->

            <a href="cat.php" class="category-item">

                <div class="category-img">

                    <img src="images/cat.png" alt="Cat">

                </div>

                <div class="category-content">

                    <h3>Cat Shop</h3>

                   
                    <p>
                        Food, Grooming
                        <br>
                        & Accessories
                    </p>

                </div>

                <span class="category-arrow">→</span>

            </a>


            <!-- BIRD -->

            <a href="bird.php" class="category-item">

                <div class="category-img">

                    <img src="images/bird.png" alt="Bird">

                </div>

                <div class="category-content">

                    <h3>Bird Shop</h3>

                    <p>
                        Food, 
                        & Accessories
                    </p>

                </div>

                <span class="category-arrow">→</span>

            </a>


            <!-- FISH -->

            <a href="fish.php" class="category-item">

                <div class="category-img">

                    <img src="images/fish.png" alt="Fish">

                </div>

                <div class="category-content">

                    <h3>Fish Shop</h3>

                    <p>
                        Food, Tanks,
                        <br>
                        & Accessories
                    </p>

                </div>

                <span class="category-arrow">→</span>

            </a>

        </div>

    </div>

</section>



<!-- ================= SPECIAL PRODUCTS ================= -->

<section class="special-section">

    <div class="container">

        <div class="section-heading">

            <span>🐾 HANDPICKED FOR YOUR PET</span>

            <h2>
                PetBazaar <b>Special Picks</b> 🐾
            </h2>

            <p>
                Handpicked products for your furry, feathered & aquatic friends
            </p>

        </div>


        <div class="row g-4">

            <?php

            include("conn.php");

            $special = mysqli_query($conn,"
                SELECT * FROM product
                ORDER BY id DESC
                LIMIT 4
            ");

            while($product = mysqli_fetch_assoc($special))
            {

            ?>

            <div class="col-lg-3 col-md-6">

                <div class="product-card">

                    <span class="new-badge">
                        NEW
                    </span>

                    <div class="product-img">
                        <?php
                        $p_img_file = $product['image'] ?? '';
                        $p_img_src = "upload/" . htmlspecialchars($p_img_file);
                        if (!empty($p_img_file) && !file_exists("upload/" . $p_img_file) && file_exists("images/" . $p_img_file)) {
                            $p_img_src = "images/" . htmlspecialchars($p_img_file);
                        }
                        ?>
                        <img
                            src="<?php echo $p_img_src; ?>"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            onerror="this.src='images/item1.jpg'"
                        >

                    </div>


                    <div class="product-info">

                        <small>
                            <?php echo $product['category']; ?>
                        </small>

                        <h3>
                            <?php echo $product['product_name']; ?>
                        </h3>

                        <div class="product-price">

                            ₹ <?php echo $product['price']; ?>

                        </div>


                        <a
                            href="single_product.php?id=<?php echo $product['id']; ?>"
                            class="product-link"
                        >
                            View Product
                            <span>→</span>
                        </a>

                    </div>

                </div>

            </div>

            <?php

            }

            ?>

        </div>

    </div>

</section>



<!-- ================= SERVICE STRIP ================= -->

<section class="service-strip">

    <div class="container">

        <div class="row">

            <div class="col-lg-3 col-md-6">

                <div class="service-item">

                    <div class="service-icon">
                        🚚
                    </div>

                    <div>

                        <h4>Free Delivery</h4>

                        <p>On orders above ₹499</p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="service-item">

                    <div class="service-icon">
                        🛡️
                    </div>

                    <div>

                        <h4>100% Secure Payment</h4>

                        <p>Safe & trusted payments</p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="service-item">

                    <div class="service-icon">
                        🏷️
                    </div>

                    <div>

                        <h4>Daily Offers</h4>

                        <p>Best deals every day</p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="service-item">

                    <div class="service-icon">
                        🏆
                    </div>

                    <div>

                        <h4>Quality Guarantee</h4>

                        <p>Only the best for your pets</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= OFFER BANNERS ================= -->

<section class="offer-section">

    <div class="container">

        <div class="row g-4">


            <!-- OFFER 1 -->

            <div class="col-lg-6">

                <div class="offer-card orange-offer">

                    <div class="offer-content">

                        <span>
                            GIVE YOUR PET
                        </span>

                        <h2>
                            The Best Care ❤️
                        </h2>

                        <p>
                            Up to <b>20% OFF</b> on selected products
                        </p>

                        <a href="#">
                            Shop Now
                        </a>

                    </div>


                    <div class="offer-pet">
                        🐶
                    </div>


                    <div class="discount-circle">

                        <small>UP TO</small>

                        <strong>20%</strong>

                        <span>OFF</span>

                    </div>

                </div>

            </div>



            <!-- OFFER 2 -->

            <div class="col-lg-6">

                <div class="offer-card green-offer">

                    <div class="offer-content">

                        <span>
                            HEALTHY PETS
                        </span>

                        <h2>
                            Happy Life ❤️
                        </h2>

                        <p>
                            Premium food, toys & care
                            for your pets
                        </p>

                        <a href="#">
                            Explore Now
                        </a>

                    </div>


                    <div class="offer-pet">
                        🐱
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= WHY PETBAZAAR ================= -->

<section class="why-section">

    <div class="container">

        <div class="section-heading">

            <h2>
                Why Pet Parents Choose PetBazaar? 🐾
            </h2>

        </div>


        <div class="row g-4">


            <div class="col-lg-3 col-md-6">

                <div class="why-card">

                    <div class="why-icon">
                        💎
                    </div>

                    <h3>
                        Premium Quality
                    </h3>

                    <p>
                        Carefully selected high quality
                        products for your pets.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="why-card">

                    <div class="why-icon">
                        ❤️
                    </div>

                    <h3>
                        Pet First
                    </h3>

                    <p>
                        Your pet's happiness
                        is our priority.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="why-card">

                    <div class="why-icon">
                        🎧
                    </div>

                    <h3>
                        24/7 Support
                    </h3>

                    <p>
                        We are always here
                        to help you.
                    </p>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="why-card">

                    <div class="why-icon">
                        👍
                    </div>

                    <h3>
                        Trusted by Thousands
                    </h3>

                    <p>
                        Join thousands of
                        happy pet parents.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ================= PET CARE ================= -->

<section class="pet-care">

    <div class="container">

        <div class="section-heading">

            <span>
                🐾 EVERYTHING YOUR PET NEEDS
            </span>

            <h2>
                One Place. <b>Every Pet.</b>
            </h2>

        </div>


        <div class="care-grid">


            <div class="care-box">

                <div class="care-emoji">
                    🥣
                </div>

                <h3>
                    Healthy Food
                </h3>

                <p>
                    Nutritious meals and treats
                    for happy pets.
                </p>

                <a href="#">
                    Explore →
                </a>

            </div>


            <div class="care-box">

                <div class="care-emoji">
                    👕
                </div>

                <h3>
                   Pet Fashion
                </h3>

                <p>
                   Cute and comfortable clothes for your lovely pets
                </p>

                <a href="#">
                    Explore →
                </a>

            </div>


            <div class="care-box">

                <div class="care-emoji">
                    🛁
                </div>

                <h3>
                    Grooming
                </h3>

                <p>
                    Keep your pets clean,
                    fresh and happy.
                </p>

                <a href="#">
                    Explore →
                </a>

            </div>


            <div class="care-box">

                <div class="care-emoji">
                    🏠
                </div>

                <h3>
                    Pet Living
                </h3>

                <p>
                    Stylish and useful essentials to make your pet’s space comfortable
                </p>

                <a href="#">
                    Explore →
                </a>

            </div>


        </div>

    </div>

</section>



<style>

/* ================= HERO ================= */

.hero-section{
    background:#f9f3ec;
    padding:15px 0 0;
}

.main-swiper{
    overflow:hidden;
}

.hero-content{
    min-height:450px;
    display:flex;
    align-items:center;
    padding:40px 7%;
    background:#f9f3ec;
    border-radius:25px;
}

.hero-text{
    width:52%;
    z-index:2;
}

.hero-small{
    color:#d87924;
    font-weight:bold;
    letter-spacing:2px;
    font-size:14px;
}

.hero-text h1{
    font-size:58px;
    line-height:1.08;
    color:#292522;
    margin:18px 0;
    font-weight:700;
}

.hero-text h1 span{
    color:#d87924;
}

.hero-text p{
    color:#655c55;
    font-size:18px;
    line-height:28px;
}

.hero-btn{
    display:inline-block;
    margin-top:20px;
    background:#ed741d;
    color:white;
    text-decoration:none;
    padding:13px 28px;
    border-radius:8px;
    font-weight:bold;
    transition:.3s;
}

.hero-btn:hover{
    background:#c85c10;
    color:white;
    transform:translateY(-2px);
}

.hero-image{
    width:48%;
    height:390px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.hero-image img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.main-swiper .swiper-pagination{
    bottom:15px;
}

.main-swiper .swiper-pagination-bullet-active{
    background:#ed741d;
}


/* ================= CATEGORY ================= */

.category-section{
    padding:20px 0 40px;
    background:#fff;
}

.category-box{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    background:#fff;
    border:1px solid #eee;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 18px rgba(0,0,0,.04);
}

.category-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:20px;
    text-decoration:none;
    position:relative;
    border-right:1px solid #eee;
    transition:.3s;
}

.category-item:last-child{
    border-right:none;
}

.category-item:hover{
    background:#fff9f2;
}

.category-img{
    width:110px;
    height:90px;
    flex-shrink:0;
    display:flex;
    align-items:center;
    justify-content:center;
}

.category-img img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.category-content h3{
    color:#292522;
    font-size:18px;
    margin:0 0 5px;
}

.category-content p{
    color:#777;
    font-size:13px;
    line-height:19px;
    margin:0;
}

.category-arrow{
    position:absolute;
    right:15px;
    bottom:13px;
    width:28px;
    height:28px;
    border-radius:50%;
    background:#fff0df;
    color:#ed741d;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
}


/* ================= HEADING ================= */

.section-heading{
    text-align:center;
    margin-bottom:35px;
}

.section-heading span{
    color:#d87924;
    font-size:13px;
    font-weight:bold;
    letter-spacing:1.5px;
}

.section-heading h2{
    color:#292522;
    font-size:35px;
    margin:8px 0;
}

.section-heading h2 b{
    color:#d87924;
}

.section-heading p{
    color:#777;
}


/* ================= PRODUCTS ================= */

.special-section{
    background:#fff;
    padding:50px 0;
}

.product-card{
    background:#fff;
    border:1px solid #eee;
    border-radius:18px;
    overflow:hidden;
    position:relative;
    height:100%;
    transition:.3s;
}

.product-card:hover{
    transform:translateY(-7px);
    box-shadow:0 12px 30px rgba(0,0,0,.08);
}

.new-badge{
    position:absolute;
    top:12px;
    left:12px;
    background:#ed741d;
    color:white;
    padding:5px 10px;
    border-radius:20px;
    font-size:10px;
    font-weight:bold;
    z-index:2;
}

.product-img{
    height:220px;
    background:#fffaf5;
    padding:15px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.product-img img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.product-info{
    padding:18px;
}

.product-info small{
    color:#d87924;
    text-transform:uppercase;
    font-weight:bold;
}

.product-info h3{
    font-size:18px;
    color:#292522;
    margin:8px 0 12px;
    min-height:45px;
}

.product-price{
    color:#e45f16;
    font-size:20px;
    font-weight:bold;
    margin-bottom:12px;
}

.product-link{
    color:#292522;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
}

.product-link span{
    color:#ed741d;
    margin-left:7px;
}


/* ================= SERVICE ================= */

.service-strip{
    background:#fff7ed;
    padding:22px 0;
    border-top:1px solid #f1e3d4;
    border-bottom:1px solid #f1e3d4;
}

.service-item{
    display:flex;
    align-items:center;
    gap:15px;
    padding:10px 20px;
    border-right:1px solid #eadcca;
}

.service-item h4{
    margin:0;
    font-size:15px;
    color:#292522;
}

.service-item p{
    margin:4px 0 0;
    font-size:12px;
    color:#777;
}

.service-icon{
    font-size:30px;
}


/* ================= OFFER ================= */

.offer-section{
    padding:45px 0;
    background:#fff;
}

.offer-card{
    min-height:250px;
    border-radius:20px;
    padding:35px;
    position:relative;
    overflow:hidden;
    display:flex;
    align-items:center;
}

.orange-offer{
    background:#ffe7cf;
}

.green-offer{
    background:#eaf3dd;
}

.offer-content{
    position:relative;
    z-index:2;
    width:65%;
}

.offer-content span{
    font-weight:bold;
    font-size:13px;
    letter-spacing:1px;
}

.orange-offer .offer-content span,
.orange-offer .offer-content h2{
    color:#d96d17;
}

.green-offer .offer-content span,
.green-offer .offer-content h2{
    color:#27632b;
}

.offer-content h2{
    font-size:32px;
    margin:8px 0;
}

.offer-content p{
    color:#555;
    font-size:15px;
}

.offer-content a{
    display:inline-block;
    padding:10px 20px;
    border-radius:7px;
    color:white;
    text-decoration:none;
    font-weight:bold;
    margin-top:5px;
}

.orange-offer .offer-content a{
    background:#ed741d;
}

.green-offer .offer-content a{
    background:#4c8c2b;
}

.offer-pet{
    position:absolute;
    right:35px;
    bottom:-15px;
    font-size:150px;
}

.discount-circle{
    position:absolute;
    right:25px;
    top:20px;
    width:100px;
    height:100px;
    border-radius:50%;
    background:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    z-index:3;
    border:3px solid #d87924;
}

.discount-circle small{
    font-size:9px;
}

.discount-circle strong{
    color:#d87924;
    font-size:27px;
}

.discount-circle span{
    font-size:10px;
}


/* ================= WHY ================= */

.why-section{
    padding:55px 0;
    background:#fffaf5;
}

.why-card{
    background:#fff;
    border:1px solid #eee6dd;
    border-radius:18px;
    padding:25px;
    min-height:190px;
    transition:.3s;
}

.why-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.why-icon{
    width:55px;
    height:55px;
    border-radius:50%;
    background:#fff1df;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:27px;
    margin-bottom:15px;
}

.why-card h3{
    color:#292522;
    font-size:18px;
}

.why-card p{
    color:#777;
    font-size:14px;
    line-height:22px;
}


/* ================= PET CARE ================= */

.pet-care{
    padding:55px 0;
    background:#fff;
}

.care-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.care-box{
    padding:30px 25px;
    border-radius:20px;
    background:#fff8ef;
    border:1px solid #f0e0ce;
    transition:.3s;
}

.care-box:hover{
    transform:translateY(-7px);
}

.care-emoji{
    font-size:38px;
    margin-bottom:15px;
}

.care-box h3{
    color:#292522;
}

.care-box p{
    color:#777;
    line-height:23px;
}

.care-box a{
    color:#d87924;
    font-weight:bold;
    text-decoration:none;
}


/* ================= RESPONSIVE ================= */

@media(max-width:992px){

    .hero-text h1{
        font-size:42px;
    }

    .category-box{
        grid-template-columns:repeat(2,1fr);
    }

    .category-item:nth-child(2){
        border-right:none;
    }

    .category-item{
        border-bottom:1px solid #eee;
    }

    .care-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .service-item{
        border-right:none;
        margin-bottom:10px;
    }

}


@media(max-width:768px){

    .hero-content{
        flex-direction:column;
        min-height:auto;
        padding:35px 20px 55px;
        text-align:center;
    }

    .hero-text{
        width:100%;
    }

    .hero-text h1{
        font-size:35px;
    }

    .hero-image{
        width:100%;
        height:280px;
        order:-1;
    }

    .category-box{
        grid-template-columns:1fr;
    }

    .category-item{
        border-right:none;
    }

    .category-item:last-child{
        border-bottom:none;
    }

    .offer-card{
        min-height:280px;
    }

    .offer-content{
        width:100%;
    }

    .offer-pet{
        opacity:.25;
    }

}


@media(max-width:576px){

    .hero-text h1{
        font-size:30px;
    }

    .hero-image{
        height:220px;
    }

    .section-heading h2{
        font-size:28px;
    }

    .care-grid{
        grid-template-columns:1fr;
    }

    .offer-content h2{
        font-size:27px;
    }

    .discount-circle{
        transform:scale(.8);
    }

}

</style>



<!-- ================= SWIPER JS ================= -->

<script>

var mainSwiper = new Swiper(".main-swiper", {

    loop:true,

    autoplay:{
        delay:3500,
        disableOnInteraction:false
    },

    pagination:{
        el:".main-swiper .swiper-pagination",
        clickable:true
    },

    effect:"slide",

});

</script>
  <?php
  include 'footer.php';
  ?>
</body>

</html>