<?php
class Nhanvien {
    private $conn;
    private $table = 'nhanvien';

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // Lấy tất cả nhân viên, hỗ trợ tìm kiếm
    public function getAll($search = '') {
    $sql = "SELECT * FROM nhanvien";
    $params = [];

    if (!empty($search)) {
        $sql .= " WHERE ho_ten LIKE ? OR chuc_vu LIKE ?";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Lấy 1 nhân viên theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM `$this->table` WHERE id_nhanvien = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm nhân viên
    public function create($data) {
        $sql = "INSERT INTO `$this->table`
            (tai_khoan_nhanvien_id, ho_ten, chuc_vu, luong_co_ban, ngay_vao_lam, so_dien_thoai, email)
            VALUES (:tai_khoan_nhanvien_id, :ho_ten, :chuc_vu, :luong_co_ban, :ngay_vao_lam, :so_dien_thoai, :email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':tai_khoan_nhanvien_id' => $data['tai_khoan_nhanvien_id'],
            ':ho_ten' => $data['ho_ten'],
            ':chuc_vu' => $data['chuc_vu'],
            ':luong_co_ban' => $data['luong_co_ban'],
            ':ngay_vao_lam' => $data['ngay_vao_lam'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':email' => $data['email']
        ]);
        return $this->conn->lastInsertId();
    }

    // Cập nhật nhân viên
    public function update($id, $data) {
        $sql = "UPDATE `$this->table` SET
            tai_khoan_nhanvien_id = :tai_khoan_nhanvien_id,
            ho_ten = :ho_ten,
            chuc_vu = :chuc_vu,
            luong_co_ban = :luong_co_ban,
            ngay_vao_lam = :ngay_vao_lam,
            so_dien_thoai = :so_dien_thoai,
            email = :email
            WHERE id_nhanvien = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tai_khoan_nhanvien_id' => $data['tai_khoan_nhanvien_id'],
            ':ho_ten' => $data['ho_ten'],
            ':chuc_vu' => $data['chuc_vu'],
            ':luong_co_ban' => $data['luong_co_ban'],
            ':ngay_vao_lam' => $data['ngay_vao_lam'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':email' => $data['email'],
            ':id' => $id
        ]);
    }
}
