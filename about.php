<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giới thiệu</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <!-- header section starts  -->
   <?php

   include("components/user_header.php");

   ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Về chúng tôi</h3>
      <p><a href="home.php">Trang chủ</a> <span> / Giới thiệu</span></p>
   </div>






   <!-- reviews section starts  -->

   <section class="reviews">
      <h1 class="title">Giới Thiệu Về Thế Giới Nông Sản Sạch</h1>
      <div class="swiper reviews-slider">
         <div class="swiper-wrapper">
            <div class="swiper-slide slide">
               <img src="./uploaded_img/nong-san-xuat-khau-3.jpg" alt="">
               <div class="profile">
                  <h1>“Thế giới nông sản” là Hệ thống bán lẻ sản phẩm nông sản sạch được nhiều
                      chị em nội trợ tin tưởng, chúng tôi cam kết :</h1>
                   
                  <div class="section">
                      <ul>
                        <li>Các sản phẩm nông sản được bán tại cửa hàng đều có nguồn gốc rõ ràng
                        </li>
                        <li>Sản phẩm nông sản đa dạng cho khách hàng lựa chọn</li>
                        <li>Các sản phẩm rau, củ qủa luôn tươi ngon, được chúng tôi nhập mới hàng ngày</li>
                        <li>Giá cả hợp lý: Chúng tôi hướng tới mục tiêu đưa đến tay khách hàng những sản phẩm sạch, an toàn với giá thành phù hợp nhất.
                        </li>
                     </ul>
                  </div>
                   
               </div>
            </div>
         </div>
      </div>
   </div>

   </section>
   <section class="products">

      <h1 class="title">TIN TỨC & HOẠT ĐỘNG</h1>

      <div class="box-container">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `tintuc` LIMIT 6");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <form action="" method="post" class="box">

                  <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                  <input type="hidden" name="new" value="<?= $fetch_products['name1']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_products['imge']; ?>">
                  <a class="fas fa-eye"></a>

                  <img src="uploaded_img/<?= $fetch_products['imge']; ?>" alt="">
                  <div class="name">
                     <?= $fetch_products['name']; ?>
                  </div>
                  <div class="new">
                     <?= $fetch_products['name1']; ?>
                  </div>
               </form>
               <?php
            }
         } else {
            echo '<p class="empty">Không có thoong tin để hiển thị!</p>';
         }
         ?>

      </div>


      <!-- footer section starts  -->
      <?php include 'components/footer.php'; ?>
      <!-- footer section ends -->=






      <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

      <!-- custom js file link  -->
      <script src="js/script.js"></script>

      <script>
         var swiper = new Swiper(".reviews-slider", {
            loop: true,
            grabCursor: true,
            spaceBetween: 20,
            pagination: {
               el: ".swiper-pagination",
               clickable: true,
            },
            breakpoints: {
               0: {
                  slidesPerView: 1,
               },
               700: {
                  slidesPerView: 2,
               },
               1024: {
                  slidesPerView: 3,
               },
            },
         });
      </script>

</body>

</html>