<?php
session_start();
include("conn.php");

$login_success = false;
$redirect_url = "";
$error_msg = "";

if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = $_GET['redirect'];
}

// If already logged in as a customer, redirect directly
if (isset($_SESSION['loggedinuser']) && $_SESSION['loggedinuser'] === true) {
    if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
        $dest = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        header("Location: $dest");
    } else {
        header("Location: index.php");
    }
    exit();
}

if (isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = mysqli_query(
        $conn,
        "SELECT * FROM ragistration
         WHERE email='$email'
         AND password='$password'"
    );

    if (!$sql)
    {
        die("SQL Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($sql) > 0)
    {
        $row = mysqli_fetch_assoc($sql);

        // Check if the account is an admin
        if (isset($row['utype']) && strtolower(trim($row['utype'])) === "admin")
        {
            $error_msg = "Admin accounts are not allowed to log in on the user side. Please use the Admin Login panel.";
        }
        else
        {
            // Set User session information
            $_SESSION['userid'] = $row['Id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['utype'] = $row['utype'] ?? 'user';
            $_SESSION['loggedinuser'] = true;

            $login_success = true;

            // Redirect back to Add to Cart or previous intended page if set
            if (isset($_POST['redirect']) && !empty($_POST['redirect']))
            {
                $redirect_url = $_POST['redirect'];
                unset($_SESSION['redirect_after_login']);
            }
            elseif (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login']))
            {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
            }
            elseif (isset($_GET['redirect']) && !empty($_GET['redirect']))
            {
                $redirect_url = $_GET['redirect'];
            }
            else
            {
                $redirect_url = "index.php";
            }
        }
    }
    else
    {
        $error_msg = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - PetBazaar</title>

    <!-- Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        .login-section {
            width: 100%;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 80px 15px;

            background: url("images/1.png") no-repeat center center;
            background-size: cover;
        }

        .login-box {
            width: 400px;
            padding: 40px;

            background: rgba(255,255,255,0.15);

            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);

            border: 1px solid rgba(255,255,255,.3);

            border-radius: 20px;

            box-shadow: 0 10px 30px rgba(0,0,0,.35);

            text-align: center;
        }

        .login-box h2 {
            color: #fff;
            font-size: 35px;
            margin-bottom: 10px;
        }

        .login-box p {
            color: green;
            margin-bottom: 25px;
        }

        .login-box input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;

            border: none;
            outline: none;
            border-radius: 8px;

            background: rgba(255,255,255,.20);

            color: #fff;
            font-size: 15px;
        }

        .login-box input::placeholder {
            color: #eee;
        }

        .login-box button {
            width: 100%;
            padding: 14px;
            margin-top: 15px;

            border: none;
            border-radius: 8px;

            background: #d89b3d;
            color: #fff;

            font-size: 17px;
            font-weight: bold;

            cursor: pointer;
            transition: .3s;
        }

        .login-box button:hover {
            background: #b97b1d;
        }

        .login-box a {
            color: #ffd27d;
            text-decoration: none;
            font-weight: bold;
        }

        .login-box a:hover {
            text-decoration: underline;
        }

        .error {
            color: red !important;
            margin-bottom: 15px !important;
        }

        @media(max-width:480px) {

            .login-box {
                width: 100%;
                padding: 30px 20px;
            }

            .login-box h2 {
                font-size: 28px;
            }

        }

    </style>

</head>

<body>

<div class="login-section">

    <div class="login-box">

        <h2>Login</h2>

        <p>Welcome To Pet Product Store</p>

        <?php
        if (!empty($error_msg))
        {
            echo "<p class='error'>$error_msg</p>";
        }
        ?>

        <form method="POST">
            <?php if (isset($_GET['redirect']) || isset($_SESSION['redirect_after_login'])): ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect'] ?? $_SESSION['redirect_after_login'] ?? ''); ?>">
            <?php endif; ?>

            <input
                type="text"
                name="email"
                placeholder="Enter email id"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

            <button type="submit" name="login">
                Login
            </button>

        </form>

        <br>

        <p>
            Don't have an account?
            <a href="reg.php">Register</a>
        </p>

    </div>

</div>


<?php if ($login_success) { ?>

<script>

    confetti({
        particleCount: 3000,
        spread: 360,
        origin: {
            y: 0.7
        }
    });

    setTimeout(function()
    {
        window.location.href = "<?php echo $redirect_url; ?>";

    }, 1500);

</script>

<?php } ?>

</body>

</html>