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
    <title>Bác Sĩ</title>
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
        <h3>Quản Lí Bác Sĩ </h3>
        <p><a href="../../home.php">Trang chủ</a> <span> /Quản lí </span> </p>
    </div>

    <!-- menu section starts  -->


    <section class="products">
        <div class="box-container">
            <div class="service">
                <div class="box_register">
                    <div class="box-item">
                        <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                            Quản lí bác sĩ</a1>
                    </div>
                    <div class="box-item">
                        <a href="#p">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>Danh sách bs
                        </a>
                    </div>
                    <div class="box-item">
                        <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Thêm mới
                        </a>
                    </div>


                </div>
            </div>

            <div class="register">
                <div class="form-container">
                    <div class="form-title">Quản lí Bs</div>

                    <form method="POST">
                        <div class="form-group">
                            <label for="randomNumber">Mã bác sĩ</label>
                            <input type="text" id="randomNumber" name="randomNumber" style="font-size: 2rem;">

                        </div>
                        <div class="form-group">
                            <label for="maKhoa">Mã Khoa </label>
                            <select id="maKhoa" name="maKhoa">
                                <?php
                                $query = $conn->prepare("SELECT MaKhoa FROM KhoaKham");
                                $query->execute();
                                if ($query->rowCount() > 0) {
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                        // tương tự như select <option></option>
                                        echo "<option value='" . $row['MaKhoa'] . "'>" . $row['MaKhoa'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No Khoa available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tenBs">Tên </label>
                            <input type="text" id="tenBs" name="tenBs">
                        </div>
                        <div class="form-group">
                            <label for="sdt">Số điện thoại</label>
                            <input type="text" id="sdt" name="sdt" role="">
                        </div>
                        <div class="form-group">
                            <label for="chuyenKhoa">Chuyên khoa</label>
                            <input type="text" id="chuyenKhoa" name="chuyenKhoa">
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

        <h1 class="title">Tìm bác sĩ</h1>


        <div class="form-title"> </div>

        <?php
        if (isset($_POST['search'])) {
            //lấy chuyên khoa được nhập từ thẻ input line 106
            $chuyenKhoa = $_POST['chuyenKhoa'];
            // rồi tìm kiếm theo chuyên khoa
            $select_doctor = $conn->prepare("SELECT MaBS,MaKhoa ,Ten, SoDienThoai, ChuyenKhoa FROM BacSi 
                                             where ChuyenKhoa like ? ");

            $search_value = "%$chuyenKhoa%";
            $select_doctor->bindParam(1, $search_value, PDO::PARAM_STR);
            $select_doctor->execute();

            if ($select_doctor->rowCount() > 0) {
                echo '<table>
            <thead>
               <tr>
                  <th>Mã BS</th>
                  <th>Mã Khoa</th>
                  <th>Tên</th>
                  <th>Số điện thoại</th>
                  <th>Chuyên khoa</th>
               </tr>
            </thead>
            <tbody>';

                while ($fetch_doctor = $select_doctor->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>
               <td><input type="text" value="' . $fetch_doctor['MaBS'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_doctor['MaKhoa'] . '" readonly></td>

               <td><input type="text" value="' . $fetch_doctor['Ten'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_doctor['SoDienThoai'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_doctor['ChuyenKhoa'] . '" readonly></td>
            </tr>';
                }

                echo '</tbody>
      </table>';
            }
        } else {
            echo '<p class="empty">Không có thông tin bác sĩ để hiển thị!</p>';
        }
        ?>
        </div>
    </section>
    <?php

    ?>
    <section class="category">

        <h1 class="title">Danh Sách BS</h1>


        <div class="form-title"> </div>

        <?php
        // xuất hết ds BacSi có trong csdl
        $select_doctor = $conn->prepare("SELECT MaBS,MaKhoa ,Ten, SoDienThoai, ChuyenKhoa from BacSi");
        $select_doctor->execute();

        if ($select_doctor->rowCount() > 0) {
            echo '<table>
            <thead>
            <tr>
                <th>Mã BS</th>
                <th>Mã Khoa</th>
                <th>Tên</th>
                <th>Số điện thoại</th>
                <th>Chuyên khoa</th>
            </tr>
            </thead>
            <tbody>';

            while ($fetch_doctor = $select_doctor->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>
                <td><input type="text" value="' . $fetch_doctor['MaBS'] . '" readonly></td>
                <td><input type="text" value="' . $fetch_doctor['MaKhoa'] . '" readonly></td>

                <td><input type="text" value="' . $fetch_doctor['Ten'] . '" readonly></td>
                <td><input type="text" value="' . $fetch_doctor['SoDienThoai'] . '" readonly></td>
                <td><input type="text" value="' . $fetch_doctor['ChuyenKhoa'] . '" readonly></td>
            </tr>';
            }

            echo '</tbody>
      </table>';


        } else {
            echo '<p class="empty">Chưa có thông tin Bác Sĩ để hiển thị!</p>';
        }
        ?>

        </div>

    </section>
    <!-- menu section ends -->
    <?php
    if (isset($_POST['add'])) {
        $maBS = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
        // kiểm tra mã bac sĩ có tồn tại trong csdl ko
    
        $check_doctor = $conn->prepare("SELECT * FROM BacSi WHERE MaBS = ?");
        $check_doctor->execute([$maBS]);

        if ($check_doctor->rowCount() > 0) {
            echo "<script>
            alert('Mã BS đã tồn tại. Vui lòng sử dụng mã khác.');
        </script>";
        } else {
            $maKhoa = filter_var($_POST['maKhoa'], FILTER_SANITIZE_STRING);
            $sdt = filter_var($_POST['sdt'], FILTER_SANITIZE_STRING);
            $chuyenKhoa = filter_var($_POST['chuyenKhoa'], FILTER_SANITIZE_STRING);
            $tenBs = filter_var($_POST['tenBs'], FILTER_SANITIZE_STRING);

            $insert_doctor = $conn->prepare("INSERT INTO BacSi (MaBS, MaKhoa, Ten, SoDienThoai, ChuyenKhoa) VALUES (?, ?, ?, ?, ?)");
            $insert_doctor->execute([$maBS, $maKhoa, $tenBs, $sdt, $chuyenKhoa]);

            echo "<script>
            alert('Thêm Mới Thành Công.');
        </script>";
        }
    }

    if (isset($_POST['update'])) {
        $maBS = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
        $maKhoa = filter_var($_POST['maKhoa'], FILTER_SANITIZE_STRING);
        $sdt = filter_var($_POST['sdt'], FILTER_SANITIZE_STRING);
        $chuyenKhoa = filter_var($_POST['chuyenKhoa'], FILTER_SANITIZE_STRING);
        $tenBs = filter_var($_POST['tenBs'], FILTER_SANITIZE_STRING);


        $check_doctor = $conn->prepare("SELECT * FROM BacSi WHERE MaBS = ?");
        $check_doctor->execute([$maBS]);

        if ($check_doctor->fetchColumn() > 0) {

            $update_doctor = $conn->prepare("UPDATE BacSi SET MaKhoa = ?, Ten = ?, SoDienThoai = ?, ChuyenKhoa = ? WHERE MaBS = ?");
            $update_doctor->execute([$maKhoa, $tenBs, $sdt, $chuyenKhoa, $maBS]);

            echo "<script>
            alert('Cập nhật Thành Công.');
        </script>";
        } else {
            echo "<script>
            alert('Mã BS không tồn tại.');
        </script>";
        }
    }

    if (isset($_POST['delete'])) {
        $maBS = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
        // xóa theo mã bac sĩ  
    
        $check_doctor = $conn->prepare("SELECT * FROM BacSi WHERE MaBS = ?");
        $check_doctor->execute([$maBS]);

        if ($check_doctor->fetchColumn() > 0) {
            $delete_doctor = $conn->prepare("DELETE FROM BacSi WHERE MaBS = ?");

            $delete_doctor->execute([$maBS]);

            echo "<script>
               alert('Xóa Thành Công.');
            </script>";
        } else {
            echo "<script>
               alert('Mã BS không tồn tại.');
           </script>";
        }
    }
    ?>





    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src=" js/script.js"></script>

</body>

</html>