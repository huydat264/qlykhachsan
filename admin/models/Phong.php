<?php
// models/Phong.php
require_once __DIR__ . '/../config/database.php';

class Phong {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection(); // PDO
    }

    public function getAll($search = '') {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = "WHERE p.so_phong LIKE :search 
                      OR p.loai_phong LIKE :search ";
            $params[':search'] = "%$search%";
        }

        // Lấy bản ghi đặt phòng mới nhất (chưa hủy, chưa hoàn thành) cho mỗi phòng
        $sql = "SELECT 
                    p.*, 
                    CASE WHEN p.trang_thai = 'Trống' THEN NULL ELSE kh.ho_ten END AS ten_khach_hang,
                    CASE WHEN p.trang_thai = 'Trống' THEN NULL ELSE kh.cccd END AS cccd_khach_hang,
                    CASE WHEN p.trang_thai = 'Trống' THEN NULL ELSE kh.so_dien_thoai END AS sdt_khach_hang
                FROM Phong p
                LEFT JOIN (
                    SELECT d1.* FROM DatPhong d1
                    INNER JOIN (
                        SELECT id_phong, MAX(id_datphong) AS max_id
                        FROM DatPhong
                        WHERE trang_thai NOT IN ('Đã hủy', 'Hoàn thành')
                        GROUP BY id_phong
                    ) d2 ON d1.id_phong = d2.id_phong AND d1.id_datphong = d2.max_id
                ) dp ON p.id_phong = dp.id_phong
                LEFT JOIN KhachHang kh ON dp.id_khachhang = kh.id_khachhang
                $where
                ORDER BY p.so_phong";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM Phong WHERE id_phong = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
    $so_phong = trim($data['so_phong']);
    $so_luong_nguoi = trim($data['so_luong_nguoi']);
    $gia_phong = trim($data['gia_phong']);

    // Kiểm tra số phòng
    if (!preg_match('/^[1-9][0-9]*$/', $so_phong)) {
        throw new Exception("Số phòng phải là số nguyên dương, không chứa ký tự đặc biệt.");
    }

    // Kiểm tra số lượng người
    if (!preg_match('/^[1-9][0-9]*$/', $so_luong_nguoi)) {
        throw new Exception("Số người phải là số nguyên dương, không chứa ký tự đặc biệt.");
    }

    // Kiểm tra giá phòng
    if (!preg_match('/^[1-9][0-9]*$/', $gia_phong)) {
        throw new Exception("Giá phòng chỉ được nhập số, không âm, không ký tự đặc biệt.");
    }

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
        ':so_phong'        => $so_phong,
        ':loai_phong'      => $data['loai_phong'],
        ':gia_phong'       => $gia_phong,
        ':so_luong_nguoi'  => $so_luong_nguoi,
        ':mo_ta'           => $data['mo_ta'],
        ':anh'             => $this->xuLyAnh($data['anh']),
        ':trang_thai'      => $data['trang_thai'],
        ':id_phong'        => $data['id_phong']
    ]);
}


    private function xuLyAnh($link) {
        $link = trim($link);

        // Nếu là link trực tiếp (bắt đầu bằng http)
        if (preg_match('/^https?:\/\//', $link)) {
            return $link;
        }

        // Nếu chỉ là tên file thì lưu tên file (ví dụ: phong1.jpg)
        // Không thêm ../../ ở đây, vì phần user đã tự nối
        return basename($link);
    }

    public function deleteKhach($id) {
        try {
            $this->conn->beginTransaction();

            $sql1 = "DELETE FROM DatPhong WHERE id_phong = :id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([':id' => $id]);

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