<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Auth.php';

class LoginController1 {
    private $userModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->userModel = new User($conn);

        Auth::startSession(); // Bắt đầu session nếu chưa
    }

    public function index() {
        $error = '';

        // Nếu đã đăng nhập, chuyển về Home
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=home&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getByUsername($username);

            if ($user) {
                // So sánh password trực tiếp
                if ($user['password'] === $password) {
                    $_SESSION['user_id']   = $user['id_taikhoan'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['logged_in'] = true;

                    header("Location: index.php?controller=home&action=index");
                    exit();
                } else {
                    $error = "Mật khẩu không đúng.";
                }
            } else {
                $error = "Tên tài khoản không tồn tại.";
            }
        }

        // Gọi view login
        include __DIR__ . '/../views/login.php';
    }
}
?>
