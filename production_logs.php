<?php
include 'components/connect.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'business'){
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if(isset($_POST['add_log'])){
   $date = $_POST['date'];
   $fertilizer = $_POST['fertilizer'];
   $pesticide = $_POST['pesticide'];
   $method = $_POST['method'];
   $notes = $_POST['notes'];

   $insert_log = $conn->prepare("INSERT INTO `production_logs` (business_id, timestamp , fertilizer, pesticide, method, notes) VALUES (?, ?, ?, ?, ?, ?)");
   $insert_log->execute([$user_id, $date, $fertilizer, $pesticide, $method, $notes]);
   $message[] = 'Đã thêm nhật ký sản xuất thành công!';
}

// Fetch production logs
$select_logs = $conn->prepare("SELECT * FROM `production_logs` WHERE business_id = ? ORDER BY timestamp DESC");
$select_logs->execute([$user_id]);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quản lý nhật ký sản xuất</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .logs-container {
         display: grid;
         grid-template-columns: 1fr 2fr;
         gap: 2rem;
         padding: 2rem;
      }
      .add-log-form {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .logs-list {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .log-item {
         padding: 1.5rem;
         border-bottom: 1px solid var(--light-bg);
      }
      .log-item:last-child {
         border-bottom: none;
      }
      .log-date {
         font-weight: bold;
         color: var(--black);
      }
      .log-details {
         margin-top: 1rem;
      }
      .log-details p {
         margin: .5rem 0;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="production-logs">
   <div class="heading">
      <h3>Quản lý nhật ký sản xuất</h3>
      <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Nhật ký sản xuất</span></p>
   </div>

   <div class="logs-container">
      <div class="add-log-form">
         <h3>Thêm nhật ký mới</h3>
         <form action="" method="post">
            <div class="inputBox">
               <span>Ngày</span>
               <input type="date" name="date" required class="box">
            </div>
            <div class="inputBox">
               <span>Phân bón sử dụng</span>
               <input type="text" name="fertilizer" placeholder="Nhập thông tin phân bón" class="box">
            </div>
            <div class="inputBox">
               <span>Thuốc trừ sâu</span>
               <input type="text" name="pesticide" placeholder="Nhập thông tin thuốc trừ sâu" class="box">
            </div>
            <div class="inputBox">
               <span>Phương pháp canh tác</span>
               <textarea name="method" placeholder="Nhập phương pháp canh tác" class="box" cols="30" rows="5"></textarea>
            </div>
            <div class="inputBox">
               <span>Ghi chú</span>
               <textarea name="notes" placeholder="Nhập ghi chú" class="box" cols="30" rows="5"></textarea>
            </div>
            <input type="submit" value="Thêm nhật ký" name="add_log" class="btn">
         </form>
      </div>

      <div class="logs-list">
         <h3>Lịch sử nhật ký</h3>
         <?php
         if($select_logs->rowCount() > 0){
            while($fetch_log = $select_logs->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="log-item">
            <div class="log-date">
               <i class="fas fa-calendar"></i>
               <?= date('d/m/Y', strtotime($fetch_log['timestamp'])); ?>
            </div>
            <div class="log-details">
               <p><strong>Phân bón:</strong> <?= $fetch_log['fertilizer']; ?></p>
               <p><strong>Thuốc trừ sâu:</strong> <?= $fetch_log['pesticide']; ?></p>
               <p><strong>Phương pháp:</strong> <?= $fetch_log['method']; ?></p>
               <?php if(!empty($fetch_log['notes'])): ?>
               <p><strong>Ghi chú:</strong> <?= $fetch_log['notes']; ?></p>
               <?php endif; ?>
            </div>
         </div>
         <?php
            }
         }else{
            echo '<p class="empty">Chưa có nhật ký nào!</p>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html> 