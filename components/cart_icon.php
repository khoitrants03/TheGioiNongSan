<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

// Get cart count for the current user
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $select_cart->execute([$user_id]);
   $cart_count = $select_cart->rowCount();
}
?>

<a href="cart.php" class="cart-icon">
   <i class="fas fa-shopping-cart"></i>
   <span class="cart-count">(<?= $cart_count; ?>)</span>
</a>
