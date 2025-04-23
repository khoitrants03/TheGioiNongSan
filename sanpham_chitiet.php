<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nông dân</title>
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
    } else {
        include("components/user_header.php");
    }

    ?> <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nông Sản</h3>
        <p><a href="home.php">Trang chủ</a> <span> /Quản lí </span> </p>
    </div>

    <!-- menu section starts  -->
    <?php
// Kiểm tra xem tham số 'id' có được truyền qua URL không
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy thông tin chi tiết sản phẩm từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM SanPham WHERE id_sanpham = ?");
    $stmt->execute([$product_id]);

    // Kiểm tra xem sản phẩm có tồn tại trong cơ sở dữ liệu không
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Hiển thị thông tin chi tiết sản phẩm
        echo "<div class='form-box'>";
        echo "<div class='form-title'>Sản phẩm chi tiết</div>";
        echo "<table class='product-table'>";
        echo "<thead><tr><th>ID</th><th>Tên</th><th>Mô tả</th><th>Giá</th><th>Số lượng</th><th>Nông dân</th><th>Danh mục</th><th>QR Code</th><th>Ngày tạo</th></tr></thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $row['id_sanpham'] . "</td>";
        echo "<td>" . $row['ten_sanpham'] . "</td>";
        echo "<td>" . $row['mo_ta'] . "</td>";
        echo "<td>" . number_format($row['gia'], 0, ',', '.') . " VND</td>";
        echo "<td>" . $row['so_luong_ton'] . "</td>";
        echo "<td>" . $row['id_nongdan'] . "</td>";
        echo "<td>" . $row['id_danhmuc'] . "</td>";
        echo "<td>" . $row['id_qrcode'] . "</td>";
        echo "<td>" . $row['ngay_tao'] . "</td>";
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>Sản phẩm không tồn tại.</p>";
    }
} else {
    echo "<p>Không có sản phẩm để hiển thị.</p>";
}
?>



 


    <!-- menu section ends -->








    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>