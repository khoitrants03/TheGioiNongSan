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
    <title>Bệnh nhân</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../../css/style.css">

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
      } elseif ($_SESSION['phanquyen'] === 'thungan') {
         require("components/user_header_thungan.php");
      }
   } else {
      include("components/user_header.php");
   }
   ?> <!-- header section ends -->

    <div class="heading">
        <h3>Hồ sơ bệnh án</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bệnh nhân</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products" style="min-height: 100vh; padding-top: 30;">


        <div class="box-container">

            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                           Đơn thuốc</a1>
                    </div>
                    <div class="box-item">
                        <a href="xemthongtinxetnghiem.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Xét nghiệm
                        </a>
                    </div>
                    <div class="box-item">
                        <a href="xemthongtinphieukham.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Phiếu khám bệnh
                        </a>
                    </div>
                </div>
            </div>
            <div class="register">

                <?php
                $select_patient = $conn->prepare("
                                    SELECT 
                                        benhnhan.MaBN AS PatientID,
                                        benhnhan.Ten AS PatientName,
                                        benhnhan.GioiTinh AS Gender,
                                        donthuoc.MaDonThuoc AS PrescriptionID,
                                        donthuoc.Ten AS PrescriptionName,
                                        donthuoc.LieuLuong AS Dosage
                                    FROM 
                                        benhnhan
                                    JOIN 
                                        donthuoc ON benhnhan.MaBN = donthuoc.MaDonThuoc
                                    JOIN 
                                        chitiet_thuoc ON donthuoc.MaDonThuoc = chitiet_thuoc.MaDonThuoc;
                                        ");
                $select_patient->execute();


                if ($select_patient->rowCount() > 0) {
                    while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="form-container">
                            <div class="form-title"> Thông tin đơn thuốc</div>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="name">Mã bệnh nhân</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['PatientID']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Họ tên</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['PatientName']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Giới tính</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['Gender']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Mã đơn thuốc</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['PrescriptionID']; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="name">Liều lượng</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['Dosage']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Tên đơn thuốc</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['PrescriptionName']; ?>">
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
                            </form>
                            <?php
                    }
                }else {
                    echo '<p class="empty">Chưa có thông tin  bệnh nhân để hiển thị!</p>';
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