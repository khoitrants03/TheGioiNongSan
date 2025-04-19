<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = $_POST['cpass'];
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Tên người dùng đã tồn tại!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $pass]);
         $message[] = 'Đăng ký thành công!';
      }
   }
}
