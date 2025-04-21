<?php
include 'components/connect.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'business'){
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Handle status update
if(isset($_POST['update_status'])){
   $order_id = $_POST['order_id'];
   $status = $_POST['status'];
   $tracking_number = $_POST['tracking_number'];
   $notification = $_POST['notification'];
   
   $update_order = $conn->prepare("UPDATE `orders` SET payment_status = ?, tracking_number = ? WHERE id = ? AND business_id = ?");
   $update_order->execute([$status, $tracking_number, $order_id, $user_id]);
   
   // Insert notification
   if(!empty($notification)){
      $insert_notification = $conn->prepare("INSERT INTO `notifications` (order_id, message, type) VALUES (?, ?, 'status_update')");
      $insert_notification->execute([$order_id, $notification]);
   }
   
   $message[] = 'Đã cập nhật trạng thái đơn hàng thành công!';
}

// Fetch orders
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE business_id = ? ORDER BY placed_on DESC");
$select_orders->execute([$user_id]);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật trạng thái phân phối</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .distribution-container {
         padding: 2rem;
      }
      .order-item {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
         margin-bottom: 2rem;
      }
      .order-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 1rem;
      }
      .order-id {
         font-weight: bold;
         color: var(--black);
      }
      .order-date {
         color: var(--light-color);
      }
      .order-details {
         margin: 1rem 0;
      }
      .order-details p {
         margin: .5rem 0;
      }
      .status-form {
         margin-top: 1rem;
         padding-top: 1rem;
         border-top: 1px solid var(--light-bg);
      }
      .status-select {
         width: 100%;
         padding: 1rem;
         border-radius: .5rem;
         border: 1px solid var(--light-bg);
         margin-bottom: 1rem;
      }
      .timeline {
         margin-top: 2rem;
         padding: 1rem;
         background: var(--light-bg);
         border-radius: .5rem;
      }
      .timeline-item {
         display: flex;
         align-items: center;
         padding: 1rem;
         border-bottom: 1px solid #fff;
      }
      .timeline-item:last-child {
         border-bottom: none;
      }
      .timeline-icon {
         width: 3rem;
         height: 3rem;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 1rem;
      }
      .timeline-content {
         flex: 1;
      }
      .timeline-date {
         color: var(--light-color);
         font-size: 1.4rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="distribution-status">
   <div class="heading">
      <h3>Cập nhật trạng thái phân phối</h3>
      <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Trạng thái phân phối</span></p>
   </div>

   <div class="distribution-container">
      <?php
      if($select_orders->rowCount() > 0){
         while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="order-item">
         <div class="order-header">
            <div class="order-id">
               <i class="fas fa-shopping-cart"></i>
               Đơn hàng #<?= str_pad($fetch_order['id'], 8, '0', STR_PAD_LEFT); ?>
            </div>
            <div class="order-date">
               <?= date('d/m/Y H:i', strtotime($fetch_order['placed_on'])); ?>
            </div>
         </div>
         
         <div class="order-details">
            <p><strong>Khách hàng:</strong> <?= $fetch_order['customer_name']; ?></p>
            <p><strong>Địa chỉ:</strong> <?= $fetch_order['customer_address']; ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($fetch_order['total_price']); ?> VNĐ</p>
            <p><strong>Trạng thái hiện tại:</strong> 
               <?php
               switch($fetch_order['payment_status']){
                  case 'pending':
                     echo '<span style="color: #ffc107;">Đang chờ xử lý</span>';
                     break;
                  case 'processing':
                     echo '<span style="color: #17a2b8;">Đang xử lý</span>';
                     break;
                  case 'shipped':
                     echo '<span style="color: #007bff;">Đang giao hàng</span>';
                     break;
                  case 'delivered':
                     echo '<span style="color: #28a745;">Đã giao hàng</span>';
                     break;
                  case 'cancelled':
                     echo '<span style="color: #dc3545;">Đã hủy</span>';
                     break;
               }
               ?>
            </p>
         </div>

         <form action="" method="post" class="status-form">
            <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>">
            
            <div class="inputBox">
               <span>Cập nhật trạng thái</span>
               <select name="status" class="status-select" required>
                  <option value="">Chọn trạng thái</option>
                  <option value="pending" <?= $fetch_order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                  <option value="processing" <?= $fetch_order['payment_status'] == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                  <option value="shipped" <?= $fetch_order['payment_status'] == 'shipped' ? 'selected' : ''; ?>>Đang giao hàng</option>
                  <option value="delivered" <?= $fetch_order['payment_status'] == 'delivered' ? 'selected' : ''; ?>>Đã giao hàng</option>
                  <option value="cancelled" <?= $fetch_order['payment_status'] == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
               </select>
            </div>

            <div class="inputBox">
               <span>Mã theo dõi</span>
               <input type="text" name="tracking_number" placeholder="Nhập mã theo dõi" class="box" value="<?= $fetch_order['tracking_number']; ?>">
            </div>

            <div class="inputBox">
               <span>Thông báo cho khách hàng</span>
               <textarea name="notification" placeholder="Nhập thông báo cho khách hàng" class="box" cols="30" rows="3"></textarea>
            </div>

            <input type="submit" value="Cập nhật trạng thái" name="update_status" class="btn">
         </form>

         <div class="timeline">
            <h3>Lịch sử cập nhật</h3>
            <?php
            $select_notifications = $conn->prepare("SELECT * FROM `notifications` WHERE order_id = ? ORDER BY created_at DESC");
            $select_notifications->execute([$fetch_order['id']]);
            
            if($select_notifications->rowCount() > 0){
               while($fetch_notification = $select_notifications->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="timeline-item">
               <div class="timeline-icon" style="background: var(--green);">
                  <i class="fas fa-bell"></i>
               </div>
               <div class="timeline-content">
                  <p><?= $fetch_notification['message']; ?></p>
                  <div class="timeline-date">
                     <?= date('d/m/Y H:i', strtotime($fetch_notification['created_at'])); ?>
                  </div>
               </div>
            </div>
            <?php
               }
            }else{
               echo '<p class="empty">Chưa có thông báo nào!</p>';
            }
            ?>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Chưa có đơn hàng nào!</p>';
      }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html> 