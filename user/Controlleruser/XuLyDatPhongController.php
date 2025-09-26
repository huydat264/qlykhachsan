<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/DatPhongModel.php";
require_once __DIR__ . "/../Modeluser/ThanhToanModel.php";
require_once __DIR__ . "/../Modeluser/PhongModel.php";

//lỗi ở code này lỗi truyền nhầm tham số id khách hàng lẽ ra phải là tai_khoan_khachhang_id
//id khách hàng tự động  tăng (check session của user trong code này )
class XuLyDatPhongController {
    private $datPhongModel;
    private $thanhToanModel;
    private $phongModel;

    public function __construct($conn) {
        $this->datPhongModel   = new DatPhongModel($conn);
        $this->thanhToanModel  = new ThanhToanModel($conn);
        $this->phongModel      = new PhongModel();
    }

    // Hiển thị form đặt phòng
    public function showForm() {
        if (!isset($_GET['id_phong'])) {
            die("Thiếu tham số id_phong");
        }

        $idPhong = intval($_GET['id_phong']);
        $phong = $this->phongModel->getRoomById($idPhong);

        if (!$phong) {
            echo "<script>alert('Phòng không tồn tại!'); window.location.href='../Viewsuser/danhsachphong.php';</script>";
            exit;
        }

        include __DIR__ . "/../Viewsuser/xulydatphong.php";
    }

    // Xử lý lưu đặt phòng
    public function save() {
        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Bạn cần đăng nhập để đặt phòng'); window.location.href='../Viewsuser/login.php';</script>";
            exit;
        }

        // ✅ Đảm bảo luôn có id_khachhang trong session
        if (empty($_SESSION['user']['id_khachhang'])) {
            echo "<script>alert('Không tìm thấy ID khách hàng trong session'); window.location.href='../Viewsuser/login.php';</script>";
            exit;
        }

        $id_khachhang = intval($_SESSION['user']['id_khachhang']);
        $id_phong     = intval($_POST['id_phong'] ?? 0);
        $ngay_nhan    = $_POST['ngay_nhan'] ?? null;
        $ngay_tra     = $_POST['ngay_tra'] ?? null;

        if (!$id_phong || !$ngay_nhan || !$ngay_tra) {
            echo "<script>alert('Thiếu dữ liệu đặt phòng'); window.location.href='../Viewsuser/danhsachphong.php';</script>";
            exit;
        }

        $data = [
            'id_khachhang' => $id_khachhang,
            'id_phong'     => $id_phong,
            'ngay_dat'     => date("Y-m-d"),
            'ngay_nhan'    => $ngay_nhan,
            'ngay_tra'     => $ngay_tra
        ];

        $idDatPhong = $this->datPhongModel->insert($data);

        if ($idDatPhong) {
            // Lấy lại thông tin chi tiết để tính tiền
            $detail   = $this->datPhongModel->getById($idDatPhong);
            $giaPhong = $detail['gia_phong'];

            $soNgay = (new DateTime($ngay_tra))->diff(new DateTime($ngay_nhan))->days;
            $tongTien = max(1, $soNgay) * $giaPhong; // tránh trường hợp 0 ngày

            $this->thanhToanModel->insert([
                'id_datphong'     => $idDatPhong,
                'ngay_thanh_toan' => date("Y-m-d"),
                'so_tien'         => $tongTien,
                'hinh_thuc'       => "Chuyển khoản",
                'loai_thanh_toan' => "Đặt cọc"
            ]);

            header("Location: ../Viewsuser/xacnhanthanhtoan.php?id_datphong=" . $idDatPhong);
            exit;
        } else {
            echo "Đặt phòng thất bại!";
        }
    }
}

// Router
$controller = new XuLyDatPhongController($conn);
$action = $_GET['action'] ?? 'form';

if ($action === 'form') {
    $controller->showForm();
} elseif ($action === 'save') {
    $controller->save();
} else {
    echo "Action không hợp lệ!";
}
