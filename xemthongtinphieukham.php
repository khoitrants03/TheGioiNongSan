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
    <title>Bệnh nhân</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
<?php
// Xử lý tìm kiếm khi người dùng gửi biểu mẫu
$searchResult = null; // Biến lưu kết quả tìm kiếm
if (isset($_POST['search'])) {
    $MaBN = $_POST['MaBN'];

<<<<<<< HEAD
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
=======
    if (!empty($MaBN)) {
        try {
            // Truy vấn thông tin phiếu khám dựa trên mã bệnh nhân
            $select_date = $conn->prepare("
                SELECT 
                    pk.MaPhieu, pk.TinhTrang, pk.NgayGio, 
                    bn.NgaySinh, bn.Ten AS tenBN, 
                    bc.Ten AS tenBS, bc.ChuyenKhoa, kh.TenKhoa 
                FROM 
                    `PhieuKhamBenh` pk 
                JOIN 
                    `bacsi` bc ON pk.MaBS = bc.MaBS                                       
                JOIN 
                    `khoakham` kh ON bc.MaKhoa = kh.MaKhoa 
                JOIN 
                    `benhnhan` bn ON pk.MaBN = bn.MaBN 
                WHERE 
                    bn.MaBN = ? 
                ORDER BY 
                    pk.NgayGio DESC 
                LIMIT 1
            ");
            $select_date->execute([$MaBN]);

            if ($select_date->rowCount() > 0) {
                $searchResult = $select_date->fetch(PDO::FETCH_ASSOC);
            } else {
                $searchResult = 'Không tìm thấy thông tin phiếu khám cho bệnh nhân này!';
            }
        } catch (Exception $e) {
            $searchResult = 'Lỗi: ' . $e->getMessage();
        }
    } else {
        $searchResult = 'Vui lòng nhập mã bệnh nhân!';
    }
}
?>
    <!-- Header Section -->
    <?php include 'components/user_header.php'; ?>
>>>>>>> ae9bf74a94eebb4d1ceab69bcf6e108b24c256c1

    <div class="heading">
        <h3>Hồ sơ bệnh án</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bệnh nhân</span></p>
    </div>
    <section class="products" style="min-height: 100vh; padding-top: 30;">
        <div class="box-container">

            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                           Phiếu khám bệnh</a1>
                    </div>
                    <div class="box-item">
                        <a href="xemthongtinxetnghiem.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Xét nghiệm
                        </a>
                    </div>
                    <div class="box-item">
                        <a href="xemthongtindonthuoc.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Đơn thuốc
                        </a>
                    </div>
                </div>
            </div>
            <div class="register">

        <!-- Form tìm kiếm -->
        <form action="" method="post" class="search-form">
            <label for="MaBN">Nhập mã bệnh nhân:</label>
            <input type="text" name="MaBN" id="MaBN" placeholder="Nhập mã bệnh nhân" required>
            <button type="submit" name="search">Tìm kiếm</button>
        </form>

        <!-- Kết quả tìm kiếm -->
        <div class="result-section">
            <?php
            if ($searchResult) {
                if (is_array($searchResult)) {
                    // Hiển thị thông tin phiếu khám nếu tìm thấy
                    ?>
                    <div class="form-container">
                        <div class="form-title">Thông tin phiếu khám bệnh</div>
                        <form>
                            <div class="form-group">
                                <label for="randomNumber">Mã Phiếu</label>
                                <input type="text" id="randomNumber" value="<?= $searchResult['MaPhieu']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="name">Họ tên</label>
                                <input type="text" id="name" value="<?= $searchResult['tenBN']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="dob">Ngày sinh</label>
                                <input type="date" id="dob" value="<?= $searchResult['NgaySinh']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="chuyenKhoa">Khoa Khám</label>
                                <input type="text" id="chuyenKhoa" value="<?= $searchResult['TenKhoa']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="appointment">Ngày khám</label>
                                <input type="datetime-local" id="appointment" value="<?= $searchResult['NgayGio']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="chuandoanbenh">Chuẩn đoán bệnh</label>
                                <textarea id="chuandoanbenh" rows="4" readonly><?= $searchResult['TinhTrang']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tenBs">Người lập</label>
                                <input type="text" id="tenBs" value="<?= $searchResult['tenBS']; ?>" readonly>
                            </div>
                        </form>
                    </div>
                    <?php
                } else {
                    // Hiển thị thông báo lỗi
                    echo '<p class="empty">' . $searchResult . '</p>';
                }
            }
            ?>
        </div>

    </section>

    <!-- Footer Section -->
    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
