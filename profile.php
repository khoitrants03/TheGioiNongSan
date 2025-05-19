<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}

// Fetch user profile information
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thông tin cá nhân</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <!-- header  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header  -->

   <section class="user-profile">
      <h1 class="title">Thông tin cá nhân</h1>
      <div class="profile-container">
         <div class="profile-picture">
            <div class="image-container">
               <?php if(!empty($fetch_profile['image'])): ?>
                  <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="Profile Picture">
               <?php else: ?>
                  <img src="imgs/default-avatar.png" alt="Default Profile Picture">
               <?php endif; ?>
            </div>
            <form action="components/update_profile_picture.php" method="post" enctype="multipart/form-data">
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
               <input type="submit" value="Cập nhật ảnh đại diện" name="update_picture" class="btn">
            </form>
         </div>
         <div class="profile-info">
            <div class="info-item">
               <span class="label">Họ tên:</span>
               <span class="value"><?= $fetch_profile['name']; ?></span>
            </div>
            <div class="info-item">
               <span class="label">Email:</span>
               <span class="value"><?= $fetch_profile['email']; ?></span>
            </div>
            <div class="info-item">
               <span class="label">Số điện thoại:</span>
               <span class="value"><?= $fetch_profile['number']; ?></span>
            </div>
            <div class="info-item">
               <span class="label">Địa chỉ:</span>
               <span class="value"><?= $fetch_profile['address']; ?></span>
            </div>
         </div>
         <div class="profile-actions">
            <a href="update_profile.php" class="btn">Cập nhật thông tin</a>
            <a href="change_password.php" class="btn">Đổi mật khẩu</a>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>