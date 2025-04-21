<?php
require_once 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

function generateProductQR($product_id, $product_name, $origin, $harvest_date) {
    // Create QR code data
    $data = json_encode([
        'product_id' => $product_id,
        'name' => $product_name,
        'origin' => $origin,
        'harvest_date' => $harvest_date,
        'timestamp' => time()
    ]);

    // Create QR code
    $qrCode = new QrCode($data);
    $qrCode->setSize(300);
    $qrCode->setMargin(10);
    
    // Create writer
    $writer = new PngWriter();
    
    // Generate QR code
    $result = $writer->write($qrCode);
    
    // Save QR code
    $filename = "qr_codes/product_{$product_id}.png";
    $result->saveToFile($filename);
    
    return $filename;
}
?> 