<?php
class Khachhang {
    private $conn;
    private $table = 'khachhang';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy tất cả khách hàng, hỗ trợ tìm kiếm
    public function getAll($search = '') {
        $sql = "SELECT * FROM `$this->table`";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE ho_ten LIKE :search OR so_dien_thoai LIKE :search OR email LIKE :search OR dia_chi LIKE :search";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy khách hàng theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM `$this->table` WHERE id_khachhang = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm khách hàng
    public function create($data) {
        $sql = "INSERT INTO `$this->table` (tai_khoan_khachhang_id, ho_ten, so_dien_thoai, email, ngay_sinh, gioi_tinh, cccd, dia_chi) 
                VALUES (:tai_khoan, :ho_ten, :sdt, :email, :ngay_sinh, :gioi_tinh, :cccd, :dia_chi)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tai_khoan' => $data['tai_khoan_khachhang_id'],
            ':ho_ten' => $data['ho_ten'],
            ':sdt' => $data['so_dien_thoai'],
            ':email' => $data['email'],
            ':ngay_sinh' => $data['ngay_sinh'],
            ':gioi_tinh' => $data['gioi_tinh'],
            ':cccd' => $data['cccd'],
            ':dia_chi' => $data['dia_chi']
        ]);
    }

    // Cập nhật khách hàng
    public function update($id, $data) {
        $sql = "UPDATE `$this->table` SET 
                    tai_khoan_khachhang_id = :tai_khoan,
                    ho_ten = :ho_ten,
                    so_dien_thoai = :sdt,
                    email = :email,
                    ngay_sinh = :ngay_sinh,
                    gioi_tinh = :gioi_tinh,
                    cccd = :cccd,
                    dia_chi = :dia_chi
                WHERE id_khachhang = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tai_khoan' => $data['tai_khoan_khachhang_id'],
            ':ho_ten' => $data['ho_ten'],
            ':sdt' => $data['so_dien_thoai'],
            ':email' => $data['email'],
            ':ngay_sinh' => $data['ngay_sinh'],
            ':gioi_tinh' => $data['gioi_tinh'],
            ':cccd' => $data['cccd'],
            ':dia_chi' => $data['dia_chi'],
            ':id' => $id
        ]);
    }
}
?>
