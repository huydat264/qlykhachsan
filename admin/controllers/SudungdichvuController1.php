<?php
require_once __DIR__ . '/../models/Sudungdichvu.php';
require_once __DIR__ . '/../core/Auth.php';

class SudungdichvuController1 {
    private $model;
    
    public function __construct($pdo) {
        // Kiểm tra đăng nhập và quyền hạn
        Auth::requireLogin();
        $role = Auth::getUserRole();
        if (!in_array($role, ['ADMIN', 'NHANVIEN'])) {
            // Hoặc chuyển hướng đến trang lỗi thay vì die()
            die("❌ Bạn không có quyền truy cập vào trang này!");
        }
        
        $this->model = new Sudungdichvu($pdo);
    }

    /**
     * Hiển thị danh sách, form thêm mới hoặc form sửa
     */
    public function index() {
        $message = '';
        // Lấy thông báo từ session (nếu có) sau khi thêm/sửa
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']); // Xóa message để không hiển thị lại
        }

        $edit_data = null;
        // Kiểm tra nếu có yêu cầu sửa
        if (isset($_GET['sua'])) {
            $id_sudungdv = (int)$_GET['sua'];
            $edit_data = $this->model->getSudungdvById($id_sudungdv);
        }

        // Lấy toàn bộ dữ liệu cần thiết cho view
        $phong_dat_result = $this->model->getPhongDat();
        $dichvu_result    = $this->model->getAllDichVu();
        $sudungdv_result  = $this->model->getAllSudungdv();

        // Gọi view để hiển thị
        include __DIR__ . '/../views/sudungdichvu.php';
    }

    /**
     * Xử lý logic Thêm Mới và Cập Nhật từ form
     */
    public function process() {
        // Chỉ xử lý nếu request là POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=sudungdichvu');
            exit();
        }

        try {
            // Lấy dữ liệu chung từ form
            $id_datphong = (int)$_POST['id_datphong'];
            $id_dichvu   = (int)$_POST['id_dichvu'];
            $so_luong    = (int)$_POST['so_luong'];

            // Validate dữ liệu cơ bản
            if ($id_datphong <= 0 || $id_dichvu <= 0 || $so_luong <= 0) {
                throw new Exception("Vui lòng điền đầy đủ thông tin.");
            }

            // Tính toán thành tiền
            $gia = $this->model->getGiaDichVu($id_dichvu);
            if ($gia === false) {
                throw new Exception("Dịch vụ được chọn không hợp lệ.");
            }
            $thanh_tien = $so_luong * $gia;

            // Xử lý logic THÊM
            if (isset($_POST['them_sudungdv'])) {
                $this->model->insertSudungdv($id_datphong, $id_dichvu, $so_luong, $thanh_tien);
                $_SESSION['message'] = "Thêm dịch vụ sử dụng thành công!";
            
            // Xử lý logic CẬP NHẬT
            } elseif (isset($_POST['capnhat_sudungdv'])) {
                $id_sudungdv = (int)$_POST['id_sudungdv'];
                if ($id_sudungdv <= 0) {
                    throw new Exception("ID để cập nhật không hợp lệ.");
                }
                $this->model->updateSudungdv($id_sudungdv, $id_datphong, $id_dichvu, $so_luong, $thanh_tien);
                $_SESSION['message'] = "Cập nhật dịch vụ sử dụng thành công!";
            }
        } catch (Exception $e) {
            // Gửi thông báo lỗi qua session
            $_SESSION['message'] = "Lỗi: " . $e->getMessage();
        }

        // Chuyển hướng về trang chính sau khi xử lý xong
        header('Location: index.php?controller=sudungdichvu&action=index');
        exit();
    }
}
