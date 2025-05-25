<?php

include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['submit'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
   $phanquyen = filter_var($_POST['phanquyen'], FILTER_SANITIZE_STRING);

   $dia_chi = ''; // Bạn có thể cập nhật theo input form nếu có
   $id_vaitro = 2; // Ví dụ mặc định là 2 cho người dùng thường
   $ngay_tao = date('Y-m-d H:i:s');

   // Kiểm tra email hoặc số điện thoại đã tồn tại
   $select_user = $conn->prepare("SELECT * FROM nguoidung WHERE email = ? OR so_dien_thoai = ?");
   $select_user->execute([$email, $number]);

   if ($select_user->rowCount() > 0) {
      $message[] = 'Email hoặc số điện thoại đã tồn tại!';
   } else {
      if ($pass !== $cpass) {
         $message[] = 'Mật khẩu không khớp!';
      } else {
         $hash_password = $pass;

         $insert_user = $conn->prepare("
            INSERT INTO nguoidung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, id_vaitro, ngay_tao, phanquyen) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
         ");
         $insert_user->execute([$name, $email, $hash_password, $number, $dia_chi, $id_vaitro, $ngay_tao, $phanquyen]);

         // Đăng nhập ngay sau khi đăng ký
         $select_user = $conn->prepare("SELECT * FROM nguoidung WHERE email = ?");
         $select_user->execute([$email]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);

         if ($row && password_verify($pass, $row['mat_khau'])) {
            $_SESSION['user_id'] = $row['id_nguoidung'];
            $_SESSION['phanquyen'] = $row['phanquyen'];
            header('location: home.php');
            exit;
         }
      }
   }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng ký</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <section class="form-container">

      <form action="" method="post">
         <h3>Đăng ký</h3>
         <input type="text" name="name" required placeholder="Nhập họ và tên của bạn" class="box" maxlength="50">
         <input type="text" name="phanquyen" class="box" maxlength="50" style="display: none;" value="khachhang">

         <input type="email" name="email" required placeholder="Nhập email của bạn" class="box" maxlength="50"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="number" name="number" required placeholder="Nhập số điện thoại" class="box" min="0"
            max="9999999999" maxlength="10">
         <input type="password" name="pass" required placeholder="Nhập mật khẩu của bạn" class="box" maxlength="50"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="Xác nhận lại mật khẩu" class="box" maxlength="50"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Đăng ký" name="submit" class="btn">
         <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>
   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>