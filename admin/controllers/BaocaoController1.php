<?php
require_once __DIR__ . '/../models/Baocao.php';
require_once __DIR__ . '/../core/Auth.php';

class BaocaoController1
{
    public function index()
    {
        require_login();
        check_permission(['ADMIN']);

        $filter = $_GET['filter'] ?? 'all';
        $model  = new Baocao();

        $data = [
            'filter'            => $filter,
            'totalPhong'        => $model->getTotalPhong(),
            'phongTrong'        => $model->getPhongByStatus('Trống'),
            'phongDangDat'      => $model->getPhongByStatus('Đã đặt'),
            'phongBaoTri'       => $model->getPhongByStatus('Bảo trì'),
            'doanhThuThucTe'    => $model->getDoanhThuThucTe($filter),
            'nhanVienByRole'    => $model->getNhanVienByRole(),
            'khachHangByGender' => $model->getKhachHangByGender(),
            'dichVuRevenueData' => $model->getDichVuRevenue(),
            'vipKhachHangData'  => $model->getVipKhachHang(),
            'totalKhachHang'    => $model->getTotalKhachHang(),
            'totalDichVu'       => $model->getTotalDichVu(),
            'totalNhanVien'     => $model->getTotalNhanVien()
        ];

        // Giải nén mảng $data thành các biến để view dùng trực tiếp
        extract($data);

        // Đưa dữ liệu ra view
        require __DIR__ . '/../views/baocao.php';
    }
}
