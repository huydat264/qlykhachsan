<?php
class KhachHangModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getByTaiKhoanId($idTaiKhoan) {
        $sql = "SELECT * FROM KhachHang WHERE tai_khoan_khachhang_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idTaiKhoan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insert($data) {
        $sql = "INSERT INTO KhachHang (tai_khoan_khachhang_id, ho_ten, ngay_sinh, gioi_tinh, so_dien_thoai, email, cccd, dia_chi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssssss",
            $data['tai_khoan_khachhang_id'],
            $data['ho_ten'],
            $data['ngay_sinh'],
            $data['gioi_tinh'],
            $data['so_dien_thoai'],
            $data['email'],
            $data['cccd'],
            $data['dia_chi']
        );
        return $stmt->execute();
    }
}
