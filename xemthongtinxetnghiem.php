<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

include 'components/add_cart.php';


$patientId = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';

// Truy vấn dữ liệu
if ($patientId) {
    $stmt = $conn->prepare("
      
        SELECT 
            bn.Ten AS TenBenhNhan,
            bn.NgaySinh,
            bn.GioiTinh,
            bn.DiaChi,
            bn.SoDienThoai,
            xn.MaXetNghiem AS Maxetnghiem,
            xn.MaPhieu AS Maphieu,
            xn.Ten AS TenXetNghiem,
            xn.Ngay AS NgayXetNghiem,
            xn.KetQua AS KetQuaXetNghiem
        FROM benhnhan bn
        LEFT JOIN phieukhambenh pkb ON bn.MaBN = pkb.MaBN
        LEFT JOIN xetnghiem xn ON pkb.MaPhieu = xn.MaPhieu
        WHERE bn.MaBN = :patientId
    ");
    $stmt->bindParam(':patientId', $patientId, PDO::PARAM_STR);
    $stmt->execute();
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $tests = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Xét Nghiệm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            line-height: 1.6;
        }

        .box-item {
            width: 100%;
            max-width: 1200px;
            margin: left;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2rem;
        }

        form {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        form input[type="text"] {
            padding: 10px;
            font-size: 1rem;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .patient-info, .test-info {
            margin: 20px 0;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .patient-info h2, .test-info h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #007bff;
        }

        .patient-info p, .test-info p {
            margin: 5px 0;
            font-size: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1rem;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #007bff;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="register">
    <div class="box-item">
        <a href="xemthongtindonthuoc.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Đơn thuốc
        </a>
    </div>
    <div class="box-item">
        <a href="xemthongtinphieukham.php"><i class="fa fa-plus-square" aria-hidden="true"></i>Phiếu khám bệnh
        </a>
    </div>
    </div>
    <div class="container">
        <header>
            <h1>Thông Tin Xét Nghiệm Bệnh Nhân</h1>
        </header>
        <form method="GET" action="">
            <label for="patient_id">Nhập mã bệnh nhân:</label>
            <input type="text" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($patientId); ?>">
            <button type="submit">Tìm Kiếm</button>
        </form>

        <?php if ($tests): ?>
            <section class="patient-info">
                <h2>Thông Tin Bệnh Nhân</h2>
                <p><strong>Tên:</strong> <?php echo htmlspecialchars($tests[0]['TenBenhNhan']); ?></p>
                <p><strong>Ngày Sinh:</strong> <?php echo htmlspecialchars($tests[0]['NgaySinh']); ?></p>
                <p><strong>Giới Tính:</strong> <?php echo htmlspecialchars($tests[0]['GioiTinh']); ?></p>
                <p><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($tests[0]['DiaChi']); ?></p>
                <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($tests[0]['SoDienThoai']); ?></p>
            </section>

            <section class="test-info">
                <h2>Thông Tin Xét Nghiệm</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Mã Xét Nghiệm</th>
                            <th>Mã Phiếu</th>
                            <th>Tên Xét Nghiệm</th>
                            <th>Ngày</th>
                            <th>Kết Quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tests as $test): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($test['Maxetnghiem']); ?></td>
                                <td><?php echo htmlspecialchars($test['Maphieu']); ?></td>
                                <td><?php echo htmlspecialchars($test['TenXetNghiem']); ?></td>
                                <td><?php echo htmlspecialchars($test['NgayXetNghiem']); ?></td>
                                <td><?php echo htmlspecialchars($test['KetQuaXetNghiem']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php elseif ($patientId): ?>
            <p>Không tìm thấy thông tin xét nghiệm cho mã bệnh nhân: <?php echo htmlspecialchars($patientId); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>