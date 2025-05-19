<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

if (isset($_GET['id'])) {
   $order_id = $_GET['id'];
   $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ?");
   $select_order->execute([$order_id, $user_id]);
   $fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);
   
   if (!$fetch_order) {
      header('location:orders.php');
   }
   
   // Fetch order items
   $select_order_items = $conn->prepare("SELECT oi.*, p.name, p.image, p.price 
                                       FROM `order_items` oi 
                                       JOIN `products` p ON oi.product_id = p.id 
                                       WHERE oi.order_id = ?");
   $select_order_items->execute([$order_id]);
} else {
   header('location:orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chi tiết đơn hàng #<?= str_pad($order_id, 8, '0', STR_PAD_LEFT); ?></title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <section class="order-details">
      <h1 class="title">Chi tiết đơn hàng #<?= str_pad($order_id, 8, '0', STR_PAD_LEFT); ?></h1>
      
      <div class="order-info">
         <div class="info-item">
            <span class="label">Ngày đặt:</span>
            <span class="value"><?= date('d/m/Y H:i:s', strtotime($fetch_order['placed_on'])); ?></span>
         </div>
         <div class="info-item">
            <span class="label">Trạng thái:</span>
            <span class="value <?= $fetch_order['payment_status']; ?>">
               <?php
               switch($fetch_order['payment_status']) {
                  case 'pending':
                     echo '<i class="fas fa-clock"></i> Chờ xác nhận';
                     break;
                  case 'confirmed':
                     echo '<i class="fas fa-check-circle"></i> Đã xác nhận';
                     break;
                  case 'processing':
                     echo '<i class="fas fa-box"></i> Đang xử lý';
                     break;
                  case 'shipped':
                     echo '<i class="fas fa-truck"></i> Đang giao hàng';
                     break;
                  case 'delivered':
                     echo '<i class="fas fa-home"></i> Đã giao hàng';
                     break;
                  case 'cancelled':
                     echo '<i class="fas fa-times-circle"></i> Đã hủy';
                     break;
               }
               ?>
            </span>
         </div>
         <?php if($fetch_order['payment_status'] == 'shipped'): ?>
         <div class="info-item">
            <span class="label">Mã theo dõi:</span>
            <span class="value"><?= $fetch_order['tracking_number']; ?></span>
            <a href="track_order.php?id=<?= $order_id; ?>" class="btn">
               <i class="fas fa-map-marker-alt"></i> Theo dõi đơn hàng
            </a>
         </div>
         <?php endif; ?>
      </div>

      <div class="order-items">
         <h2>Sản phẩm đã đặt</h2>
         <div class="items-container">
            <?php
            while($fetch_item = $select_order_items->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="item">
               <div class="item-image">
                  <img src="uploaded_img/<?= $fetch_item['image']; ?>" alt="<?= $fetch_item['name']; ?>">
               </div>
               <div class="item-details">
                  <h3><?= $fetch_item['name']; ?></h3>
                  <div class="price-quantity">
                     <span class="price"><?= number_format($fetch_item['price']); ?> VNĐ</span>
                     <span class="quantity">x <?= $fetch_item['quantity']; ?></span>
                  </div>
                  <div class="subtotal">
                     <span class="label">Thành tiền:</span>
                     <span class="value"><?= number_format($fetch_item['price'] * $fetch_item['quantity']); ?> VNĐ</span>
                  </div>
               </div>
            </div>
            <?php
            }
            ?>
         </div>
      </div>

      <div class="order-summary">
         <h2>Tổng kết đơn hàng</h2>
         <div class="summary-item">
            <span class="label">Tổng tiền sản phẩm:</span>
            <span class="value"><?= number_format($fetch_order['total_price']); ?> VNĐ</span>
         </div>
         <div class="summary-item">
            <span class="label">Phí vận chuyển:</span>
            <span class="value"><?= number_format($fetch_order['shipping_fee'] ?? 0); ?> VNĐ</span>
         </div>
         <div class="summary-item total">
            <span class="label">Tổng cộng:</span>
            <span class="value"><?= number_format($fetch_order['total_price'] + ($fetch_order['shipping_fee'] ?? 0)); ?> VNĐ</span>
         </div>
      </div>

      <div class="shipping-info">
         <h2>Thông tin giao hàng</h2>
         <div class="info-item">
            <span class="label">Họ tên:</span>
            <span class="value"><?= $fetch_order['name']; ?></span>
         </div>
         <div class="info-item">
            <span class="label">Số điện thoại:</span>
            <span class="value"><?= $fetch_order['number']; ?></span>
         </div>
         <div class="info-item">
            <span class="label">Email:</span>
            <span class="value"><?= $fetch_order['email']; ?></span>
         </div>
         <div class="info-item">
            <span class="label">Địa chỉ:</span>
            <span class="value"><?= $fetch_order['address']; ?></span>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html> 