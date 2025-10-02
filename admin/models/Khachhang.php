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

    // ✅ Validate số điện thoại
    private function validatePhone($phone) {
        return preg_match('/^[0-9]{9,15}$/', $phone);
    }

    // ✅ Validate địa chỉ (không để trống)
    private function validateAddress($address) {
        return !empty(trim($address));
    }

    // Thêm khách hàng
    public function create($data) {
        if (!$this->validatePhone($data['so_dien_thoai'])) {
            $_SESSION['error'] = "❌ Số điện thoại không hợp lệ! (Chỉ nhập số, từ 9-15 ký tự)";
            return false;
        }

        if (!$this->validateAddress($data['dia_chi'])) {
            $_SESSION['error'] = "❌ Vui lòng nhập quê quán (địa chỉ)!";
            return false;
        }

        $sql = "INSERT INTO `$this->table` 
                (tai_khoan_khachhang_id, ho_ten, so_dien_thoai, email, ngay_sinh, gioi_tinh, cccd, dia_chi) 
                VALUES (:tai_khoan, :ho_ten, :sdt, :email, :ngay_sinh, :gioi_tinh, :cccd, :dia_chi)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tai_khoan' => $data['tai_khoan_khachhang_id'],
            ':ho_ten' => $data['ho_ten'],
            ':sdt' => (string)$data['so_dien_thoai'],
            ':email' => $data['email'],
            ':ngay_sinh' => $data['ngay_sinh'],
            ':gioi_tinh' => $data['gioi_tinh'],
            ':cccd' => $data['cccd'],
            ':dia_chi' => $data['dia_chi']
        ]);
    }

    // Cập nhật khách hàng
public function update($id, $data) {
    if (!$this->validatePhone($data['so_dien_thoai'])) {
        $_SESSION['error'] = "❌ Số điện thoại không hợp lệ! (Chỉ nhập số, từ 9-15 ký tự)";
        return false;
    }

    if (!$this->validateAddress($data['dia_chi'])) {
        $_SESSION['error'] = "❌ Vui lòng nhập quê quán (địa chỉ)!";
        return false;
    }

    // ✅ Kiểm tra trùng email hoặc CCCD (nhưng bỏ qua chính khách hàng đang sửa)
    $checkSql = "SELECT id_khachhang FROM `$this->table` 
                 WHERE (email = :email OR cccd = :cccd) AND id_khachhang != :id";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->execute([
        ':email' => $data['email'],
        ':cccd'  => $data['cccd'],
        ':id'    => $id
    ]);

    if ($checkStmt->rowCount() > 0) {
        $_SESSION['error'] = "❌ Email hoặc CCCD đã tồn tại ở khách hàng khác!";
        return false;
    }

    // ✅ Nếu không trùng thì cập nhật
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
        ':sdt' => (string)$data['so_dien_thoai'],
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
