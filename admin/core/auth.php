<?php
class Auth {
    // Bắt đầu session nếu chưa
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Kiểm tra đăng nhập
    public static function requireLogin($redirect = 'index.php?controller=login&action=index') {
        self::startSession();
        if (!isset($_SESSION['user_id']) || !($_SESSION['logged_in'] ?? false)) {
            header("Location: $redirect");
            exit();
        }
    }

    // Lấy role người dùng
    public static function getUserRole() {
        self::startSession();
        return $_SESSION['user_role'] ?? 'NHANVIEN';
    }

    // Kiểm tra có phải ADMIN không
    public static function isAdmin() {
        return self::getUserRole() === 'ADMIN';
    }

    // Kiểm tra quyền truy cập theo danh sách role được phép
    public static function checkPermission(array $allowedRoles, $redirect = 'index.php?controller=home&action=index') {
        self::requireLogin();
        $userRole = self::getUserRole();
        if (!in_array($userRole, $allowedRoles)) {
            $_SESSION['error_message'] = "Bạn không có quyền truy cập trang này.";
            header("Location: $redirect");
            exit();
        }
    }
}

/* ------------------ Thêm 2 hàm toàn cục cho code cũ ------------------ */
function require_login($redirect = 'index.php?controller=login&action=index') {
    Auth::requireLogin($redirect);
}

function check_permission(array $allowedRoles, $redirect = 'index.php?controller=home&action=index') {
    Auth::checkPermission($allowedRoles, $redirect);
}
