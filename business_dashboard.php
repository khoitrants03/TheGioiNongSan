<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'business') {
   header('location:business_login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Get business profile
$select_profile = $conn->prepare("SELECT * FROM `business_profiles` WHERE user_id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang quản lý doanh nghiệp</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .dashboard-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 2rem;
         padding: 2rem;
      }

      .dashboard-card {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
         text-align: center;
         cursor: pointer;
         transition: .2s linear;
      }

      .dashboard-card:hover {
         transform: translateY(-5px);
      }

      .dashboard-card i {
         font-size: 3rem;
         color: var(--green);
         margin-bottom: 1rem;
      }

      .dashboard-card h3 {
         font-size: 2rem;
         color: var(--black);
      }

      .dashboard-card p {
         font-size: 1.5rem;
         color: var(--light-color);
         margin-top: .5rem;
      }
   </style>
</head>

<body>

   <?php
   // include 'components/user_header.php'; 
   ?>



   <section class="dashboard">
      <div class="heading">
         <h3>Trang quản lý doanh nghiệp</h3>
         <p><a href="home.php">Trang chủ</a> <span> / Quản lý doanh nghiệp</span></p>
      </div>

      <div class="dashboard-container">
         <a href="production_logs.php" class="dashboard-card">
            <i class="fas fa-clipboard-list"></i>
            <h3>Quản lý nhật ký sản xuất</h3>
            <p>Theo dõi phân bón, thuốc trừ sâu và phương pháp canh tác</p>
         </a>

         <a href="farmer_connections.php" class="dashboard-card">
            <i class="fas fa-handshake"></i>
            <h3>Kết nối với nông dân</h3>
            <p>Tìm kiếm và gửi yêu cầu kết nối với nông dân</p>
         </a>

         <a href="manage_orders.php" class="dashboard-card">
            <i class="fas fa-shopping-cart"></i>
            <h3>Quản lý đơn hàng</h3>
            <p>Tạo, chỉnh sửa và quản lý đơn hàng</p>
         </a>

         <a href="distribution_status.php" class="dashboard-card">
            <i class="fas fa-truck"></i>
            <h3>Cập nhật trạng thái phân phối</h3>
            <p>Gửi thông báo và cập nhật quá trình giao hàng</p>
         </a>
         <a href="capnhatthongtinsp.php" class="dashboard-card">
            <i class="fas fa-seedling"></i>
            <h3>Quản lí sản phẩm </h3>
            <p>Cập nhật danh sách sản ohaarm của nông dân</p>
         </a>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>

</html>