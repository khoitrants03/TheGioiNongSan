<?php
session_start();
include 'connect.php';

$response = ['count' => 0];

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $select_cart->execute([$user_id]);
   $response['count'] = $select_cart->rowCount();
}

header('Content-Type: application/json');
echo json_encode($response);
?>