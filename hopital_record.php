<?php

include '../../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

// include 'components/add_cart.php';
// include './convert_currency.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bác Sĩ</title>
    <link rel="shortcut icon" href="../imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

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
      }
   } else {
      include("components/user_header.php");
   }
   ?>    <!-- header section ends -->

    <div class="heading">
        <h3>Lưu trữ hồ sơ bệnh nhân</h3>
        <p><a href="home.php">Trang chủ</a> <span> /Bác sĩ </span> </p>
    </div>

    <!-- menu section starts  -->



    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>Thông tin hồ sơ
                            </a1>
                    </div>
                </div>
            </div>

            <div class="register">
                <div class="form-container">
                    <div class="form-title">Thông tin bệnh nhân</div>

                    <form method="POST">
                    <div class="form-group">
                        <label for="MaBN">Mã bệnh nhân</label>
                        <input type="text" id="MaBN" name="MaBN">
                    </div>
                    <div class="form-group">
                        <label for="Ten">Họ tên</label>
                        <input type="text" id="Ten" name="Ten">
                    </div>
                    <div class="form-group">
                        <label for="NgaySinh">Ngày sinh</label>
                        <input type="date" id="NgaySinh" name="NgaySinh">
                    </div>
                    <div class="form-group">
                        <label for="GioiTinh">Giới tính</label>
                        <input type="text" id="GioiTinh" name="GioiTinh">
                    </div>
                    <div class="form-group">
                        <label for="DiaChi">Địa chỉ</label>
                        <input type="text" id="DiaChi" name="DiaChi">
                    </div>
                    <div class="form-group">
                        <label for="SoDienThoai">Số điện thoại</label>
                        <input type="text" id="SoDienThoai" name="SoDienThoai">
                    </div>
                    <div class="form-group">
                        <label for="ThongTinBaoHiem">Thông tin bảo hiểm</label>
                        <input type="text" id="ThongTinBaoHiem" name="ThongTinBaoHiem">
                    </div>                   
                        <div class="button-container">
                            <button type="submit" class="submit-btn" name="search">Tìm kiếm</button>
                            <button type="submit" class="submit-btn" name="update">Cập nhật</button>
                            <button type="submit" class="submit-btn" name="add">Thêm mới</button>
                            <button type="submit" class="submit-btn" name="delete">Xóa</button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="category">

        <h1 class="title">Tìm kiếm bệnh nhân</h1>


        <div class="form-title"> </div>

        <?php
        if (isset($_POST['search'])) {
            //lấy chuyên khoa được nhập từ thẻ input line 106
            $MaBN = $_POST['MaBN'];
            // rồi tìm kiếm theo chuyên khoa
            $select_patient = $conn->prepare("SELECT MaBN, Ten, NgaySinh, GioiTinh, DiaChi, SoDienThoai, ThongTinBaoHiem FROM benhnhan 
                                             where MaBN like ? ");

            $search_value = "%$MaBN%";
            $select_patient->bindParam(1, $search_value, PDO::PARAM_STR);
            $select_patient->execute();

            if ($select_patient->rowCount() > 0) {
                echo '<table>
            <thead>
               <tr>
                  <th>Mã bệnh nhân</th>
                  <th>Họ tên</th>
                  <th>Ngày sinh</th>
                  <th>Giới tính</th>
                  <th>Địa chỉ</th>
                  <th>Số điện thoại</th>
                  <th>Thông tin bảo hiểm</th>
               </tr>
            </thead>
            <tbody>';

                while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>
               <td><input type="text" value="' . $fetch_patient['MaBN'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['Ten'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['NgaySinh'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['GioiTinh'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['DiaChi'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['SoDienThoai'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_patient['ThongTinBaoHiem'] . '" readonly></td>
            </tr>';
                }

                echo '</tbody>
      </table>';
            }
        } else {
            echo '<p class="empty">Không có thông tin bệnh nhân để hiển thị!</p>';
        }
        ?>
        </div>
    </section>
    <?php

    ?>
    <section class="category">

        <h1 class="title">Danh sách bệnh nhân</h1>


        <div class="form-title"> </div>

        <?php
        // xuất hết ds Bệnh nhân có trong csdl
        $select_patient = $conn->prepare("SELECT MaBN, Ten, NgaySinh, GioiTinh, DiaChi, SoDienThoai, ThongTinBaoHiem from benhnhan");
        $select_patient->execute();

        if ($select_patient->rowCount() > 0) {
            echo '<table>
            <thead>
            <tr>
                <th>Mã bệnh nhân</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Thông tin bảo hiểm</th>
            </tr>
            </thead>
            <tbody>';

            while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>
                    <td><input type="text" value="' . $fetch_patient['MaBN'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['Ten'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['NgaySinh'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['GioiTinh'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['DiaChi'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['SoDienThoai'] . '" readonly></td>
                    <td><input type="text" value="' . $fetch_patient['ThongTinBaoHiem'] . '" readonly></td>
            </tr>';
            }

            echo '</tbody>
      </table>';


        } else {
            echo '<p class="empty">Chưa có thông tin Bệnh nhân để hiển thị!</p>';
        }
        ?>

        </div>

    </section>
    <!-- menu section ends -->
    <?php
    if (isset($_POST['add'])) {
        $MaBN = filter_var($_POST['MaBN'], FILTER_SANITIZE_STRING);
        // kiểm tra mã bệnh nhân có tồn tại trong csdl ko
    
        $check_patient = $conn->prepare("SELECT * FROM benhnhan WHERE MaBN = ?");
        $check_patient->execute([$MaBN]);

        if ($check_patient->rowCount() > 0) {
            echo "<script>
            alert('Mã bệnh nhân đã tồn tại. Vui lòng sử dụng mã khác.');
        </script>";
        } else {
            $MaBN = filter_var($_POST['MaBN'], FILTER_SANITIZE_STRING);
            $Ten = filter_var($_POST['Ten'], FILTER_SANITIZE_STRING);
            $NgaySinh = filter_var($_POST['NgaySinh'], FILTER_SANITIZE_STRING);
            $GioiTinh = filter_var($_POST['GioiTinh'], FILTER_SANITIZE_STRING);
            $DiaChi = filter_var($_POST['DiaChi'], FILTER_SANITIZE_STRING);
            $SoDienThoai = filter_var($_POST['SoDienThoai'], FILTER_SANITIZE_STRING);
            $ThongTinBaoHiem = filter_var($_POST['ThongTinBaoHiem'], FILTER_SANITIZE_STRING);
           
            $insert_patient = $conn->prepare("INSERT INTO benhnhan (MaBN, Ten, NgaySinh, GioiTinh, DiaChi, SoDienThoai, ThongTinBaoHiem) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_patient->execute([$MaBN, $Ten, $NgaySinh, $GioiTinh, $DiaChi, $SoDienThoai, $ThongTinBaoHiem]);

            echo "<script>
            alert('Thêm Mới Thành Công.');
        </script>";
        }
    }

    if (isset($_POST['update'])) {
        $MaBN = filter_var($_POST['MaBN'], FILTER_SANITIZE_STRING);
        $Ten = filter_var($_POST['Ten'], FILTER_SANITIZE_STRING);
        $NgaySinh = filter_var($_POST['NgaySinh'], FILTER_SANITIZE_STRING);
        $GioiTinh = filter_var($_POST['GioiTinh'], FILTER_SANITIZE_STRING);
        $DiaChi = filter_var($_POST['DiaChi'], FILTER_SANITIZE_STRING);
        $SoDienThoai = filter_var($_POST['SoDienThoai'], FILTER_SANITIZE_STRING);
        $ThongTinBaoHiem = filter_var($_POST['ThongTinBaoHiem'], FILTER_SANITIZE_STRING);


        $check_patient = $conn->prepare("SELECT * FROM benhnhan WHERE MaBN = ?");
        $check_patient->execute([$MaBN]);

        if ($check_patient->fetchColumn() > 0) {

            $update_patient = $conn->prepare("UPDATE benhnhan SET Ten = ?, NgaySinh = ?, GioiTinh = ?, DiaChi = ?, SoDienThoai = ?, ThongTinBaoHiem = ? WHERE MaBN = ?");
            $update_patient->execute([$MaBN, $Ten, $NgaySinh, $GioiTinh, $DiaChi, $SoDienThoai, $ThongTinBaoHiem]);

            echo "<script>
            alert('Cập nhật Thành Công.');
        </script>";
        } else {
            echo "<script>
            alert('Mã bệnh nhân không tồn tại.');
        </script>";
        }
    }

    if (isset($_POST['delete'])) {
        $MaBN = filter_var($_POST['MaBN'], FILTER_SANITIZE_STRING);
        // xóa theo mã bệnh nhân 
    
        $check_patient = $conn->prepare("SELECT * FROM benhnhan WHERE MaBN = ?");
        $check_patient->execute([$MaBN]);

        if ($check_patient->fetchColumn() > 0) {
            $delete_patient = $conn->prepare("DELETE FROM benhnhan WHERE MaBN = ?");

            $delete_patient->execute([$MaBN]);

            echo "<script>
               alert('Xóa Thành Công.');
            </script>";
        } else {
            echo "<script>
               alert('Mã Bệnh nhân không tồn tại.');
           </script>";
        }
    }
    ?>





    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>