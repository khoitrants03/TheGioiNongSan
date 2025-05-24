<?php
//$db_name = 'mysql:host=127.0.0.1:4306;dbname=quan_li_bv;charset=utf8mb4'; 
$db_name = 'mysql:host=127.0.0.1;dbname=qlii;charset=utf8mb4';
$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);

// Create farmer_profiles table if not exists
$create_farmer_profiles = "CREATE TABLE IF NOT EXISTS `farmer_profiles` (
    `id` int(100) NOT NULL AUTO_INCREMENT,
    `user_id` int(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `location` varchar(255) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `email` varchar(100) NOT NULL,
    `description` text,
    `image` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$conn->exec($create_farmer_profiles);
?>
