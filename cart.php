<!-- Thao My -->
<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

if (isset($_POST['delete'])) {
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'Đã xóa sản phẩm khỏi giỏ hàng!';
}

if (isset($_POST['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   // header('location:cart.php');
   $message[] = 'Đã xóa tất cả khỏi giỏ hàng!';
}

if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Đã cập nhật số lượng!';
}

$grand_total = 0;
$cart_items = [];

// Get cart items
$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$user_id]);
$cart_items = $select_cart->fetchAll(PDO::FETCH_ASSOC);

// Calculate grand total
foreach($cart_items as $item){
    $grand_total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giỏ hàng - TheGioiNongSan</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/cart.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Thông tin giỏ hàng</h3>
      <p><a href="home.php">Trang chủ</a> <span> / Giỏ hàng</span></p>
   </div>

   <!-- shopping cart section starts  -->

   <section class="shopping-cart">
      <h1 class="heading">Giỏ hàng của bạn</h1>

      <div class="cart-container">
         <?php if(count($cart_items) > 0): ?>
            <div class="cart-items">
               <?php foreach($cart_items as $item): ?>
                  <div class="cart-item" data-cart-id="<?= $item['id']; ?>">
                     <div class="item-image">
                        <img src="uploaded_img/<?= $item['image']; ?>" alt="<?= $item['name']; ?>">
                     </div>
                     <div class="item-details">
                        <h3><?= $item['name']; ?></h3>
                        <div class="price"><?= number_format($item['price']); ?> VNĐ</div>
                        <div class="quantity">
                           <button class="qty-btn minus" onclick="updateQuantity(<?= $item['id']; ?>, 'decrease')">-</button>
                           <input type="number" class="qty-input" value="<?= $item['quantity']; ?>" min="1" max="99" onchange="updateQuantity(<?= $item['id']; ?>, 'set', this.value)">
                           <button class="qty-btn plus" onclick="updateQuantity(<?= $item['id']; ?>, 'increase')">+</button>
                        </div>
                        <div class="subtotal">
                           Tổng: <span><?= number_format($item['price'] * $item['quantity']); ?></span> VNĐ
                        </div>
                     </div>
                     <button class="remove-btn" onclick="removeItem(<?= $item['id']; ?>)">
                        <i class="fas fa-times"></i>
                     </button>
                  </div>
               <?php endforeach; ?>
            </div>

            <div class="cart-summary">
               <h2>Tổng đơn hàng</h2>
               <div class="summary-item">
                  <span>Tạm tính:</span>
                  <span id="subtotal"><?= number_format($grand_total); ?> VNĐ</span>
               </div>
               <div class="summary-item">
                  <span>Phí vận chuyển:</span>
                  <span>30,000 VNĐ</span>
               </div>
               <div class="summary-item total">
                  <span>Tổng cộng:</span>
                  <span id="grand-total"><?= number_format($grand_total + 30000); ?> VNĐ</span>
               </div>
               <a href="checkout.php" class="checkout-btn">Thanh toán</a>
            </div>
         <?php else: ?>
            <div class="empty-cart">
               <p>Giỏ hàng của bạn đang trống!</p>
               <a href="product.php" class="btn">Tiếp tục mua sắm</a>
            </div>
         <?php endif; ?>
      </div>
   </section>

   <!-- shopping cart section ends -->

   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->


   <!-- custom js file link  -->
   <script src="js/cart.js"></script>

</body>

</html>