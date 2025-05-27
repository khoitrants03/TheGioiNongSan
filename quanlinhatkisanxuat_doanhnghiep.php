<?php

include 'components/connect.php';

session_start();

 
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



    ?> <!-- header section ends -->

    <div class="heading">
        <h3>Quản Lí Nhật Kí</h3>
        <p><a href="home.php">Trang chủ</a> <span> /Quản lí </span> </p>
    </div>

    <!-- menu section starts  -->



    <section class="form-box">
        <div class="form-title">DANH SÁCH NHẬT KÍ SẢN XUẤT</div>

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
                // Chuẩn bị truy vấn và thực thi
                $stmt = $conn->prepare("SELECT tt.id_nongdan,tt.vi_tri_trang_trai,tt.phuong_phap_trong_trot,tt.ngay_gieo_trong,tt.ngay_thu_hoach,nd.ho_ten
                                              FROM thongtinnongdan tt join nguoidung nd 
                                                on tt.id_nongdan= nd.id_nguoidung ");
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_nongdan'] . "</td>";
                    echo "<td>" . $row['ho_ten'] . "</td>";

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