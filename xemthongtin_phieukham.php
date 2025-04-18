<?php

include '../../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

include '../../components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bác Sĩ</title>
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
         require("../../components/user_header_doctor.php");
      } elseif ($_SESSION['phanquyen'] === 'bacsi') {
         require("../../components/user_header_doctor.php");
      } elseif ($_SESSION['phanquyen'] === 'benhnhan') {
         require("../../components/user_header_patient.php");
      }
      elseif ($_SESSION['phanquyen'] === 'tieptan') {
         require("../../components/user_header_tieptan.php");
      }
      elseif ($_SESSION['phanquyen'] === 'nhathuoc') {
         require("../../components/user_header_nhathuoc.php");
      } elseif ($_SESSION['phanquyen'] === 'thungan') {
         require("../../components/user_header_thungan.php");
      }
   } else {
      include("../../components/user_header.php");
   }
   ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Lập phiếu khám bệnh</h3>
        <p><a href="../../home.php">Trang chủ</a> <span> / Bác sĩ</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products">


        <div class="box-container">

            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                            Lập phiếu khám</a1>
                    </div>
                    <div class="box-item">
                        <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân
                        </a>
                    </div>

                </div>
            </div>
            <div class="register">

                <?php
                $select_date = $conn->prepare("
                                                SELECT pk.MaPhieu, pk.TinhTrang, pk.NgayGio, bn.NgaySinh, bn.Ten as tenBN,
                                                bc.Ten as tenBS, bc.ChuyenKhoa, kh.TenKhoa 
                                                FROM `PhieuKhamBenh` pk 
                                                    JOIN `bacsi` bc ON pk.MaBS = bc.MaBS 
                                                    join khoakham kh on bc.MaKhoa= kh.MaKhoa 
                                                    join benhnhan bn on pk.MaBN= bn.MaBN
                                                    ORDER BY pk.NgayGio DESC
                                                    LIMIT 1;
                                            ");
                $select_date->execute();


                if ($select_date->rowCount() > 0) {
                    while ($fetch_date = $select_date->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="form-container">
                            <div class="form-title"> Phiếu khám</div>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="name">Mã Phiếu</label>
                                    <input type="text" id="randomNumber" value="<?= $fetch_date['MaPhieu']; ?>"
                                        style="font-size: 2rem;" readonly>

                                </div>

                                <div class="form-group">
                                    <label for="name">Họ tên</label>
                                    <input type="text" id="name" value="<?= $fetch_date['tenBN']; ?>" readonly>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="maBN">Mã Bệnh Nhân</label>
                                    <input type="text" id="maBN" name="maBN" value="<?= ($fetch_patient['MaBN']); ?>" readonly>
                                </div> -->
                                <div class="form-group">
                                    <label for="dob">Ngày sinh</label>
                                    <input type="date" id="dob" value="<?= $fetch_date['NgaySinh']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="chuyenKhoa">Khoa Khám</label>
                                    <input type="text" id="chuyenKhoa" value="<?= $fetch_date['TenKhoa']; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="appointment">Ngày khám</label>
                                    <input type="datetime-local" id="appointment" value="<?= $fetch_date['NgayGio']; ?>" readonly>
                                </div>


                                <div class="form-group">
                                    <label for="chuandoanbenh">Chuẩn đoán bệnh</label>
                                    <textarea id="chuandoanbenh" rows="4" readonly><?= $fetch_date['TinhTrang']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tenBs">Người lập</label>
                                    <input type="text" id="tenBs" value="<?= $fetch_date['tenBS']; ?>" readonly rows="4">
                                </div>
                                <style>
                                    textarea {
                                        border: 2px solid #ccc;
                                        border-radius: 4px;
                                        font-size: 16px;
                                        width: 68%;

                                    }

                                    textarea:valid {
                                        border-color: green;
                                    }
                                </style>
                                <button type="button" class="submit-btn"
                                    onclick="window.location.href='search_patient.php';">sXác nhận</button>

                            </form>
                            <?php
                    }
                } else {
                    echo '<p class="empty">Chưa thông tin  bệnh nhân để hiển thị!</p>';
                }
                ?>
                </div>
            </div>

        </div>

    </section>
    <!-- footer section starts  -->
    <?php include '../../components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="../../js/script.js"></script>

</body>

</html>