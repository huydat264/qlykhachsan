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
    // Lấy dữ liệu tìm kiếm: ưu tiên POST, nếu không có thì GET (để khi redirect vẫn giữ giá trị)
    $search = $_REQUEST['search'] ?? ''; // GET hoặc POST đều lấy được


    // Lấy danh sách nhân viên dựa theo tìm kiếm
    $nhanviens = $this->model->getAll($search);

    $edit_data = null;
    if ($this->is_admin && isset($_GET['edit'])) {
        $id = (int)$_GET['edit'];
        $edit_data = $this->model->getById($id);
    }

    // Truyền biến ra view
    $is_admin = $this->is_admin;

    include __DIR__ . '/../views/nhanvien.php';
}

public function createOrUpdate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    // Chuẩn bị dữ liệu
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
        if (isset($_POST['them']) || isset($_POST['luu'])) {
            if (!$this->is_admin) {
                die("<script>alert('Bạn không có quyền thực hiện thao tác này!');window.location='index.php?controller=nhanvien&action=index';</script>");
            }
        }

        if (isset($_POST['them'])) {
            $this->model->create($data);
        } elseif (isset($_POST['luu'])) {
            $id = (int)$_POST['id_nhanvien'];
            $this->model->update($id, $data);
        }

        // Quay về danh sách sau khi thêm/sửa
        header("Location: index.php?controller=nhanvien&action=index");
        exit();

    } catch (PDOException $e) {
        // Nếu là lỗi khóa ngoại, thông báo cụ thể
        if ($e->getCode() == '23000') {
            die("<script>alert('Lỗi: ID tài khoản không tồn tại trong bảng taikhoan!');window.location='index.php?controller=nhanvien&action=index';</script>");
        }
        die("❌ Lỗi SQL: " . $e->getMessage());
    }
}
}
