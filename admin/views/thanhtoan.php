<?php include __DIR__ . '/layouts/header.php'; ?>

<style>
    /* Reset và cấu hình cơ bản */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #eef2f5;
        color: #444;
    }

    /* Container chính */
    .container {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #1a237e;
        margin-bottom: 30px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    /* Cấu trúc form */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    input, select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }

    /* Hiển thị chi tiết thanh toán */
    .payment-details {
        margin-top: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px dashed #ced4da;
    }

    .payment-details p {
        margin: 0 0 10px;
        display: flex;
        justify-content: space-between;
        font-size: 15px;
    }

    .payment-details p:last-child {
        margin-bottom: 0;
        font-weight: bold;
        color: #28a745;
        font-size: 18px;
        border-top: 1px solid #e9ecef;
        padding-top: 10px;
        margin-top: 10px;
    }

    /* Nút button */
    .form-actions {
        margin-top: 30px;
        text-align: center;
    }

    .form-actions button {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        background-color: #116e8eff;
        color: white;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    .form-actions button:hover {
        background-color: #20a7e0ff;
        transform: translateY(-2px);
    }

    .form-actions button:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(40, 167, 69, 0.4);
    }
</style>

<div class="container">
    <h2>Thanh toán</h2>
  <form method="post" action="index.php?controller=process_payment&action=store">

        <div class="form-group">
            <label for="id_phong">Chọn phòng:</label>
            <select id="id_phong" name="id_phong" required onchange="updatePrice()">
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($phong_list as $phong) { ?>
                    <option value="<?= htmlspecialchars($phong['id_phong']) ?>"
                            data-total-price="<?= htmlspecialchars($phong['tong_tien_phai_tra']) ?>"
                            data-so-phong="<?= htmlspecialchars($phong['so_phong']) ?>"
                            data-gia-phong="<?= htmlspecialchars($phong['gia_phong']) ?>"
                            data-tien-dv="<?= htmlspecialchars($phong['tong_tien_dichvu']) ?>"
                            data-id-datphong="<?= htmlspecialchars($phong['id_datphong']) ?>">
                        Phòng <?= htmlspecialchars($phong['so_phong']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <input type="hidden" id="id_datphong" name="id_datphong" value="">

        <div class="form-group">
            <label for="so_tien">Số tiền phải trả (VND):</label>
            <input type="text" id="so_tien" name="so_tien" required readonly>
        </div>
        
        <!-- Chi tiết thanh toán -->
        <div id="payment-details-summary" class="payment-details" style="display:none;">
            <p><strong>Số phòng:</strong> <span id="summary-so-phong"></span></p>
            <p><strong>Giá phòng:</strong> <span id="summary-gia-phong"></span></p>
            <p><strong>Tiền dịch vụ:</strong> <span id="summary-tien-dv"></span></p>
            <p><strong>Tổng cộng:</strong> <span id="summary-tong-cong"></span></p>
        </div>

        <div class="form-group">
            <label for="hinh_thuc">Hình thức:</label>
            <select id="hinh_thuc" name="hinh_thuc" required>
                <option value="Tiền mặt">Tiền mặt</option>
                <option value="Chuyển khoản">Chuyển khoản</option>
            </select>
        </div>

        <div class="form-group">
            <label for="loai_thanh_toan">Loại thanh toán:</label>
            <select id="loai_thanh_toan" name="loai_thanh_toan" required>
                <option value="Thanh toán cuối">Thanh toán cuối</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" name="submit_thanh_toan">Thanh toán</button>
        </div>
    </form>
</div>

<script>
    function formatCurrency(number) {
        if (!number) return '0 VND';
        return Number(number).toLocaleString('vi-VN') + ' VND';
    }

    function updatePrice() {
        const selectElement = document.getElementById('id_phong');
        const priceInput = document.getElementById('so_tien');
        const idDatphongInput = document.getElementById('id_datphong');
        const summaryDiv = document.getElementById('payment-details-summary');
        
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (selectedOption.value) {
            const soPhong = selectedOption.getAttribute('data-so-phong');
            const giaPhong = selectedOption.getAttribute('data-gia-phong');
            const tienDV = selectedOption.getAttribute('data-tien-dv');
            const totalPrice = selectedOption.getAttribute('data-total-price');
            const idDatphong = selectedOption.getAttribute('data-id-datphong');
            
            priceInput.value = formatCurrency(totalPrice);
            idDatphongInput.value = idDatphong;

            summaryDiv.style.display = 'block';
            document.getElementById('summary-so-phong').textContent = soPhong;
            document.getElementById('summary-gia-phong').textContent = formatCurrency(giaPhong);
            document.getElementById('summary-tien-dv').textContent = formatCurrency(tienDV);
            document.getElementById('summary-tong-cong').textContent = formatCurrency(totalPrice);
        } else {
            priceInput.value = '';
            idDatphongInput.value = '';
            summaryDiv.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updatePrice();
    });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
