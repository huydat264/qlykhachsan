<?php
// models/Phong.php
require_once __DIR__ . '/../config/database.php';

class Phong {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection(); // PDO
    }

    // Lấy danh sách phòng + khách hàng
    public function getAll($search = '') {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = "WHERE Phong.so_phong LIKE :search 
                      OR Phong.loai_phong LIKE :search 
                      OR KhachHang.ho_ten LIKE :search";
            $params[':search'] = "%$search%";
        }

        $sql = "SELECT 
                    Phong.*, 
                    KhachHang.ho_ten AS ten_khach_hang,
                    KhachHang.cccd AS cccd_khach_hang,
                    KhachHang.so_dien_thoai AS sdt_khach_hang
                FROM Phong 
                LEFT JOIN DatPhong ON Phong.id_phong = DatPhong.id_phong
                LEFT JOIN KhachHang ON DatPhong.id_khachhang = KhachHang.id_khachhang
                $where
                ORDER BY Phong.so_phong";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng
    }

    // Lấy phòng theo ID
    public function getById($id) {
        $sql = "SELECT * FROM Phong WHERE id_phong = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về 1 phòng
    }

    // Cập nhật thông tin phòng
    public function update($data) {
        $sql = "UPDATE Phong 
                SET so_phong = :so_phong, 
                    loai_phong = :loai_phong, 
                    gia_phong = :gia_phong, 
                    so_luong_nguoi = :so_luong_nguoi, 
                    mo_ta = :mo_ta, 
                    anh = :anh, 
                    trang_thai = :trang_thai
                WHERE id_phong = :id_phong";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':so_phong'        => $data['so_phong'],
            ':loai_phong'      => $data['loai_phong'],
            ':gia_phong'       => $data['gia_phong'],
            ':so_luong_nguoi'  => $data['so_luong_nguoi'],
            ':mo_ta'           => $data['mo_ta'],
            ':anh'             => $data['anh'],
            ':trang_thai'      => $data['trang_thai'],
            ':id_phong'        => $data['id_phong']
        ]);
    }

    // Trả phòng (xóa khách khỏi DatPhong và set trạng thái về Trống)
    public function deleteKhach($id) {
        try {
            $this->conn->beginTransaction();

            // Xóa trong bảng đặt phòng
            $sql1 = "DELETE FROM DatPhong WHERE id_phong = :id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([':id' => $id]);

            // Cập nhật trạng thái phòng
            $sql2 = "UPDATE Phong SET trang_thai = 'Trống' WHERE id_phong = :id";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi trả phòng: " . $e->getMessage());
            return false;
        }
    }
}
