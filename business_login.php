<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'business'){

   header('location:business_dashboard.php');
   exit();
}

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? AND user_type = 'business'");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_type'] = 'business';
      header('location:business_dashboard.php');
   }else{
      $message[] = 'Email hoặc mật khẩu không đúng!';
   }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập doanh nghiệp</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .form-container {
         max-width: 500px;
         margin: 2rem auto;
         padding: 2rem;
         background: #fff;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .form-container h3 {
         text-align: center;
         color: var(--black);
         font-size: 2.5rem;
         margin-bottom: 1.5rem;
      }
      .form-container .box {
         width: 100%;
         margin: 1rem 0;
         padding: 1.2rem;
         font-size: 1.6rem;
         color: var(--black);
         border: var(--border);
         border-radius: .5rem;
      }
      .form-container .btn {
         width: 100%;
         margin-top: 1.5rem;
         padding: 1.2rem;
         font-size: 1.6rem;
      }
      .form-container p {
         text-align: center;
         margin-top: 1.5rem;
         font-size: 1.5rem;
         color: var(--light-color);
      }
      .form-container p a {
         color: var(--green);
      }
      .form-container p a:hover {
         text-decoration: underline;
      }
      .business-icon {
         text-align: center;
         margin-bottom: 2rem;
      }
      .business-icon i {
         font-size: 5rem;
         color: var(--green);
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">
   <div class="business-icon">
      <i class="fas fa-building"></i>
   </div>
   <h3>Đăng nhập doanh nghiệp</h3>
   <form action="" method="post">
      <input type="email" name="email" required placeholder="Nhập email" class="box" maxlength="50">
      <input type="password" name="pass" required placeholder="Nhập mật khẩu" class="box" maxlength="50">
      <input type="submit" value="Đăng nhập" class="btn" name="submit">
      <p>Bạn chưa có tài khoản? <a href="business_register.php">Đăng ký ngay</a></p>
      <p>Quay lại <a href="home.php">Trang chủ</a></p>
   </form>
</section>

</body>
</html> 