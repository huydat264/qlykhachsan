<?php
$servername = "localhost";  // Nếu bạn dùng XAMPP thì để localhost
$username   = "root";       // Tài khoản MySQL mặc định
$password   = "";           // Mật khẩu mặc định của XAMPP thường để trống
$dbname     = "qlykhachsan"; // Tên database của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập charset UTF-8 để hỗ trợ tiếng Việt
$conn->set_charset("utf8");
?>
