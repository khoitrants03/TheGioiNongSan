<?php
include 'components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id'])) {
    $patient_id = trim($_POST['patient_id']);
    

    if (empty($patient_id)) {
        echo json_encode(['error' => 'Vui lòng nhập mã bệnh nhân.']);
        exit;
    }

    try {
        // Truy vấn thông tin bệnh nhân
        $stmt = $conn->prepare("SELECT * FROM benhnhan WHERE MaBN = ?");
        $stmt->execute([$patient_id]);
        $patient_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$patient_data) {
            echo json_encode(['error' => 'Mã bệnh nhân không tồn tại.']);
            exit;
        }
    
        // Truy vấn thông tin bảo hiểm
        $insurance_stmt = $conn->prepare("
            SELECT MaBH 
            FROM yeucaubaohiem 
            WHERE MaBN = ? AND NgayBD <= CURDATE() AND NgayHH >= CURDATE()
        ");
        $insurance_stmt->execute([$patient_id]);
        $insurance_data = $insurance_stmt->fetch(PDO::FETCH_ASSOC);
        $insurance_code = $insurance_data['MaBH'] ?? 'Không có bảo hiểm hợp lệ';
    
        // Truy vấn khoa khám từ bảng lịch hẹn
        $appointment_stmt = $conn->prepare("
            SELECT DISTINCT KhoaKham 
            FROM lichhen 
            WHERE MaBN = ?
        ");
        $appointment_stmt->execute([$patient_id]);
        $appointment_data = $appointment_stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$appointment_data) {
            echo json_encode(['error' => 'Không tìm thấy lịch hẹn cho bệnh nhân này.']);
            exit;
        }
    
        // Tính tiền thuốc dựa trên bảng donthuoc
        $medicine_fee = 0;
        $medicine_stmt = $conn->prepare("
            SELECT ThanhTien 
            FROM donthuoc 
            WHERE MaBN = ?
        ");
        $medicine_stmt->execute([$patient_id]);
    
        while ($medicine = $medicine_stmt->fetch(PDO::FETCH_ASSOC)) {
            $medicine_fee += $medicine['ThanhTien'];
        }
    
        $total_amount = 150000 + $medicine_fee;
    
        // Kiểm tra nếu bệnh nhân có bảo hiểm y tế
        if ($insurance_code !== 'Không có bảo hiểm hợp lệ') {
            $discounted_amount = $total_amount * 0.2; // 20% chi phí
            $total_amount *= 0.8; // Bệnh nhân chỉ trả 80%
        } else {
            $discounted_amount = 0; // Không có giảm giá
        }
    
        echo json_encode([
            'name' => $patient_data['Ten'],
            'consultation_fee' => $discounted_amount,  // Số tiền được giảm khi có bảo hiểm
            'test_fee' => 150000,                      // Tiền xét nghiệm cố định
            'department' => $appointment_data['KhoaKham'], // Khoa khám
            'medicine_fee' => $medicine_fee,          // Tiền thuốc
            'insurance_code' => $insurance_code,      // Mã bảo hiểm
            'total_amount' => $total_amount           // Tổng chi phí sau giảm
        ]);
    
    } catch (Exception $e) {
        echo json_encode(['error' => 'Lỗi xử lý: ' . $e->getMessage()]);
    }
    
    exit;
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="css/thanhtoan.css">
    
</head>
<body>
<section class="checkout">
    <h1 class="title">Thanh Toán</h1>

    <!-- Form chính -->
    <form action="hoadon.php" method="post" class="payment-form">
        <div class="form-group">
            <label for="patient_id">Mã bệnh nhân</label>
            <input type="text" id="patient_id" name="patient_id" placeholder="Nhập mã bệnh nhân" required>
        </div>

        <div class="form-group">
            <label for="name">Họ tên</label>
            <input type="text" id="name" name="name" readonly>
        </div>

        <div class="form-group">
            <label for="department">Khoa khám:</label>
            <input type="text" id="department" name="department" readonly>
         </div>


        <!-- <div class="form-group">
            <label for="consultation_fee">Tiền khám bệnh</label>
            <input type="text" id="consultation_fee" name="consultation_fee" readonly>
        </div> -->

        <div class="form-group">
            <label for="medicine_fee">Tiền thuốc</label>
            <input type="text" id="medicine_fee" name="medicine_fee" readonly>
        </div>

        <div class="form-group">
            <label for="test_fee">Tiền khám bệnh</label>
            <input type="text" id="test_fee" name="test_fee" readonly>
        </div>

        <div class="form-group">
            <label for="insurance_code">Mã bảo hiểm</label>
            <input type="text" id="insurance_code" name="insurance_code" readonly>
         </div>

         <div class="form-group">
            <label for="consultation_fee">Tiền bảo hiểm</label>
            <input type="text" id="consultation_fee" name="consultation_fee" readonly>
         </div>


        <div class="form-group">
            <label for="total_amount">Tổng tiền</label>
            <input type="text" id="total_amount" name="total_amount" readonly>
        </div>

        <div class="form-group">
            <label for="payment_method">Hình thức thanh toán</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cash">Tiền mặt</option>
                <option value="MoMo">MoMo</option>
                <option value="Visa">Visa</option>
            </select>
        </div>

        <button type="submit" name="submit" class="confirm-btn">Xác nhận</button>
    </form>
</section>

<script>
   document.getElementById('patient_id').addEventListener('blur', function () {
    const patientId = this.value.trim();

    if (patientId) {
        fetch('', { // Tệp PHP hiện tại
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ patient_id: patientId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                clearFields();
            } else {
                document.getElementById('name').value = data.name;
                document.getElementById('consultation_fee').value = data.consultation_fee; // Chi phí được giảm
                document.getElementById('medicine_fee').value = data.medicine_fee;
                document.getElementById('test_fee').value = data.test_fee;
                document.getElementById('total_amount').value = data.total_amount; // Tổng chi phí
                document.getElementById('department').value = data.department; // Khoa khám
                document.getElementById('insurance_code').value = data.insurance_code; // Bảo hiểm 


            }
        })
        .catch(error => console.error('Error:', error));
      } else {
         clearFields();
      }
   });

   function clearFields() {
      document.querySelectorAll('.form-group input[type="text"]').forEach(input => input.value = '');
   }

   


</script>
</body>
</html>
