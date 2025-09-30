<?php

require_once __DIR__ . '/../core/Auth.php';
require_login();
check_permission(['ADMIN', 'NHANVIEN']);

// Kết nối PDO
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/HoadonController1.php';

$database = new Database();
$db = $database->getConnection();

$id_datphong = isset($_GET['id_datphong']) ? intval($_GET['id_datphong']) : 0;

$controller = new HoadonController1($db);
list($hoa_don, $dichvu_sudung_list, $error) = $controller->showInvoice($id_datphong);

$tong_tien_phai_tra = 0;
if ($hoa_don) {
    $tong_tien_phai_tra = $hoa_don['tong_tien'];
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f9f9f9;
    padding: 20px;
}
.invoice-container {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.15);
    max-width: 800px;
    margin: auto;
}
.invoice-header {
    text-align: center;
    margin-bottom: 20px;
}
.invoice-header h1 {
    margin: 0;
    font-size: 24px;
    color: #333;
}
.invoice-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}
.invoice-info p {
    margin: 4px 0;
}
.invoice-details table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.invoice-details th, .invoice-details td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
.invoice-details th {
    background: #f1f1f1;
}
.sub-title {
    text-align: center;
    background: #e6e6e6;
    font-weight: bold;
}
.invoice-total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    margin-top: 10px;
}
.actions {
    text-align: center;
    margin-top: 20px;
}
.print-button {
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.print-button:hover {
    background: #45a049;
}
.back-button {
    display: inline-block;
    margin-left: 15px;
    padding: 10px 20px;
    background: #2196F3;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 15px;
    transition: background 0.3s ease;
}

.back-button:hover {
    background: #1976D2;
}

@media print {
    .actions { display: none; }
    body { background: #fff; }
    .invoice-container { box-shadow: none; border: none; }
}
</style>

<div class="invoice-container">
    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($hoa_don): ?>
        <div class="invoice-header">
            <h1 class="invoice-title">HÓA ĐƠN THANH TOÁN</h1>
            <p>Ngày xuất: <?= htmlspecialchars($hoa_don['ngay_xuat']) ?></p>
        </div>
        <div class="invoice-info">
            <div>
                <p><strong>Tên khách hàng:</strong> <?= htmlspecialchars($hoa_don['ho_ten']) ?></p>
                <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($hoa_don['so_dien_thoai']) ?></p>
            </div>
            <div>
                <p><strong>Mã hóa đơn:</strong> <?= htmlspecialchars($hoa_don['id_hoadon']) ?></p>
                <p><strong>Mã đặt phòng:</strong> <?= htmlspecialchars($id_datphong) ?></p>
            </div>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="sub-title">Thông tin phòng</th>
                    </tr>
                    <tr>
                        <th>Tên phòng</th>
                        <th>Giá phòng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($hoa_don['so_phong']) ?></td>
                        <td><?= number_format($hoa_don['gia_phong'], 0, ',', '.') ?> VND</td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="2" class="sub-title">Thời gian lưu trú</th>
                    </tr>
                    <tr>
                        <th>Ngày đến</th>
                        <th>Ngày đi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($hoa_don['ngay_nhan']) ?></td>
                        <td><?= htmlspecialchars($hoa_don['ngay_tra']) ?></td>
                    </tr>
                </tbody>
            </table>
            
            <?php if (!empty($dichvu_sudung_list)): ?>
            <br>
            <table>
                <thead>
                    <tr>
                        <th colspan="3" class="sub-title">Chi tiết dịch vụ</th>
                    </tr>
                    <tr>
                        <th>Tên dịch vụ</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dichvu_sudung_list as $dv): ?>
                        <tr>
                            <td><?= htmlspecialchars($dv['ten_dich_vu']) ?></td>
                            <td><?= htmlspecialchars($dv['so_luong']) ?></td>
                            <td><?= number_format($dv['thanh_tien'], 0, ',', '.') ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        
        <p class="invoice-total">Tổng tiền: <?= number_format($tong_tien_phai_tra, 0, ',', '.') ?> VND</p>

        <div class="actions">
            <button class="print-button" onclick="window.print()">In hóa đơn</button>
           <a href="../public/index.php" class="back-button">⬅ Quay lại Trang chủ</a>
        </div>
    <?php endif; ?>
</div>

<?php // ❌ bỏ footer để không in menu ?>
