<?php

include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
}

// Truy vấn dữ liệu
$query = "SELECT * FROM yeucaubaohiem";
$stmt = $conn->prepare($query);
$stmt->execute();

// Lấy dữ liệu từ bảng
$bhyt_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách BHYT</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Danh sách BHYT</h1>
    <?php if (count($bhyt_list) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã bệnh nhân</th>
                    <th>Mã BHYT/CCCD</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày hết hạn</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bhyt_list as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['MaBN']); ?></td>
                        <td><?php echo htmlspecialchars($row['MaBH']); ?></td>
                        <td><?php echo htmlspecialchars($row['NgayBD']); ?></td>
                        <td><?php echo htmlspecialchars($row['NgayHH']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không có dữ liệu BHYT nào trong cơ sở dữ liệu.</p>
    <?php endif; ?>
    <button onclick="window.location.href='bhyt.php';">Quay lại</button>
</body>
</html>
