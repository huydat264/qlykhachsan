<?php
session_start();
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/TaiKhoanModel.php";

class DangKiDangNhapController {
    private $model;

    public function __construct($conn) {
        $this->model = new TaiKhoanModel($conn);
    }

  public function dangNhap() {
    $error = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tenDangNhap = $_POST['username'];
        $matKhau = $_POST['password'];

        $user = $this->model->dangNhap($tenDangNhap, $matKhau);
        if ($user) {
            $_SESSION['user'] = $user;

            // redirect theo vai trò
            if ($user['role'] === 'ADMIN') {
                header("Location: ../../admin/trangchu.php");
            } elseif ($user['role'] === 'NHANVIEN') {
                header("Location: ../nhanvien/trangchu.php");
            } else {
                // 👇 CHỈNH Ở ĐÂY
                header("Location: ../Viewsuser/trangchu.php");
            }
            exit;
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
        }
    }
    include __DIR__ . "/../Viewsuser/login.php";
}


public function dangKi() {
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tenDangNhap = $_POST['username'];
        $matKhau = $_POST['password'];
        $nhapLai = $_POST['confirm_password'];

        if ($matKhau !== $nhapLai) {
            $message = "Mật khẩu nhập lại không khớp!";
            include __DIR__ . "/../Viewsuser/register.php"; // vẫn load lại form
        } else {
            $ketQua = $this->model->dangKi($tenDangNhap, $matKhau);
            if ($ketQua === true) {
                   // Lưu message vào session
              $_SESSION['success_message'] = "Đăng ký thành công, hãy đăng nhập!";
             header("Location: ../Viewsuser/login.php");
              exit;
            } else {
                $message = $ketQua;
                include __DIR__ . "/../Viewsuser/register.php";
            }
        }
    } else {
        include __DIR__ . "/../Viewsuser/register.php";
    }
}


    public function dangXuat() {
        session_destroy();
        header("Location: ../Viewsuser/login.php");
        exit;
    }
}

// Router
$auth = new DangKiDangNhapController($conn);
$action = $_GET['hanhdong'] ?? '';

if ($action === 'dangnhap') {
    $auth->dangNhap();
} elseif ($action === 'dangki') {
    $auth->dangKi();
} elseif ($action === 'dangxuat') {
    $auth->dangXuat();
} else {
    echo "Không có hành động hợp lệ!";
}
