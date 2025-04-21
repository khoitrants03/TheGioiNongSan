<?php
include 'components/connect.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'business'){
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Handle order creation
if(isset($_POST['create_order'])){
   $customer_name = $_POST['customer_name'];
   $customer_phone = $_POST['customer_phone'];
   $customer_address = $_POST['customer_address'];
   $products = $_POST['products'];
   $quantities = $_POST['quantities'];
   $total_price = $_POST['total_price'];
   
   $insert_order = $conn->prepare("INSERT INTO `orders` (business_id, customer_name, customer_phone, customer_address, total_price, payment_status) VALUES (?, ?, ?, ?, ?, 'pending')");
   $insert_order->execute([$user_id, $customer_name, $customer_phone, $customer_address, $total_price]);
   $order_id = $conn->lastInsertId();
   
   // Insert order items
   for($i = 0; $i < count($products); $i++){
      $insert_item = $conn->prepare("INSERT INTO `order_items` (order_id, product_id, quantity) VALUES (?, ?, ?)");
      $insert_item->execute([$order_id, $products[$i], $quantities[$i]]);
   }
   
   $message[] = 'Đã tạo đơn hàng thành công!';
}

// Handle order deletion
if(isset($_POST['delete_order'])){
   $order_id = $_POST['order_id'];
   
   $delete_items = $conn->prepare("DELETE FROM `order_items` WHERE order_id = ?");
   $delete_items->execute([$order_id]);
   
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ? AND business_id = ?");
   $delete_order->execute([$order_id, $user_id]);
   
   $message[] = 'Đã xóa đơn hàng thành công!';
}

// Fetch orders
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE business_id = ? ORDER BY placed_on DESC");
$select_orders->execute([$user_id]);

