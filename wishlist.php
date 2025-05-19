<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

// Fetch wishlist items
$select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
$select_wishlist->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Danh sách yêu thích</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <section class="wishlist">
      <h1 class="title">Danh sách yêu thích</h1>
      <div class="wishlist-container">
         <?php
         if ($select_wishlist->rowCount() > 0) {
            while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
               $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
               $select_products->execute([$fetch_wishlist['product_id']]);
               $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
         ?>
               <div class="wishlist-item">
                  <div class="image">
                     <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                  </div>
                  <div class="content">
                     <h3><?= $fetch_products['name']; ?></h3>
                     <div class="price"><?= number_format($fetch_products['price']); ?> VNĐ</div>
                     <div class="flex-btn">
                        <a href="product_detail.php?pid=<?= $fetch_products['id']; ?>" class="btn">Xem chi tiết</a>
                        <a href="components/remove_wishlist.php?pid=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?');">Xóa</a>
                     </div>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Danh sách yêu thích trống!</p>';
         }
         ?>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html> 