<?php
session_start();
include "conn.php";
include "header.php";

$message = "";

if (isset($_POST['submit_feedback'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $sql = "INSERT INTO feedback (name, email, rating, feedback)
            VALUES ('$name', '$email', '$rating', '$feedback')";

    if (mysqli_query($conn, $sql)) {
        $message = "Thank you! Your feedback has been submitted successfully. ❤️";
    } else {
        $message = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PetBazaar - Feedback</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fffaf3;
        }

        .feedback-section {
            min-height: 100vh;
            padding: 70px 20px;
            background: linear-gradient(135deg, #fff8ed, #fff);
        }

        .feedback-container {
            max-width: 1100px;
            margin: auto;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.10);
        }

        /* LEFT SIDE */

        .feedback-left {
            background: linear-gradient(145deg, #d89b3d, #f0b85a);
            padding: 55px 40px;
            color: white;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feedback-left::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            top: -60px;
            right: -60px;
        }

        .feedback-left::after {
            content: "";
            position: absolute;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255,255,255,0.10);
            bottom: -40px;
            left: -40px;
        }

        .pet-icon {
            width: 75px;
            height: 75px;
            border-radius: 20px;
            background: white;
            color: #d89b3d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin-bottom: 25px;
        }

        .feedback-left h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .feedback-left p {
            font-size: 17px;
            line-height: 1.7;
            opacity: 0.95;
        }

        .pet-points {
            margin-top: 35px;
        }

        .pet-point {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            font-size: 15px;
        }

        .pet-point i {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.20);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        /* RIGHT SIDE */

        .feedback-right {
            padding: 55px 45px;
        }

        .feedback-title {
            font-size: 30px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }


        .feedback-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 13px 15px;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #d89b3d;
            box-shadow: 0 0 0 3px rgba(216,155,61,0.12);
        }

        textarea.form-control {
            resize: none;
            height: 130px;
        }

        /* RATING */

        .rating-box {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 7px;
            margin-bottom: 25px;
        }

        .rating-box input {
            display: none;
        }

        .rating-box label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
            transition: 0.2s;
        }

        .rating-box label:hover,
        .rating-box label:hover ~ label,
        .rating-box input:checked ~ label {
            color: #ffb400;
        }

        /* BUTTON */

        .submit-btn {
            width: 100%;
            border: none;
            background: #d89b3d;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #bd8129;
            transform: translateY(-2px);
        }

        .success-message {
            background: #e9f8ee;
            color: #218838;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* MOBILE */

        @media(max-width: 767px) {

            .feedback-section {
                padding: 30px 15px;
            }

            .feedback-left {
                padding: 40px 25px;
            }

            .feedback-left h1 {
                font-size: 32px;
            }

            .feedback-right {
                padding: 40px 25px;
            }

            .feedback-title {
                font-size: 26px;
            }
        }

    </style>
</head>

<body>

<section class="feedback-section">

    <div class="feedback-container">

        <div class="row g-0">

            <!-- LEFT -->

            <div class="col-lg-5">

                <div class="feedback-left">

                    <div class="pet-icon">
                        <i class="fa-solid fa-paw"></i>
                    </div>

                    <h1>We Love Your Feedback!</h1>

                    <p>
                        Your feedback helps PetBazaar become a better
                        place for you and your lovely pets.
                    </p>

                    <div class="pet-points">

                        <div class="pet-point">
                            <i class="fa-solid fa-heart"></i>
                            <span>Your opinion matters to us</span>
                        </div>

                        <div class="pet-point">
                            <i class="fa-solid fa-paw"></i>
                            <span>Help us improve our services</span>
                        </div>

                        <div class="pet-point">
                            <i class="fa-solid fa-star"></i>
                            <span>Share your PetBazaar experience</span>
                        </div>

                    </div>

                </div>

            </div>


            <!-- RIGHT -->

            <div class="col-lg-7">

                <div class="feedback-right">

                    <h2 class="feedback-title">
                        Share Your Experience
                    </h2>

                    <p class="feedback-subtitle">
                        Tell us what you think about PetBazaar.
                    </p>

                    <?php if ($message != "") { ?>

                        <div class="success-message">
                            <i class="fa-solid fa-circle-check"></i>
                            <?php echo $message; ?>
                        </div>

                    <?php } ?>


                    <form method="POST">

                        <!-- NAME -->

                        <div class="mb-3">

                            <label class="form-label">
                                Your Name
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Enter your name"
                                   required>

                        </div>


                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Enter your email"
                                   required>

                        </div>


                        <!-- RATING -->

                        <div class="mb-3">

                            <label class="form-label">
                                How was your experience?
                            </label>

                            <div class="rating-box">

                                <input type="radio"
                                       name="rating"
                                       id="star5"
                                       value="5"
                                       required>

                                <label for="star5">
                                    <i class="fa-solid fa-star"></i>
                                </label>


                                <input type="radio"
                                       name="rating"
                                       id="star4"
                                       value="4">

                                <label for="star4">
                                    <i class="fa-solid fa-star"></i>
                                </label>


                                <input type="radio"
                                       name="rating"
                                       id="star3"
                                       value="3">

                                <label for="star3">
                                    <i class="fa-solid fa-star"></i>
                                </label>


                                <input type="radio"
                                       name="rating"
                                       id="star2"
                                       value="2">

                                <label for="star2">
                                    <i class="fa-solid fa-star"></i>
                                </label>


                                <input type="radio"
                                       name="rating"
                                       id="star1"
                                       value="1">

                                <label for="star1">
                                    <i class="fa-solid fa-star"></i>
                                </label>

                            </div>

                        </div>


                        <!-- FEEDBACK -->

                        <div class="mb-4">

                            <label class="form-label">
                                Your Feedback
                            </label>

                            <textarea
                                name="feedback"
                                class="form-control"
                                placeholder="Write your feedback here..."
                                required></textarea>

                        </div>


                        <!-- SUBMIT -->

                        <button type="submit"
                                name="submit_feedback"
                                class="submit-btn">

                            <i class="fa-solid fa-paper-plane"></i>
                            &nbsp; Submit Feedback

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>
<?php
 include "footer.php";
?>
</body>
</html>