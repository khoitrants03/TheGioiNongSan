<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php';
session_start();

// Kiểm tra tham số MaBS
if (!isset($_GET['MaBS'])) {
    die("Lỗi: Không có mã bác sĩ");
}

$doctor_id = $_GET['MaBS'];

try {
    // Truy vấn chi tiết bác sĩ
    $stmt = $conn->prepare("SELECT * FROM `bacsi` WHERE MaBS = :maBS");
    $stmt->bindParam(':maBS', $doctor_id, PDO::PARAM_STR);
    $stmt->execute();

    // Kiểm tra và hiển thị kết quả
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$doctor) {
        die("Không tìm thấy bác sĩ với mã: " . $doctor_id);
    }
} catch(PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Bác Sĩ</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .doctor-card { 
            display: flex; 
            border: 1px solid #ddd; 
            padding: 20px; 
            margin-top: 20px; 
        }
        .doctor-card img { 
            max-width: 200px; 
            margin-right: 20px; 
        }
        .doctor-info { flex-grow: 1; }
    </style>
</head>
<body>
    <div class="doctor-card">
        <img src="uploaded_img/<?= htmlspecialchars($doctor['imge']); ?>" alt="Ảnh bác sĩ">
        <div class="doctor-info">
            <h1><?= htmlspecialchars($doctor['Ten']); ?></h1>
            <p><strong>Mã Bác Sĩ:</strong> <?= htmlspecialchars($doctor['MaBS']); ?></p>
            <p><strong>Chuyên Khoa:</strong> <?= htmlspecialchars($doctor['ChuyenKhoa']); ?></p>
            <p><strong>Số Điện Thoại:</strong> <?= htmlspecialchars($doctor['SoDienThoai']); ?></p>
        </div>
        <a href="bacsi.php">Back</a>
    </div>

    <!-- Debug Information -->
    <!-- <div style="margin-top: 20px; background: #f0f0f0; padding: 10px;">
        <h3>Debug Information:</h3>
        <pre><?php print_r($doctor); ?></pre>
    </div> -->
</body>
</html>