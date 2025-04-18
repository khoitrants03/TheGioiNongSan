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
    <section class="content_bg-white">

        <a href="home.php" class="logo"><i id="logo" class="fa-sharp-duotone fa-solid fa-tractor"></i>thegioinongsan</a>

        <nav class="navbar">
            <a href="#"><i class="fa-duotone fa-solid fa-phone-volume"></i> KHẨN CẤP: 1900 10854</a>
            <a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i></i>GIỜ LÀM VIỆC: 27/7</a>
            <a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i>
                </i>VỊ TRÍ: TP.HCM</a>
        </nav>
    </section>
    <section class="flex">
        <nav class="navbar">
            <a href="home.php"><i class="fa-solid fa-bars" aria-hidden="true"></i>DANH MỤC SAN PHAM</a>
            <a href="about.php">Về chúng tôi</a>

            
             <a href="contact.php">Liên hệ</a>
        </nav>

        <div class="icons">
            <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
            ?>
            <a href="search.php"><i class="fas fa-search"></i></a>

            <div id="user-btn" class="fas fa-user"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
                <p class="name">
                    <?= $fetch_profile['name']; ?>
                </p>
                <div class="flex">
                    <a href="profile.php" class="btn">Thông tin</a>
                    <a href="components/user_logout.php" onclick="return confirm('Bạn có chắc muốn đăng xuất?');"
                        class="delete-btn">Đăng xuất</a>
                </div>
                <p class="account">
                    <a href="login.php">Đăng nhập</a> or
                    <a href="register.php">Đăng ký</a>
                </p>
                <?php
            } else {
                ?>
                <p class="name">Vui lòng đăng nhập!</p>
                <a href="login.php" class="btn">Đăng nhập</a>
                <?php
            }
            ?>
        </div>
    </section>

</header>

</header>