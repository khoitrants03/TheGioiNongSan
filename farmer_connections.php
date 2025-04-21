<?php
include 'components/connect.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'business'){
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Handle connection request
if(isset($_POST['send_request'])){
   $farmer_id = $_POST['farmer_id'];
   $message = $_POST['message'];
   
   $insert_request = $conn->prepare("INSERT INTO `connection_requests` (business_id, farmer_id, message, status) VALUES (?, ?, ?, 'pending')");
   $insert_request->execute([$user_id, $farmer_id, $message]);
   $message[] = 'Đã gửi yêu cầu kết nối thành công!';
}

// Search farmers
$search = '';
if(isset($_GET['search'])){
   $search = $_GET['search'];
   $select_farmers = $conn->prepare("SELECT * FROM `farmer_profiles` WHERE name LIKE ? OR location LIKE ?");
   $select_farmers->execute(["%$search%", "%$search%"]);
}else{
   $select_farmers = $conn->prepare("SELECT * FROM `farmer_profiles`");
   $select_farmers->execute();
}

// Get existing connections
$select_connections = $conn->prepare("SELECT * FROM `connection_requests` WHERE business_id = ?");
$select_connections->execute([$user_id]);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kết nối với nông dân</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .connections-container {
         display: grid;
         grid-template-columns: 1fr 2fr;
         gap: 2rem;
         padding: 2rem;
      }
      .search-section {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .farmers-list {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .farmer-item {
         padding: 1.5rem;
         border-bottom: 1px solid var(--light-bg);
         display: flex;
         align-items: center;
         justify-content: space-between;
      }
      .farmer-info {
         flex: 1;
      }
      .farmer-name {
         font-weight: bold;
         color: var(--black);
      }
      .farmer-location {
         color: var(--light-color);
         margin-top: .5rem;
      }
      .connection-status {
         padding: .5rem 1rem;
         border-radius: .5rem;
         font-size: 1.4rem;
      }
      .status-pending {
         background: #fff3cd;
         color: #856404;
      }
      .status-accepted {
         background: #d4edda;
         color: #155724;
      }
      .status-rejected {
         background: #f8d7da;
         color: #721c24;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="farmer-connections">
   <div class="heading">
      <h3>Kết nối với nông dân</h3>
      <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Kết nối nông dân</span></p>
   </div>

   <div class="connections-container">
      <div class="search-section">
         <h3>Tìm kiếm nông dân</h3>
         <form action="" method="GET">
            <div class="inputBox">
               <input type="text" name="search" placeholder="Tìm theo tên hoặc địa điểm" class="box" value="<?= $search; ?>">
               <button type="submit" class="btn"><i class="fas fa-search"></i> Tìm kiếm</button>
            </div>
         </form>
      </div>

      <div class="farmers-list">
         <h3>Danh sách nông dân</h3>
         <?php
         if($select_farmers->rowCount() > 0){
            while($fetch_farmer = $select_farmers->fetch(PDO::FETCH_ASSOC)){
               // Check if already connected
               $check_connection = $conn->prepare("SELECT * FROM `connection_requests` WHERE business_id = ? AND farmer_id = ?");
               $check_connection->execute([$user_id, $fetch_farmer['user_id']]);
               $connection = $check_connection->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="farmer-item">
            <div class="farmer-info">
               <div class="farmer-name"><?= $fetch_farmer['name']; ?></div>
               <div class="farmer-location">
                  <i class="fas fa-map-marker-alt"></i>
                  <?= $fetch_farmer['location']; ?>
               </div>
            </div>
            <?php if($connection): ?>
            <div class="connection-status status-<?= $connection['status']; ?>">
               <?php
               switch($connection['status']){
                  case 'pending':
                     echo 'Đang chờ phản hồi';
                     break;
                  case 'accepted':
                     echo 'Đã kết nối';
                     break;
                  case 'rejected':
                     echo 'Đã từ chối';
                     break;
               }
               ?>
            </div>
            <?php else: ?>
            <form action="" method="post" class="connection-form">
               <input type="hidden" name="farmer_id" value="<?= $fetch_farmer['user_id']; ?>">
               <div class="inputBox">
                  <textarea name="message" placeholder="Nhập lời nhắn" class="box" cols="30" rows="3"></textarea>
               </div>
               <input type="submit" value="Gửi yêu cầu" name="send_request" class="btn">
            </form>
            <?php endif; ?>
         </div>
         <?php
            }
         }else{
            echo '<p class="empty">Không tìm thấy nông dân nào!</p>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html> 