<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/DatPhongModel.php";
require_once __DIR__ . "/../Modeluser/ThanhToanModel.php";
require_once __DIR__ . "/../Modeluser/PhongModel.php";
require_once __DIR__ . "/../Modeluser/KhachHangModel.php";

class XuLyDatPhongController {
    private $datPhongModel;
    private $thanhToanModel;
    private $phongModel;
    private $khachHangModel;

    public function __construct($conn) {
        $this->datPhongModel   = new DatPhongModel($conn);
        $this->thanhToanModel  = new ThanhToanModel($conn);
        $this->phongModel      = new PhongModel($conn);
        $this->khachHangModel  = new KhachHangModel($conn);
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
        // Kiểm tra login
        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Bạn cần đăng nhập để đặt phòng'); window.location.href='../Viewsuser/login.php';</script>";
            exit;
        }

        // ✅ Sửa: lấy id_taikhoan từ session (debug m gửi lên)
        $taiKhoanId = intval($_SESSION['user']['id_taikhoan'] ?? 0);

        if (!$taiKhoanId) {
            echo "<script>alert('Không tìm thấy id_taikhoan trong session'); window.location.href='../Viewsuser/login.php';</script>";
            exit;
        }

        // ✅ Lấy thông tin khách hàng bằng id_taikhoan
        $khachHang = $this->khachHangModel->getByTaiKhoanId($taiKhoanId);
        if (!$khachHang) {
            echo "<script>alert('Bạn cần nhập thông tin khách hàng trước khi đặt phòng'); window.location.href='../Viewsuser/nhapthongtin.php';</script>";
            exit;
        }
        $id_khachhang = $khachHang['id_khachhang'];

        // Lấy dữ liệu từ form
        $id_phong  = intval($_POST['id_phong'] ?? 0);
        $ngay_nhan = $_POST['ngay_nhan'] ?? null;
        $ngay_tra  = $_POST['ngay_tra'] ?? null;

        if (!$id_phong || !$ngay_nhan || !$ngay_tra) {
            echo "<script>alert('Thiếu dữ liệu đặt phòng'); window.location.href='../Viewsuser/danhsachphong.php';</script>";
            exit;
        }

        // Chuẩn bị dữ liệu insert
        $data = [
            'id_khachhang' => $id_khachhang,
            'id_phong'     => $id_phong,
            'ngay_dat'     => date("Y-m-d"),
            'ngay_nhan'    => $ngay_nhan,
            'ngay_tra'     => $ngay_tra,
            'trang_thai'   => 'Chờ xác nhận'
        ];

        // Insert đặt phòng
        $idDatPhong = $this->datPhongModel->insert($data);

        if ($idDatPhong) {
            // Lấy chi tiết phòng để tính tiền
            $detail   = $this->datPhongModel->getById($idDatPhong);
            $giaPhong = $detail['gia_phong'] ?? 0;

            // Tính số ngày và tổng tiền
            $soNgay   = (new DateTime($ngay_tra))->diff(new DateTime($ngay_nhan))->days;
            $tongTien = max(1, $soNgay) * $giaPhong;

            // Insert thanh toán
            $this->thanhToanModel->insert([
                'id_datphong'     => $idDatPhong,
                'ngay_thanh_toan' => date("Y-m-d"),
                'so_tien'         => $tongTien,
                'hinh_thuc'       => "Chuyển khoản",
                'loai_thanh_toan' => "Đặt cọc"
            ]);

            // Chuyển sang trang xác nhận
            header("Location: ../Viewsuser/xacnhanthanhtoan.php?id_datphong=" . $idDatPhong);
            exit;
        } else {
            echo "<script>alert('Đặt phòng thất bại!'); window.location.href='../Viewsuser/danhsachphong.php';</script>";
        }
    }
}

$controller = new XuLyDatPhongController($conn);
$action = $_GET['action'] ?? 'form';

if ($action === 'form') {
    $controller->showForm();
} elseif ($action === 'save') {
    $controller->save();
} else {
    echo "Action không hợp lệ!";
}
