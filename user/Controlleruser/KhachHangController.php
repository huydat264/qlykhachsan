<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/KhachHangModel.php";

class KhachHangController {
    private $model;

    public function __construct($conn) {
        $this->model = new KhachHangModel($conn);
    }

    public function save() {
        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Bạn phải đăng nhập!'); window.location.href='../Viewsuser/login.php';</script>";
            exit;
        }

        $errors = [];
        $data = [
            'ho_ten' => trim($_POST['ho_ten']),
            'ngay_sinh' => $_POST['ngay_sinh'],
            'gioi_tinh' => $_POST['gioi_tinh'],
            'so_dien_thoai' => trim($_POST['so_dien_thoai']),
            'email' => trim($_POST['email']),
            'cccd' => trim($_POST['cccd']),
            'dia_chi' => trim($_POST['dia_chi']),
        ];

        // validate
        if (!preg_match("/^[\p{L}\s]+$/u", $data['ho_ten'])) {
            $errors['ho_ten'] = "Họ tên chỉ được chứa chữ cái và khoảng trắng.";
        }
        $birthDate = new DateTime($data['ngay_sinh']);
        $age = (new DateTime())->diff($birthDate)->y;
        if ($age < 16) {
            $errors['ngay_sinh'] = "Khách hàng phải đủ 16 tuổi.";
        }
        if (empty($data['gioi_tinh'])) {
            $errors['gioi_tinh'] = "Chọn giới tính.";
        }
        if (!preg_match("/^[0-9]{10,11}$/", $data['so_dien_thoai'])) {
            $errors['so_dien_thoai'] = "Số điện thoại phải là chữ số và dài từ 10-11 số.";
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email không hợp lệ.";
        }
        if (!preg_match("/^[0-9]{11,12}$/", $data['cccd'])) {
            $errors['cccd'] = "CCCD bắt buộc là 11-12 chữ số.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header("Location: ../Viewsuser/nhap_thongtin_khachhang.php?id_phong=" . $_POST['id_phong']);
            exit;
        }

        // thêm id user
        $data['tai_khoan_khachhang_id'] = $_SESSION['user']['id_taikhoan'];

        if ($this->model->insert($data)) {
            $idPhong = $_POST['id_phong'];
            header("Location: ../Viewsuser/xulydatphong.php?id_phong=" . $idPhong);
        } else {
            echo "Lưu thông tin thất bại!";
        }
    }
}

// Router
$controller = new KhachHangController($conn);
$action = $_GET['action'] ?? '';
if ($action === 'save') {
    $controller->save();
} else {
    echo "Action không hợp lệ!";
}
