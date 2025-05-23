<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

// Fetch user's orders
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
$select_orders->execute([$user_id]);

//include './convert_currency.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đơn hàng của tôi</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
   .order-item {
       border: 2px solid #1976d2;
       border-radius: 10px;
       background: #fff;
       margin-bottom: 24px;
       padding: 20px 18px;
       box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
       transition: box-shadow 0.2s, border-color 0.2s;
   }
   .order-item:hover {
       border-color: #1565c0;
       box-shadow: 0 4px 16px rgba(25, 118, 210, 0.18);
       background: #f5faff;
   }
   .order-item .order-header {
       font-weight: bold;
       font-size: 1.15em;
       margin-bottom: 10px;
       color: #1976d2;
   }
   .order-item .order-info {
       margin-bottom: 8px;
       color: #333;
   }
   </style>

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Đơn hàng của tôi</h3>
      <p><a href="home.php">Trang chủ</a> <span> / Đơn hàng của tôi</span></p>
   </div>

   <section class="orders">
      <h1 class="title">Đơn hàng của tôi</h1>
      <div class="orders-container">
         <?php
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="order-item">
                  <div class="order-header">
                     <div class="order-id">
                        <span class="label">Mã đơn hàng:</span>
                        <span class="value">#<?= str_pad($fetch_orders['id'], 8, '0', STR_PAD_LEFT); ?></span>
                     </div>
                     <div class="order-date">
                        <span class="label">Ngày đặt:</span>
                        <span class="value"><?= date('d/m/Y H:i:s', strtotime($fetch_orders['placed_on'])); ?></span>
                     </div>
                  </div>
                  <div class="order-details">
                     <div class="products">
                        <span class="label">Sản phẩm:</span>
                        <span class="value"><?= $fetch_orders['total_products']; ?> sản phẩm</span>
                     </div>
                     <div class="total">
                        <span class="label">Tổng tiền:</span>
                        <span class="value"><?= number_format($fetch_orders['total_price']); ?> VNĐ</span>
                     </div>
                     <div class="status">
                        <span class="label">Trạng thái:</span>
                        <span class="value <?= $fetch_orders['payment_status']; ?>">
                           <?php
                           switch($fetch_orders['payment_status']) {
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
                              default:
                                 echo $fetch_orders['payment_status'];
                           }
                           ?>
                        </span>
                     </div>
                     <?php if($fetch_orders['payment_status'] == 'shipped'): ?>
                     <div class="tracking">
                        
                        
                        <a href="track_order.php?id=<?= $fetch_orders['id']; ?>" class="btn">
                           <i class="fas fa-map-marker-alt"></i> Theo dõi đơn hàng
                        </a>
                     </div>
                     <?php endif; ?>
                  </div>
                  <div class="order-actions">
                    <!-- <a href="view_order.php?id=<?= $fetch_orders['id']; ?>" class="btn">
                        <i class="fas fa-eye"></i> Xem chi tiết 
                     </a>-->
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Bạn chưa có đơn hàng nào!</p>';
         }
         ?>
      </div>
   </section>

   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>