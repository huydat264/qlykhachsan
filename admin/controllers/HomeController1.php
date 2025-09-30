<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Auth.php';

class HomeController1 {
    private $userModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->userModel = new User($conn);

        Auth::startSession();
    }

    public function index() {
        // Bắt buộc đăng nhập
        Auth::requireLogin();

        $user_role = $_SESSION['user_role'] ?? 'NHANVIEN';
        $is_admin = ($user_role === 'ADMIN');

        // Gọi view
        include __DIR__ . '/../views/index.php';
    }
}
?>
