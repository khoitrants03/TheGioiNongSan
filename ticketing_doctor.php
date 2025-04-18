<?php

include '../../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bác Sĩ</title>
    <link rel="shortcut icon" href="../../imgs/hospital-solid.svg" type="image/x-icon">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../../css/style.css">

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
        } elseif ($_SESSION['phanquyen'] === 'tieptan') {
            require("components/user_header_tieptan.php");
        } elseif ($_SESSION['phanquyen'] === 'nhathuoc') {
            require("components/user_header_nhathuoc.php");
        }
    } else {
        include("components/user_header.php");
    }
    ?>
    <div class="heading">
        <h3>Lập phiếu khám bệnh</h3>
        <p><a href="home.php">Trang chủ</a> <span> / Bác sĩ</span></p>
    </div>

    <!-- menu section starts  -->

    <section class="products">
        <?php
        $pid = $_GET['pid'];
        $select_patient = $conn->prepare("SELECT * FROM `benhnhan` WHERE MaBN = ?");
        $select_patient->execute([$pid]);
        if ($select_patient->rowCount() > 0) {
            while ($fetch_patient = $select_patient->fetch(PDO::FETCH_ASSOC)) {
                ?>

                <div class="box-container">

                    <div class="service">
                        <div class="box_register">
                            <div class="box-item">
                                <a1 href="#"><i class="fa-sharp-duotone fa-solid fa-gears"></i>
                                    Lập phiếu khám</a1>
                            </div>
                            <div class="box-item">
                                <a href="#"><i class="fa fa-plus-square" aria-hidden="true"></i>Bệnh nhân
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="register">
                        <?php ?>
                        <div class="form-container">
                            <div class="form-title">Lập phiếu khám</div>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="name">Mã Phiếu</label>
                                    <input type="number" id="randomNumber" name="randomNumber" style="font-size: 2rem;">
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const randomNum = Math.floor(Math.random() * 10000) + 1;
                                            // Gán giá trị cho thẻ input
                                            document.getElementById("randomNumber").value = randomNum;
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="maBS">MaBS</label>
                                     <select id="maBS" name="maBS">
                                        <?php
                                         $query = $conn->prepare("SELECT MaBS FROM BacSi");
                                        $query->execute();

                                         if ($query->rowCount() > 0) {
                                             while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['MaBS'] . "'>" . $row['MaBS'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Không có Mã BS</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group" style="display: none;">
                                    <label for="maBN">Mã Bệnh Nhân</label>
                                    <input type="hidden" id="maBN" name="maBN"
                                        value="<?= htmlspecialchars($fetch_patient['MaBN']); ?>">
                                    <input type="text" value="<?= htmlspecialchars($fetch_patient['MaBN']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="name">Họ tên</label>
                                    <input type="text" id="name" value="<?= $fetch_patient['Ten']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="dob">Ngày sinh</label>
                                    <input type="date" id="dob" value="<?= $fetch_patient['NgaySinh']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Giới tính</label>
                                    <input type="text" id="dob" value="<?= $fetch_patient['GioiTinh']; ?>" readonly>

                                </div>

                                <div class="form-group">
                                    <label for="appointment">Ngày khám</label>
                                    <input type="datetime-local" id="appointment" name="appointment">
                                </div>

                                <div class="form-group">
                                    <label for="chuandoanbenh">Chuẩn đoán bệnh</label>
                                    <textarea type="text" id="chuandoanbenh" name="chuandoanbenh" rows="4"></textarea>
                                </div>
                                <style>
                                    textarea {
                                        border: 2px solid #ccc;
                                        border-radius: 4px;
                                        font-size: 16px;
                                        width: 68%;

                                    }

                                    textarea:valid {
                                        border-color: green;
                                    }
                                </style>
                                <button type="submit" class="submit-btn" name="add_patient">Xác nhận</button>
                            </form>

                        </div>
                    </div>

                </div>
                <?php
            }
        } else {
            echo '<p class="empty">Chưa thông tin  bệnh nhân để hiển thị!</p>';
        }
        ?>
    </section>


    <?php

    if (isset($_POST['add_patient'])) {
        $randomNumber = filter_var($_POST['randomNumber'], FILTER_SANITIZE_STRING);
        $date = filter_var($_POST['appointment'], FILTER_SANITIZE_STRING);
        $chuandoanbenh = filter_var($_POST['chuandoanbenh'], FILTER_SANITIZE_STRING);
        $maBS = filter_var($_POST['maBS'], FILTER_SANITIZE_STRING);
        $maBN = filter_var($_POST['maBN'], FILTER_SANITIZE_STRING);

        // $currentDateTime = new DateTime(); // Lấy ngày giờ hiện tại
        // $appointmentDateTime = new DateTime($date); // Thời gian được nhập vào từ form
        
        // // So sánh thời gian nhập với thời gian hiện tại
        // if ($appointmentDateTime > $currentDateTime) {
        //     echo "<script>
        //             alert('Thời gian khám phải trước hoặc bằng thời gian hiện tại!');
        //             window.history.back();  
        //         </script>";
        //     exit;
        // }
        
              $insert_patient = $conn->prepare("INSERT INTO `phieukhambenh`(MaPhieu, NgayGio, TinhTrang, MaBS,MaBN) VALUES (?,?, ?, ?, ?)");
        $insert_patient->execute([$randomNumber, $date, $chuandoanbenh, $maBS, $maBN]);

        
       
        echo "<script>
                    alert('Thêm  Thành Công.');
                    window.location.href = 'xemthongtin_phieukham.php'; 
                </script>";


    }

    ?>


    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->


    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>