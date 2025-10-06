<?php
class Nhanvien {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách nhân viên
    // Lấy danh sách nhân viên (tìm kiếm theo tên hoặc chức vụ)
public function getAll($search = '') {
    $sql = "SELECT nv.*, tk.username, tk.role 
            FROM nhanvien nv
            LEFT JOIN taikhoan tk ON nv.tai_khoan_nhanvien_id = tk.id_taikhoan
            WHERE nv.ho_ten LIKE :search OR nv.chuc_vu LIKE :search";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':search' => "%$search%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Lấy nhân viên theo ID
    public function getById($id) {
        $sql = "SELECT * FROM nhanvien WHERE id_nhanvien = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra tài khoản tồn tại và role = NHANVIEN
    public function validateTaiKhoanNhanvien($id_taikhoan) {
        $sql = "SELECT * FROM taikhoan WHERE id_taikhoan = :id AND role = 'NHANVIEN'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_taikhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Validate số điện thoại (10 hoặc 11 chữ số)
private function validatePhone($phone) {
    return preg_match('/^[0-9]{10,11}$/', $phone);
}


    // Validate họ tên (chỉ chữ cái + khoảng trắng, có dấu tiếng Việt)
    private function validateHoTen($ho_ten) {
        return preg_match("/^[\p{L}\s]+$/u", $ho_ten);
    }

    // Validate chức vụ (chỉ chữ cái + khoảng trắng, có dấu tiếng Việt)
    private function validateChucVu($chuc_vu) {
        return preg_match("/^[\p{L}\s]+$/u", $chuc_vu);
    }

    // Validate lương cơ bản (≥0)
    private function validateLuong($luong) {
        return is_numeric($luong) && $luong >= 0;
    }
// Validate email
private function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

    // Thêm nhân viên
    public function create($data) {
        if (!$this->validateTaiKhoanNhanvien($data['tai_khoan_nhanvien_id'])) {
            $_SESSION['error'] = "❌ ID tài khoản không tồn tại hoặc không phải là NHÂN VIÊN!";
            return false;
        }
        if (!$this->validateHoTen($data['ho_ten'])) {
            $_SESSION['error'] = "❌ Họ tên không hợp lệ! Chỉ được chứa chữ cái và khoảng trắng.";
            return false;
        }
        if (!$this->validateChucVu($data['chuc_vu'])) {
            $_SESSION['error'] = "❌ Chức vụ không hợp lệ! Chỉ được chứa chữ cái và khoảng trắng.";
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
        if (!$this->validateLuong($data['luong_co_ban'])) {
            $_SESSION['error'] = "❌ Lương cơ bản không được là số âm!";
            return false;
        }

        $sql = "INSERT INTO nhanvien 
            (ho_ten, chuc_vu, luong_co_ban, ngay_vao_lam, so_dien_thoai, email, tai_khoan_nhanvien_id)
            VALUES (:ho_ten, :chuc_vu, :luong_co_ban, :ngay_vao_lam, :so_dien_thoai, :email, :tk_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ho_ten'        => $data['ho_ten'],
            ':chuc_vu'       => $data['chuc_vu'],
            ':luong_co_ban'  => $data['luong_co_ban'],
            ':ngay_vao_lam'  => $data['ngay_vao_lam'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':email'         => $data['email'],
            ':tk_id'         => $data['tai_khoan_nhanvien_id']
        ]);
    }

    // Cập nhật nhân viên
    public function update($id, $data) {
        if (!$this->validateTaiKhoanNhanvien($data['tai_khoan_nhanvien_id'])) {
            $_SESSION['error'] = "❌ ID tài khoản không tồn tại hoặc không phải là NHÂN VIÊN!";
            return false;
        }
        if (!$this->validateHoTen($data['ho_ten'])) {
            $_SESSION['error'] = "❌ Họ tên không hợp lệ! Chỉ được chứa chữ cái và khoảng trắng.";
            return false;
        }
        if (!$this->validateChucVu($data['chuc_vu'])) {
            $_SESSION['error'] = "❌ Chức vụ không hợp lệ! Chỉ được chứa chữ cái và khoảng trắng.";
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
        if (!$this->validateLuong($data['luong_co_ban'])) {
            $_SESSION['error'] = "❌ Lương cơ bản không được là số âm!";
            return false;
        }

        $sql = "UPDATE nhanvien SET 
                    ho_ten = :ho_ten, chuc_vu = :chuc_vu, luong_co_ban = :luong_co_ban,
                    ngay_vao_lam = :ngay_vao_lam, so_dien_thoai = :so_dien_thoai, 
                    email = :email, tai_khoan_nhanvien_id = :tk_id
                WHERE id_nhanvien = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':ho_ten'        => $data['ho_ten'],
            ':chuc_vu'       => $data['chuc_vu'],
            ':luong_co_ban'  => $data['luong_co_ban'],
            ':ngay_vao_lam'  => $data['ngay_vao_lam'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':email'         => $data['email'],
            ':tk_id'         => $data['tai_khoan_nhanvien_id'],
            ':id'            => $id
        ]);
    }

    // Xóa nhân viên
    public function delete($id) {
        $sql = "DELETE FROM nhanvien WHERE id_nhanvien = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
