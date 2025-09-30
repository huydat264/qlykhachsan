<?php
class Hoadon {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy thông tin hóa đơn theo id_datphong
    public function getHoadonByDatphong($id_datphong) {
        $sql = "
            SELECT 
                hd.id_hoadon,
                hd.tong_tien,
                hd.ngay_xuat,
                dp.id_phong,
                dp.ngay_nhan,
                dp.ngay_tra,
                kh.ho_ten,
                kh.so_dien_thoai,
                p.so_phong,
                p.gia_phong,
                tt.hinh_thuc,
                tt.loai_thanh_toan
            FROM hoadon hd
            JOIN datphong dp ON hd.id_datphong = dp.id_datphong
            JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
            JOIN phong p ON dp.id_phong = p.id_phong
            LEFT JOIN thanhtoan tt ON hd.id_datphong = tt.id_datphong
            WHERE hd.id_datphong = ?
            GROUP BY hd.id_hoadon
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_datphong]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách dịch vụ đã sử dụng
    public function getDichvuSudung($id_datphong) {
        $sql = "
            SELECT
                dv.ten_dich_vu,
                sdv.so_luong,
                sdv.thanh_tien
            FROM sudungdichvu sdv
            JOIN dichvu dv ON sdv.id_dichvu = dv.id_dichvu
            WHERE sdv.id_datphong = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_datphong]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
