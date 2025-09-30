<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Process_payment.php';

require_login();    // bắt buộc đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // quyền truy cập

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_thanh_toan'])) {
    if (!isset($_POST['id_datphong']) || empty($_POST['id_datphong'])) {
        die("<p style='color:red'>Lỗi: Không tìm thấy ID đặt phòng.</p>");
    }

    $id_phong = $_POST['id_phong'];
    $id_datphong = $_POST['id_datphong'];
    $tong_tien_string = $_POST['so_tien'];
    $tong_tien = str_replace(['.', ','], '', $tong_tien_string); // chuyển về số
    $hinh_thuc = $_POST['hinh_thuc'];
    $loai_thanh_toan = $_POST['loai_thanh_toan'];
    $ngay_thanh_toan = date("Y-m-d H:i:s");

    try {
        $conn = Database::getConnection();
        $paymentModel = new ProcessPayment($conn);
        $paymentModel->thanhToan($id_datphong, $tong_tien, $hinh_thuc, $loai_thanh_toan, $ngay_thanh_toan, $id_phong);

        header("Location: ../views/hoadon.php?id_datphong=" . $id_datphong);
        exit();
    } catch (PDOException $e) {
        echo "<p style='color:red'>Lỗi thanh toán: " . $e->getMessage() . "</p>";
    }
} else {
    echo "Truy cập không hợp lệ.";
}
