<?php
include 'connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:../login.php');
}

if (isset($_POST['update_picture'])) {
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/';

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Kích thước ảnh quá lớn!';
      } else {
         $image_ext = pathinfo($image, PATHINFO_EXTENSION);
         $image_name = 'user_' . $user_id . '_' . time() . '.' . $image_ext;
         
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image_name, $user_id]);
         
         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder . $image_name);
            $message[] = 'Cập nhật ảnh đại diện thành công!';
         } else {
            $message[] = 'Không thể cập nhật ảnh đại diện!';
         }
      }
   }
}

header('location:../profile.php');
?> 