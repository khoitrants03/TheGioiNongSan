<?php
require_once 'components/connect.php';
session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'business') {
//     header('Location: login.php');
//     exit();
// }

$business_id = $_SESSION['user_id'];

// Handle connection request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['farmer_id'])) {
    $farmer_id = $_POST['farmer_id'];
    
    // Check if connection request already exists
    $check_request = $conn->prepare("SELECT * FROM connection_requests WHERE business_id = ? AND farmer_id = ?");
    $check_request->execute([$business_id, $farmer_id]);
    
    if ($check_request->rowCount() == 0) {
        // Insert new connection request
        $insert_request = $conn->prepare("INSERT INTO connection_requests (business_id, farmer_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
        $insert_request->execute([$business_id, $farmer_id]);
        
        $_SESSION['message'] = "Yêu cầu kết nối đã được gửi thành công!";
    } else {
        $_SESSION['error'] = "Bạn đã gửi yêu cầu kết nối cho nông dân này!";
    }
    
    header('Location: farmer_connections.php');
    exit();
}

// Get list of farmers
$select_farmers = $conn->prepare("
    SELECT n.*, 
           CASE 
               WHEN cr.status = 'accepted' THEN 'connected'
               WHEN cr.status = 'pending' THEN 'pending'
               ELSE 'not_connected'
           END as connection_status
    FROM nguoidung n
    LEFT JOIN connection_requests cr ON n.id_nguoidung = cr.farmer_id AND cr.business_id = ?
    WHERE n.phanquyen = 'nongdan'
    ORDER BY n.ho_ten ASC
");
$select_farmers->execute([$business_id]);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết nối với nông dân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .farmers-list {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .farmer-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .farmer-info {
            flex: 1;
        }
        .farmer-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .farmer-details {
            color: #666;
            margin-bottom: 5px;
        }
        .connection-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-left: 20px;
        }
        .status-connected {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-pending {
            background: #fff3e0;
            color: #ef6c00;
        }
        .status-not-connected {
            background: #f5f5f5;
            color: #757575;
        }
        .connect-btn {
            background: #1976d2;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .connect-btn:hover {
            background: #1565c0;
        }
        .connect-btn:disabled {
            background: #bdbdbd;
            cursor: not-allowed;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
    </style>
</head>
<body>
   

    <div class="farmers-list">
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if ($select_farmers->rowCount() > 0): ?>
            <?php while ($farmer = $select_farmers->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="farmer-card">
                    <div class="farmer-info">
                        <div class="farmer-name"><?= htmlspecialchars($farmer['ho_ten']); ?></div>
                        <div class="farmer-details">
                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($farmer['email']); ?>
                        </div>
                        <div class="farmer-details">
                            <i class="fas fa-phone"></i> <?= htmlspecialchars($farmer['so_dien_thoai']); ?>
                        </div>
                        <div class="farmer-details">
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($farmer['dia_chi']); ?>
                        </div>
                    </div>
                    <div class="connection-status status-<?= $farmer['connection_status']; ?>">
                        <?php
                        switch($farmer['connection_status']) {
                            case 'connected':
                                echo '<i class="fas fa-check-circle"></i> Đã kết nối';
                                break;
                            case 'pending':
                                echo '<i class="fas fa-clock"></i> Đang chờ xác nhận';
                                break;
                            default:
                                echo '<i class="fas fa-link"></i> Chưa kết nối';
                        }
                        ?>
                    </div>
                    <?php if ($farmer['connection_status'] === 'not_connected'): ?>
    <form method="POST" style="margin-left: 20px;">
        <input type="hidden" name="farmer_id" value="<?= $farmer['id_nguoidung']; ?>">
        <button type="submit" class="connect-btn">
            <i class="fas fa-link"></i> Kết nối
        </button>
    </form>
<?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">Không tìm thấy nông dân nào!</p>
        <?php endif; ?>
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html> 