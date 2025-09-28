<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình mysqli để báo cáo ngoại lệ
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Lấy id_datphong từ URL
$id_datphong = isset($_GET['id_datphong']) ? intval($_GET['id_datphong']) : 0;

if ($id_datphong == 0) {
    echo "<p class='error-message'>Không tìm thấy thông tin đặt phòng.</p>";
    include 'footer.php';
    exit();
}

// Truy vấn thông tin hóa đơn chính
$sql_hoadon = "
    SELECT 
        hd.id_hoadon,
        hd.tong_tien,
        hd.ngay_xuat,
        dp.id_phong,
        dp.ngay_nhan,
        dp.ngay_tra,
        kh.ho_ten,
        kh.so_dien_thoai,
        p.so_phong,
        p.gia_phong,
        tt.hinh_thuc,
        tt.loai_thanh_toan
    FROM hoadon hd
    JOIN datphong dp ON hd.id_datphong = dp.id_datphong
    JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
    JOIN phong p ON dp.id_phong = p.id_phong
    LEFT JOIN thanhtoan tt ON hd.id_datphong = tt.id_datphong
    WHERE hd.id_datphong = ?
    GROUP BY hd.id_hoadon
";

$hoa_don = null;
$error = false;
try {
    $stmt = $conn->prepare($sql_hoadon);
    $stmt->bind_param("i", $id_datphong);
    $stmt->execute();
    $result = $stmt->get_result();
    $hoa_don = $result->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $error = true;
    echo "<p class='error-message'>Lỗi truy vấn: " . $e->getMessage() . "</p>";
}

// Truy vấn thông tin dịch vụ đã sử dụng
$sql_dichvu_sudung = "
    SELECT
        dv.ten_dich_vu,
        sdv.so_luong,
        sdv.thanh_tien
    FROM sudungdichvu sdv
    JOIN dichvu dv ON sdv.id_dichvu = dv.id_dichvu
    WHERE sdv.id_datphong = ?
";

$dichvu_sudung_list = [];
if (!$error) {
    try {
        $stmt_dv = $conn->prepare($sql_dichvu_sudung);
        $stmt_dv->bind_param("i", $id_datphong);
        $stmt_dv->execute();
        $result_dv = $stmt_dv->get_result();
        while($row = $result_dv->fetch_assoc()) {
            $dichvu_sudung_list[] = $row;
        }
        $stmt_dv->close();
    } catch (mysqli_sql_exception $e) {
        $error = true;
        echo "<p class='error-message'>Lỗi truy vấn dịch vụ: " . $e->getMessage() . "</p>";
    }
}

// Tính tổng tiền phòng và dịch vụ
$tong_tien_phai_tra = 0;
if ($hoa_don) {
    $tong_tien_dichvu = 0;
    foreach ($dichvu_sudung_list as $dv) {
        $tong_tien_dichvu += $dv['thanh_tien'];
    }
    // Sử dụng tổng tiền từ bảng hoadon (đã tính ở trang thanh toán)
    $tong_tien_phai_tra = $hoa_don['tong_tien'];
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        padding-top: 20px;
    }
    .invoice-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px;
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .invoice-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .invoice-title {
        font-size: 2.5em;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .invoice-info p {
        margin: 5px 0;
    }
    .invoice-details table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    .invoice-details th, .invoice-details td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .invoice-details th {
        background-color: #f2f2f2;
        color: #555;
    }
    .invoice-details .sub-title {
        background-color: #e6e6e6;
        font-weight: bold;
    }
    .invoice-total {
        text-align: right;
        font-size: 1.5em;
        font-weight: bold;
        margin-top: 20px;
    }
    .actions {
        text-align: center;
        margin-top: 30px;
    }
    .actions button {
        padding: 10px 20px;
        font-size: 1em;
        cursor: pointer;
        border: none;
        border-radius: 5px;
    }
    .print-button {
        background-color: #007bff;
        color: white;
    }
    .print-button:hover {
        background-color: #0056b3;
    }
    .error-message {
        text-align: center;
        color: red;
        font-size: 1.2em;
        margin-top: 50px;
    }
    @media print {
        .actions, .header, .footer {
            display: none;
        }
        body {
            background-color: #fff;
        }
        .invoice-container {
            border: none;
            box-shadow: none;
        }
    }
</style>

<div class="invoice-container">
    <?php if ($hoa_don) { ?>
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
            
            <?php if (!empty($dichvu_sudung_list)) { ?>
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
                    <?php foreach ($dichvu_sudung_list as $dv) { ?>
                        <tr>
                            <td><?= htmlspecialchars($dv['ten_dich_vu']) ?></td>
                            <td><?= htmlspecialchars($dv['so_luong']) ?></td>
                            <td><?= number_format($dv['thanh_tien'], 0, ',', '.') ?> VND</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
        
        <p class="invoice-total">Tổng tiền: <?= number_format($tong_tien_phai_tra, 0, ',', '.') ?> VND</p>

        <div class="actions">
            <button class="print-button" onclick="window.print()">In hóa đơn</button>
        </div>
    <?php } else { ?>
        <p class="error-message">Không tìm thấy thông tin hóa đơn. Vui lòng kiểm tra lại ID đặt phòng.</p>
    <?php } ?>
</div>

<?php 
if ($conn) {
    $conn->close();
}
include 'footer.php'; 
?>