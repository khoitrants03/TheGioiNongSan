<?php
include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';
$product_id = $_GET['id'] ?? '';
$row = [];

if ($product_id) {
    // Xử lý cập nhật và xoá
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (isset($_POST['delete'])) {
            $stmt = $conn->prepare("DELETE FROM thongtinnongdan WHERE id = ?");
            $stmt->execute([$product_id]);
            echo "<script>alert('Đã xoá bản ghi!'); window.location.href='chitiet_nhatki.php';</script>";
            exit;
        }

        // Cập nhật thông tin
        $vi_tri = $_POST['vi_tri_trang_trai'];
        $phuong_phap = $_POST['phuong_phap_trong_trot'];
        $ngay_gieo = $_POST['ngay_gieo_trong'];
        $ngay_thu = $_POST['ngay_thu_hoach'];

        $stmt = $conn->prepare("UPDATE thongtinnongdan 
                                SET vi_tri_trang_trai=?, phuong_phap_trong_trot=?, ngay_gieo_trong=?, ngay_thu_hoach=? 
                                WHERE id = ?");
        $stmt->execute([$vi_tri, $phuong_phap, $ngay_gieo, $ngay_thu, $product_id]);

        echo "<script>alert('Cập nhật thành công!'); window.location.href='chitiet_nhatki.php?id=$product_id';</script>";
        exit;
    }

    // Truy vấn dữ liệu chi tiết
    $stmt = $conn->prepare("SELECT tt.*, nd.ho_ten 
                            FROM thongtinnongdan tt 
                            JOIN nguoidung nd ON tt.id_nongdan = nd.id_nguoidung 
                            WHERE tt.id = ?");
    $stmt->execute([$product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết nhật kí</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            padding-top: 20px;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table td input,
        .table td textarea {
            width: 100%;
            border: none;
            background: transparent;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['phanquyen'])) {
        if ($_SESSION['phanquyen'] === 'nongdan') {
            require("components/user_header_nongdan.php");
        }
        if ($_SESSION['phanquyen'] === 'doanhnghiep') {
            require("components/user_header_doanhnghiep.php");
        }
    } else {
        include("components/user_header.php");
    }
    ?>

    <div class="heading">
        <h3>Quản Lí Nhật Kí</h3>
        <p><a href="home.php">Trang chủ</a> <span><a href="#"> /Quản lí </a></span></p>
    </div>

    <div class="container">
        <h3 class="text-center mb-4">Chi tiết nhật ký canh tác</h3>

        <?php if (!empty($row)): ?>
        <form method="post">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID Nông Dân</th>
                            <th>Họ tên</th>
                            <th>Vị trí trang trại</th>
                            <th>Phương pháp gieo trồng</th>
                            <th>Ngày gieo trồng</th>
                            <th>Ngày thu hoạch</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($row['id_nongdan']) ?></td>
                            <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                            <td><input type="text" name="vi_tri_trang_trai" class="form-control"
                                    value="<?= htmlspecialchars($row['vi_tri_trang_trai']) ?>" required></td>
                            <td><input type="text" name="phuong_phap_trong_trot" class="form-control"
                                    value="<?= htmlspecialchars($row['phuong_phap_trong_trot']) ?>" required></td>
                            <td><input type="date" name="ngay_gieo_trong" class="form-control"
                                    value="<?= htmlspecialchars($row['ngay_gieo_trong']) ?>" required></td>
                            <td><input type="date" name="ngay_thu_hoach" class="form-control"
                                    value="<?= htmlspecialchars($row['ngay_thu_hoach']) ?>" required></td>
                            <td>
                                <button type="submit" name="save" class="btn btn-danger mb-2">Lưu thay đổi</button><br>
                                <button type="submit" name="delete" class="btn btn-info"
                                        onclick="return confirm('Bạn có chắc muốn xóa bản ghi này không?');">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <?php else: ?>
            <p class="text-danger">Không tìm thấy dữ liệu nhật ký.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
