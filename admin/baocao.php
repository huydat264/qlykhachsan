<?php
include 'header.php';
include 'db.php';

// Tổng số phòng
$totalPhong = $conn->query("SELECT COUNT(*) as total FROM phong")->fetch_assoc()['total'];

// Tổng số dịch vụ
$totalDichVu = $conn->query("SELECT COUNT(*) as total FROM dichvu")->fetch_assoc()['total'];

// Tổng số khách hàng
$totalKhachHang = $conn->query("SELECT COUNT(*) as total FROM khachhang")->fetch_assoc()['total'];

// Tổng số nhân viên
$totalNhanVien = $conn->query("SELECT COUNT(*) as total FROM nhanvien")->fetch_assoc()['total'];

// Phòng trống / đang đặt / đã đặt
$phongTrong = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Trống'")->fetch_assoc()['total'];
$phongDangDat = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Đang đặt'")->fetch_assoc()['total'];
$phongDaDat = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Đã đặt'")->fetch_assoc()['total'];

// Doanh thu ước tính (tổng giá phòng của các phòng đang được đặt hoặc đã được đặt)
$doanhThuUocTinh = $conn->query("SELECT SUM(gia_phong) as total FROM phong WHERE trang_thai IN ('Đang đặt', 'Đã đặt')")->fetch_assoc()['total'];
if (!$doanhThuUocTinh) $doanhThuUocTinh = 0;

// Doanh thu thực tế (dựa trên bảng HoaDon)
$doanhThuThucTe = $conn->query("SELECT SUM(tong_tien) as total FROM hoadon")->fetch_assoc()['total'];
if (!$doanhThuThucTe) $doanhThuThucTe = 0;

?>

<main style="padding:20px;">
    <h2>Báo cáo thống kê</h2>

    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:20px;">
        <div style="padding:20px;background:#f1f1f1;border-radius:8px;">
            <h3>Tổng số phòng</h3>
            <p><?= $totalPhong ?> phòng</p>
            <p>Trống: <?= $phongTrong ?> | Đang đặt: <?= $phongDangDat ?> | Đã đặt: <?= $phongDaDat ?></p>
        </div>

        <div style="padding:20px;background:#f1f1f1;border-radius:8px;">
            <h3>Tổng số dịch vụ</h3>
            <p><?= $totalDichVu ?> dịch vụ</p>
        </div>

        <div style="padding:20px;background:#f1f1f1;border-radius:8px;">
            <h3>Tổng số khách hàng</h3>
            <p><?= $totalKhachHang ?> khách hàng</p>
        </div>

        <div style="padding:20px;background:#f1f1f1;border-radius:8px;">
            <h3>Tổng số nhân viên</h3>
            <p><?= $totalNhanVien ?> nhân viên</p>
        </div>

        <div style="padding:20px;background:#002060;color:#fff;border-radius:8px;grid-column:1/3;">
            <h3>Doanh thu ước tính (đang đặt/đã đặt)</h3>
            <p><strong><?= number_format($doanhThuUocTinh, 0, ",", ".") ?> VND</strong></p>
        </div>
        
        <div style="padding:20px;background:#002060;color:#fff;border-radius:8px;grid-column:1/3;">
            <h3>Tổng doanh thu thực tế (đã xuất hóa đơn)</h3>
            <p><strong><?= number_format($doanhThuThucTe, 0, ",", ".") ?> VND</strong></p>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>