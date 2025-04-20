<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ</title>
    <link rel="shortcut icon" href="imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- header section starts -->
    <?php
    if (isset($_SESSION['phanquyen'])) {
        if ($_SESSION['phanquyen'] === 'nongdan') {
            require("components/user_header_nongdan.php");
        }
    } else {
        include("components/user_header.php");
    }
    ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Đăng kí khám bệnh</h3>
        <p><a href="home.php">Trang chủ</a> <span> /Tiếp tân</span><span> / Đăng kí khám bệnh</span></p>
    </div>

    <!-- menu section starts -->
    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i> Đăng kí khám bệnh</a>
                    </div>
                    <div class="box-item">
                        <a href="Register_medical_new.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân
                            mới</a>
                    </div>
                    <div class="box-item">
                        <a href="Register_medical_old.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân
                            cũ</a>
                    </div>
                </div>
            </div>
            <div class="register">
                <div class="form-container">
                    <div class="form-title">Đăng kí bệnh nhân mới</div>
                    <form method="POST">
                        
                        <div class="form-group">
                            <label for="txt_soluong">Số lượng </label>
                            <input type="text" id="txt_soluong" name="txt_soluong">
                        </div>

                        <div class="form-group">
                            <label for="txt_manongdan">Mã Nông dân </label>
                            <select id="txt_manongdan" name="txt_manongdan">
                                <?php
                                $query = $conn->prepare("SELECT id_nguoidung FROM NguoiDung");
                                $query->execute();
                                if ($query->rowCount() > 0) {
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['id_nguoidung'] . "'>" . $row['id_nguoidung'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No  available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txt_madanhmuc">Mã danh mục </label>
                            <select id="txt_madanhmuc" name="txt_madanhmuc">
                                <?php
                                $query = $conn->prepare("SELECT id_danhmuc FROM danhmucsanpham");
                                $query->execute();
                                if ($query->rowCount() > 0) {
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['id_danhmuc'] . "'>" . $row['id_danhmuc'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No  available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txt_maqr">Mã qr </label>
                            <select id="txt_maqr" name="txt_maqr">
                                <?php
                                $query = $conn->prepare("SELECT id_qrcodr FROM qrcode");
                                $query->execute();
                                if ($query->rowCount() > 0) {
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['id_qrcodr'] . "'>" . $row['id_qrcodr'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No  available</option>";
                                }
                                ?>
                            </select>
                  
                        <button type="submit" class="submit-btn" name="addnew_patient">Xác nhận</button>
                        <?php if (isset($_SESSION['user_id'])): ?>

                        <?php else: ?>
                            <p class="notice">Vui lòng <a href="login.php">đăng nhập</a> để đăng kí khám bệnh.</p>
                        <?php endif; ?>


                    </form>
                </div>
            </div>

        
        </div>
    </section>
    <!-- menu section ends -->

    <!-- footer section starts -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link -->
    <script src="js/script.js"></script>

</body>

</html>