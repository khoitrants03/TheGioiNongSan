<?php
// patient_access.php
session_start();
// Include database connection
include('components/connect.php'); // Kết nối cơ sở dữ liệu

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;
 
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaBN = $_POST['MaBN'];
//    $Ten = $_POST['Ten'];
//    $NgaySinh = $_POST['NgaySinh'];
//   $GioiTinh = $_POST['GioiTinh'];
  //  $DiaChi = $_POST['DiaChi'];
    //$SoDienThoai = $_POST['SoDienThoai'];
    //x$ThongTinBaoHiem = $_POST['ThongTinBaoHiem'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bệnh nhân</title>
    <link rel="shortcut icon" href="../imgs/hospital-solid.svg" type="image/x-icon">
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
        <h3>Hồ sơ bệnh án</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bệnh nhân</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products" >
        <div class="box-container">
            <div class="register">
                <div class="form-container">
                    <div class="form-title">Tìm kiếm hồ sơ bệnh án</div>
                    <form method="POST">

                        <div class="form-group">
                            <label for="MaBN">Mã bệnh nhân</label>
                            <input type="text" name="MaBN">
                        </div>
                        <button type="submit" class="submit-btn" name="search_btn">Tìm kiếm</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- tìm kiếm thông tin bệnh nhân -->
    <section class="products" style="min-height: 10vh; padding-top:0;">
        <div class="box-container">
            <?php
            if (isset($_POST['search_btn'])) {
                // Get user input safely
                $MaBN = $_POST['MaBN'];

                // Secure query with parameterized SQL
                $select_patient = $conn->prepare("SELECT * FROM `benhnhan` WHERE MaBN LIKE ?");
                $search_value = "%$MaBN%";
                $select_patient->bindParam(1, $search_value, PDO::PARAM_STR);
                $select_patient->execute();

                if ($select_patient->rowCount() > 0) {

                    while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="pid" value="  <?= htmlspecialchars($fetch_patient['MaBN']); ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_patient['Ten']); ?>">
                            <input type="hidden" name="phone" value="<?= htmlspecialchars($fetch_patient['SoDienThoai']); ?>">
                            <a href="patient_record.php?pid=<?= $fetch_patient['MaBN']; ?>" class="fas fa-eye"></a>
                            <div class="flex">
                                <div class="pid">
                                    <?= htmlspecialchars($fetch_patient['MaBN']); ?>
                                </div>
                            </div>
                            <div class="name">
                                <?= htmlspecialchars($fetch_patient['Ten']); ?>
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo '<p class="empty">Không tìm thấy hồ sơ!</p>';
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
    <script src="js/script.js"></script>

</body>

</html>