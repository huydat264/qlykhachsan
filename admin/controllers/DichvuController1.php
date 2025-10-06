<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Dichvu.php';

class DichvuController1 {
    private $dichvuModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->dichvuModel = new Dichvu($conn);
    }

    // Trang chính
    public function index() {
        // POST xử lý thêm / sửa
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['them'])) {
                $this->add($_POST);
            } elseif (isset($_POST['luu'])) {
                $this->update($_POST['id_dichvu'], $_POST);
            }
        }

        // GET xử lý xóa
        if (isset($_GET['xoa'])) {
            $this->delete((int)$_GET['xoa']);
        }

        // GET xử lý sửa
        $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
        $edit_data = $edit_id ? $this->dichvuModel->getById($edit_id) : null;

        // Lấy danh sách dịch vụ
        $search = $_POST['search'] ?? '';
        $dichvuList = $this->dichvuModel->getAll($search);

        include __DIR__ . '/../views/dichvu.php';
    }

    // Thêm dịch vụ
    private function add($data) {
        try {
            if (!isset($data['gia']) || $data['gia'] < 0) {
                throw new Exception("❌ Giá dịch vụ không được âm!");
            }

            $this->dichvuModel->add($data);

            echo "<script>alert('✅ Thêm dịch vụ thành công!');window.location='index.php?controller=dichvu';</script>";
            exit;
        } catch (Exception $e) {
            // Hiển thị lỗi từ model (vd: tên dịch vụ sai định dạng)
            echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
        }
    }

    // Cập nhật dịch vụ
    private function update($id, $data) {
        try {
            if (!isset($data['gia']) || $data['gia'] < 0) {
                throw new Exception("❌ Giá dịch vụ không được âm!");
            }

            $this->dichvuModel->update($id, $data);

            echo "<script>alert('✅ Cập nhật dịch vụ thành công!');window.location='index.php?controller=dichvu';</script>";
            exit;
        } catch (Exception $e) {
            // Hiển thị lỗi từ model
            echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
        }
    }

    // Xóa dịch vụ
    public function delete($id) {
        if ($id) {
            $this->dichvuModel->delete($id);
            echo "<script>alert('🗑️ Xóa dịch vụ thành công!');window.location='index.php?controller=dichvu';</script>";
            exit;
        }
    }
}
?>
