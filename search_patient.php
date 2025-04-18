<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header_doctor.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Lập phiếu khám bệnh</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bác sĩ</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>Lập phiếu khám</a>
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
                            <a href="ticketing_doctor.php?pid=<?= $fetch_patient['MaBN']; ?>" class="fas fa-eye"></a>
                            <div class="flex">
                                <div class="pid">
                                    Mã :
                                    <?= htmlspecialchars($fetch_patient['MaBN']); ?>
                                </div>
                            </div>
                            <div class="name">
                                Tên bệnh nhân:
                                <?= htmlspecialchars($fetch_patient['Ten']); ?>
                            </div>

                            <div class="flex">
                                <div class="phone">
                                    Số điện thoại:
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

    <!-- menu section ends -->


    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src=" js/script.js"></script>

</body>

</html>