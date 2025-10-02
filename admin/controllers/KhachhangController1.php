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
            'dia_chi' => $_POST['dia_chi'] ?? ''
        ];

        // ✅ Bắt buộc nhập quê quán
        if (empty(trim($data['dia_chi']))) {
            $_SESSION['error'] = "❌ Vui lòng nhập quê quán (địa chỉ)!";
            header("Location: index.php?controller=khachhang&action=index");
            exit();
        }

        // ✅ Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "❌ Email không hợp lệ! Vui lòng nhập đúng định dạng (vd: abc@gmail.com)";
            header("Location: index.php?controller=khachhang&action=index");
            exit();
        }

        // ✅ Validate số điện thoại (10-11 số)
        if (!preg_match('/^[0-9]{10,11}$/', $data['so_dien_thoai'])) {
            $_SESSION['error'] = "❌ Số điện thoại không hợp lệ! Chỉ được nhập số, từ 10-11 chữ số";
            header("Location: index.php?controller=khachhang&action=index");
            exit();
        }

        // Thêm khách hàng
        if (isset($_POST['them'])) {
            // 👉 Kiểm tra trùng email/cccd khi thêm
            $check = $this->khachhangModel->getAll();
            foreach ($check as $row) {
                if ($row['email'] === $data['email']) {
                    $_SESSION['error'] = "❌ Email đã tồn tại!";
                    header("Location: index.php?controller=khachhang&action=index");
                    exit();
                }
                if ($row['cccd'] === $data['cccd']) {
                    $_SESSION['error'] = "❌ CCCD đã tồn tại!";
                    header("Location: index.php?controller=khachhang&action=index");
                    exit();
                }
            }

            if ($this->khachhangModel->create($data)) {
                $_SESSION['success'] = "✅ Thêm khách hàng thành công!";
            } else {
                $_SESSION['error'] = "❌ Thêm khách hàng thất bại!";
            }

        // Cập nhật khách hàng
        } elseif (isset($_POST['capnhat'])) {
            $id = (int)$_POST['id_khachhang'];

            if ($this->khachhangModel->update($id, $data)) {
                $_SESSION['success'] = "✅ Cập nhật khách hàng thành công!";
            } else {
                // Model update() đã có kiểm tra trùng email/cccd
                if (!isset($_SESSION['error'])) {
                    $_SESSION['error'] = "❌ Cập nhật khách hàng thất bại!";
                }
            }
        }

        header("Location: index.php?controller=khachhang&action=index");
        exit();
    }
}

}