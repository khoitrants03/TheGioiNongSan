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
    <title>Bệnh nhân</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
      }
      elseif ($_SESSION['phanquyen'] === 'tieptan') {
         require("components/user_header_tieptan.php");
      }
      elseif ($_SESSION['phanquyen'] === 'nhathuoc') {
         require("components/user_header_nhathuoc.php");
      }
   } else {
      include("components/user_header.php");
   }
   ?>  <!-- header section ends -->

    <div class="heading">
        <h3>Đăng kí khám bệnh</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Tiếp tân</span><span> / Đăng kí khám bệnh</span></p>
    </div>

    <!-- menu section starts  -->
    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a href="#"><i class="fa-solid fa-gears"></i> Đăng kí khám bệnh</a>
                    </div>
                    <div class="box-item">
                        <a href="Register_medical_new.php"><i class="fa fa-plus-square" aria-hidden="true"></i> Bệnh
                            nhân mới</a>
                    </div>
                    <div class="box-item">
                        <a href="Register_medical_old.php"><i class="fa fa-plus-square" aria-hidden="true"></i> Bệnh
                            nhân cũ</a>
                    </div>
                </div>
            </div>
            <div class="register">
                <?php
                $select_date = $conn->prepare("SELECT lh.MaLichHen, lh.MaBS, lh.MaBN, lh.Ngay, lh.Gio, lh.STT,lh.PhongKham, lh.KhoaKham, bc.Ten 
            FROM `lichhen` lh 
            JOIN `bacsi` bc ON lh.MaBS = bc.MaBS 
            ORDER BY lh.MaLichHen DESC LIMIT 1");
                $select_date->execute();

                if ($select_date->rowCount() > 0) {
                    while ($fetch_date = $select_date->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <div class="form-container">
                            <div class="form-title"> Thông Tin Đăng Kí</div>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="randomNumber">Mã lịch hẹn</label>
                                    <input type="text" id="randomNumber" name="randomNumber" style="font-size: 2rem;"
                                        value="<?= $fetch_date['MaLichHen']; ?>" readonly>

                                </div>

                                <div class="form-group">
                                    <label for="maBN">Mã BN</label>
                                    <input type="text" name="maBN" id="maBN" value="<?= $fetch_date['MaBN']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="STT">STT</label>
                                    <input type="text" name="STT" id="STT" value="<?= $fetch_date['STT']; ?>" readonly>

                                </div>
                                <!-- chọn theo tên bác sĩ -->
                                <div class="form-group">
                                    <label for="department">Khoa khám bệnh</label>
                                    <input type="text" value="<?= $fetch_date['KhoaKham']; ?>" readonly>

                                </div>
                                <div class="form-group">
                                    <label for="class">Phòng khám</label>
                                    <input type="text" name="class" id="class" value="<?= $fetch_date['PhongKham']; ?>"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="doctor">Bác Sĩ</label>
                                    <input type="text" name="doctor" id="doctor" value="<?= $fetch_date['Ten']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="appointment">Ngày khám</label>
                                    <input type="date" name="appointment" id="appointment" value="<?= $fetch_date['Ngay']; ?>"
                                        readonly>
                                </div>
                                <button type="button" class="submit-btn" onclick="window.location.href='home.php';">Xác nhận</button>                            </form>

                        </div>
                    </div>
                    <?php
                    }
                } else {
                    echo '<p class="empty">Chưa thông tin  bệnh nhân để hiển thị!</p>';
                }
                ?>
        </div>


    </section>

    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->

    <script src="js/script.js"></script>
</body>

</html>