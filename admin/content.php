<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['add_post'])) {

   $title = $_POST['title'];

   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../content_image/' . $image;

   // Checking if a post with the same content already exists
   $select_posts = $conn->prepare("SELECT * FROM `new` WHERE content = ?");
   $select_posts->execute([$content]);

   if ($select_posts->rowCount() > 0) {
      $message[] = 'Bài viết đã tồn tại!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'Kích thước hình ảnh không được quá 20 MB';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_post = $conn->prepare("INSERT INTO `new`(content, image) VALUES(?,?)");
         $insert_post->execute([$content, $image]);

         $message[] = 'Thêm bài viết thành công!';
      }
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_post_image = $conn->prepare("SELECT * FROM `new` WHERE id = ?");
   $delete_post_image->execute([$delete_id]);
   $fetch_delete_image = $delete_post_image->fetch(PDO::FETCH_ASSOC);
   unlink('../content_image/' . $fetch_delete_image['image']);
   $delete_post = $conn->prepare("DELETE FROM `new` WHERE id = ?");
   $delete_post->execute([$delete_id]);
   header('location:content.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bài viết</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- add posts section starts  -->

   <section class="form-container">

      <form action="" method="POST" enctype="multipart/form-data" style="text-align: center;">
         <label for="title">Tiêu đề:</label>
         <input type="text" name="title" required>
         <textarea name="content" cols="30px" required class="box" style="border: 1px;" placeholder="Nhập nội dung bài viết"></textarea>
         <br>
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
         <input type="submit" value="Thêm bài viết" name="add_post" class="btn">
      </form>

   </section>

   <!-- add posts section ends -->

   <!-- show posts section starts  -->

   <section class="show-products" style="padding-top: 0;">

      <div class="box-container">

         <?php
         $show_posts = $conn->prepare("SELECT * FROM `new`");
         $show_posts->execute();
         if ($show_posts->rowCount() > 0) {
            while ($fetch_posts = $show_posts->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <img src="../content_image/<?= $fetch_posts['image']; ?>"  alt="">
                  <div class="content"><?= $fetch_posts['content']; ?></div>
                  <div class="flex-btn">
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Chưa có bài viết nào được thêm vào!</p>';
         }
         ?>

      </div>

   </section>

   <!-- show posts section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>
