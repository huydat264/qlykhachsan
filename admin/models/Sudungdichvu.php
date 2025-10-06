<?php
class Sudungdichvu {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getGiaDichVu($id_dichvu) {
        $sql = "SELECT gia FROM dichvu WHERE id_dichvu = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_dichvu]);
        return $stmt->fetchColumn();
    }

    public function insertSudungdv($id_datphong, $id_dichvu, $so_luong, $thanh_tien) {
        $sql = "INSERT INTO sudungdichvu (id_datphong, id_dichvu, so_luong, thanh_tien)
                VALUES (:id_datphong, :id_dichvu, :so_luong, :thanh_tien)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_datphong' => $id_datphong,
            ':id_dichvu'   => $id_dichvu,
            ':so_luong'    => $so_luong,
            ':thanh_tien'  => $thanh_tien
        ]);
    }

    public function getSudungdvById($id_sudungdv) {
        $sql = "SELECT * FROM sudungdichvu WHERE id_sudungdv = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_sudungdv]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSudungdv($id_sudungdv, $id_datphong, $id_dichvu, $so_luong, $thanh_tien) {
        $sql = "UPDATE sudungdichvu SET
                    id_datphong = :id_datphong,
                    id_dichvu = :id_dichvu,
                    so_luong = :so_luong,
                    thanh_tien = :thanh_tien
                WHERE id_sudungdv = :id_sudungdv";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_datphong' => $id_datphong,
            ':id_dichvu'   => $id_dichvu,
            ':so_luong'    => $so_luong,
            ':thanh_tien'  => $thanh_tien,
            ':id_sudungdv' => $id_sudungdv
        ]);
    }

    public function getPhongDat() {
        // Lấy bản ghi đặt phòng mới nhất có trạng thái 'Đã xác nhận' cho mỗi phòng
        $sql = "SELECT dp.*, p.so_phong, kh.ho_ten
                FROM datphong dp
                JOIN (
                    SELECT id_phong, MAX(id_datphong) AS max_id
                    FROM datphong
                    WHERE trang_thai = 'Đã xác nhận'
                    GROUP BY id_phong
                ) latest ON dp.id_phong = latest.id_phong AND dp.id_datphong = latest.max_id
                JOIN phong p ON dp.id_phong = p.id_phong
                JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
                WHERE dp.trang_thai = 'Đã xác nhận'";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDichVu() {
        $sql = "SELECT * FROM dichvu ORDER BY ten_dich_vu";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSudungdv() {
        $sql = "SELECT sudungdichvu.*, phong.so_phong, dichvu.ten_dich_vu, dichvu.gia, khachhang.ho_ten
                FROM sudungdichvu
                JOIN datphong ON sudungdichvu.id_datphong = datphong.id_datphong
                JOIN dichvu ON sudungdichvu.id_dichvu = dichvu.id_dichvu
                JOIN phong ON datphong.id_phong = phong.id_phong
                JOIN khachhang ON datphong.id_khachhang = khachhang.id_khachhang
                ORDER BY sudungdichvu.id_sudungdv DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ======================= HÀM TÌM KIẾM DỊCH VỤ =======================
    public function searchDichVu($keyword = '') {
        $sql = "SELECT * FROM dichvu 
                WHERE ten_dich_vu LIKE :keyword
                ORDER BY ten_dich_vu";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ======================= HÀM TÌM KIẾM DỊCH VỤ ĐÃ SỬ DỤNG =======================
    public function searchSudungdv($keyword = '') {
        $sql = "SELECT sudungdichvu.*, phong.so_phong, dichvu.ten_dich_vu, dichvu.gia, khachhang.ho_ten
                FROM sudungdichvu
                JOIN datphong ON sudungdichvu.id_datphong = datphong.id_datphong
                JOIN dichvu ON sudungdichvu.id_dichvu = dichvu.id_dichvu
                JOIN phong ON datphong.id_phong = phong.id_phong
                JOIN khachhang ON datphong.id_khachhang = khachhang.id_khachhang
                WHERE dichvu.ten_dich_vu LIKE :keyword
                   OR khachhang.ho_ten LIKE :keyword
                   OR phong.so_phong LIKE :keyword
                ORDER BY sudungdichvu.id_sudungdv DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
