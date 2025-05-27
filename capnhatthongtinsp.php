<?php

include 'components/connect.php';

session_start();

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// } else {
//     $user_id = '';
// }
// ;
$id_nongdan = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$id_nongdan) {
    echo "<script>alert('Bạn chưa đăng nhập'); window.location.href='login.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lí Sản Phẩm</title>
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
        if ($_SESSION['phanquyen'] === 'nongdan') {
            require("components/user_header_nongdan.php");
        }
        if ($_SESSION['phanquyen'] === 'khachhang') {
            require("components/user_header_khachhang.php");
        }
        
    } else {
        include("components/user_header.php");
    }

    ?> <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nông Sản</h3>
        <p><a href="business_dashboard.php">Trang chủ</a> <span> /Quản lí </span> </p>
    </div>

    <!-- menu section starts  -->


    <section class="products_1">


        <div class="product-wrapper">
            <div class="menu-box">
                <a href="quanlisanpham.php"><i class="fa-solid fa-gear"></i> Quản lí</a>
                <a href="#"><i class="fa fa-plus-square"></i> Xem chi tiết</a>
                <a href="#"><i class="fa fa-plus-square"></i> Thêm sản phẩm</a>
            </div>

            <div class="form-box" enctype="multipart/form-data">
                <div class="form-title">THÊM MỚI SẢN PHẨM</div>
                <form method="POST">
                    <div class="form-group">
                        <label for="txt_manongsan">Mã nông sản</label>
                        <input type="text" id="txt_manongsan" name="txt_manongsan">
                        
                    </div>
                    <div class="form-group">
                        <label for="txt_tenns">Tên nông sản</label>
                        <input type="text" id="txt_tenns" name="txt_tenns">
                    </div>
                    <div class="form-group">
                        <label for="txt_mota">Mô tả</label>
                        <input type="text" id="txt_mota" name="txt_mota">
                    </div>
                    <div class="form-group">
                        <label for="txt_gia">Giá</label>
                        <input type="text" id="txt_gia" name="txt_gia">
                    </div>
                    <div class="form-group">
                        <label for="txt_soluong">Số lượng</label>
                        <input type="text" id="txt_soluong" name="txt_soluong">
                    </div>
                    <div class="form-group">
                        <label for="txt_img">Ảnh sản phẩm</label>
                        <input type="file" id="txt_img" name="txt_img">
                    </div>
                    <div class="form-group">
                        <label for="txt_manongdan">Tên Nông dân</label>
                        <select class="form-control" id="txt_manongdan" name="txt_manongdan">
                            <?php
                            $query = $conn->prepare("
                                SELECT nd.ho_ten AS ten, tt.id_nongdan AS id
                                FROM thongtinnongdan tt
                                JOIN nguoidung nd ON nd.id_nguoidung = tt.id_nongdan
                            ");
                            $query->execute();
                            $ds_nongdan = $query->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <option value="">-- Chọn nông dân --</option>
                            <?php foreach ($ds_nongdan as $nongdan): ?>
                                <option value="<?php echo htmlspecialchars($nongdan['id']); ?>"
                                    <?php echo (isset($id_nongdan) && $nongdan['id'] == $id_nongdan) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($nongdan['ten']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txt_madanhmuc">Mã danh mục</label>
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
                        <label for="txt_ngaytao">Ngày tạo</label>
                        <input type="datetime-local" id="txt_ngaytao" name="txt_ngaytao">
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const now = new Date();

                            const year = now.getFullYear();
                            const month = String(now.getMonth() + 1).padStart(2, '0'); // tháng bắt đầu từ 0
                            const day = String(now.getDate()).padStart(2, '0');
                            const hours = String(now.getHours()).padStart(2, '0');
                            const minutes = String(now.getMinutes()).padStart(2, '0');

                            const localDatetime = `${year}-${month}-${day}T${hours}:${minutes}`;
                            document.getElementById("txt_ngaytao").value = localDatetime;
                        });
                    </script>
                    <div class="form-group">
                        <button type="submit" name="add" class="submit-btn">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
if (isset($_POST['add'])) {
    // Lấy dữ liệu từ form
    $ten_nongsan = $_POST['txt_tenns'];
    $mota = $_POST['txt_mota'];
    $gia = $_POST['txt_gia'];
    $soluong = $_POST['txt_soluong'];
    $id_nongdan = $_POST['txt_manongdan'];
    $id_danhmuc = $_POST['txt_madanhmuc'];
    $ngay_tao = $_POST['txt_ngaytao'];

    // Ràng buộc: đơn giá và số lượng phải > 1
    if ($gia > 1000 && $soluong > 1) {
        // Chuẩn bị câu lệnh insert
        $insert = $conn->prepare("INSERT INTO SanPham 
            (ten_sanpham, mo_ta, Gia, so_luong_ton, id_nongdan, id_danhmuc, ngay_tao) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Thực thi câu lệnh
        $success = $insert->execute([
            $ten_nongsan,
            $mota,
            $gia,
            $soluong,
            $id_nongdan,
            $id_danhmuc,
            $ngay_tao
        ]);

        if ($success) {
            echo "<script>alert('Thêm sản phẩm thành công!');</script>";
        } else {
            echo "<script>alert('Thêm sản phẩm thất bại.');</script>";
        }
    } else {
        echo "<script>alert('Đơn giá >1000 và số lượng phải lớn hơn 1.');</script>";
    }
}
?>



    </section>
    <section class="products_1">
        <div class="form-title">DANH SÁCH SẢN PHẨM MỚI TẠO</div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Nông dân</th>
                    <th>Danh mục</th>
                     <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM SanPham   ORDER BY ngay_tao DESC LIMIT 10");
                $stmt->execute( );
                // $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td><a href='sanpham_chitiet.php?id=" . $row['id_sanpham'] . "'>" . $row['id_sanpham'] . "</a></td>";
                    echo "<td><a href='sanpham_chitiet.php?id=" . $row['id_sanpham'] . "'>" . $row['ten_sanpham'] . "</a></td>";
                    echo "<td>" . $row['mo_ta'] . "</td>";
                    echo "<td>" . number_format($row['gia'], 0, ',', '.') . " VND</td>";
                    echo "<td>" . $row['so_luong_ton'] . "</td>";
                    echo "<td>" . $row['id_nongdan'] . "</td>";
                    echo "<td>" . $row['id_danhmuc'] . "</td>";
                     echo "<td>" . $row['ngay_tao'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
    </section>


    <!-- menu section ends -->
    <!-- footer section starts  -->
    <?php include 'components/footer_admin.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>