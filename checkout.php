<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

// Fetch user profile information
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

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
            $cart_items = array();
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  $subtotal = $fetch_cart['price'] * $fetch_cart['quantity'];
                  $grand_total += $subtotal;
            ?>
                  <div class="cart-item">
                     <p><span class="name"><?= $fetch_cart['name']; ?></span></p>
                     <p class="price-details">
                        <span class="price"><?= number_format($fetch_cart['price']); ?> VNĐ</span>
                        <span class="quantity">x <?= $fetch_cart['quantity']; ?></span>
                        <span class="subtotal">= <?= number_format($subtotal); ?> VNĐ</span>
                     </p>
                  </div>
            <?php
               }
               $total_products = implode($cart_items);
            ?>
               <div class="order-summary">
                  <h3>Tổng đơn hàng</h3>
                  <div class="summary-item">
                     <span>Tổng tiền hàng:</span>
                     <span><?= number_format($grand_total); ?> VNĐ</span>
                  </div>
                  <div class="summary-item">
                     <span>Phí vận chuyển:</span>
                     <span>30,000 VNĐ</span>
                  </div>
                  <div class="summary-item total">
                     <span>Tổng thanh toán:</span>
                     <span><?= number_format($grand_total + 30000); ?> VNĐ</span>
                  </div>
               </div>
            <?php
            } else {
               echo '<p class="empty">Giỏ hàng trống!</p>';
               $total_products = '';
               $grand_total = 0;
            }
            ?>
            <a href="cart.php" class="btn">Xem giỏ hàng</a>
         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

         <div class="user-info">
   <h3>Thông tin thanh toán</h3>
   <?php
$name_value = $number_value = $email_value = '';

if ($fetch_profile) {
    $name_value = htmlspecialchars($fetch_profile['name']);
    $number_value = htmlspecialchars($fetch_profile['number']);
    $email_value = htmlspecialchars($fetch_profile['email']);
}
?>
   <!-- Thêm 3 input cần thiết để tránh lỗi Undefined array key -->
   <input type="text" name="name" required placeholder="Nhập họ tên" class="box" maxlength="50" value="<?= $name_value; ?>">
<input type="text" name="number" required placeholder="Nhập số điện thoại" class="box" maxlength="15" value="<?= $number_value; ?>">
<input type="email" name="email" required placeholder="Nhập email" class="box" maxlength="50" value="<?= $email_value; ?>">

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
