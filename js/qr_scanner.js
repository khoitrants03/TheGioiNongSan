let html5QrcodeScanner;

function onScanSuccess(decodedText, decodedResult) {
    // Hiển thị nội dung quét được (dù là văn bản, URL hay JSON...)
    document.getElementById('result').innerText = `Kết quả: ${decodedText}`;

    // Nếu bạn muốn dừng quét sau khi có kết quả:
    html5QrcodeScanner.clear();
}

function onScanFailure(error) {
    console.warn(`QR scan error: ${error}`);
}

document.addEventListener('DOMContentLoaded', function() {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: { width: 250, height: 250 } },
        false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});