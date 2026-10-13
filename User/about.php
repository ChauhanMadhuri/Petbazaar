<style>
  /*======================
        ABOUT
======================*/

.about{
    padding:80px 0;
}

.title{
    font-size:40px;
    font-weight:700;
    color:#222;
    margin-bottom:20px;
}

.center{
    text-align:center;
    margin-bottom:50px;
}

.sub-title{
    font-size:28px;
    color: #DEAD6F;;
    margin:25px 0 15px;
}

.about p{
    font-size:16px;
    color:#666;
    line-height:30px;
}

.about-img{
    width:100%;
    border-radius:15px;
    transition:.4s;
}

.about-img:hover{
    transform:scale(1.05);
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
    z-index:999;
    position:relative;
}
.page-header{
    background:#DEAD6F;
    color:white;
    text-align:center;
    padding:60px 20px;
}

.page-header h1{
    font-size:45px;
    margin-bottom:10px;
}

.page-header p{
    font-size:18px;
}

/*======================
      CORE VALUES
======================*/

.values{
    padding:80px 0;
    background:#f8f9fa;
}

.value-box{
    background:#fff;
    padding:35px 25px;
    text-align:center;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.4s;
}

.value-box:hover{
    transform:translateY(-10px);
}

.value-box h3{
    color: #DEAD6F;
    margin-bottom:15px;
}

.value-box p{
    color:#666;
}


/*======================
        SERVICE
======================*/

.service{
    padding:80px 0;
}

.service-box{
    text-align:center;
    padding:30px 20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.4s;
    background:#fff;
}

.service-box:hover{
    transform:translateY(-10px);
}

.service-box iconify-icon{
    font-size:55px;
    color: #DEAD6F;
}

.service-box h3{
    margin:20px 0 10px;
    font-size:24px;
}

.service-box p{
    color:#666;
}


/*======================
        VISION
======================*/

.vision{
    padding:80px 0;
    background:#fff;
}

.vision h2{
    margin-bottom:20px;
}

.vision p{
    color:#666;
    line-height:30px;
}

.vision-img{
    width:100%;
    border-radius:15px;
    transition:.4s;
}

.vision-img:hover{
     transform:scale(1.05);
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
    z-index:999;
    position:relative;
}


/*======================
      CATEGORY
======================*/

.category{
    padding:80px 0;
    background:#f8f9fa;
}

.cat-box{
    background:#fff;
    padding:30px;
    text-align:center;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.4s;
}

.cat-box:hover{
    transform:translateY(-10px);
}

.cat-box h1{
    font-size:60px;
}

.cat-box h4{
    margin:15px 0;
    color: #DEAD6F;

}

.cat-box p{
    color:#666;
}


/*======================
        TRUST
======================*/

.trust{
    padding:80px 0;
}

.trust-list{
    list-style:none;
    padding:0;
}

.trust-list li{
    background:#f8f9fa;
    margin-bottom:15px;
    padding:15px 20px;
    border-radius:10px;
    font-size:17px;
    transition:.3s;
}

.trust-list li:hover{
    background: #DEAD6F;
    color:#fff;
}

.trust-img{
    width:100%;
    border-radius:15px;
}
.trust-img:hover{
transform:scale(1.05);
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
    z-index:999;
    position:relative;
}


/*======================
          CTA
======================*/

.cta{
    padding:80px 0;
    background: #DEAD6F;
    text-align:center;
}

.cta h2{
    color:#fff;
    font-size:42px;
    margin-bottom:20px;
}

.cta p{
    color:#ddd;
    width:70%;
    margin:0 auto 30px;
    line-height:30px;
}

.shop-btn{
    display:inline-block;
    padding:14px 35px;
    background: #8F5C2E;
    color:#fff;
    text-decoration:none;
    border-radius:30px;
    font-size:18px;
    transition:.4s;
}

.shop-btn:hover{
    background:#fff;
    color: #DEAD6F;
}
  </style>

<?php
  include 'header.php';
?>
<!-- hrader-->
<div class="page-header">
    <h1>About Us</h1>
   
