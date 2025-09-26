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
            echo "<script>
                    alert('Bạn phải đăng nhập!');
                    window.location.href='../Viewsuser/login.php';
                  </script>";
            exit;
        }

        $data = [
            'tai_khoan_khachhang_id' => $_SESSION['user']['id_taikhoan'],
            'ho_ten' => $_POST['ho_ten'],
            'ngay_sinh' => $_POST['ngay_sinh'],
            'gioi_tinh' => $_POST['gioi_tinh'],
            'so_dien_thoai' => $_POST['so_dien_thoai'],
            'email' => $_POST['email'],
            'cccd' => $_POST['cccd'],
            'dia_chi' => $_POST['dia_chi']
        ];

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
