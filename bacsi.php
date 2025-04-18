<?php
include '../../components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:../../home.php');
}

$search_keyword = ''; // Biến để lưu từ khóa tìm kiếm
$search_results = null; // Biến để lưu kết quả tìm kiếm

// Xử lý tìm kiếm khi người dùng nhấn nút
if (isset($_POST['search']) && !empty($_POST['search_keyword'])) {
   $search_keyword = trim($_POST['search_keyword']); // Lấy từ khóa tìm kiếm và loại bỏ khoảng trắng thừa
   $search_query = "SELECT * FROM `bacsi` WHERE `ChuyenKhoa` LIKE ?";
   $stmt = $conn->prepare($search_query);
   $stmt->execute(["%$search_keyword%"]);
   $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <link rel="shortcut icon" href="../../imgs/hospital-solid.svg" type="image/x-icon">
   <!-- font awesome cdn link  -->
=======
   <title>Danh sách Bác sĩ</title>
>>>>>>> ae9bf74a94eebb4d1ceab69bcf6e108b24c256c1
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php include 'components/user_header.php'; ?>

   <div class="heading">
      <h3>Bác sĩ hiện có</h3>
      <p><a href="home.php">Trang chủ</a> <span> / Bác sĩ</span></p>
   </div>

   <section class="search-bar">
      <form action="" method="post" class="search-form">
         <input type="text" name="search_keyword" placeholder="Tìm bác sĩ..." value="<?= htmlspecialchars($search_keyword); ?>" class="box">
         <button type="submit" name="search" class="btn">Tìm kiếm</button>
      </form>
   </section>

   <section class="products">
      <div class="box-container">
         <?php
         if ($search_results) {
            foreach ($search_results as $doctor) {
         ?>
               <div class="box">
                  <a href="bacsichitiet.php?MaBS=<?= $doctor['MaBS']; ?>" class="fas fa-eye"></a>
                  <div class="image">
                     <img src="uploaded_img/<?= $doctor['imge']; ?>" alt="<?= $doctor['Ten']; ?>">
                  </div>
                  <div class="name-flex">
                     <div class="name"><?= htmlspecialchars($doctor['Ten']); ?></div>
                     <div class="flex">
                        <div class="price">Chuyên Khoa: <?= htmlspecialchars($doctor['ChuyenKhoa']); ?></div>
                     </div>
                  </div>
               </div>
         <?php
            }
         } elseif (isset($_POST['search'])) {
            echo '<p class="empty">Không tìm thấy bác sĩ nào với từ khóa "' . htmlspecialchars($search_keyword) . '"!</p>';
         } 
         ?>
      </div>
   </section>

   <section class="products">
      <h1 class="title">Danh sách bác sĩ</h1>

      <div class="box-container">
         <?php
         if (!$search_results) {
            $select_doctors = $conn->prepare("SELECT * FROM `bacsi` LIMIT 6");
            $select_doctors->execute();

            if ($select_doctors->rowCount() > 0) {
               while ($doctor = $select_doctors->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <div class="box">
                     <a href="bacsichitiet.php?MaBS=<?= $doctor['MaBS']; ?>" class="fas fa-eye"></a>
                     <div class="image">
                        <img src="uploaded_img/<?= $doctor['imge']; ?>" alt="<?= htmlspecialchars($doctor['Ten']); ?>">
                     </div>
                     <div class="name-flex">
                        <div class="name"><?= htmlspecialchars($doctor['Ten']); ?></div>
                        <div class="flex">
                           <div class="price">Chuyên Khoa: <?= htmlspecialchars($doctor['ChuyenKhoa']); ?></div>
                        </div>
                     </div>
                  </div>
         <?php
               }
            } else {
               echo '<p class="empty">Hiện chưa có bác sĩ để hiển thị!</p>';
            }
         }
         ?>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>

</html>
