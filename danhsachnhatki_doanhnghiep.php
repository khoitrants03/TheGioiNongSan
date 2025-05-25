<?php

include 'components/connect.php';

session_start();


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
    <title>Doanh nghiệp</title>
    <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- header section starts  -->
    <?php


    ?> <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nhật Kí</h3>
        <p><a href="business_dashboard.php">Trang chủ</a> <span><a href="quanlinhatkisanxuat_doanhnghiep.php">/Quản lí
                </a> </span> </p>
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
        <div class="form-title">DANH SÁCH Các Nhật Kí</div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>ID Nông Dân</th>
                    <th>Tên</th>
                    <th>Vị trí</th>
                    <th>Phương pháp gieo trồng</th>
                    <th>Ngày gieo trồng</th>
                    <th>Ngày thu hoạch</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT tt.id_nongdan, tt.id, tt.vi_tri_trang_trai, tt.phuong_phap_trong_trot, 
                                           tt.ngay_gieo_trong, tt.ngay_thu_hoach, nd.ho_ten 
                                    FROM thongtinnongdan tt 
                                    JOIN nguoidung nd ON tt.id_nongdan = nd.id_nguoidung 
                                     
                                    LIMIT 10");
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr onclick=\"window.location.href='chitiet_nhatki.php?id=" . $row['id'] . "'\" style='cursor: pointer;'>";
                    echo "<td>" . htmlspecialchars($row['id_nongdan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ho_ten']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['vi_tri_trang_trai']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phuong_phap_trong_trot']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ngay_gieo_trong']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ngay_thu_hoach']) . "</td>";
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