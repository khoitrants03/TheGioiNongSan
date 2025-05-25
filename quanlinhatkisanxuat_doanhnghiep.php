<?php

include 'components/connect.php';

session_start();

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// } else {
//     $user_id = '';
// }
;
// $id_nongdan = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// if (!$id_nongdan) {
//     echo "<script>alert('Bạn chưa đăng nhập'); window.location.href='login.php';</script>";
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lí nhật kí sản xuất</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nhật Kí</h3>
        <p><a href="business_dashboard.php">Trang chủ</a> <span> /Quản lí </span> </p>
    </div>

    <!-- menu section starts  -->


    <section class="products_1">


        <div class="product-wrapper">
            <div class="menu-box">
                <a href="danhsachnhatki_doanhnghiep.php"><i class="fa-solid fa-gear"></i> Quản lí</a>
                <a href="#"><i class="fa fa-plus-square"></i> Xem chi tiết</a>
                <a href="#"><i class="fa fa-plus-square"></i> Thêm mới</a>
            </div>

            <div class="form-box" enctype="multipart/form-data">
                <div class="form-title">THÊM MỚI </div>
                <form method="POST">
                    <div class="form-group">
                        <label for="txt_manongdan">Chọn Nông Dân</label>
                        <select class="form-control" id="txt_manongdan" name="txt_manongdan" required>
                            <option value="">-- Chọn nông dân --</option>
                            <?php
                            // Kết nối CSDL và truy vấn danh sách nông dân
                            $stmt = $conn->prepare("SELECT ho_ten, id_nguoidung FROM nguoidung WHERE id_vaitro = 1");
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($row['id_nguoidung'] == $id_nongdan) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($row['id_nguoidung']) . "' $selected>" . htmlspecialchars($row['ho_ten']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="txt_vitri">Vị trí trang trại</label>
                        <input type="text" id="txt_vitri" name="txt_vitri" placeholder="Đang lấy vị trí...">
                    </div>

                    <script>
                        if ("geolocation" in navigator) {
                            navigator.geolocation.getCurrentPosition(function (position) {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;

                                // Gọi API Nominatim để lấy địa chỉ cụ thể
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`, {
                                    headers: {
                                        'User-Agent': 'ViFarm App' // Tránh bị chặn bởi API
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        const address = data.display_name || `${lat}, ${lon}`;
                                        document.getElementById("txt_vitri").value = address;
                                    })
                                    .catch(error => {
                                        console.error("Không thể lấy địa chỉ:", error);
                                        document.getElementById("txt_vitri").value = ""; // Không dùng tọa độ nếu thất bại
                                    });

                            }, function (error) {
                                document.getElementById("txt_vitri").placeholder = "Không thể lấy vị trí";
                                console.error("Lỗi lấy vị trí:", error.message);
                            });
                        } else {
                            document.getElementById("txt_vitri").placeholder = "Trình duyệt không hỗ trợ định vị";
                        }
                    </script>
                    <div class="form-group">
                        <label for="txt_phuongphap">Phương pháp gieo trồng</label>
                        <select id="txt_phuongphap" name="txt_phuongphap" class="form-control">
                            <option value="">-- Chọn phương pháp --</option>
                            <option value="truyen_thong">Gieo trồng truyền thống</option>
                            <option value="thuy_canh">Thủy canh</option>
                            <option value="khi_can">Khí canh</option>
                            <option value="tu_dong">Gieo trồng tự động (Công nghệ cao)</option>
                            <option value="trong_phu_mang">Trồng phủ màng (nhà màng, nhà kính)</option>
                            <option value="trong_tren_gia_the">Trồng trên giá thể</option>
                            <option value="hữu_cơ">Canh tác hữu cơ</option>
                            <option value="tu_nhien">Canh tác tự nhiên (Natural Farming)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txt_ngaygieotrong" style="width:36%;">Ngày Gieo Trồng</label>
                        <input type="datetime-local" id="txt_ngaygieotrong" name="txt_ngaygieotrong">
                    </div>
                    <div class="form-group">
                        <label for="txt_ngaythuhoach" style="width:36%;">Ngay Thu Hoạch</label>
                        <input type="datetime-local" id="txt_ngaythuhoach" name="txt_ngaythuhoach">
                    </div>

                    <div class="form-group">
                        <button type="submit" name="add" class="submit-btn">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        if (isset($_POST['add'])) {
            // Lấy dữ liệu từ form
            $id_nongdan = $_POST['txt_manongdan'];
            $vitri = $_POST['txt_vitri'];
            $pp_gieotrong = isset($_POST['txt_phuongphap']) ? $_POST['txt_phuongphap'] : '';
            $ngay_gieo = $_POST['txt_ngaygieotrong'];
            $ngay_thu = $_POST['txt_ngaythuhoach'];

            // Kiểm tra điều kiện ngày gieo phải <= ngày thu
            if (strtotime($ngay_gieo) > strtotime($ngay_thu)) {
                echo "<script>alert('Lỗi: Ngày gieo trồng phải trước hoặc bằng ngày thu hoạch.');</script>";
            } else {
                // Chuẩn bị câu lệnh insert
                $insert = $conn->prepare("INSERT INTO ThongTinNongDan 
            (id_nongdan, vi_tri_trang_trai, phuong_phap_trong_trot, ngay_gieo_trong, ngay_thu_hoach) 
            VALUES (?, ?, ?, ?, ?)");

                // Thực thi câu lệnh
                $success = $insert->execute([
                    $id_nongdan,
                    $vitri,
                    $pp_gieotrong,
                    $ngay_gieo,
                    $ngay_thu
                ]);

                if ($success) {
                    echo "<script>alert('Thêm nhật ký thành công!');</script>";
                } else {
                    echo "<script>alert('Thêm thất bại.');</script>";
                }
            }
        }

        ?>



    </section>
    <section class="form-box">
        <div class="form-title">DANH SÁCH SẢN PHẨM MỚI TẠO</div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID Nông Dân</th>
                    <th>Vị trí</th>
                    <th>Phương pháp gieo trồng</th>
                    <th>Ngày gieo trồng</th>
                    <th>Ngày thu hoạch</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Chuẩn bị truy vấn và thực thi
                $stmt = $conn->prepare("SELECT * FROM thongtinnongdan ");
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_nongdan'] . "</td>";
                    echo "<td>" . $row['vi_tri_trang_trai'] . "</td>";
                    echo "<td>" . $row['phuong_phap_trong_trot'] . "</td>";
                    echo "<td>" . $row['ngay_gieo_trong'] . "</td>";
                    echo "<td>" . $row['ngay_thu_hoach'] . "</td>";
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