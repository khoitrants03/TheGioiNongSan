<?php
include_once("Controller/cQLHS.php");

if (isset($_GET['id'])) {
    $MaBN = $_GET['id'];
    $controller = new controllQLHS();
    $result = $controller->deleteHSBN($MaBN);

    // Sử dụng kết quả trả về để hiển thị thông báo
    echo "<script>alert('$result'); window.location.href = 'QLHSBN.php';</script>";
}
?>