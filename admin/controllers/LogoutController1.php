<?php
class LogoutController1 {
    public function index() {
        session_start();
        session_unset();   // Xoá toàn bộ session
        session_destroy(); // Huỷ session

        // Quay về trang login
        header("Location: index.php?controller=home");
        exit();
    }
}
