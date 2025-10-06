<?php
class Dichvu {
    private $conn;
    private $table = 'dichvu';
    private $id_col = 'id_dichvu';
    private $name_col = 'ten_dich_vu';
    private $price_col = 'gia';
    private $desc_col = 'mo_ta';
    private $image_col = 'hinh_anh'; // ✅ thêm cột hình ảnh

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    private function isValidName($name) {
        return preg_match('/^[\p{L}\s]+$/u', $name);
    }

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

    public function getById($id) {
        $sql = "SELECT * FROM `$this->table` WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        if (!$this->isValidName($data[$this->name_col])) {
            throw new Exception("Tên dịch vụ chỉ được chứa chữ cái và khoảng trắng!");
        }

        $sql = "INSERT INTO `$this->table` 
                (`$this->name_col`, `$this->price_col`, `$this->desc_col`, `$this->image_col`) 
                VALUES (:ten, :gia, :mota, :hinh)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ten' => $data[$this->name_col],
            ':gia' => $data[$this->price_col],
            ':mota' => $data[$this->desc_col] ?? '',
            ':hinh' => $data[$this->image_col] ?? null
        ]);
        return $this->conn->lastInsertId();
    }

    public function update($id, $data) {
        if (!$this->isValidName($data[$this->name_col])) {
            throw new Exception("Tên dịch vụ không được có ký tự đặc biệt!");
        }

        $sql = "UPDATE `$this->table` 
                SET `$this->name_col` = :ten, 
                    `$this->price_col` = :gia, 
                    `$this->desc_col` = :mota, 
                    `$this->image_col` = :hinh 
                WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ten' => $data[$this->name_col],
            ':gia' => $data[$this->price_col],
            ':mota' => $data[$this->desc_col] ?? '',
            ':hinh' => $data[$this->image_col] ?? null,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM `$this->table` WHERE `$this->id_col` = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
