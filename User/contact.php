<?php include("header.php"); ?>


<style>
  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f5f5f5;
}

/* Header */

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

/* Contact Section */

.contact-section{
    width:90%;
    margin:60px auto;
    background:white;
    padding:40px;
    border-radius:10px;
}

.contact-title{
    text-align:center;
    margin-bottom:40px;
}

.contact-title h2{
    font-size:40px;
    color:white;
    margin-bottom:10px;
}

.contact-title p{
    color:#666;
    line-height:28px;
}

/* Two Column */

.contact-content{
    display:flex;
    justify-content:space-between;
    gap:40px;
}

/* Form */

.contact-form{
    width:65%;
}

.contact-form input,
.contact-form textarea{
    width:100%;
    padding:15px;
    margin-bottom:20px;
    border:none;
    background:#f2f2f2;
    border-radius:6px;
    font-size:16px;
}

.contact-form textarea{
    height:180px;
    resize:none;
}

.contact-form button{
    background:#DEAD6F;
    color:white;
    border:none;
    padding:15px 35px;
    border-radius:5px;
    font-size:18px;
    cursor:pointer;
}

.contact-form button:hover{
    background:#DEAD6F;
}
.btn-box{
  text-align: center;
}

/* Contact Info */

.contact-info{
    width:35%;
}

.info-box{
    display:flex;
    align-items:center;
    background:#f8f8f8;
    padding:20px;
    margin-bottom:20px;
    border-radius:8px;
}

.info-box i{
    font-size:30px;
    color:#DEAD6F;
    margin-right:20px;
}

.info-box h3{
    margin-bottom:8px;
}

.info-box p{
    color:#666;
}

/* Map */

.map{
    margin-top:40px;
}

.map iframe{
    width:100%;
    height:400px;
    border:none;
    border-radius:10px;
}
  </style>
<!-- Header -->
<div class="page-header">
    <h1>Contact Us</h1>
   
</div>

<!-- Contact Section -->
<div class="contact-section">

    <div class="contact-title">
        <h2>Get In Touch</h2>
        <p>
            We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.
        </p>
    </div>

    <div class="contact-content">

        <!-- Left Form -->
        <div class="contact-form">

            <form action="" method="post">

                <input type="text" name="name" placeholder="Your Name">

                <input type="email" name="email" placeholder="Your Email">

                <textarea name="message" placeholder="Your Message"></textarea>
            <div class="btn-box">
                <button type="submit" name="submit">Send Message</button>
            </div>
            </form>

        </div>

        <!-- Right Side -->
        <div class="contact-info">

            <div class="info-box">
                <i class="fa-solid fa-location-dot"></i>

                <div>
                    <h3>Address</h3>
                    <p>Junagadh, Gujarat, India</p>
                </div>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-envelope"></i>

                <div>
                    <h3>Email</h3>
                    <p>petbazaar@gmail.com</p>
                </div>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-phone"></i>

                <div>
                    <h3>Phone</h3>
                    <p>+91 9876543210</p>
                </div>
            </div>

        </div>

    </div>

    <!-- Map -->

    <div class="map">

        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3700.0744370511696!2d70.4586895758328!3d21.523682985949007!2m3!1f0!2f0!3f0!1m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a32a611f2a4a3f9%3A0x255dc0e5bdf746a2!2sJunagadh%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1694063985865!5m2!1sen!2sin"
            allowfullscreen=""
            loading="lazy">
        </iframe>

    </div>

</div>
<?php
include 'conn.php';

if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $query = "INSERT INTO contact(name,email,message)
              VALUES('$name','$email','$message')";

    $result = mysqli_query($conn, $query);

    if($result)
    {
        echo "<script>
                alert('Your message has been sent successfully.');
                window.location='contact.php';
              </script>";
    }
    else
    {
        echo "<script>
                alert('Message not sent.');
              </script>";
    }
}
?>
<?php
 include ("footer.php");  
?>