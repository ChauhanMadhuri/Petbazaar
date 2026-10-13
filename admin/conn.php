<?php

$conn = mysqli_connect("localhost","root","","pet_bazaar");

if(!$conn)
{
    die("Connection Failed: " . mysqli_connect_error());
}

// Auto-check and create required columns in bill, orders, addtocart if missing
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `subtotal` decimal(10,2) DEFAULT 0");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `shipping` decimal(10,2) DEFAULT 0");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `order_status` varchar(50) DEFAULT 'Pending'");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `payment_status` varchar(50) DEFAULT 'Pending'");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `cancelled_by` varchar(50) DEFAULT NULL");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `cancelled_at` datetime DEFAULT NULL");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT ''");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `state` varchar(100) DEFAULT ''");
@mysqli_query($conn, "ALTER TABLE `bill` ADD COLUMN IF NOT EXISTS `pincode` varchar(20) DEFAULT ''");

@mysqli_query($conn, "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `product_id` int(11) DEFAULT 0");
@mysqli_query($conn, "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `subtotal` decimal(10,2) DEFAULT 0");
@mysqli_query($conn, "ALTER TABLE `addtocart` ADD COLUMN IF NOT EXISTS `p_id` int(11) DEFAULT 0");
?>