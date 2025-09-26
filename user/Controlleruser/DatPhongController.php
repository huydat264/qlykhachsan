<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/KhachHangModel.php";

class DatPhongController {
    private $model;

    public function __construct($conn) {
        $this->model = new KhachHangModel($conn);
    }

    public function check() {
        // 1. Chưa đăng nhập -> bắt buộc login
        if (!isset($_SESSION['user'])) {
            echo "<script>
                    alert('Bạn phải đăng nhập để đặt phòng!');
                    window.location.href='../Viewsuser/login.php';
                  </script>";
            exit;
        }

        $idPhong = $_GET['id_phong'] ?? null;
        if (!$idPhong) {
            echo "Thiếu thông tin phòng!";
            exit;
        }

        $idTaiKhoan = $_SESSION['user']['id_taikhoan']; // lấy id từ bảng TaiKhoan

        // 2. Kiểm tra đã có khách hàng gắn với tài khoản chưa
        $khachHang = $this->model->getByTaiKhoanId($idTaiKhoan);

        if (!$khachHang) {
            // lần đầu đặt phòng → nhập thông tin
            header("Location: ../Viewsuser/nhap_thongtin_khachhang.php?id_phong=" . $idPhong);
            exit;
        }

        // 3. Đã có khách hàng → chuyển sang xử lý đặt phòng
        header("Location: ../Viewsuser/xulydatphong.php?id_phong=" . $idPhong);
        exit;
    }
}

// Router
$controller = new DatPhongController($conn);
$action = $_GET['action'] ?? '';

if ($action === 'check') {
    $controller->check();
} else {
    echo "Hành động không hợp lệ!";
}
