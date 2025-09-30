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
            $success = $this->phongModel->update($_POST);
            if ($success) {
                echo "<script>alert('Cập nhật phòng thành công!');window.location='index.php?controller=phong';</script>";
            } else {
                echo "<p style='color:red'>Lỗi khi cập nhật phòng.</p>";
            }
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
