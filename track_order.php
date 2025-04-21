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
   <title>Theo dõi đơn hàng</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <section class="tracking">
      <h1 class="title">Theo dõi đơn hàng #<?= $order_id; ?></h1>
      <div class="tracking-container">
         <div class="tracking-info">
            <div class="info-item">
               <span class="label">Mã đơn hàng:</span>
               <span class="value">#<?= $order_id; ?></span>
            </div>
            <div class="info-item">
               <span class="label">Mã theo dõi:</span>
               <span class="value"><?= $fetch_order['tracking_number']; ?></span>
            </div>
            <div class="info-item">
               <span class="label">Trạng thái:</span>
               <span class="value <?= $fetch_order['payment_status']; ?>">
                  <?php
                  switch($fetch_order['payment_status']) {
                     case 'pending':
                        echo 'Chờ xác nhận';
                        break;
                     case 'confirmed':
                        echo 'Đã xác nhận';
                        break;
                     case 'processing':
                        echo 'Đang xử lý';
                        break;
                     case 'shipped':
                        echo 'Đang giao hàng';
                        break;
                     case 'delivered':
                        echo 'Đã giao hàng';
                        break;
                     case 'cancelled':
                        echo 'Đã hủy';
                        break;
                     default:
                        echo $fetch_order['payment_status'];
                  }
                  ?>
               </span>
            </div>
         </div>

         <div class="tracking-timeline">
            <div class="timeline-item <?= $fetch_order['payment_status'] == 'pending' ? 'active' : ''; ?>">
               <div class="timeline-icon">
                  <i class="fas fa-clock"></i>
               </div>
               <div class="timeline-content">
                  <h3>Chờ xác nhận</h3>
                  <p>Đơn hàng đã được đặt và đang chờ xác nhận</p>
               </div>
            </div>
            <div class="timeline-item <?= $fetch_order['payment_status'] == 'confirmed' ? 'active' : ''; ?>">
               <div class="timeline-icon">
                  <i class="fas fa-check-circle"></i>
               </div>
               <div class="timeline-content">
                  <h3>Đã xác nhận</h3>
                  <p>Đơn hàng đã được xác nhận</p>
               </div>
            </div>
            <div class="timeline-item <?= $fetch_order['payment_status'] == 'processing' ? 'active' : ''; ?>">
               <div class="timeline-icon">
                  <i class="fas fa-box"></i>
               </div>
               <div class="timeline-content">
                  <h3>Đang xử lý</h3>
                  <p>Đơn hàng đang được đóng gói</p>
               </div>
            </div>
            <div class="timeline-item <?= $fetch_order['payment_status'] == 'shipped' ? 'active' : ''; ?>">
               <div class="timeline-icon">
                  <i class="fas fa-truck"></i>
               </div>
               <div class="timeline-content">
                  <h3>Đang giao hàng</h3>
                  <p>Đơn hàng đang trên đường giao đến bạn</p>
               </div>
            </div>
            <div class="timeline-item <?= $fetch_order['payment_status'] == 'delivered' ? 'active' : ''; ?>">
               <div class="timeline-icon">
                  <i class="fas fa-home"></i>
               </div>
               <div class="timeline-content">
                  <h3>Đã giao hàng</h3>
                  <p>Đơn hàng đã được giao thành công</p>
               </div>
            </div>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html> 