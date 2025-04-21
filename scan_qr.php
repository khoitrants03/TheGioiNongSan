<?php
session_start();
include 'components/connect.php';
include 'components/user_header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quét mã QR sản phẩm - TheGioiNongSan</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/qr_scanner.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
    <section class="qr-scanner">
        <h1 class="heading">Quét mã QR sản phẩm</h1>
        
        <div class="scanner-container">
            <div id="reader"></div>
            <div id="result"></div>
        </div>

        <div class="product-info" id="productInfo" style="display: none;">
            <h2>Thông tin sản phẩm</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Tên sản phẩm:</span>
                    <span class="value" id="productName"></span>
                </div>
                <div class="info-item">
                    <span class="label">Nguồn gốc:</span>
                    <span class="value" id="productOrigin"></span>
                </div>
                <div class="info-item">
                    <span class="label">Ngày thu hoạch:</span>
                    <span class="value" id="harvestDate"></span>
                </div>
                <div class="info-item">
                    <span class="label">Mã sản phẩm:</span>
                    <span class="value" id="productId"></span>
                </div>
            </div>
        </div>
    </section>

    <script src="js/qr_scanner.js"></script>
</body>
</html> 