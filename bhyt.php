<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
}

// Khởi tạo biến thông báo
$message = '';

// Xử lý khi form được submit
if (isset($_POST['check'])) {
   $MaBH = $_POST['MaBH'];
   
   // Kiểm tra mã BHYT/CCCD đã tồn tại trong cơ sở dữ liệu
   $query = "SELECT * FROM yeucaubaohiem WHERE MaBH = ?";
   $stmt = $conn->prepare($query);
   $stmt->execute([$MaBH]);
   
   if ($stmt->rowCount() > 0) {
       // Nếu mã đã tồn tại
       $message = "Mã BHYT/CCCD đã tồn tại.";
   } else {
       // Nếu chưa có
       $message = "Mã BHYT/CCCD chưa có trong cơ sở dữ liệu.";
   }
}



if (isset($_POST['add'])) {
   
   $MaBN = $_POST['MaBN'];
   $MaBH = $_POST['MaBH'];
   $NgayBD = $_POST['NgayBD'];
   $NgayHH = $_POST['NgayHH'];
   

   if (!empty($MaBH) && !empty($NgayBD) && !empty($NgayHH) && !empty($MaBN)) {
      $query = "SELECT * FROM yeucaubaohiem WHERE MaBH = ? OR MaBN = ?";
      $stmt = $conn->prepare($query);
      $stmt->execute([$MaBH, $MaBN]);

      if ($stmt->rowCount() > 0) {
         $message = "Dữ liệu đã có, không thể thêm.";
      } else {
         $insert_query = "INSERT INTO yeucaubaohiem (MaBN, MaBH, NgayBD, NgayHH) VALUES (?, ?, ?, ?)";
         $insert_stmt = $conn->prepare($insert_query);
         $insert_stmt->execute([$MaBN, $MaBH, $NgayBD, $NgayHH]);
         $message = "Thêm mã BHYT/CCCD thành công.";

         // Xóa dữ liệu trong form
         $_POST['MaBN'] = '';
         $_POST['MaBH'] = '';
         $_POST['NgayBD'] = '';
         $_POST['NgayHH'] = '';
      }
   } else {
      $message = "Vui lòng nhập đầy đủ thông tin.";
   }
}


if (isset($_POST['delete'])) {
   $MaBH = $_POST['MaBH'];
   $MaBN = $_POST['MaBN'];

   if (!empty($MaBH) || !empty($MaBN)) {
      $query = "SELECT * FROM yeucaubaohiem WHERE MaBH = ? OR MaBN = ?";
      $stmt = $conn->prepare($query);
      $stmt->execute([$MaBH, $MaBN]);

      if ($stmt->rowCount() > 0) {
         $delete_query = "DELETE FROM yeucaubaohiem WHERE MaBH = ? OR MaBN = ?";
         $delete_stmt = $conn->prepare($delete_query);
         $delete_stmt->execute([$MaBH, $MaBN]);
         $message = "Xóa mã BHYT/CCCD hoặc mã bệnh nhân thành công.";

         // Xóa dữ liệu trong form
         $_POST['MaBN'] = '';
         $_POST['MaBH'] = '';
         $_POST['NgayBD'] = '';
         $_POST['NgayHH'] = '';
      } else {
         $message = "Mã BHYT/CCCD hoặc mã bệnh nhân không tồn tại.";
      }
   } else {
      $message = "Vui lòng nhập mã BHYT/CCCD hoặc mã bệnh nhân để xóa.";
   }
}



?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thêm BHYT</title>
   <link rel="shortcut icon" href="./imgs/icon.png" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css"> 
   <link rel="stylesheet" href="css/bhyt.css"> 
</head>
<body>
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <p><a href="home.php">Trang chủ</a> <span> / Quản lý BHYT</span></p>
</div>

<div class="container">
   <aside class="sidebar">
      <button type="submit" form="bhytForm" name="add" class="confirm-btn">Thêm</button>
      <button type="submit" form="bhytForm" name="delete" class="confirm-btn">Xóa</button>
      <button onclick="window.location.href='danhsach_bhyt.php';">Danh sách BHYT</button>
   </aside>

   <section class="checkout">
      <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
      <form action="" method="post" id="bhytForm" class="insurance-form">

         <div class="form-group">   
            <label for="MaBN">Mã Bệnh Nhân</label>
            <input type="text" id="MaBN" name="MaBN" placeholder="Nhập mã bệnh nhân" 
                  value="<?php echo isset($_POST['MaBN']) ? htmlspecialchars($_POST['MaBN']) : ''; ?>" required>
         </div>

         <div class="form-group">
            <label for="MaBH">Số BHYT/CCCD</label>
            <input type="text" id="MaBH" name="MaBH" placeholder="Nhập số BHYT/CCCD" 
                  value="<?php echo isset($_POST['MaBH']) ? htmlspecialchars($_POST['MaBH']) : ''; ?>" required>
         </div>

         <div class="form-group">
            <label for="start_date">Ngày bắt đầu</label>
            <input type="date" id="NgayBD" name="NgayBD" 
                  value="<?php echo isset($_POST['NgayBD']) ? htmlspecialchars($_POST['NgayBD']) : ''; ?>" required>
         </div>

         <div class="form-group">
            <label for="NgayHH">Ngày hết hạn</label>
            <input type="date" id="NgayHH" name="NgayHH" 
                  value="<?php echo isset($_POST['NgayHH']) ? htmlspecialchars($_POST['NgayHH']) : ''; ?>" required>
         </div>

         <button type="submit" name="check" class="confirm-btn">Kiểm tra</button>
      </form>
   </section>


</div>

</body>
</html>