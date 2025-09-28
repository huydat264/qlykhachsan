<?php
class DatPhongModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy phòng theo ID đây là file xử lý sau nhập tt
    public function getPhongById($id) {
        $sql = "SELECT * FROM phong WHERE id_phong = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Lưu đặt phòng
    public function insert($data) {
        $sql = "INSERT INTO datphong (id_khachhang, id_phong, ngay_dat, ngay_nhan, ngay_tra, trang_thai)
                VALUES (?, ?, ?, ?, ?, 'Chờ xác nhận')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisss",
            $data['id_khachhang'],
            $data['id_phong'],
            $data['ngay_dat'],
            $data['ngay_nhan'],
            $data['ngay_tra']
        );
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // Lấy thông tin đặt phòng + join khách hàng + phòng
    public function getById($id) {
        $sql = "SELECT d.*, k.ho_ten, k.email, k.so_dien_thoai, k.dia_chi,
                       p.so_phong, p.loai_phong, p.gia_phong
                FROM datphong d
                JOIN khachhang k ON d.id_khachhang = k.id_khachhang
                JOIN phong p ON d.id_phong = p.id_phong
                WHERE d.id_datphong = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Lấy danh sách phòng đã đặt theo khách hàng
    public function getByKhachHang($id_khachhang) {
        $sql = "SELECT d.*, p.so_phong, p.loai_phong, p.gia_phong
                FROM datphong d
                JOIN phong p ON d.id_phong = p.id_phong
                WHERE d.id_khachhang = ?
                ORDER BY d.ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_khachhang);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
