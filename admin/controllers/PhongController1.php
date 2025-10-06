<?php
// controllers/PhongController1.php
require_once __DIR__ . '/../models/Phong.php';

class PhongController1 {
    private $phongModel;

    public function __construct() {
        $this->phongModel = new Phong();
    }

    public function index() {
        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $phongs = $this->phongModel->getAll($search);

       $edit_data = null;
if (isset($_GET['id'])) {   // thay vì $_GET['edit']
    $edit_data = $this->phongModel->getById((int)$_GET['id']);
}

        // gom dữ liệu lại thành mảng $data cho view
        $data = [
            'search' => $search,
            'phongs' => $phongs,
            'edit_data' => $edit_data
        ];

        include __DIR__ . '/../views/phong.php';
    }

    public function update() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['luu'])) {
        $error = ''; 

        try {
            $so_phong = trim($_POST['so_phong'] ?? '');
            $so_luong_nguoi = trim($_POST['so_luong_nguoi'] ?? '');
            $gia_phong = trim($_POST['gia_phong'] ?? '');

            // --- Kiểm tra số phòng ---
            if (!preg_match('/^[1-9][0-9]*$/', $so_phong)) {
                throw new Exception("Số phòng chỉ được nhập số nguyên dương, không chứa ký tự đặc biệt.");
            }

            // --- Kiểm tra số lượng người ---
            if (!preg_match('/^[1-9][0-9]*$/', $so_luong_nguoi)) {
                throw new Exception("Số người chỉ được nhập số nguyên dương, không chứa ký tự đặc biệt.");
            }

            // --- Kiểm tra giá phòng ---
            if (!preg_match('/^[1-9][0-9]*$/', $gia_phong)) {
                throw new Exception("Giá phòng chỉ được nhập số, không âm, không ký tự đặc biệt.");
            }

            // Nếu hợp lệ -> gọi model để cập nhật
            $success = $this->phongModel->update($_POST);

            if ($success) {
                echo "<script>alert('Cập nhật phòng thành công!');window.location='index.php?controller=phong';</script>";
                exit;
            } else {
                $error = "Lỗi khi cập nhật phòng.";
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // Giữ lại dữ liệu form và hiển thị lỗi
        $search = $_POST['search'] ?? '';
        $phongs = $this->phongModel->getAll($search);
        $edit_data = $_POST;

        $data = [
            'search' => $search,
            'phongs' => $phongs,
            'edit_data' => $edit_data,
            'error' => $error
        ];

        include __DIR__ . '/../views/phong.php';
    }
}

    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $success = $this->phongModel->deleteKhach($id);
            if ($success) {
                echo "<script>alert('Trả phòng thành công!');window.location='index.php?controller=phong';</script>";
            } else {
                echo "<p style='color:red'>Lỗi khi xóa.</p>";
            }
        }
    }
}
