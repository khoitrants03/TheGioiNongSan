<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php
   if (isset($_SESSION['phanquyen'])) {
      if ($_SESSION['phanquyen'] === 'nongdan') {
         require("components/user_header_nongdan.php");
      }
      if ($_SESSION['phanquyen'] === 'doanhnghiep') {
         require("components/user_header_doanhnghiep.php");
      }
      if ($_SESSION['phanquyen'] === 'khachhang') {
         require("components/user_header_khachhang.php");
      }
   } else {
      include("components/user_header.php");
   }

   ?>

   <section class="hero">

      <div class="swiper hero-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <div class="content">
                  <!-- <span>mua sắm</span> -->
                  <h3>TheGioiNongSan</h3>
                  <a href="#" class="btn">Xem thêm</a>
               </div>
               <div class="image">
                  <img src="imgs/nong-san-xuat-khau-3.jpg" alt="">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <!-- <span>mua sắm</span> -->
                  <h4>TỰ HÀO LÀ THƯƠNG HIỆU HÀNG DẦU</h4>
                  <a href="#" class="btn">Xem thêm</a>
               </div>
               <div class="image">
                  <img src="imgs/nong-san-sach-ha-noi-9.jpg" alt="">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <!-- <span>mua sắm</span> -->
                  <h4>"LUÔN LUÔN TƯƠI MỚI</h4>
                  <a href="#" class="btn">Xem thêm</a>
               </div>
               <div class="image">
                  <img src="imgs/luontuoimoi.jpg" alt="">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <!-- <span>mua sắm</span> -->
                  <h4> VỰA HOA QUẢ <br><br> TƯƠI MÁT MÙA HÈ</h4>
                  <a href="#" class="btn">Xem thêm</a>
               </div>
               <div class="image">
                  <img src="imgs/vuahoaqua.jpg " alt="">
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- <section class="category">

      <h1 class="title">DỊCH VỤ CỦA CHÚNG TÔI</h1>

     

   </section> -->


   <section class="products">

      <h1 class="title">SẢN PHẨM BÁN CHẠY</h1>

      <div class="box-container">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `sanpham` ");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <form action="" method="post" class="box">
                  <input type="hidden" name="pid" value="<?= $fetch_products['ten_sanpham']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_products['so_luong_ton']; ?>">
                  <input type="hidden" name="new" value="<?= $fetch_products['gia']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_products['img']; ?>">
                  <a href="product_detail.php?pid=<?= $fetch_products['ten_sanpham']; ?>" class="fas fa-eye"></a>
                  <img src="imgs/<?= $fetch_products['img']; ?>" alt="">
                  <div class="new">
                     Tên sản phẩm: <?= $fetch_products['ten_sanpham']; ?>
                  </div>
                  <div class="name">
                     Số lượng: <?= $fetch_products['so_luong_ton']; ?>
                  </div>
                  <div class="new">
                     Giá: <?= $fetch_products['gia']; ?>
                  </div>
               </form>
               <?php
            }
         } else {
            echo '<p class="empty">Không có thoong tin để hiển thị!</p>';
         }
         ?>

      </div>

      <div class="more-btn">
         <a href="#" class="btn">Xem tất cả</a>
      </div>

      <section class="content">
         <h1 class="title">THÔNG BÁO MỚI NHẤT</h1>




      </section>

      <?php include 'components/footer.php'; ?>


      <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

      <!-- custom js file link  -->
      <script src="js/script.js"></script>

      <script>
         var swiper = new Swiper(".hero-slider", {
            loop: true,
            grabCursor: true,
            effect: "flip",
            pagination: {
               el: ".swiper-pagination",
               clickable: true,
            },
         });
      </script>

</body>

</html>