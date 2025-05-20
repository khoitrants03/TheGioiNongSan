<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connect.php';

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// Get user info if logged in
$user_id = '';
$user_name = '';
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

$user_name = null;
if ($user_id) {
    $select_profile = $conn->prepare("SELECT * FROM `nguoidung` WHERE id_nguoidung = ?");
    $select_profile->execute([$user_id]);
    if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        $user_name = $fetch_profile['ho_ten'];
    }
}

// Get cart count
$cart_count = 0;
if ($user_id != '') {
    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $select_cart->execute([$user_id]);
    $cart_count = $select_cart->rowCount();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheGioiNongSan</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <!-- Thông báo -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>

    <!-- Header -->
    <header class="header">
        <section class="content_bg-white">
            <a href="../TheGioiNongSan" class="logo"><i id="logo" class="fas fa-tractor"></i>TheGioiNongSan</a>

            <nav class="navbar">
                <a href="#"><i class="fas fa-phone-volume"></i> KHẨN CẤP: 1900 10854</a>
                <a href="#"><i class="fas fa-clock"></i> GIỜ LÀM VIỆC: 24/7</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> VỊ TRÍ: TP.HCM</a>
            </nav>
        </section>

        <section class="flex">
            <nav class="navbar">
                <div class="dropdown">
                    <a href="product.php" class="dropdown-toggle">DANH MỤC SẢN PHAM</a>
                    <div class="dropdown-content">
                        <a href="#">Rau củ sạch</a>
                        <a href="#">Bún miến</a>
                        <a href="#">Gạo các lọai</a>
                        <a href="#">Nông sản sạch</a>
                    </div>
                </div>
                <a href="../about.php">Về chúng tôi</a>
                <a href="../TheGioiNongSan/scan_qr.php">Quét mã QR</a>
                <a href="#" class="dropdown-toggle">Rau củ sạch</a>
                <a href="#" class="dropdown-toggle">Bún miến</a>
                <a href="#" class="dropdown-toggle">Gạo các lọai</a>
                <a href="../contact.php">Liên hệ</a>
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
                <div class="profile-header">
                    <div class="profile-picture">
                        <?php if(!empty($fetch_profile['image'])): ?>
                            <img src="../uploaded_img/<?= $fetch_profile['image']; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="../imgs/default-avatar.png" alt="Default Profile Picture">
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h3><?= $fetch_profile['ho_ten']; ?></h3>
                        <p><?= $fetch_profile['email']; ?></p>
                    </div>
                </div>
                <div class="profile-links">
                    <a href="../profile.php" class="btn">Thông tin cá nhân</a>
                <p class="name"><?= $fetch_profile['ho_ten']; ?></p>
                <div class="flex">
                    <a href="profile.php" class="btn">Thông tin cá nhân</a>
                    <a href="orders.php" class="btn">Đơn hàng của tôi</a>
                    <a href="wishlist.php" class="btn">Danh sách yêu thích</a>
                </div>
                <div class="flex-btn">
                    <a href="components/user_logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');" class="delete-btn">Đăng xuất</a>
                </div>
            </div>
        </section>
    </header>

    <script src="../js/script.js"></script>
</body>

</html>