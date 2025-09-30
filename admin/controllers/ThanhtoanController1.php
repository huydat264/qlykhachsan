<?php
require_once __DIR__ . '/../models/Thanhtoan.php';

class ThanhtoanController1 {
    private $model;

    public function __construct($pdo) {
        $this->model = new Thanhtoan($pdo);
    }

    // Hiển thị form thanh toán
    public function index() {
        // Yêu cầu đăng nhập & phân quyền
        require_once __DIR__ . '/../core/Auth.php';
        require_login();
        check_permission(['ADMIN', 'NHANVIEN']);

        // Lấy danh sách phòng
        $phong_list = $this->model->getPhongList();

        // Gọi view
        include __DIR__ . '/../views/thanhtoan.php';
    }
}
