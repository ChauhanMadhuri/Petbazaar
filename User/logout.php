<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['loggedinuser']);
unset($_SESSION['userid']);
unset($_SESSION['email']);
unset($_SESSION['utype']);
unset($_SESSION['redirect_after_login']);

header("Location: index.php");
exit();
?>