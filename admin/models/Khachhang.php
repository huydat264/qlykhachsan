<?php
class Khachhang {
    private $conn;
    private $table = 'khachhang';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ✅ Validate tên khách hàng
    private function validateName($name) {
        return preg_match('/^[\p{L}\s]+$/u', $name); // chỉ chữ cái + khoảng trắng
    }

    private function validatePhone($phone) {
    return preg_match('/^[0-9]{10,11}$/', $phone);
}


    // ✅ Validate email
    private function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // ✅ Validate CCCD (12 số)
    private function validateCCCD($cccd) {
        return preg_match('/^[0-9]{12}$/', $cccd);
    }

    // ✅ Validate địa chỉ
    private function validateAddress($address) {
        return !empty(trim($address));
    }

    // ✅ Kiểm tra ID tài khoản có role USER không
    private function validateUserAccount($tai_khoan_id) {
        $sql = "SELECT * FROM taikhoan WHERE id_taikhoan = :id AND role = 'USER'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $tai_khoan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Lấy tất cả khách hàng
    public function getAll($search = '') {
        if (!empty($search)) {
            $sql = "SELECT * FROM `$this->table`
                    WHERE ho_ten LIKE :search
                       OR email LIKE :search
                       OR so_dien_thoai LIKE :search
                       OR cccd LIKE :search";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':search' => "%$search%"]);
        } else {
            $sql = "SELECT * FROM `$this->table`";
            $stmt = $this->conn->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy khách hàng theo ID
    public function getById($id) {
        $sql = "SELECT * FROM `$this->table` WHERE id_khachhang = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm khách hàng
    public function create($data) {
        // ✅ Validate dữ liệu
        if (!empty($data['tai_khoan_khachhang_id'])) {
            if (!$this->validateUserAccount($data['tai_khoan_khachhang_id'])) {
                $_SESSION['error'] = "❌ ID tài khoản không hợp lệ hoặc không phải USER!";
                return false;
            }
        }
        if (!$this->validateName($data['ho_ten'])) {
            $_SESSION['error'] = "❌ Tên khách hàng chỉ được chứa chữ cái và khoảng trắng!";
            return false;
        }
        if (!$this->validatePhone($data['so_dien_thoai'])) {
            $_SESSION['error'] = "❌ Số điện thoại không hợp lệ!";
            return false;
        }
        if (!$this->validateEmail($data['email'])) {
            $_SESSION['error'] = "❌ Email không hợp lệ!";
            return false;
        }
        if (!$this->validateCCCD($data['cccd'])) {
            $_SESSION['error'] = "❌ CCCD phải có đúng 12 số!";
            return false;
        }
        if (!$this->validateAddress($data['dia_chi'])) {
            $_SESSION['error'] = "❌ Vui lòng nhập địa chỉ!";
            return false;
        }

        // ✅ Kiểm tra trùng lặp
        $checkSql = "SELECT * FROM `$this->table` 
                     WHERE cccd = :cccd OR so_dien_thoai = :sdt OR email = :email";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([
            ':cccd' => $data['cccd'],
            ':sdt'  => $data['so_dien_thoai'],
            ':email'=> $data['email']
        ]);
        $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if ($row['email'] === $data['email']) {
                $_SESSION['error'] = "❌ Email đã tồn tại!";
                return false;
            }
            if ($row['so_dien_thoai'] === $data['so_dien_thoai']) {
                $_SESSION['error'] = "❌ Số điện thoại đã tồn tại!";
                return false;
            }
            if ($row['cccd'] === $data['cccd']) {
                $_SESSION['error'] = "❌ CCCD đã tồn tại!";
                return false;
            }
        }

        // ✅ Thêm khách hàng
    $sql = "INSERT INTO `$this->table` 
        (tai_khoan_khachhang_id, ho_ten, so_dien_thoai, email, ngay_sinh, gioi_tinh, cccd, dia_chi) 
        VALUES (:tai_khoan, :ho_ten, :sdt, :email, :ngay_sinh, :gioi_tinh, :cccd, :dia_chi)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':tai_khoan' => !empty($data['tai_khoan_khachhang_id']) ? $data['tai_khoan_khachhang_id'] : null,
        ':ho_ten'    => $data['ho_ten'],
        ':sdt'       => $data['so_dien_thoai'],
        ':email'     => $data['email'],
        ':ngay_sinh' => $data['ngay_sinh'],
        ':gioi_tinh' => $data['gioi_tinh'],
        ':cccd'      => $data['cccd'],
        ':dia_chi'   => $data['dia_chi']
    ]);
    }

    // Cập nhật khách hàng
    public function update($id, $data) {
        // ✅ Validate dữ liệu
        if (!empty($data['tai_khoan_khachhang_id'])) {
            if (!$this->validateUserAccount($data['tai_khoan_khachhang_id'])) {
                $_SESSION['error'] = "❌ ID tài khoản không hợp lệ hoặc không phải USER!";
                return false;
            }
        }
        if (!$this->validateName($data['ho_ten'])) {
            $_SESSION['error'] = "❌ Tên khách hàng chỉ được chứa chữ cái và khoảng trắng!";
            return false;
        }
        if (!$this->validatePhone($data['so_dien_thoai'])) {
            $_SESSION['error'] = "❌ Số điện thoại không hợp lệ!";
            return false;
        }
        if (!$this->validateEmail($data['email'])) {
            $_SESSION['error'] = "❌ Email không hợp lệ!";
            return false;
        }
        if (!$this->validateCCCD($data['cccd'])) {
            $_SESSION['error'] = "❌ CCCD phải có đúng 12 số!";
            return false;
        }
        if (!$this->validateAddress($data['dia_chi'])) {
            $_SESSION['error'] = "❌ Vui lòng nhập địa chỉ!";
            return false;
        }

        // ✅ Kiểm tra trùng lặp (bỏ qua chính khách hàng đang sửa)
        $checkSql = "SELECT * FROM `$this->table` 
                     WHERE (email = :email OR cccd = :cccd OR so_dien_thoai = :sdt) 
                     AND id_khachhang != :id";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([
            ':email' => $data['email'],
            ':cccd'  => $data['cccd'],
            ':sdt'   => $data['so_dien_thoai'],
            ':id'    => $id
        ]);
        $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if ($row['email'] === $data['email']) {
                $_SESSION['error'] = "❌ Email đã tồn tại!";
                return false;
            }
            if ($row['so_dien_thoai'] === $data['so_dien_thoai']) {
                $_SESSION['error'] = "❌ Số điện thoại đã tồn tại!";
                return false;
            }
            if ($row['cccd'] === $data['cccd']) {
                $_SESSION['error'] = "❌ CCCD đã tồn tại!";
                return false;
            }
        }

        // ✅ Cập nhật khách hàng
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
            ':tai_khoan' => !empty($data['tai_khoan_khachhang_id']) ? $data['tai_khoan_khachhang_id'] : null,
            ':ho_ten'    => $data['ho_ten'],
            ':sdt'       => $data['so_dien_thoai'],
            ':email'     => $data['email'],
            ':ngay_sinh' => $data['ngay_sinh'],
            ':gioi_tinh' => $data['gioi_tinh'],
            ':cccd'      => $data['cccd'],
            ':dia_chi'   => $data['dia_chi'],
            ':id'        => $id
        ]);
    }
}