</div>

   <!-- About -->
<section class="about">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 text-center">
                <h2 class="title">About Petbazaar</h2>

                <p>
                    Petbazaar is a comprehensive online platform dedicated to pet lovers and their furry companions.
                    We are passionate about providing high-quality pet products, resources, and support to enhance
                    the lives of pets and their owners.
                </p>

                <h3 class="sub-title" color: #DEAD6F>Our Mission</h3>

                <p>
                    Our mission is to create a one-stop destination for all pet care needs, offering a curated
                    selection of products, expert advice, and a supportive community for pet owners.
                </p>
            </div>

            <div class="col-md-6">
                <img src="./images/about.png" class="about-img" alt="">
            </div>

        </div>
    </div>
</section>

<!-- Values -->

<section class="values">

    <div class="container">

        <h2 class="title center">Our Core Values</h2>

        <div class="row">

            <div class="col-md-4">
                <div class="value-box">
                    <h3>Quality</h3>
                    <p>We are committed to providing only the highest quality products for your pets.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="value-box">
                    <h3>Care</h3>
                    <p>Every pet deserves love, attention, and the best possible care.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="value-box">
                    <h3>Community</h3>
                    <p>We believe in building a supportive community of pet lovers.</p>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- Services -->

<section class="service">

    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <div class="service-box">
                    <iconify-icon icon="la:shopping-cart"></iconify-icon>
                    <h3>Free Delivery</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-box">
                    <iconify-icon icon="la:user-check"></iconify-icon>
                    <h3>Secure Payment</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-box">
                    <iconify-icon icon="la:tag"></iconify-icon>
                    <h3>Daily Offers</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-box">
                    <iconify-icon icon="la:award"></iconify-icon>
                    <h3>Quality Guarantee</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- Vision -->

<section class="vision">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6 ">
                <img src="images/vision.png" class="vision-img" alt="">
            </div>
   
            <div class="col-md-6">
                <h2 class="title">Our Vision</h2>

                <p>
                    At Petbazaar, we envision a world where every pet receives the love,
                    nutrition, and care they deserve.
                </p>

                <p>
                    We continuously work towards improving pet lives through innovation,
                    quality, and compassion.
                </p>
            </div>

        </div>

    </div>

</section>

<!-- Categories -->

<section class="category">

    <div class="container">

        <h2 class="title center">Explore Pet Categories</h2>

        <div class="row">

            <div class="col-md-3">
                <div class="cat-box">
                    <h1>🐶</h1>
                    <h4>Dogs</h4>
                    <p>Food, Toys & Accessories</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="cat-box">
                    <h1>🐱</h1>
                    <h4>Cats</h4>
                    <p>Healthy Food & Grooming</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="cat-box">
                    <h1>🐦</h1>
                    <h4>Birds</h4>
                    <p>Cages & Nutrition</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="cat-box">
                    <h1>🐠</h1>
                    <h4>Fish</h4>
                    <p>Aquarium & Care</p>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- Trust -->

<section class="trust">

    <div class="container">

        <h2 class="title center">Why Pet Owners Trust Us</h2>

        <div class="row align-items-center">

            <div class="col-md-6">

                <ul class="trust-list">
                    <li>✔ Premium Quality Products</li>
                    <li>✔ Affordable Prices</li>
                    <li>✔ Fast Home Delivery</li>
                    <li>✔ Secure Online Payment</li>
                    <li>✔ Friendly Customer Support</li>
                    <li>✔ Trusted by Thousands of Pet Lovers</li>
                </ul>

            </div>

            <div class="col-md-6">
                <img src="./images/trust.png" class="trust-img" alt="">
            </div>

        </div>

    </div>

</section>

<!-- CTA -->

<section class="cta">

    <div class="container">

        <h2>Because Every Pet Deserves the Best</h2>

        <p>
            Discover premium pet products designed to keep your furry friends
            happy, healthy, and full of life.
        </p>

        

    </div>

</section>
  <?php
   include 'footer.php';
  ?>