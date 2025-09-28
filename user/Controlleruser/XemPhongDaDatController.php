<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/DatPhongModel.php";
require_once __DIR__ . "/../Modeluser/PhongModel.php";
require_once __DIR__ . "/../Modeluser/ThanhToanModel.php";
require_once __DIR__ . "/../Modeluser/KhachHangModel.php";

// Nếu chưa login -> đẩy về login
if (!isset($_SESSION['user'])) {
    header("Location: ../Viewsuser/login.php");
    exit;
}

$taiKhoanId = $_SESSION['user']['id_taikhoan'] ?? 0;
$khachHangModel = new KhachHangModel($conn);
$khachHang = $khachHangModel->getByTaiKhoanId($taiKhoanId);

if (!$khachHang) {
    echo "<script>alert('Bạn cần đặt phòng trước khi xem phòng đã đặt-Quay lại trang chủ?'); 
    window.location.href='../Viewsuser/trangchu.php';</script>";
    exit;
}

$id_khachhang = $khachHang['id_khachhang'];

// Model
$datPhongModel  = new DatPhongModel($conn);
$phongModel     = new PhongModel($conn);
$thanhToanModel = new ThanhToanModel($conn);

// Lấy danh sách đặt phòng
$dsDatPhong = $datPhongModel->getByKhachHang($id_khachhang);

// Truyền sang View
include __DIR__ . "/../Viewsuser/xemphongdadat.php";
