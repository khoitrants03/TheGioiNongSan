<?php

include 'components/connect.php';

session_start();

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// } else {
//     $user_id = '';
// }
// ;
// $id_nongdan = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
//  if (!$id_nongdan) {
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

    // if (isset($_SESSION['phanquyen'])) {
    //     if ($_SESSION['phanquyen'] === 'nongdan') {
    //         require("components/user_header_nongdan.php");
    //     }
    // } else {
    //     include("components/user_header.php");
    // }

    ?> <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nông Sản</h3>
        <p><a href="business_dashboard.php">Trang chủ</a> <span><a href="capnhatthongtinsp.php">/Quản lí </a> </span> </p>
    </div>

    <!-- menu section starts  -->


    <section class="products_1">


        <div class="product-wrapper">
            <div class="menu-box">
                <a href="#"><i class="fa-solid fa-gear"></i> Quản lí</a>
                <!-- <a href="#"><i class="fa fa-plus-square"></i> Xem chi tiết</a> -->
                <!-- <a href="#"><i class="fa fa-plus-square"></i> Thêm sản phẩm</a> -->
            
        </div>
        <!-- #endregion -->
    </section>
    <section class="form-box">
        <div class="form-title">DANH SÁCH SẢN PHẨM </div>

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
                $stmt = $conn->prepare("SELECT * FROM SanPham    ORDER BY ngay_tao  DESC LIMIT 10");
               $stmt->execute();
                $stmt->execute();
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
    <?php include 'components/footer_admin.php';?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>