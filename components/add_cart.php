<?php
if (isset($_POST['add_to_cart'])) {
    if ($user_id == '') {
        header('location:login.php');
        exit;
    }

    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $qty = 1;

    // Check if product already exists in cart
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND name = ?");
    $check_cart->execute([$user_id, $name]);

    if ($check_cart->rowCount() > 0) {
        // If product exists, update quantity
        $fetch_cart = $check_cart->fetch(PDO::FETCH_ASSOC);
        $new_qty = $fetch_cart['quantity'] + 1;
        
        // Check stock availability
        $check_stock = $conn->prepare("SELECT so_luong_ton FROM `sanpham` WHERE ten_sanpham = ?");
        $check_stock->execute([$pid]);
        $stock = $check_stock->fetch(PDO::FETCH_ASSOC);

        if ($stock['so_luong_ton'] >= $new_qty) {
            $update_cart = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE user_id = ? AND name = ?");
            $update_cart->execute([$new_qty, $user_id, $name]);
            $message[] = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng!';
        } else {
            $message[] = 'Số lượng sản phẩm trong kho không đủ!';
        }
    } else {
        // Check product stock
        $check_stock = $conn->prepare("SELECT so_luong_ton FROM `sanpham` WHERE ten_sanpham = ?");
        $check_stock->execute([$pid]);
        $stock = $check_stock->fetch(PDO::FETCH_ASSOC);

        if ($stock['so_luong_ton'] > 0) {
            $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?,?,?,?,?)");
            $insert_cart->execute([$user_id, $name, $price, $qty, $image]);
            $message[] = 'Đã thêm vào giỏ hàng!';
        } else {
            $message[] = 'Sản phẩm đã hết hàng!';
        }
    }
}
?>
