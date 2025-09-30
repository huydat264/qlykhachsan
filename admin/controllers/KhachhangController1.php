<?php
require_once __DIR__ . '/../models/Khachhang.php';
require_once __DIR__ . '/../core/Auth.php';

class KhachhangController1 {
    private $khachhangModel;

    public function __construct($conn) {
        Auth::requireLogin();
        $role = Auth::getUserRole();
        if (!in_array($role, ['ADMIN', 'NHANVIEN'])) {
            die("❌ Bạn không có quyền truy cập!");
        }
        $this->khachhangModel = new Khachhang($conn);
    }

    // Hiển thị danh sách và form edit, đồng thời xử lý tìm kiếm
    public function index() {
        $search = $_POST['search'] ?? '';
        $khachhangs = $this->khachhangModel->getAll($search);

        $edit_data = null;
        if (isset($_GET['sua'])) {
            $id = (int)$_GET['sua'];
            $edit_data = $this->khachhangModel->getById($id);
        }

        include __DIR__ . '/../views/khachhang.php';
    }

    // Thêm hoặc cập nhật khách hàng
    public function createOrUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tai_khoan_khachhang_id' => !empty($_POST['tai_khoan_khachhang_id']) ? (int)$_POST['tai_khoan_khachhang_id'] : null,
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                'email' => $_POST['email'] ?? '',
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'gioi_tinh' => $_POST['gioi_tinh'] ?? null,
                'cccd' => $_POST['cccd'] ?? null,
                'dia_chi' => $_POST['dia_chi'] ?? null
            ];

            if (isset($_POST['them'])) {
                $this->khachhangModel->create($data);
            } elseif (isset($_POST['capnhat'])) {
                $id = (int)$_POST['id_khachhang'];
                $this->khachhangModel->update($id, $data);
            }

            header("Location: index.php?controller=khachhang&action=index");
            exit();
        }
    }
}
