<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Dashboard</span></a>

      <nav class="navbar">
         <a href="dashboard.php">Trang chủ</a>
          <a href="placed_orders.php">Đơn hàng</a>
         <a href="admin_accounts.php">Admin</a>
         <a href="users_accounts.php">Người dùng</a>
         <a href="messages.php">Phản hồi</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Cập nhật thông tin</a>
         <div class="flex-btn">
            <a href="admin_login.php" class="option-btn">Đăng nhập</a>
            <a href="register_admin.php" class="option-btn">Đăng ký</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('Bạn thực sự muốn đăng xuất?');" class="delete-btn">Đăng xuất</a>
      </div>

   </section>

</header>
<script src="../js/admin_script.js"></script>
