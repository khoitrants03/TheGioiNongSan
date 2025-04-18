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
    <title>Dịch vụ</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
     <link rel="stylesheet" href="css/style.css">

</head>

<body>

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
   ?>
 
    <div class="heading">
         <p><a href="home.php">Trang chủ</a> <span> / Tra cứu</span></p>
    </div>

 
    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>Tra cứu thông tin</a>
                    </div>
                    <div class="box-item">
                        <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân</a>
                    </div>
                </div>
            </div>

            <div class="register">
                <div class="form-container">
                    <div class="form-title">Tra cứu thông tin bệnh nhân</div>
                    <form method="POST">

                        <div class="form-group">
                            <label for="mabn">Mã định danh</label>
                            <input type="text" name="mabn">
                        </div>
                        <button type="submit" class="submit-btn" name="search_btn"> Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- tìm kiếm thông tin bệnh nhân -->
    <section class="products" style="min-height: 100vh; padding-top:0;">
        <div class="box-container">
            <?php
            if (isset($_POST['search_btn'])) {
                 $mabn = $_POST['mabn'];

                 $select_patient = $conn->prepare("SELECT * FROM `benhnhan` WHERE MaBN LIKE ?");
                $search_value = "%$mabn%";
                $select_patient->bindParam(1, $search_value, PDO::PARAM_STR);
                $select_patient->execute();

                if ($select_patient->rowCount() > 0) {

                    while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="pid" value="  <?= htmlspecialchars($fetch_patient['MaBN']); ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_patient['Ten']); ?>">
                            <input type="hidden" name="phone" value="<?= htmlspecialchars($fetch_patient['SoDienThoai']); ?>">
                            <a href="laphoadon.php?pid=<?= $fetch_patient['MaBN']; ?>" class="fas fa-eye"></a>
                            <div class="name">
                                <?= htmlspecialchars($fetch_patient['Ten']); ?>
                            </div>
                            <div class="flex">
                                <div class="pid">
                                    <?= htmlspecialchars($fetch_patient['MaBN']); ?>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="phone">
                                    <?= htmlspecialchars($fetch_patient['SoDienThoai']); ?>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo '<p class="empty">Bệnh nhân không có sẵn!</p>';
                }
            }
            ?>
        </div>
    </section>

 

    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
 

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>