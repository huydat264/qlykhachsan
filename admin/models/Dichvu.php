<?php
class Dichvu {
    private $conn;
    private $table = 'dichvu';
    private $id_col = 'id_dichvu';
    private $name_col = 'ten_dich_vu';
    private $price_col = 'gia';
    private $desc_col = 'mo_ta';

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // ✅ Hàm kiểm tra tên dịch vụ hợp lệ (chỉ chữ cái + khoảng trắng)
    private function isValidName($name) {
        // Cho phép chữ cái (cả có dấu) và khoảng trắng
        return preg_match('/^[\p{L}\s]+$/u', $name);
    }

    // Lấy tất cả dịch vụ, có thể search
    public function getAll($search = '') {
        $sql = "SELECT * FROM `$this->table`";
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE `$this->name_col` LIKE :search OR `$this->desc_col` LIKE :search";
            $params[':search'] = "%$search%";
        }
        $sql .= " ORDER BY `$this->name_col`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy dịch vụ theo ID
    public function getById($id) {
        $sql = "SELECT * FROM `$this->table` WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm dịch vụ
    public function add($data) {
        if (!$this->isValidName($data[$this->name_col])) {
            throw new Exception("Tên dịch vụ chỉ được chứa chữ cái và khoảng trắng!");
        }

        $sql = "INSERT INTO `$this->table` (`$this->name_col`, `$this->price_col`, `$this->desc_col`) 
                VALUES (:ten, :gia, :mota)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ten' => $data[$this->name_col],
            ':gia' => $data[$this->price_col],
            ':mota' => $data[$this->desc_col] ?? ''
        ]);
        return $this->conn->lastInsertId();
    }

    // Cập nhật dịch vụ
    public function update($id, $data) {
        if (!$this->isValidName($data[$this->name_col])) {
            throw new Exception("Tên dịch vụ không được có ký tự đặc biệt!");
        }

        $sql = "UPDATE `$this->table` 
                SET `$this->name_col` = :ten, `$this->price_col` = :gia, `$this->desc_col` = :mota 
                WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten' => $data[$this->name_col],
            ':gia' => $data[$this->price_col],
            ':mota' => $data[$this->desc_col] ?? '',
            ':id' => $id
        ]);
    }

    // Xóa dịch vụ
    public function delete($id) {
        $sql = "DELETE FROM `$this->table` WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
