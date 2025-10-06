<?php
require_once __DIR__ . '/../models/Khachhang.php';
require_once __DIR__ . '/../core/Auth.php';

class KhachhangController1 {
    private $khachhangModel;
    private $conn;

    public function __construct($conn) {
        Auth::requireLogin();
        $role = Auth::getUserRole();
        if (!in_array($role, ['ADMIN', 'NHANVIEN'])) {
            die("❌ Bạn không có quyền truy cập!");
        }
        $this->conn = $conn;
        $this->khachhangModel = new Khachhang($conn);
    }

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
                'ho_ten'        => trim($_POST['ho_ten'] ?? ''),
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'email'         => trim($_POST['email'] ?? ''),
                'ngay_sinh'     => $_POST['ngay_sinh'] ?? null,
                'gioi_tinh'     => $_POST['gioi_tinh'] ?? null,
                'cccd'          => trim($_POST['cccd'] ?? ''),
                'dia_chi'       => trim($_POST['dia_chi'] ?? '')
            ];
            $error = null;

            // Validate từng trường và trả về lỗi đầu tiên gặp phải
            if ($data['tai_khoan_khachhang_id']) {
                $sql = "SELECT role FROM taikhoan WHERE id_taikhoan = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':id' => $data['tai_khoan_khachhang_id']]);
                $role = $stmt->fetchColumn();
                if ($role !== 'USER') {
                    $error = "❌ ID tài khoản không hợp lệ! Chỉ chấp nhận tài khoản có role USER.";
                }
            }
            if (!$error && !preg_match('/^[\p{L}\s0-9]+$/u', $data['ho_ten'])) {
                $error = "❌ Tên khách hàng không hợp lệ! Không được chứa ký tự đặc biệt.";
            }
            if (!$error && !preg_match('/^[0-9]{10,11}$/', $data['so_dien_thoai'])) {
                $error = "❌ Số điện thoại không hợp lệ! Chỉ được nhập số, từ 10-11 chữ số.";
            }
            if (!$error && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "❌ Email không hợp lệ! Vui lòng nhập đúng định dạng (vd: abc@gmail.com).";
            }
            if (!$error && !preg_match('/^[0-9]{12}$/', $data['cccd'])) {
                $error = "❌ CCCD không hợp lệ! Phải đúng 12 chữ số.";
            }
            if (!$error && empty($data['dia_chi'])) {
                $error = "❌ Vui lòng nhập quê quán (địa chỉ)!";
            }

            if ($error) {
                // Truyền lại dữ liệu và lỗi ra view
                $search = $_POST['search'] ?? '';
                $khachhangs = $this->khachhangModel->getAll($search);
                $edit_data = $data;
                $_SESSION['error'] = $error;
                include __DIR__ . '/../views/khachhang.php';
                return;
            }

            // 👉 Thêm khách hàng
            if (isset($_POST['them'])) {
                if ($this->khachhangModel->create($data)) {
                    $_SESSION['success'] = "✅ Thêm khách hàng thành công!";
                } else {
                    if (!isset($_SESSION['error'])) {
                        $_SESSION['error'] = "❌ Thêm khách hàng thất bại!";
                    }
                    // Truyền lại dữ liệu khi thêm thất bại
                    $search = $_POST['search'] ?? '';
                    $khachhangs = $this->khachhangModel->getAll($search);
                    $edit_data = $data;
                    include __DIR__ . '/../views/khachhang.php';
                    return;
                }

            // 👉 Cập nhật khách hàng
            } elseif (isset($_POST['capnhat'])) {
                $id = (int)$_POST['id_khachhang'];
                if ($this->khachhangModel->update($id, $data)) {
                    $_SESSION['success'] = "✅ Cập nhật khách hàng thành công!";
                } else {
                    if (!isset($_SESSION['error'])) {
                        $_SESSION['error'] = "❌ Cập nhật khách hàng thất bại!";
                    }
                    // Truyền lại dữ liệu khi cập nhật thất bại
                    $search = $_POST['search'] ?? '';
                    $khachhangs = $this->khachhangModel->getAll($search);
                    $edit_data = $data;
                    include __DIR__ . '/../views/khachhang.php';
                    return;
                }
            }

            header("Location: index.php?controller=khachhang&action=index");
            exit();
        }
    }
}
