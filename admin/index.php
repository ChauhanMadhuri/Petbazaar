<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['loggedinadmin']) && $_SESSION['loggedinadmin'] === true) {
    header("Location: dashbord.php");
} else {
    header("Location: login.php");
}
exit();
?>
