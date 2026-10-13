<?php

session_start();
include("conn.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $delete = mysqli_query($conn, "DELETE FROM addtocart WHERE c_id='$id'");

    if($delete)
    {
        header("Location: cart.php");
        exit();
    }
    else
    {
        die("Delete Error: " . mysqli_error($conn));
    }
}

?>