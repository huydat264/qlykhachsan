<?php
require_once __DIR__ . '/../includes/db.php';
require_once 'DichVu.php';

class DichVuDAO {
    private $conn;

    public function __construct() {
        global $conn; // lấy kết nối từ db.php
        $this->conn = $conn;
    }

    // Lấy tất cả dịch vụ
    public function getAll() {
        $sql = "SELECT * FROM dichvu";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = [];

        while ($row = $result->fetch_assoc()) {
            $list[] = new DichVu(
                $row['id_dichvu'],
                $row['ten_dich_vu'],
                $row['mo_ta'],
                $row['gia']
            );
        }
        return $list;
    }

    // Lấy dịch vụ theo ID
    public function getById($id) {
        $sql = "SELECT * FROM dichvu WHERE id_dichvu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return new DichVu(
                $row['id_dichvu'],
                $row['ten_dich_vu'],
                $row['mo_ta'],
                $row['gia']
            );
        }
        return null;
    }
}
