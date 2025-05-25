<?php
include 'components/connect.php';

session_start();

$id_nongdan = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$id_nongdan) {
   echo "<script>alert('Bạn chưa đăng nhập'); window.location.href='login.php';</script>";
   exit();
}

$message = '';

// Xử lý cập nhật trạng thái khi nhận POST AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_donhang']) && isset($_POST['trang_thai'])) {
   header('Content-Type: application/json; charset=utf-8');

   $id_donhang = $_POST['id_donhang'];
   $trang_thai = $_POST['trang_thai'];

   try {
      // Kiểm tra đơn hàng có tồn tại và thuộc sản phẩm của nông dân đang đăng nhập không
      $check_order = $conn->prepare("
            SELECT dh.id_donhang 
            FROM donhang dh
            JOIN chitietdonhang ct ON dh.id_donhang = ct.id_donhang
            JOIN sanpham sp ON ct.id_sanpham = sp.id_sanpham
            WHERE dh.id_donhang = ? AND sp.id_nongdan = ?
        ");
      $check_order->execute([$id_donhang, $id_nongdan]);

      if ($check_order->rowCount() > 0) {
         // Kiểm tra xem id_donhang đã tồn tại trong bảng trangthaiphanphoi chưa
         $check_status = $conn->prepare("SELECT id_trangthai FROM trangthaiphanphoi WHERE id_donhang = ?");
         $check_status->execute([$id_donhang]);

         if ($check_status->rowCount() > 0) {
            // Nếu tồn tại thì UPDATE
            $update = $conn->prepare("UPDATE trangthaiphanphoi SET trang_thai = ?, ngay_cap_nhat = NOW() WHERE id_donhang = ?");
            $success = $update->execute([$trang_thai, $id_donhang]);
            $action = "cập nhật";
         } else {
            // Nếu không tồn tại thì INSERT
            $insert = $conn->prepare("INSERT INTO trangthaiphanphoi (id_donhang, trang_thai, ngay_cap_nhat) VALUES (?, ?, NOW())");
            $success = $insert->execute([$id_donhang, $trang_thai]);
            $action = "thêm mới";
         }

         if ($success) {
            echo json_encode([
               'status' => 'success',
               'message' => "Đã $action trạng thái đơn hàng #$id_donhang thành '$trang_thai' thành công!",
               'action' => $action
            ]);
         } else {
            echo json_encode([
               'status' => 'error',
               'message' => "Lỗi khi $action trạng thái đơn hàng #$id_donhang."
            ]);
         }
      } else {
         echo json_encode([
            'status' => 'error',
            'message' => "Đơn hàng #$id_donhang không tồn tại hoặc bạn không có quyền cập nhật."
         ]);
      }
   } catch (PDOException $e) {
      echo json_encode([
         'status' => 'error',
         'message' => "Lỗi cơ sở dữ liệu: " . $e->getMessage()
      ]);
   }
   exit();
}

