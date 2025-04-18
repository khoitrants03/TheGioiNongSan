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
    <title>Thanh toán tiền</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- header section starts  -->
    <?php
    if (isset($_SESSION['phanquyen'])) {
        if ($_SESSION['phanquyen'] === 'nhanvien') {
            require("components/user_header_doctor.php");
        } elseif ($_SESSION['phanquyen'] === 'bacsi') {
            require("components/user_header_doctor.php");
        } elseif ($_SESSION['phanquyen'] === 'benhnhan') {
            require("components/user_header_patient.php");
        } elseif ($_SESSION['phanquyen'] === 'tieptan') {
            require("components/user_header_tieptan.php");
        } elseif ($_SESSION['phanquyen'] === 'nhathuoc') {
            require("components/user_header_nhathuoc.php");
        } elseif ($_SESSION['phanquyen'] === 'thungan') {
            require("components/user_header_thungan.php");
        }
    } else {
        include("components/user_header.php");
    }
    ?>
    <div class="heading">
        <h3>Lập phiếu khám bệnh</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bác sĩ</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products">
        <?php
        $pid = $_GET['pid'];
        $select_patient = $conn->prepare("SELECT * FROM `benhnhan` WHERE MaBN = ?");
        $select_patient->execute([$pid]);
        if ($select_patient->rowCount() > 0) {
            while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <div class="box-container">

                    <div class="service">
                        <div class="box_register">
                            <div class="box-item">
                                <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                                    Lập hóa đơn</a1>
                            </div>
                            <div class="box-item">
                                <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="register">
                        <div class="form-container">
                            <div class="form-title">Lập hóa đơn</div>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="name">Mã Giao Dịch</label>
                                    <input type="number" id="randomNumber" name="randomNumber" style="font-size: 2rem;"
                                        readonly>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const randomNum = Math.floor(Math.random() * 10000) + 1;
                                            document.getElementById("randomNumber").value = randomNum;
                                        });
                                    </script>
                                </div>

                                <div class="form-group">
                                    <label for="maThuNgan">Mã Thu Ngân</label>
                                    <select id="maThuNgan" name="maThuNgan">
                                        <?php
                                        $query = $conn->prepare("SELECT MaThuNgan FROM thungan");
                                        $query->execute();

                                        if ($query->rowCount() > 0) {
                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['MaThuNgan'] . "'>" . $row['MaThuNgan'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Không có thu ngân</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="maDonThuoc">Mã Đơn Thuốc</label>
                                    <input type="text" id="maDonThuoc" name="maDonThuoc">
                                </div>
                                <div class="form-group">
                                    <label for="maBN">Mã BN</label>
                                    <input type="text" id="maBN" value="<?= $fetch_patient['MaBN']; ?>" name="maBN">
                                </div>

                                <div class="form-group">
                                    <label for="dob">Ngày</label>
                                    <input type="date" id="dob" name="dob">
                                </div>
                                <div class="form-group">
                                    <label for="soTien">Số tiền</label>
                                    <input type="text" id="soTien" name="soTien" class="form-control" min="0" step="1000"
                                        placeholder="Nhập số tiền">
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Phương thức thanh toán</label>
                                    <select id="payment_method" name="payment_method" class="form-control">
                                        <option value="Chuyển khoản">Chuyển khoản</option>
                                        <option value="Tiền mặt">Tiền mặt</option>
                                    </select>
                                </div>

                                <button type="submit" class="submit-btn" name="add-phieuhoadon">Xác nhận</button>
                            </form>
                        </div>
                    </div>

                </div>
                <?php
            }
        } else {
            echo '<p class="empty">Chưa thông tin  bệnh nhân để hiển thị!</p>';
        }
        ?>
    </section>

    <?php

    if (isset($_POST['add-phieuhoadon'])) {
        $randomNumber = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
        $maThuNgan = filter_var($_POST['maThuNgan'], FILTER_SANITIZE_STRING);

        $maDonThuoc = isset($_POST['maDonThuoc']) && !empty($_POST['maDonThuoc'])
            ? filter_var($_POST['maDonThuoc'], FILTER_SANITIZE_STRING)
            : NULL;
        $maBN = filter_var($_POST['maBN'], FILTER_SANITIZE_STRING);

        $soTien = filter_var($_POST['soTien'], FILTER_SANITIZE_STRING);
        $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);
        $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);

        $insert_patient = $conn->prepare("INSERT INTO `hoadon`(MaGiaoDich, MaThuNgan, MaBN, MaDonThuoc, Ngay, SoTien, PhuongThucThanhToan) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");

        $insert_patient->execute([$randomNumber, $maThuNgan, $maBN, $maDonThuoc, $dob, $soTien, $payment_method]);

        echo "<script>
                alert(' Thành Công.');
             </script>";

    }
    ?>

    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script src=" js/script.js"></script>

</body>

</html>