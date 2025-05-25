<?php
session_start();
require_once 'components/connect.php';

$business_id = $_SESSION['user_id'];

// Xử lý cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // Kiểm tra đơn hàng có tồn tại và thuộc doanh nghiệp này không
    $stmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = :id AND business_id = :business_id");
    $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':business_id', $business_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $message = "Đơn hàng không tồn tại hoặc đã bị xóa trước đó.";
    } elseif (in_array($order['payment_status'], ['completed', 'shipped']) && $new_status !== 'cancelled') {
        $message = "Bạn không thể chỉnh sửa đơn hàng này vì đã được xử lý hoặc đang giao hàng.";
    } else {
        // Cập nhật trạng thái
        $stmt = $conn->prepare("UPDATE orders SET payment_status = :status WHERE id = :id");
        $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        // Gửi thông báo (giả lập)
        if ($new_status === 'processing') {
            $message = "Đã xác nhận đơn hàng và chuyển sang trạng thái Đang xử lý. Đã gửi thông báo cho khách hàng/nông dân.";
        } elseif ($new_status === 'cancelled') {
            $message = "Đơn hàng đã được hủy và thông báo đã gửi đến khách hàng/nông dân.";
        } else {
            $message = "Cập nhật trạng thái đơn hàng thành công!";
        }
    }
}

// Lấy danh sách đơn hàng (luôn luôn lấy sau khi xử lý POST để đảm bảo dữ liệu mới nhất)
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = "WHERE business_id = :business_id";
if ($status_filter !== 'all') {
    $where_clause .= " AND payment_status = :status";
}
$query = "SELECT * FROM orders $where_clause ORDER BY placed_on DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':business_id', $business_id, PDO::PARAM_INT);
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
}
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn Hàng - Thế Giới Nông Sản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 20px;
            background: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .order-header {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.2em;
        }
        .order-info {
            margin-bottom: 8px;
        }
        .order-body {
            padding: 20px;
        }
        .order-footer {
            background-color: #f8f9fa;
            padding: 15px;
            border-top: 1px solid #ddd;
            border-radius: 0 0 8px 8px;
        }
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 14px 12px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s, background 0.2s;
        }
        .product-item:hover {
            background: #f1f1f1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 18px;
            border: 1px solid #ddd;
            background: #fff;
        }
        .product-item h6 {
            font-size: 1.1rem;
            margin-bottom: 2px;
            color: #222;
        }
        .product-item p {
            margin-bottom: 0;
            color: #666;
            font-size: 0.98rem;
        }
        .product-item .text-end {
            min-width: 100px;
            text-align: right;
            font-weight: 600;
            color: #1a8917;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
        }
        .status-pending { background-color: #ffeeba; color: #856404; }
        .status-processing { background-color: #b8daff; color: #004085; }
        .status-shipped { background-color: #c3e6cb; color: #155724; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .filter-section {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .order-item {
            border: 2px solid #1976d2;         /* Viền xanh dương nổi bật */
            border-radius: 10px;
            background: #fff;
            margin-bottom: 24px;
            padding: 20px 18px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .order-item:hover {
            border-color: #1565c0;
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.18);
            background: #f5faff;
        }
        .order-item .order-header {
            font-weight: bold;
            font-size: 1.15em;
            margin-bottom: 10px;
            color: #1976d2;
        }
        .order-item .order-info {
            margin-bottom: 8px;
            color: #333;
        }
    </style>
</head>
<body>
    

    <div class="heading">
        <h3>Quản Lí Đơn Hàng</h3>
        <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Quản lí đơn hàng</span></p>
    </div>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2><i class="fas fa-shopping-cart"></i> Quản lý Đơn Hàng</h2>
            </div>
            <div class="col-md-4">
                <div class="filter-section">
                    <form method="GET" class="d-flex">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>Tất cả đơn hàng</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                            <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Đang giao</option>
                            <!-- <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Hoàn tất</option> -->
                            <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <div class="order-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-bag"></i> Đơn hàng #<?php echo $order['id']; ?>
                                </h5>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> <?php echo date('d/m/Y', strtotime($order['placed_on'])); ?>
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="status-badge status-<?php echo $order['payment_status']; ?>">
                                    <?php
                                    echo $order['payment_status'] === 'pending' ? 'Chờ xử lý' :
                                        ($order['payment_status'] === 'processing' ? 'Đang xử lý' :
                                        ($order['payment_status'] === 'shipped' ? 'Đang giao' :
                                        ($order['payment_status'] === 'completed' ? 'Hoàn tất' : 'Đã hủy')));
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="customer-info">
                                    <h6><i class="fas fa-user"></i> Thông tin khách hàng</h6>
                                    <p class="mb-1"><strong>Tên:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                                    <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['number']); ?></p>
                                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                    <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order-info">
                                    <h6><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h6>
                                    <p class="mb-1"><strong>Phương thức thanh toán:</strong> <?php echo $order['method']; ?></p>
                                    <p class="mb-1"><strong>Tổng sản phẩm:</strong> <?php echo htmlspecialchars($order['total_products']); ?></p>
                                    <p class="mb-1"><strong>Tổng tiền:</strong> <?php echo number_format($order['total_price']); ?> VNĐ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <?php if ($order['payment_status'] === 'pending'): ?>
                                <button type="submit" name="new_status" value="processing" class="btn btn-success btn-sm">Xác nhận</button>
                                <button type="submit" name="new_status" value="cancelled" class="btn btn-danger btn-sm">Hủy</button>
                            <?php elseif (in_array($order['payment_status'], ['processing', 'shipped'])): ?>
                                <select name="new_status" onchange="this.form.submit()" class="form-select d-inline w-auto">
                                    <option value="">Cập nhật trạng thái</option>
                                    <?php if ($order['payment_status'] === 'processing'): ?>
                                        <option value="shipped">Đang giao</option>
                                    <?php endif; ?>
                                    <!-- <option value="completed">Hoàn tất</option> -->
                                    <option value="cancelled">Hủy</option>
                                </select>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Không có đơn hàng nào.
            </div>
        <?php endif; ?>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 