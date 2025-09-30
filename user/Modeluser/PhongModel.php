<?php
include_once '../includes/db.php';

class PhongModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

public function getAllRooms() {
    $sql = "SELECT id_phong, so_phong, loai_phong, gia_phong, mo_ta, hinh_anh, trang_thai FROM phong";
    $result = $this->conn->query($sql);
    $rooms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
    return $rooms;
}

public function getRoomsByCategory($category) {
    $sql = "SELECT id_phong, so_phong, loai_phong, gia_phong, mo_ta, hinh_anh, trang_thai FROM phong WHERE loai_phong = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $rooms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
    $stmt->close();
    return $rooms;
}

public function getRoomById($id) {
    $sql = "SELECT id_phong, so_phong, loai_phong, gia_phong, so_luong_nguoi, mo_ta, hinh_anh, trang_thai 
            FROM phong WHERE id_phong = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $stmt->close();
    return $room;
}
public function updateTrangThai($idPhong, $trangThai) {
    $sql = "UPDATE Phong SET trang_thai = ? WHERE id_phong = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $trangThai, $idPhong);
    return $stmt->execute();
}

public function capNhatPhongHetHan() {
    $sql = "UPDATE Phong p
            JOIN DatPhong d ON p.id_phong = d.id_phong
            SET p.trang_thai = 'Trống'
            WHERE d.ngay_tra < CURDATE()";
    return $this->conn->query($sql);
}



}
?>