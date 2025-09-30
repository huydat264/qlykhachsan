<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db; // PDO connection
    }

    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id_taikhoan, username, password, role FROM taikhoan WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
