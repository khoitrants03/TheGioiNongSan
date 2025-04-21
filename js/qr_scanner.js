let html5QrcodeScanner;

function onScanSuccess(decodedText, decodedResult) {
    try {
        const productData = JSON.parse(decodedText);
        displayProductInfo(productData);
        html5QrcodeScanner.clear();
    } catch (e) {
        document.getElementById('result').innerHTML = 'Mã QR không hợp lệ!';
    }
}

function displayProductInfo(productData) {
    const productInfo = document.getElementById('productInfo');
    const productName = document.getElementById('productName');
    const productOrigin = document.getElementById('productOrigin');
    const harvestDate = document.getElementById('harvestDate');
    const productId = document.getElementById('productId');

    // Format the harvest date
    const date = new Date(productData.harvest_date);
    const formattedDate = date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Update the UI
    productName.textContent = productData.name;
    productOrigin.textContent = productData.origin;
    harvestDate.textContent = formattedDate;
    productId.textContent = productData.product_id;

    // Show the product info
    productInfo.style.display = 'block';
}

function onScanFailure(error) {
    console.warn(`QR scan error: ${error}`);
}

// Initialize the scanner when the page loads
document.addEventListener('DOMContentLoaded', function() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
        false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}); 