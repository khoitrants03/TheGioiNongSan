<?php

include '../../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:../../home.php');
}
;
 
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Lịch hẹn</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../../css/style.css">

</head>

<body>

   <!-- header section starts  -->
   <?php
   if (isset($_SESSION['phanquyen'])) {
      if ($_SESSION['phanquyen'] === 'nhanvien') {
         require("../../components/user_header_doctor.php");
      } elseif ($_SESSION['phanquyen'] === 'bacsi') {
         require("../../components/user_header_doctor.php");
      } elseif ($_SESSION['phanquyen'] === 'benhnhan') {
         require("../../components/user_header_patient.php");
      }
      elseif ($_SESSION['phanquyen'] === 'tieptan') {
         require("../../components/user_header_tieptan.php");
      }
      elseif ($_SESSION['phanquyen'] === 'nhathuoc') {
         require("../../components/user_header_nhathuoc.php");
      }
   } else {
      include("../../components/user_header.php");
   }
   ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Lịch hẹn</h3>
      <p><a href="../../home.php">Trang chủ</a> <span> / Lịch hẹn</span></p>
   </div>

   <section class="orders">

      <h1 class="title">Lịch hẹn của bạn</h1>

      <div class="box-container">

         <?php
         if ($user_id == '') {
            echo '<p class="empty">vui lòng đăng nhập để lịch khám của bạn!</p>';
         } else {
            $select_orders = $conn->prepare("SELECT * FROM `LichHen` WHERE user_id = ?");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <div class="box">
                     <p>Thời gian : <span>
                           <?= $fetch_orders['placed_on']; ?>
                        </span></p>
                     <p>Tên : <span>
                           <?= $fetch_orders['name']; ?>
                        </span></p>
                     <p>Địa chỉ : <span>
                           <?= $fetch_orders['address']; ?>
                        </span></p>
                     <p>Chuyen khoa: <span>
                           <?= $fetch_orders['email']; ?>
                        </span></p>
                     <p>Số điện thoại : <span>
                           <?= $fetch_orders['number']; ?>
                        </span></p>
                     <p>Hình thức thanh toán : <span>
                           <?= $fetch_orders['method']; ?>
                        </span></p>
                     <p>Đơn hàng của bạn : <span>
                           <?= $fetch_orders['total_products']; ?>
                        </span></p>
                     <p> Trạng thái thanh toán : <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                        echo 'red';
                     } else {
                        echo 'green';
                     }
                     ; ?>"><?php
                      if ($fetch_orders['payment_status'] == 'pending') {
                         echo "Chưa giải quyết";
                      } else {
                         echo "Hoàn thành";
                      }
                      ?></span> </p>
                  </div>
                  <?php
               }
            } else {
               echo '<p class="empty">Bạn chưa đặt lịch!</p>';
            }
         }
         ?>

      </div>

   </section>

   <!-- footer section starts  -->
   <?php include '../../components/footer.php'; ?>
   <!-- footer section ends -->

   <!-- custom js file link  -->
   <script src="../../js/script.js"></script>

</body>

</html>