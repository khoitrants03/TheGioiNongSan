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
   <title>Dịch vụ</title>
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
   ?>   <!-- header section ends -->

   <div class="heading">
      <h3>Quản Lí thuốc</h3>
      <p><a href="home.php">Trang chủ</a> <span> /Nhà thuốc</span> <span> /Quản lí thuốc</span> </p>
   </div>

   <!-- menu section starts  -->



   <section class="products">
      <div class="box-container">
         <div class="service">
            <div class="box_register">
               <div class="box-item">
                  <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                     Quản lí thuốc</a1>
               </div>
               <div class="box-item">
                  <a href="#p">
                     <i class="fa fa-plus-square" aria-hidden="true"></i>Danh sách thuốc
                  </a>
               </div>
               <div class="box-item">
                  <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Thêm mới
                     thuốc</a>
               </div>


            </div>
         </div>

         <div class="register">
            <div class="form-container">
               <div class="form-title">Quản lí thuốc</div>

               <form method="POST">
                  <div class="form-group">
                     <label for="randomNumber">Mã thuốc</label>
                     <input type="text" id="randomNumber" name="randomNumber" style="font-size: 2rem;">

                  </div>
                  <div class="form-group">
                     <label for="tenThuoc">Tên </label>
                     <input type="text" id="tenThuoc" name="tenThuoc">
                  </div>
                  <div class="form-group">
                     <label for="loaiThuoc">Loại thuốc </label>
                     <input type="text" id="loaiThuoc" name="loaiThuoc">
                  </div>
                  <div class="form-group">
                     <label for="dangThuoc">Dạng thuốc</label>
                     <input type="text" id="dangThuoc" name="dangThuoc" role="">
                  </div>
                  <div class="form-group">
                     <label for="soLuong">Số lượng tồn</label>
                     <input type="text" id="soLuong" name="soLuong">
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

      <h1 class="title">Tìm Thuốc</h1>


      <div class="form-title"> </div>

      <?php
      if (isset($_POST['search'])) {
         $tenthuoc = $_POST['tenThuoc'];
         $randomNumber = $_POST['randomNumber'];


         $select_medicine = $conn->prepare("SELECT MaThuoc, Ten, DangThuoc, SoLuongTon,LoaiThuoc FROM Thuoc 
                                             where Ten like ? and MaThuoc like ? ");

         $search_value = "%$tenthuoc%";
         $search_value1 = "%$randomNumber%";

         $select_medicine->bindParam(1, $search_value, PDO::PARAM_STR);
         $select_medicine->bindParam(2, $search_value1, PDO::PARAM_STR);

         $select_medicine->execute();

         if ($select_medicine->rowCount() > 0) {
            echo '<table>
            <thead>
               <tr>
                  <th>Mã thuốc</th>
                  <th>Tên</th>
                  <th>Dạng thuốc</th>
                  <th>Số lượng tồn</th>
                  <th>Loại Thuốc</th>

               </tr>
            </thead>
            <tbody>';

            while ($fetch_medicine = $select_medicine->fetch(PDO::FETCH_ASSOC)) {
               echo '<tr>
               <td><input type="text" value="' . $fetch_medicine['MaThuoc'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['Ten'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['DangThuoc'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['SoLuongTon'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['LoaiThuoc'] . '" readonly></td>

            </tr>';
            }

            echo '</tbody>
      </table>';
         }
      } else {
         
         echo '<p class="empty">Chưa có thông tin thuốc để hiển thị!</p>';
      }
      ?>
      </div>
   </section>
   <?php

   ?>
   <section class="category">

      <h1 class="title">Danh Sách Thuốc</h1>


      <div class="form-title"> </div>

      <?php
      $select_medicine = $conn->prepare("SELECT MaThuoc, Ten, DangThuoc, SoLuongTon,LoaiThuoc FROM Thuoc");
      $select_medicine->execute();

      if ($select_medicine->rowCount() > 0) {
         echo '<table>
            <thead>
               <tr>
                  <th>Mã thuốc</th>
                  <th>Tên</th>
                  <th>Dạng thuốc</th>
                  <th>Số lượng tồn</th>
                  <th>Loại Thuốc</th>

               </tr>
            </thead>
            <tbody>';

         while ($fetch_medicine = $select_medicine->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>
               <td><input type="text" value="' . $fetch_medicine['MaThuoc'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['Ten'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['DangThuoc'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['SoLuongTon'] . '" readonly></td>
               <td><input type="text" value="' . $fetch_medicine['LoaiThuoc'] . '" readonly></td>

            </tr>';
         }

         echo '</tbody>
      </table>';


      } else {
         echo '<p class="empty">Chưa có thông tin thuốc để hiển thị!</p>';
      }
      ?>

      </div>

   </section>
   <!-- menu section ends -->
   <?php
   if (isset($_POST['add'])) {
      $maThuoc = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
   

      $check_medicine = $conn->prepare("SELECT * FROM thuoc WHERE MaThuoc = ?");
      $check_medicine->execute([$maThuoc]);

      if ($check_medicine->rowCount() > 0) {
         echo "<script>
            alert('Mã thuốc đã tồn tại. Vui lòng sử dụng mã thuốc khác.');
        </script>";
       } else {
         $maThuoc = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
         $tenThuoc = filter_var($_POST['tenThuoc'], FILTER_SANITIZE_STRING);
         $dangThuoc = filter_var($_POST['dangThuoc'], FILTER_SANITIZE_STRING);
         $soLuong = filter_var($_POST['soLuong'], FILTER_SANITIZE_STRING);
         $loaiThuoc = filter_var($_POST['loaiThuoc'], FILTER_SANITIZE_STRING);



         $insert_medicine = $conn->prepare("INSERT INTO `thuoc`(MaThuoc, Ten, DangThuoc, SoLuongTon,LoaiThuoc) VALUES (?,?, ?, ?, ?)");
         $insert_medicine->execute([$maThuoc, $tenThuoc, $dangThuoc, $soLuong,$loaiThuoc]);
         echo "<script>
            alert('Thêm Mới Thành Công.');
         </script>";
       }
   }
   if (isset($_POST['update'])) {
        $maThuoc = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
       $tenThuoc = filter_var($_POST['tenThuoc'], FILTER_SANITIZE_STRING);
       $dangThuoc = filter_var($_POST['dangThuoc'], FILTER_SANITIZE_STRING);
       $soLuong = filter_var($_POST['soLuong'], FILTER_SANITIZE_NUMBER_INT);
       $loaiThuoc = filter_var($_POST['loaiThuoc'], FILTER_SANITIZE_STRING);

        $check_medicine = $conn->prepare("SELECT COUNT(*) FROM thuoc WHERE MaThuoc = ?");
       $check_medicine->execute([$maThuoc]);

       if ($check_medicine->fetchColumn() > 0) {
            $update_medicine = $conn->prepare("UPDATE thuoc SET Ten = ?, DangThuoc = ?, SoLuongTon = ?, LoaiThuoc = ? WHERE MaThuoc = ?");
           $update_medicine->execute([$tenThuoc, $dangThuoc, $soLuong, $loaiThuoc, $maThuoc]);

           echo "<script>
               alert('Cập nhật thành công.');
           </script>";
       } else {
           echo "<script>
               alert('Mã thuốc không tồn tại.');
           </script>";
       }
   }
    if (isset($_POST['delete'])) {
        $maThuoc = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
     
        $check_medicine = $conn->prepare("SELECT COUNT(*) FROM thuoc WHERE MaThuoc = ?");
       $check_medicine->execute([$maThuoc]);
     
        if ($check_medicine->fetchColumn() > 0) {
            $delete_medicine = $conn->prepare("DELETE FROM thuoc WHERE MaThuoc = ?");

            $delete_medicine->execute([$maThuoc]);
   
            echo "<script>
               alert('Xóa Thành Công.');
            </script>";
       } else {
            echo "<script>
               alert('Mã thuốc không tồn tại.');
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