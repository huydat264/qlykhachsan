<?php
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình mysqli để báo cáo ngoại lệ
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['submit_thanh_toan'])) {
    // Kiểm tra và lấy id_datphong
    if (!isset($_POST['id_datphong']) || empty($_POST['id_datphong'])) {
        echo "<p style='color:red'>Lỗi: Không tìm thấy ID đặt phòng. Vui lòng kiểm tra lại form thanh toán.</p>";
        exit();
    }
    
    $id_phong = $_POST['id_phong'];
    $id_datphong = $_POST['id_datphong'];
    $tong_tien_string = $_POST['so_tien'];
    
    // Loại bỏ dấu chấm và phẩy để chuyển đổi sang số
    $tong_tien = str_replace(['.', ','], '', $tong_tien_string); 

    $hinh_thuc = $_POST['hinh_thuc'];
    $loai_thanh_toan = $_POST['loai_thanh_toan'];
    $ngay_thanh_toan = date("Y-m-d H:i:s");

    $conn->begin_transaction();

    try {
        // 1. Thêm bản ghi vào bảng thanhtoan
        $sql_insert_thanhtoan = "INSERT INTO `thanhtoan` (`id_datphong`, `so_tien`, `hinh_thuc`, `loai_thanh_toan`, `ngay_thanh_toan`) VALUES (?, ?, ?, ?, ?)";
        $stmt_thanhtoan = $conn->prepare($sql_insert_thanhtoan);
        $stmt_thanhtoan->bind_param("idsss", $id_datphong, $tong_tien, $hinh_thuc, $loai_thanh_toan, $ngay_thanh_toan);
        $stmt_thanhtoan->execute();
        $stmt_thanhtoan->close();

        // 2. Thêm bản ghi vào bảng hoadon
        $sql_insert_hoadon = "INSERT INTO `hoadon` (`id_datphong`, `tong_tien`, `ngay_xuat`) VALUES (?, ?, ?)";
        $stmt_hoadon = $conn->prepare($sql_insert_hoadon);
        $stmt_hoadon->bind_param("ids", $id_datphong, $tong_tien, $ngay_thanh_toan);
        $stmt_hoadon->execute();
        $stmt_hoadon->close();

        // 3. Cập nhật trạng thái phòng thành "Trống"
        $sql_update_phong = "UPDATE `phong` SET `trang_thai` = 'Trống' WHERE `id_phong` = ?";
        $stmt_phong = $conn->prepare($sql_update_phong);
        $stmt_phong->bind_param("i", $id_phong);
        $stmt_phong->execute();
        $stmt_phong->close();
        
        // 4. Cập nhật trạng thái đặt phòng thành "Đã thanh toán"
        $sql_update_datphong = "UPDATE `datphong` SET `trang_thai` = 'Đã thanh toán' WHERE `id_datphong` = ?";
        $stmt_datphong = $conn->prepare($sql_update_datphong);
        $stmt_datphong->bind_param("i", $id_datphong);
        $stmt_datphong->execute();
        $stmt_datphong->close();
        
        $conn->commit();

        // Chuyển hướng đến trang hóa đơn sau khi hoàn tất
        header("Location: hoadon.php?id_datphong=" . $id_datphong);
        exit();

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo "<p style='color:red'>Lỗi thanh toán: " . $e->getMessage() . "</p>";
    }
} else {
    echo "Truy cập không hợp lệ.";
}

if ($conn) {
    $conn->close();
}
?>