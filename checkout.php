<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

if (isset($_POST['order'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Đơn hàng đã được đặt thành công!';
   } else {
      $message[] = 'Giỏ hàng của bạn trống!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thanh toán</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <section class="checkout">
      <h1 class="title">Thanh toán</h1>
      <form action="" method="post">
         <div class="cart-items">
            <h3>Sản phẩm đã chọn</h3>
            <?php
            $grand_total = 0;
            $cart_items[] = '';
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                  <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price"><?= $fetch_cart['price']; ?> VNĐ x <?= $fetch_cart['quantity']; ?></span></p>
            <?php
               }
            } else {
               echo '<p class="empty">Giỏ hàng trống!</p>';
            }
            ?>
            <p class="grand-total"><span>Tổng cộng:</span><span class="price"><?= $grand_total; ?> VNĐ</span></p>
            <a href="cart.php" class="btn">Xem giỏ hàng</a>
         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

         <div class="user-info">
            <h3>Thông tin thanh toán</h3>
            <p><i class="fas fa-user"></i><span><?= $fetch_profile['name']; ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
            <input type="text" name="address" required placeholder="Nhập địa chỉ giao hàng" class="box" maxlength="50">
            <select name="method" class="box" required>
               <option value="" disabled selected>Chọn phương thức thanh toán</option>
               <option value="cash on delivery">Thanh toán khi nhận hàng</option>
               <option value="credit card">Thẻ tín dụng</option>
               <option value="paypal">Paypal</option>
            </select>
            <input type="submit" value="Đặt hàng" class="btn" name="order">
         </div>
      </form>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html>
