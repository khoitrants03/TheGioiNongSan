<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_GET['pid'])) {
   $pid = $_GET['pid'];
   $select_product = $conn->prepare("SELECT * FROM `sanpham` WHERE ten_sanpham = ?");
   $select_product->execute([$pid]);
   if ($select_product->rowCount() > 0) {
      $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
   } else {
      header('location: home.php');
   }
} else {
   header('location: home.php');
}

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chi tiết sản phẩm</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <section class="quick-view">
      <div class="box">
         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="uploaded_img/<?= $fetch_product['img']; ?>" alt="">
               </div>
            </div>
            <div class="content">
               <h3 class="name"><?= $fetch_product['ten_sanpham']; ?></h3>
               <div class="flex">
                  <div class="price">Giá: <?= $fetch_product['gia']; ?> VNĐ</div>
                  <div class="quantity">Số lượng còn: <?= $fetch_product['so_luong_ton']; ?></div>
               </div>
               <div class="details">
                  <h3>Thông tin chi tiết</h3>
                  
                  <p>Mô tả: <?= $fetch_product['mo_ta']; ?></p>
               </div>
               <form action="" method="post" class="flex-btn">
                  <input type="hidden" name="pid" value="<?= $fetch_product['ten_sanpham']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_product['ten_sanpham']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_product['gia']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_product['img']; ?>">
                
                  <input type="submit" value="Thêm vào giỏ" class="btn" name="add_to_cart">
                  <a href="home.php" class="btn">Quay lại</a>
               </form>
            </div>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html> 