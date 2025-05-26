<?php
include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Nếu form được submit
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Nếu nhấn nút XÓA
        if (isset($_POST['delete'])) {
            $delete = $conn->prepare("DELETE FROM SanPham WHERE id_sanpham = ?");
            $delete->execute([$product_id]);

            echo "<script>alert('Đã xoá sản phẩm!'); window.location.href='quanlisanpham_doanhnghiep.php';</script>";
            exit;
        }

        // Ngược lại là CẬP NHẬT
        $ten_sanpham = $_POST['ten_sanpham'];
        $mo_ta = $_POST['mo_ta'];
        $gia = $_POST['gia'];
        $so_luong_ton = $_POST['so_luong_ton'];

        $update = $conn->prepare("UPDATE SanPham SET ten_sanpham=?, mo_ta=?, gia=?, so_luong_ton=? WHERE id_sanpham=?");
        $update->execute([$ten_sanpham, $mo_ta, $gia, $so_luong_ton, $product_id]);

        echo "<script>alert('Cập nhật thành công!');window.location.href='quanlisanpham_doanhnghiep.php';</script>";
    }

    // Lấy lại dữ liệu sau cập nhật hoặc trước khi hiển thị
    $stmt = $conn->prepare("SELECT * FROM SanPham WHERE id_sanpham = ?");
    $stmt->execute([$product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <title>Chi tiết sản phẩm</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        body, html {
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
    // if (isset($_SESSION['phanquyen']) && $_SESSION['phanquyen'] === 'nongdan') {
    //     require("components/user_header_nongdan.php");
    // } else {
    //     include("components/user_header.php");
    // }
    ?>
    <div class="heading">
        <h3>Quản Lí Nông Sản</h3>
        <p><a href="home.php">Trang chủ</a> <span><a href="capnhatthongtinsp.php"> /Quản lí </a></span></p>
    </div>

    <div class="container">
        <h3 class="text-center mb-4">Chi tiết sản phẩm</h3>

        <?php if (!empty($row)): ?>
            <form method="post">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
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
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($row['id_sanpham']) ?></td>
                                <td><input type="text" name="ten_sanpham" value="<?= htmlspecialchars($row['ten_sanpham']) ?>" required></td>
                                <td><textarea name="mo_ta" rows="2" required><?= htmlspecialchars($row['mo_ta']) ?></textarea></td>
                                <td><input type="number" name="gia" value="<?= htmlspecialchars($row['gia']) ?>" required></td>
                                <td><input type="number" name="so_luong_ton" value="<?= htmlspecialchars($row['so_luong_ton']) ?>" required></td>
                                <td><?= htmlspecialchars($row['id_nongdan']) ?></td>
                                <td><?= htmlspecialchars($row['id_danhmuc']) ?></td>
                                 <td><?= htmlspecialchars($row['ngay_tao']) ?></td>
                                <td>
                                    <button type="submit" class="btn btn-danger mb-2">Lưu thay đổi</button>
                                    <button type="submit" name="delete" class="btn btn-info"
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?');">Xóa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        <?php else: ?>
            <p class="text-danger">Không tìm thấy sản phẩm.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
