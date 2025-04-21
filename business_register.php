<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'business'){

   header('location:business_dashboard.php');
   exit();
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = $_POST['cpass'];
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $business_name = $_POST['business_name'];
   $business_name = filter_var($business_name, FILTER_SANITIZE_STRING);
   $business_type = $_POST['business_type'];
   $business_type = filter_var($business_type, FILTER_SANITIZE_STRING);
   $tax_code = $_POST['tax_code'];
   $tax_code = filter_var($tax_code, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Email đã tồn tại!';
   }else{
      if($pass != $cpass){
         $message[] = 'Mật khẩu không khớp!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password, phone, address, user_type) VALUES(?,?,?,?,?,'business')");
         $insert_user->execute([$name, $email, $pass, $phone, $address]);
         $user_id = $conn->lastInsertId();
         
         $insert_business = $conn->prepare("INSERT INTO `business_profiles`(user_id, business_name, business_type, tax_code) VALUES(?,?,?,?)");
         $insert_business->execute([$user_id, $business_name, $business_type, $tax_code]);
         
         $message[] = 'Đăng ký thành công! Vui lòng đăng nhập.';
         header('location:business_login.php');
      }
   }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng ký doanh nghiệp</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .form-container {
         max-width: 800px;
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
      .form-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 1rem;
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
   <h3>Đăng ký doanh nghiệp</h3>
   <form action="" method="post">
      <div class="form-grid">
         <div class="inputBox">
            <span>Tên người đại diện</span>
            <input type="text" name="name" required placeholder="Nhập tên người đại diện" class="box" maxlength="50">
         </div>
         <div class="inputBox">
            <span>Email</span>
            <input type="email" name="email" required placeholder="Nhập email" class="box" maxlength="50">
         </div>
         <div class="inputBox">
            <span>Mật khẩu</span>
            <input type="password" name="pass" required placeholder="Nhập mật khẩu" class="box" maxlength="50">
         </div>
         <div class="inputBox">
            <span>Xác nhận mật khẩu</span>
            <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu" class="box" maxlength="50">
         </div>
         <div class="inputBox">
            <span>Số điện thoại</span>
            <input type="text" name="phone" required placeholder="Nhập số điện thoại" class="box" maxlength="15">
         </div>
         <div class="inputBox">
            <span>Địa chỉ</span>
            <input type="text" name="address" required placeholder="Nhập địa chỉ" class="box" maxlength="100">
         </div>
         <div class="inputBox">
            <span>Tên doanh nghiệp</span>
            <input type="text" name="business_name" required placeholder="Nhập tên doanh nghiệp" class="box" maxlength="100">
         </div>
         <div class="inputBox">
            <span>Loại hình doanh nghiệp</span>
            <select name="business_type" required class="box">
               <option value="">Chọn loại hình doanh nghiệp</option>
               <option value="company">Công ty TNHH</option>
               <option value="cooperative">Hợp tác xã</option>
               <option value="enterprise">Doanh nghiệp tư nhân</option>
               <option value="other">Khác</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Mã số thuế</span>
            <input type="text" name="tax_code" required placeholder="Nhập mã số thuế" class="box" maxlength="20">
         </div>
      </div>
      <input type="submit" value="Đăng ký" class="btn" name="submit">
      <p>Đã có tài khoản? <a href="business_login.php">Đăng nhập ngay</a></p>
      <p>Quay lại <a href="home.php">Trang chủ</a></p>
   </form>
</section>

</body>
</html> 