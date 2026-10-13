<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['loggedinadmin']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_utype']);

header("Location: login.php");
exit();
?>