// Fetch products
$select_products = $conn->prepare("SELECT * FROM `products` WHERE business_id = ?");
$select_products->execute([$user_id]);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quản lý đơn hàng</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .orders-container {
         display: grid;
         grid-template-columns: 1fr 2fr;
         gap: 2rem;
         padding: 2rem;
      }
      .create-order-form {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .orders-list {
         background: #fff;
         padding: 2rem;
         border-radius: .5rem;
         box-shadow: var(--box-shadow);
      }
      .order-item {
         padding: 1.5rem;
         border-bottom: 1px solid var(--light-bg);
      }
      .order-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 1rem;
      }
      .order-id {
         font-weight: bold;
         color: var(--black);
      }
      .order-date {
         color: var(--light-color);
      }
      .order-details {
         margin-top: 1rem;
      }
      .order-details p {
         margin: .5rem 0;
      }
      .product-row {
         display: flex;
         align-items: center;
         gap: 1rem;
         margin-bottom: 1rem;
      }
      .product-select {
         flex: 2;
      }
      .quantity-input {
         flex: 1;
      }
      .add-product-btn {
         margin-top: 1rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="manage-orders">
   <div class="heading">
      <h3>Quản lý đơn hàng</h3>
      <p><a href="business_dashboard.php">Trang quản lý</a> <span> / Quản lý đơn hàng</span></p>
   </div>

   <div class="orders-container">
      <div class="create-order-form">
         <h3>Tạo đơn hàng mới</h3>
         <form action="" method="post" id="orderForm">
            <div class="inputBox">
               <span>Tên khách hàng</span>
               <input type="text" name="customer_name" required class="box">
            </div>
            <div class="inputBox">
               <span>Số điện thoại</span>
               <input type="text" name="customer_phone" required class="box">
            </div>
            <div class="inputBox">
               <span>Địa chỉ</span>
               <textarea name="customer_address" required class="box" cols="30" rows="3"></textarea>
            </div>
            
            <div id="productsContainer">
               <div class="product-row">
                  <div class="product-select">
                     <span>Sản phẩm</span>
                     <select name="products[]" class="box" required>
                        <option value="">Chọn sản phẩm</option>
                        <?php
                        while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <option value="<?= $fetch_product['id']; ?>" data-price="<?= $fetch_product['price']; ?>">
                           <?= $fetch_product['name']; ?> - <?= number_format($fetch_product['price']); ?> VNĐ
                        </option>
                        <?php
                        }
                        ?>
                     </select>
                  </div>
                  <div class="quantity-input">
                     <span>Số lượng</span>
                     <input type="number" name="quantities[]" min="1" value="1" class="box" required>
                  </div>
               </div>
            </div>
            
            <button type="button" class="btn add-product-btn" onclick="addProductRow()">
               <i class="fas fa-plus"></i> Thêm sản phẩm
            </button>
            
            <div class="inputBox">
               <span>Tổng tiền</span>
               <input type="text" name="total_price" class="box" readonly>
            </div>
            
            <input type="submit" value="Tạo đơn hàng" name="create_order" class="btn">
         </form>
      </div>

      <div class="orders-list">
         <h3>Danh sách đơn hàng</h3>
         <?php
         if($select_orders->rowCount() > 0){
            while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="order-item">
            <div class="order-header">
               <div class="order-id">
                  <i class="fas fa-shopping-cart"></i>
                  Đơn hàng #<?= str_pad($fetch_order['id'], 8, '0', STR_PAD_LEFT); ?>
               </div>
               <div class="order-date">
                  <?= date('d/m/Y H:i', strtotime($fetch_order['placed_on'])); ?>
               </div>
            </div>
            <div class="order-details">
               <p><strong>Khách hàng:</strong> <?= $fetch_order['customer_name']; ?></p>
               <p><strong>Điện thoại:</strong> <?= $fetch_order['customer_phone']; ?></p>
               <p><strong>Địa chỉ:</strong> <?= $fetch_order['customer_address']; ?></p>
               <p><strong>Tổng tiền:</strong> <?= number_format($fetch_order['total_price']); ?> VNĐ</p>
               <p><strong>Trạng thái:</strong> 
                  <?php
                  switch($fetch_order['payment_status']){
                     case 'pending':
                        echo '<span style="color: #ffc107;">Đang chờ xử lý</span>';
                        break;
                     case 'processing':
                        echo '<span style="color: #17a2b8;">Đang xử lý</span>';
                        break;
                     case 'completed':
                        echo '<span style="color: #28a745;">Đã hoàn thành</span>';
                        break;
                     case 'cancelled':
                        echo '<span style="color: #dc3545;">Đã hủy</span>';
                        break;
                  }
                  ?>
               </p>
            </div>
            <div class="order-actions">
               <a href="view_order.php?id=<?= $fetch_order['id']; ?>" class="btn">
                  <i class="fas fa-eye"></i> Xem chi tiết
               </a>
               <form action="" method="post" style="display: inline;">
                  <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>">
                  <button type="submit" name="delete_order" class="btn" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                     <i class="fas fa-trash"></i> Xóa
                  </button>
               </form>
            </div>
         </div>
         <?php
            }
         }else{
            echo '<p class="empty">Chưa có đơn hàng nào!</p>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script>
function addProductRow() {
   const container = document.getElementById('productsContainer');
   const newRow = container.firstElementChild.cloneNode(true);
   newRow.querySelector('select').value = '';
   newRow.querySelector('input').value = '1';
   container.appendChild(newRow);
   updateTotal();
}

function updateTotal() {
   const form = document.getElementById('orderForm');
   const products = form.querySelectorAll('select[name="products[]"]');
   const quantities = form.querySelectorAll('input[name="quantities[]"]');
   let total = 0;
   
   products.forEach((product, index) => {
      if(product.value) {
         const price = product.options[product.selectedIndex].dataset.price;
         const quantity = quantities[index].value;
         total += price * quantity;
      }
   });
   
   form.querySelector('input[name="total_price"]').value = total.toLocaleString('vi-VN') + ' VNĐ';
}

document.addEventListener('DOMContentLoaded', function() {
   const form = document.getElementById('orderForm');
   form.querySelectorAll('select[name="products[]"], input[name="quantities[]"]').forEach(element => {
      element.addEventListener('change', updateTotal);
   });
});
</script>
</body>
</html> 