<?php
class Database {
    // Thông tin database của Quân
    private static $host = "localhost";
    private static $db   = "qlykhachsan";   // ⚠️ Tên DB giữ nguyên
    private static $user = "root";
    private static $pass = "";
    private static $conn = null;

    // Lấy kết nối PDO
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=utf8",
                    self::$user,
                    self::$pass
                );
                // Bật chế độ báo lỗi
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Kết nối thất bại: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
