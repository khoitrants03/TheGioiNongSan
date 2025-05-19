<?php
include 'components/connect.php';
session_start();

// Kiểm tra và lấy user_id từ session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Thêm cart nếu có
include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bác Sĩ</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="../WebHopital-master/css/style.css">
    <link rel="stylesheet" href="../WebHopital-master/css/ThemHSBN.css">
</head>
<body>

<?php
    include_once("Controller/cQLHS.php");

    // Tạo đối tượng controller
    $controller = new controllQLHS();
?>

<!-- header section starts -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="appointment-section">
    <div class="containerr">
        <h2>Thêm Hồ Sơ Bệnh Nhân</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ma_BN">Mã Bệnh Nhân</label>
                <input type="text" id="ma_BN" name="ma_BN" placeholder="Nhập mã bệnh nhân..." required>
            </div>
            <div class="form-group">
                <label for="ho_ten">Họ tên</label>
                <input type="text" id="ho_ten" name="ho_ten" placeholder="Nhập họ tên..." required>
            </div>
            <div class="form-group">
                <label for="date">Ngày Sinh</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="gioitinh">Giới Tính</label>
                <select id="gioitinh" name="gioitinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dia_chi">Địa Chỉ</label>
                <input type="text" id="dia_chi" name="dia_chi" placeholder="Địa chỉ..." required>
            </div>
            <div class="form-group">
                <label for="sdt">Số Điện Thoại</label>
                <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại..." required>
            </div>
            <div class="form-group">
                <label for="thongtinbaohiem">Thông Tin Bảo Hiểm</label>
                <input type="text" id="thongtinbaohiem" name="thongtinbaohiem" placeholder="Nhập số bảo hiểm...">
            </div>
            <div class="button-group">
                <button class="nut-huy" type="button" onclick="window.location.href = 'QLHSBN.php';">Huỷ</button>
                <button class="nut-luu" type="submit" name="btnSubmit">Thêm Hồ Sơ</button>
            </div>
        </form>
    </div>

<?php
if (isset($_POST["btnSubmit"])) {
    $MaBN = trim($_POST["ma_BN"]);
    $Ten = trim($_POST["ho_ten"]);
    $NgaySinh = trim($_POST["date"]);
    $GioiTinh = trim($_POST["gioitinh"]);
    $DiaChi = trim($_POST["dia_chi"]);
    $SoDienThoai = trim($_POST["sdt"]);
    $ThongTinBaoHiem = trim($_POST["thongtinbaohiem"]);

    // Kiểm tra các trường bắt buộc
    if (empty($MaBN) || empty($Ten) || empty($NgaySinh) || empty($GioiTinh) || empty($DiaChi) || empty($SoDienThoai)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin bắt buộc!');</script>";
    } elseif (!preg_match('/^(02|07|09)\d{8}$/', $SoDienThoai)) {
        // Kiểm tra số điện thoại hợp lệ
        echo "<script>alert('Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số và bắt đầu bằng 02, 07 hoặc 09!');</script>";
    } elseif (strtotime($NgaySinh) > time()) {
        // Kiểm tra ngày sinh phải trước ngày hiện tại
        echo "<script>alert('Ngày sinh phải trước ngày hiện tại!');</script>";
    } else {
        // Gán giá trị mặc định nếu thông tin bảo hiểm để trống
        $ThongTinBaoHiem = !empty($ThongTinBaoHiem) ? $ThongTinBaoHiem : 'Không';

        // Kiểm tra mã bệnh nhân đã tồn tại
        if ($controller->checkLichHenExists($MaBN)) {
            echo "<script>alert('Mã bệnh nhân đã tồn tại. Vui lòng kiểm tra lại!');</script>";
        } else {
            // Thêm hồ sơ bệnh nhân mới
            $result = $controller->addHSBN($MaBN, $Ten, $NgaySinh, $GioiTinh, $DiaChi, $SoDienThoai, $ThongTinBaoHiem);
            if ($result) {
                echo "<script>
                    alert('Thêm hồ sơ thành công!');
                    window.location.href = 'QLHSBN.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Có lỗi xảy ra khi thêm hồ sơ.');</script>";
            }
        }
    }
}
?>

</div>

<!-- footer section starts -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
