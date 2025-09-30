<?php
    // auth.php

    // 1. Kiểm tra session đã bắt đầu chưa
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /**
     * Khóa trang: Yêu cầu người dùng phải đăng nhập để truy cập trang này.
     * Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập.
     * @param string $login_page Đường dẫn đến trang đăng nhập.
     */
    function require_login($login_page = 'login.php') {
        // Kiểm tra ĐÃ ĐĂNG NHẬP bằng cách check $_SESSION['user_id'] (biến quan trọng)
        // Nếu cả user_id và logged_in đều KHÔNG tồn tại, thì coi như chưa đăng nhập.
        if (!isset($_SESSION['user_id']) && (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)) {
            
            // Tránh vòng lặp: Chỉ chuyển hướng nếu trang hiện tại KHÔNG PHẢI là login.php
            $current_page = basename($_SERVER['PHP_SELF']);

            if ($current_page !== $login_page) {
                header("Location: " . $login_page);
                exit();
            }
        }
    }

    /**
     * Phân quyền: Kiểm tra role của người dùng có được phép truy cập không.
     * @param array $allowed_roles Mảng chứa các role được phép (ví dụ: ['ADMIN', 'NHANVIEN']).
     * @param string $redirect_page Trang chuyển hướng khi không có quyền (ví dụ: 'index.php').
     */
    function check_permission($allowed_roles, $redirect_page = 'index.php') {
        // 1. Đảm bảo người dùng đã đăng nhập trước khi kiểm tra quyền
        require_login($redirect_page); 

        if (!isset($_SESSION['user_role'])) {
            // Lỗi: Đã đăng nhập nhưng không có role. Chuyển hướng về trang chủ.
            header("Location: " . $redirect_page);
            exit();
        }

        $user_role = $_SESSION['user_role'];
        
        // 2. Nếu role của người dùng KHÔNG nằm trong danh sách được phép
        if (!in_array($user_role, $allowed_roles)) {
            // Thiết lập thông báo lỗi
            $_SESSION['error_message'] = "Bạn không có quyền truy cập trang này.";
            header("Location: " . $redirect_page);
            exit();
        }
    }

    /**
     * Phân quyền nhanh cho ADMIN
     */
    function require_admin($redirect_page = 'index.php') {
        check_permission(['ADMIN'], $redirect_page);
    }
    ?>