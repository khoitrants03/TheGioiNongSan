<?php
session_start();
include 'connect.php';

$response = ['success' => false];

if (isset($_SESSION['user_id']) && isset($_POST['cart_id']) && isset($_POST['qty'])) {
   $user_id = $_SESSION['user_id'];
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];

   // Validate quantity
   if ($qty > 0 && $qty <= 99) {
      $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
      if ($update_cart->execute([$qty, $cart_id, $user_id])) {
         $response['success'] = true;
      }
   }
}

header('Content-Type: application/json');
echo json_encode($response);
?>