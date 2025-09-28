<?php
// Model xử lý thông tin thanh toán và hiển thị hóa đơn
class ThanhToanModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Thêm bản ghi thanh toán
    public function insert($data) {
        $sql = "INSERT INTO thanhtoan (id_datphong, ngay_thanh_toan, so_tien, hinh_thuc, loai_thanh_toan)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isdss",
            $data['id_datphong'],
            $data['ngay_thanh_toan'],
            $data['so_tien'],
            $data['hinh_thuc'],
            $data['loai_thanh_toan']
        );
        return $stmt->execute();
    }

    // Lấy thông tin hóa đơn thanh toán theo id đặt phòng
    public function getByDatPhongId($id_datphong) {
        $sql = "SELECT 
                    d.id_datphong,
                    d.ngay_dat,
                    d.ngay_tra,
                    d.trang_thai,
                    k.ho_ten,
                    k.email,
                    k.so_dien_thoai,
                    p.so_phong,
                    p.loai_phong,
                    p.gia_phong,
                    t.ngay_thanh_toan,
                    t.so_tien,
                    t.hinh_thuc,
                    t.loai_thanh_toan
                FROM datphong d
                JOIN khachhang k ON d.id_khachhang = k.id_khachhang
                JOIN phong p ON d.id_phong = p.id_phong
                LEFT JOIN thanhtoan t ON d.id_datphong = t.id_datphong
                WHERE d.id_datphong = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_datphong);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
