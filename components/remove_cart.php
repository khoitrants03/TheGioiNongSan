<?php
session_start();
include 'connect.php';

$response = ['success' => false];

if (isset($_SESSION['user_id']) && isset($_POST['cart_id'])) {
   $user_id = $_SESSION['user_id'];
   $cart_id = $_POST['cart_id'];

   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
   if ($delete_cart->execute([$cart_id, $user_id])) {
      $response['success'] = true;
   }
}

header('Content-Type: application/json');
echo json_encode($response);
?>