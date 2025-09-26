<?php
class TaiKhoanModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function dangKi($tenDangNhap, $matKhau) {
        // Kiểm tra username đã tồn tại chưa
        $sql = "SELECT * FROM TaiKhoan WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $tenDangNhap);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Tên đăng nhập đã tồn tại!";
        }

        // ❌ Bỏ mã hoá, lưu mật khẩu thẳng vào DB
        $sql = "INSERT INTO TaiKhoan (username, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $tenDangNhap, $matKhau);
        if ($stmt->execute()) {
            return true;
        }
        return "Có lỗi xảy ra khi đăng ký!";
    }

    public function dangNhap($tenDangNhap, $matKhau) {
        // Lấy user với mật khẩu gốc
        $sql = "SELECT * FROM TaiKhoan WHERE username = ? AND password = ? AND trang_thai = 'ACTIVE'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $tenDangNhap, $matKhau);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            return $user;
        }
        return false;
    }
}