// Lấy danh sách đơn hàng cho nông dân
$stmt = $conn->prepare("
   SELECT sp.ten_sanpham, ct.so_luong, ct.don_gia, dh.tong_tien, dh.ngay_dat,
         dh.id_donhang, COALESCE(tt.trang_thai, 'Chờ xác nhận') as trang_thai, 
         dh.trang_thai as trangthai_donhang,
         nd.ho_ten, nd.so_dien_thoai,
         tt.ngay_cap_nhat
   FROM donhang dh
   LEFT JOIN trangthaiphanphoi tt ON dh.id_donhang = tt.id_donhang
   JOIN chitietdonhang ct ON ct.id_donhang = dh.id_donhang
   JOIN sanpham sp ON sp.id_sanpham = ct.id_sanpham
   JOIN nguoidung nd ON nd.id_nguoidung = dh.id_khachhang
   WHERE sp.id_nongdan = ?
   ORDER BY dh.ngay_dat DESC
");
$stmt->execute([$id_nongdan]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Theo dõi đơn hàng</title>
   <link rel="shortcut icon" href="./imgs/hospital-solid.svg" type="image/x-icon" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
   <link rel="stylesheet" href="css/style.css" />
   <link rel="stylesheet" href="css/track_order.css" />
</head>

<body>
   <?php
   if (isset($_SESSION['phanquyen']) && $_SESSION['phanquyen'] === 'nongdan') {
      require("components/user_header_nongdan.php");
   } else {
      include("components/user_header.php");
   }
   ?>

   <!-- Toast notification -->
   <section id="toast" class="toast" style="background: aquamarine;"></section>

   <?php if ($message): ?>
      <section class="alert alert-info">
         <?= htmlspecialchars($message) ?>
         <button onclick="this.parentElement.style.display='none'">×</button>
      </section>
   <?php endif; ?>

   <section class="products_1">
      <div class="form-title">DANH SÁCH SẢN PHẨM ĐÃ ĐẶT</div>
      <div class="table-container">
         <table class="product-table">
            <thead>
               <tr>
                  <th>Mã ĐH</th>
                  <th>Người đặt</th>
                  <th>Thông tin</th>
                  <th>Tên sản phẩm</th>
                  <th>Số lượng</th>
                  <th>Đơn giá (VNĐ)</th>
                  <th>Tổng tiền (VNĐ)</th>
                  <th>Trạng thái thanh toán</th>
                  <th>Trạng thái giao hàng</th>
                  <th>Ngày đặt</th>
                  <th>Cập nhật lần cuối</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($orders as $row): ?>
                  <tr class="order-row clickable-row" data-id-donhang="<?= $row['id_donhang'] ?>">
                     <td class="order-id">#<?= htmlspecialchars($row['id_donhang']) ?></td>
                     <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                     <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                     <td><?= htmlspecialchars($row['ten_sanpham']) ?></td>
                     <td><?= $row['so_luong'] ?></td>
                     <td><?= number_format($row['don_gia'], 0, ',', '.') ?></td>
                     <td><?= number_format($row['tong_tien'], 0, ',', '.') ?></td>
                     <td>
                        <span class="payment-status <?= strtolower(str_replace(' ', '-', $row['trangthai_donhang'])) ?>">
                           <?= htmlspecialchars($row['trangthai_donhang']) ?>
                        </span>
                     </td>
                     <td class="delivery-status-cell">
                        <span class="delivery-status <?= strtolower(str_replace(' ', '-', $row['trang_thai'])) ?>">
                           <?= htmlspecialchars($row['trang_thai']) ?>
                        </span>
                     </td>
                     <td><?= date('d/m/Y', strtotime($row['ngay_dat'])) ?></td>
                     <td>
                        <?= $row['ngay_cap_nhat'] ? date('d/m/Y H:i', strtotime($row['ngay_cap_nhat'])) : 'Chưa cập nhật' ?>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>

      <div class="instruction">
         <i class="fas fa-info-circle"></i>
         <span>Nhấp vào một dòng trong bảng để cập nhật trạng thái giao hàng</span>
      </div>
   </section>

   <div class="container">
      <h1>Cập nhật trạng thái đơn hàng</h1>
      <div class="order-filter">
         <input type="text" id="searchInput"
            placeholder="Tìm kiếm theo mã đơn hàng, tên khách hàng hoặc trạng thái..." />
         <button type="button" id="refreshBtn" class="refresh-btn">
            <i class="fas fa-sync-alt"></i> Làm mới
         </button>
      </div>

      <div class="order-list">
         <div class="no-selection" id="noSelection">
            <h3>Chọn đơn hàng để cập nhật</h3>
            <p>Vui lòng nhấp vào một dòng trong bảng phía trên để hiển thị form cập nhật trạng thái</p>
         </div>

         <?php
         // Gom đơn hàng theo id_donhang (mỗi đơn 1 form)
         $grouped_orders = [];
         foreach ($orders as $order) {
            $grouped_orders[$order['id_donhang']] = $order;
         }
         foreach ($grouped_orders as $order): ?>
            <form class="order-item ajax-form" data-id-donhang="<?= $order['id_donhang'] ?>" style="display:none;">
               <div class="order-header">
                  <div class="order-info">
                     <span class="order-number">Đơn hàng #<?= htmlspecialchars($order['id_donhang']) ?></span>
                     <span class="order-date"><?= date('d/m/Y H:i', strtotime($order['ngay_dat'])) ?></span>
                  </div>
                  <span class="status delivery-status <?= strtolower(str_replace(' ', '-', $order['trang_thai'])) ?>">
                     <?= htmlspecialchars($order['trang_thai']) ?>
                  </span>
               </div>

               <div class="order-details">
                  <input type="hidden" name="id_donhang" value="<?= $order['id_donhang'] ?>" />
                  <p><strong>Sản phẩm:</strong> <?= htmlspecialchars($order['ten_sanpham']) ?></p>
                  <p><strong>Số lượng:</strong> <?= $order['so_luong'] ?></p>
                  <p style="color: red;"><strong>Thanh toán:</strong> <?= htmlspecialchars($order['trangthai_donhang']) ?>
                  </p>

                  <p><strong>Tình trạng:</strong>
                     <select name="trang_thai" required>
                        <option value="Chờ xác nhận" <?= $order['trang_thai'] === 'Chờ xác nhận' ? 'selected' : '' ?>>Chờ xác
                           nhận</option>
                        <option value="Đã xác nhận" <?= $order['trang_thai'] === 'Đã xác nhận' ? 'selected' : '' ?>>Đã xác
                           nhận</option>
                        <!-- <option value="Đang giao hàng" <?= $order['trang_thai'] === 'Đang giao hàng' ? 'selected' : '' ?>>Đang
                           giao hàng</option>
                        <option value="Đã giao hàng" <?= $order['trang_thai'] === 'Đã giao hàng' ? 'selected' : '' ?>>Đã giao
                           hàng</option> -->
                        <option value="Đã hủy" <?= $order['trang_thai'] === 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                     </select>
                  </p>

                  <div class="button-group">
                     <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Cập nhật trạng thái
                     </button>
                     <button type="button" class="btn-close" onclick="hideOrderForm()">
                        <i class="fas fa-times"></i> Đóng
                     </button>
                  </div><?php if ($order['ngay_cap_nhat']): ?>
                     <p> <small><i class="fas fa-clock"></i> Cập nhật lần cuối:
                           <?= date('d/m/Y H:i:s', strtotime($order['ngay_cap_nhat'])) ?></small></p>

                  </div>
            </div>



         <?php endif; ?>
      </div>
      </form>
   <?php endforeach; ?>
   </div>
   </div>

   <script>
      // Toast notification function
      function showToast(message, type = 'success') {
         const toast = document.getElementById('toast');
         toast.textContent = message;
         toast.className = `toast ${type} show`;
         setTimeout(() => {
            toast.classList.remove('show');
         }, 3000);
      }

      function hideOrderForm() {
         const forms = document.querySelectorAll('.order-item');
         forms.forEach(form => form.style.display = 'none');
         document.getElementById('noSelection').style.display = 'block';

         // Remove selected class from all rows
         const rows = document.querySelectorAll('.order-row');
         rows.forEach(row => row.classList.remove('selected'));
      }

      document.addEventListener('DOMContentLoaded', () => {
         const rows = document.querySelectorAll('.order-row');
         const forms = document.querySelectorAll('.order-item');

         // Handle row clicks
         rows.forEach(row => {
            row.addEventListener('click', () => {
               const id = row.getAttribute('data-id-donhang');

               // Hide all forms and no-selection
               forms.forEach(form => form.style.display = 'none');
               document.getElementById('noSelection').style.display = 'none';

               // Remove selected class from all rows
               rows.forEach(r => r.classList.remove('selected'));

               // Add selected class to clicked row
               row.classList.add('selected');

               // Show the corresponding form
               const formToShow = document.querySelector(`.order-item[data-id-donhang="${id}"]`);
               if (formToShow) {
                  formToShow.style.display = 'block';
                  formToShow.scrollIntoView({ behavior: 'smooth', block: 'start' });
               }
            });
         });

         // Handle AJAX form submissions
         const ajaxForms = document.querySelectorAll('.ajax-form');
         ajaxForms.forEach(form => {
            form.addEventListener('submit', function (e) {
               e.preventDefault();

               const submitBtn = form.querySelector('.btn-update');
               const originalText = submitBtn.innerHTML;

               // Show loading state
               submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
               submitBtn.disabled = true;

               const formData = new FormData(form);
               const select = form.querySelector('select[name="trang_thai"]');
               const trangThai = select.value;
               const idDonhang = form.dataset.idDonhang;

               fetch('', {
                  method: 'POST',
                  body: formData
               })
                  .then(res => res.json())
                  .then(data => {
                     if (data.status === 'success') {
                        showToast(data.message, 'success');

                        // Update status in form
                        const statusSpan = form.querySelector('.status');
                        if (statusSpan) {
                           statusSpan.textContent = trangThai;
                           statusSpan.className = 'status delivery-status ' + trangThai.toLowerCase().replace(/\s/g, '-');
                        }

                        // Update status in table
                        const row = document.querySelector(`.order-row[data-id-donhang="${idDonhang}"]`);
                        if (row) {
                           const deliveryStatusCell = row.querySelector('.delivery-status');
                           if (deliveryStatusCell) {
                              deliveryStatusCell.textContent = trangThai;
                              deliveryStatusCell.className = 'delivery-status ' + trangThai.toLowerCase().replace(/\s/g, '-');
                           }

                           // Update last update time
                           const lastUpdateCell = row.cells[10];
                           if (lastUpdateCell) {
                              const now = new Date();
                              const dateStr = now.toLocaleDateString('vi-VN') + ' ' +
                                 now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                              lastUpdateCell.textContent = dateStr;
                           }
                        }

                        // Update last update info in form
                        const lastUpdateDiv = form.querySelector('.last-update small');
                        if (lastUpdateDiv) {
                           const now = new Date();
                           const dateStr = now.toLocaleDateString('vi-VN') + ' ' +
                              now.toLocaleTimeString('vi-VN');
                           lastUpdateDiv.innerHTML = `<i class="fas fa-clock"></i> Cập nhật lần cuối: ${dateStr}`;
                        }
                     } else {
                        showToast(data.message, 'error');
                     }
                  })
                  .catch(() => {
                     showToast('Lỗi kết nối! Vui lòng thử lại.', 'error');
                  })
                  .finally(() => {
                     // Restore button state
                     submitBtn.innerHTML = originalText;
                     submitBtn.disabled = false;
                  });
            });
         });

         // Search functionality
         const searchInput = document.getElementById('searchInput');
         searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            rows.forEach(row => {
               const text = row.textContent.toLowerCase();
               if (text.includes(filter)) {
                  row.style.display = '';
               } else {
                  row.style.display = 'none';
               }
            });
         });

         // Refresh button
         document.getElementById('refreshBtn').addEventListener('click', () => {
            location.reload();
         });
      });
   </script>
       <?php include 'components/footer_admin.php'; ?>

    <script src="js/script.js"></script>
</body>