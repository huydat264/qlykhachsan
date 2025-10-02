<?php
require_once __DIR__ . '/../models/Nhanvien.php';
require_once __DIR__ . '/../core/Auth.php';

class NhanvienController1 {
    private $model;
    private $is_admin;

    public function __construct($conn) {
        Auth::requireLogin();
        $role = Auth::getUserRole();
        if (!in_array($role, ['ADMIN', 'NHANVIEN'])) {
            die("❌ Bạn không có quyền truy cập!");
        }

        $this->model = new Nhanvien($conn);
        $this->is_admin = ($role === 'ADMIN');
    }

    // Hiển thị danh sách + form edit
    public function index() {
        $search = $_REQUEST['search'] ?? '';

        $nhanviens = $this->model->getAll($search);

        $edit_data = null;
        if ($this->is_admin && isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $edit_data = $this->model->getById($id);
        }

        $is_admin = $this->is_admin;
        include __DIR__ . '/../views/nhanvien.php';
    }

    public function createOrUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $data = [
            'tai_khoan_nhanvien_id' => !empty($_POST['tai_khoan_nhanvien_id']) ? (int)$_POST['tai_khoan_nhanvien_id'] : null,
            'ho_ten' => $_POST['ho_ten'] ?? '',
            'chuc_vu' => $_POST['chuc_vu'] ?? '',
            'luong_co_ban' => $_POST['luong_co_ban'] ?? 0,
            'ngay_vao_lam' => $_POST['ngay_vao_lam'] ?? null,
            'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];

        try {
            // Chỉ ADMIN mới được thêm/sửa
            if ((isset($_POST['them']) || isset($_POST['luu'])) && !$this->is_admin) {
                die("<script>alert('Bạn không có quyền thực hiện thao tác này!');window.location='index.php?controller=nhanvien&action=index';</script>");
            }

            // Kiểm tra ID tài khoản tồn tại & role = NHANVIEN
            if (!$this->model->validateTaiKhoanNhanvien($data['tai_khoan_nhanvien_id'])) {
                die("<script>alert('❌ ID tài khoản không tồn tại hoặc không phải là NHÂN VIÊN!');window.location='index.php?controller=nhanvien&action=index';</script>");
            }

            // Thêm hoặc cập nhật
            if (isset($_POST['them'])) {
                $result = $this->model->create($data);
            } elseif (isset($_POST['luu'])) {
                $id = (int)$_POST['id_nhanvien'];
                $result = $this->model->update($id, $data);
            }

            // Nếu có lỗi validate từ model
            if (!$result && !empty($_SESSION['error'])) {
                $msg = $_SESSION['error'];
                unset($_SESSION['error']);
                die("<script>alert('$msg');window.location='index.php?controller=nhanvien&action=index';</script>");
            }

            // Nếu thành công
            header("Location: index.php?controller=nhanvien&action=index");
            exit();

        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                die("<script>alert('Lỗi: ID tài khoản không tồn tại trong bảng taikhoan!');window.location='index.php?controller=nhanvien&action=index';</script>");
            }
            die("❌ Lỗi SQL: " . $e->getMessage());
        }
    }
}
