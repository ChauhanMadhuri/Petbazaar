<?php
include("header.php");
include("conn.php");

if(isset($_POST['ragistration']))
{
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    $sql = "INSERT INTO ragistration(fullname,email,password,phone,gender,city,address)
            VALUES('$fullname','$email','$password','$phone','$gender','$city','$address')";

    if(mysqli_query($conn, $sql))
    {
        echo "<script>alert('Registration Successful');
               
                window.location='login.php';
    </script>";
    }
    else
    {
        echo mysqli_error($conn);
    }
}
?>
<link rel="stylesheet" href="css/reg.css">
<section class="registration-section">
    <div class="registration-container">
        <!-- Left Side -->
        <div class="left-side">
            <img src="./images/bg_r.png" class="pet-img" alt="Pet">
            <h2>Join PetBazar Family 🐾</h2>
            <p>
                Create your account and enjoy the best shopping experience
                for your lovely pets.
            </p>
            <div class="feature-box">
                <div class="feature">🐶 Premium Dog Products</div>
                <div class="feature">🐱 Cat Accessories</div>
                <div class="feature">🐦 Bird Food</div>
                <div class="feature">🐠 Fish Care</div>
            </div>
        </div>
        <!-- Right Side -->
        <div class="right-side">
            <h2>Create Account</h2>
            <form method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter Full Name" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter Phone Number" required>
                </div> <br>
                <div class="input-group">
                    <label>Gender</label>
                    <div class="radio-group"> <br>
                        <label>
                            <input type="radio" name="gender" value="Male" required>
                            Male
                        </label>
                        <label>
                            <input type="radio" name="gender" value="Female">
                            Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="Other">
                            Other
                        </label>
                    </div>
                </div>
                <div class="input-group">
                    <label>City</label>
                    <select name="city" required>
                        <option selected disabled hidden>Select City</option>
                        <option value="Ahmedabad">Ahmedabad</option>
                        <option value="Rajkot">Rajkot</option>
                        <option value="Surat">Surat</option>
                        <option value="Vadodara">Vadodara</option>
                        <option value="Jamnagar">Jamnagar</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Address</label>
                    <textarea name="address" rows="4" placeholder="Enter Address" required></textarea>
                </div>
                <button type="submit" name="ragistration" class="register-btn">
                    Register Now
                </button>
            </form>
        </div>
    </div>
</section>
<?php include("footer.php"); ?>