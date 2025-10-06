<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Datphong.php';
require_once __DIR__ . '/../models/Khachhang.php';

class DatphongController1 {
    private $datphongModel;
    private $khachhangModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->datphongModel = new Datphong($conn);
        $this->khachhangModel = new Khachhang($conn);
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['dat_phong'])) {
                $this->add($_POST);
            } elseif (isset($_POST['cap_nhat_dat_phong'])) {
                $this->update($_POST);
            }
        }

        if (isset($_GET['xoa'])) {
            $this->delete((int)$_GET['xoa']);
        }

        $editData = isset($_GET['sua']) ? $this->datphongModel->getById((int)$_GET['sua']) : null;
        $datPhongList = $this->datphongModel->getAll();
        $phongTrong = $this->datphongModel->getPhongTrong();
        $khachHangList = $this->khachhangModel->getAll(); // lấy dropdown KH

        include __DIR__ . '/../views/datphong.php';
    }

    // ✅ Thêm đặt phòng (cho khách hàng cũ hoặc mới)
    private function add($post) {
        try {
            // kiểm tra trường bắt buộc
            if (empty($post['id_phong']) || empty($post['ngay_nhan']) || empty($post['ngay_tra'])) {
                throw new Exception("Vui lòng chọn đầy đủ thông tin đặt phòng!");
            }

            // gọi thẳng model Datphong->add() (tự xử lý khách hàng cũ/mới)
            $this->datphongModel->add($post);

            echo "<script>alert('Đặt phòng thành công!');window.location='index.php?controller=datphong';</script>";
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . addslashes($e->getMessage()) . "');window.location='index.php?controller=datphong';</script>";
            exit;
        }
    }

    private function update($post) {
        try {
            $this->datphongModel->update($post['id_datphong'], $post);
            echo "<script>alert('Cập nhật thành công!');window.location='index.php?controller=datphong';</script>";
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
        }
    }

    private function delete($id) {
        try {
            $this->datphongModel->delete($id);
           echo "
<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Đã hủy!',
    text: 'Đặt phòng đã được hủy thành công.',
    confirmButtonText: 'OK',
    confirmButtonColor: '#3085d6'
}).then(() => {
    window.location = 'index.php?controller=datphong';
});
</script>
</body>
</html>";

            exit;
        } catch (Exception $e) {
            echo "<script>alert('Lỗi: " . $e->getMessage() . "');window.location='index.php?controller=datphong';</script>";
        }
    }
}
?>
