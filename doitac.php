<?php
require_once 'components/connect.php';
session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'farmer') {
//     header('Location: login.php');
//     exit();
// }

$farmer_id = $_SESSION['user_id'];

// Handle connection request response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Update connection request status
        $update_request = $conn->prepare("UPDATE connection_requests SET status = 'accepted', updated_at = NOW() WHERE id = ? AND farmer_id = ?");
        $update_request->execute([$request_id, $farmer_id]);

        $_SESSION['message'] = "Đã chấp nhận yêu cầu kết nối!";
    } elseif ($action === 'reject') {
        // Update connection request status
        $update_request = $conn->prepare("UPDATE connection_requests SET status = 'rejected', updated_at = NOW() WHERE id = ? AND farmer_id = ?");
        $update_request->execute([$request_id, $farmer_id]);

        $_SESSION['message'] = "Đã từ chối yêu cầu kết nối!";
    }

    header('Location: doitac.php');
    exit();
}

// Get pending connection requests
$select_requests = $conn->prepare("
    SELECT cr.*, n.ho_ten, n.email, n.so_dien_thoai, n.dia_chi
    FROM connection_requests cr
    JOIN nguoidung n ON cr.business_id = n.id_nguoidung
    WHERE cr.farmer_id = ? AND cr.status = 'pending'
    ORDER BY cr.created_at DESC
");
$select_requests->execute([$farmer_id]);

// Get accepted connections
$select_connections = $conn->prepare("
    SELECT cr.*, n.ho_ten, n.email, n.so_dien_thoai, n.dia_chi
    FROM connection_requests cr
    JOIN nguoidung n ON cr.business_id = n.id_nguoidung
    WHERE cr.farmer_id = ? AND cr.status = 'accepted'
    ORDER BY cr.updated_at DESC
");
$select_connections->execute([$farmer_id]);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đối tác</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/doitac.css">

    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['phanquyen'])) {
        if ($_SESSION['phanquyen'] === 'nongdan') {
            require("components/user_header_nongdan.php");
        }
        if ($_SESSION['phanquyen'] === 'khachhang') {
            require("components/user_header_khachhang.php");
        }
        if ($_SESSION['phanquyen'] === 'doanhnghiep') {
            require("components/user_header_doanhnghiep.php");
        }
    } else {
        include("components/user_header_doanhnghiep.php");
    }
    ?>

    <section class="connections-container">
        <div class="heading">
            <h3>Tạo liên kết với nông dân</h3>
            <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Danh sách nông dân</span></p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <div class="section" style="    margin-top: 30px;">
            <h2 class="section-title">Yêu cầu kết nối mới</h2>
            <?php if ($select_requests->rowCount() > 0): ?>
                <?php while ($request = $select_requests->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="request-card">
                        <div class="business-info">
                            <div class="business-name"><?= htmlspecialchars($request['ho_ten']); ?></div>
                            <div class="business-details">
                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($request['email']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-phone"></i> <?= htmlspecialchars($request['so_dien_thoai']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($request['dia_chi']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-clock"></i> Yêu cầu được gửi:
                                <?= date('d/m/Y H:i', strtotime($request['created_at'])); ?>
                            </div>
                        </div>
                        <div class="request-actions">
                            <form method="POST">
                                <input type="hidden" name="request_id" value="<?= $request['id']; ?>">
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="accept-btn">
                                    <i class="fas fa-check"></i> Chấp nhận
                                </button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="request_id" value="<?= $request['id']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="reject-btn">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty">Không có yêu cầu kết nối mới!</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2 class="section-title">Đối tác đã kết nối</h2>
            <?php if ($select_connections->rowCount() > 0): ?>
                <?php while ($connection = $select_connections->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="connection-card">
                        <div class="business-info">
                            <div class="business-name"><?= htmlspecialchars($connection['ho_ten']); ?></div>
                            <div class="business-details">
                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($connection['email']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-phone"></i> <?= htmlspecialchars($connection['so_dien_thoai']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($connection['dia_chi']); ?>
                            </div>
                            <div class="business-details">
                                <i class="fas fa-check-circle"></i> Đã kết nối:
                                <?= date('d/m/Y H:i', strtotime($connection['updated_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty">Chưa có đối tác nào được kết nối!</p>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>