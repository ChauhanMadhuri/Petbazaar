<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("conn.php");

// If already logged in as admin, redirect to dashboard
if (isset($_SESSION['loggedinadmin']) && $_SESSION['loggedinadmin'] === true) {
    header("Location: dashbord.php");
    exit();
}

$error_msg = "";

if (isset($_POST['admin_login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = mysqli_query(
        $conn,
        "SELECT * FROM ragistration
         WHERE email='$email'
         AND password='$password'"
    );

    if (!$sql) {
        $error_msg = "Database Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);

        // Check if user is an admin
        if (isset($row['utype']) && strtolower(trim($row['utype'])) === "admin") {
            $_SESSION['loggedinadmin'] = true;
            $_SESSION['admin_id'] = $row['Id'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_utype'] = 'admin';

            header("Location: dashbord.php");
            exit();
        } else {
            $error_msg = "Access Denied: Only Admin accounts are allowed to log in here.";
        }
    } else {
        $error_msg = "Invalid Admin Email or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PetBazaar</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #d89b3d, #f59e0b, #e3a33b);
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: rgba(216, 155, 61, 0.12);
            color: #d89b3d;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .login-card h2 {
            font-size: 26px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-card p.subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .error-alert {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i.field-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            font-size: 16px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-wrapper input:focus {
            border-color: #d89b3d;
            box-shadow: 0 0 0 3px rgba(216, 155, 61, 0.2);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #d89b3d 0%, #c4862b 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(216, 155, 61, 0.35);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #c4862b 0%, #b0741e 100%);
            box-shadow: 0 6px 16px rgba(216, 155, 61, 0.45);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-links {
            margin-top: 25px;
            font-size: 13px;
            color: #6b7280;
        }

        .footer-links a {
            color: #d89b3d;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2>Admin Portal</h2>
        <p class="subtitle">Please sign in with your administrative credentials</p>

        <?php if (!empty($error_msg)): ?>
            <div class="error-alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label for="email">Admin Email</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope field-icon"></i>
                    <input type="email" id="email" name="email" placeholder="admin@petbazaar.com" required autofocus>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock field-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Enter administrative password" required>
                </div>
            </div>

            <button type="submit" name="admin_login" class="btn-submit">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In to Dashboard
            </button>
        </form>

        <div class="footer-links">
            <span>Looking for customer portal? </span>
            <a href="../User/login.php">Go to User Login</a>
        </div>
    </div>

</body>
</html>
