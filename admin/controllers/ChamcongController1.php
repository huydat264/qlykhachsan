<?php
require_once __DIR__ . '/../models/Chamcong.php';
require_once __DIR__ . '/../core/Auth.php';

class ChamcongController1
{
    public function index()
    {
        require_login();
        check_permission(['ADMIN', 'NHANVIEN']);

        $model = new Chamcong();
        $userRole = $_SESSION['user_role'] ?? 'NHANVIEN';
        $isAdmin  = ($userRole === 'ADMIN');

        $message = '';
        $error   = false;
        $editData = null;

        // Xử lý thêm / cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            if (!$isAdmin) {
                $message = "Bạn không có quyền Thêm/Sửa chấm công!";
                $error   = true;
            } else {
                $data = [
                    'id_nhanvien'            => (int)$_POST['id_nhanvien'],
                    'thang'                  => (int)$_POST['thang'],
                    'nam'                    => (int)$_POST['nam'],
                    'so_ngay_di_lam'         => (int)$_POST['so_ngay_di_lam'],
                    'so_ngay_nghi_co_phep'   => (int)$_POST['so_ngay_nghi_co_phep'],
                    'so_ngay_nghi_khong_phep'=> (int)$_POST['so_ngay_nghi_khong_phep'],
                ];
                $idChamcong = isset($_POST['id_chamcong']) ? (int)$_POST['id_chamcong'] : 0;

                try {
                    if ($idChamcong > 0) {
                        $ok = $model->update($idChamcong, $data);
                        $message = $ok ? "Cập nhật chấm công thành công!" : "Lỗi khi cập nhật!";
                        $error   = !$ok;
                    } else {
                        $ok = $model->insert($data);
                        $message = $ok ? "Thêm chấm công thành công!" : "Lỗi khi thêm!";
                        $error   = !$ok;
                    }
                } catch (PDOException $e) {
                    $message = "Lỗi: " . $e->getMessage();
                    $error   = true;
                }

                if (!$error && $idChamcong === 0) {
                   header("Location: index.php?controller=Chamcong&message=" . urlencode($message));

                    exit();
                }
            }
        }

        // Thông báo từ URL
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $error   = str_contains($message, 'Lỗi') || str_contains($message, 'quyền');
        }

        // Dữ liệu sửa
        if (isset($_GET['edit'])) {
            if ($isAdmin) {
                $editData = $model->getById((int)$_GET['edit']);
            } else {
                $message = "Bạn không có quyền chỉnh sửa dữ liệu chấm công.";
                $error   = true;
            }
        }

        $nhanvienList = $model->getNhanVienList();
        $chamcongList = $model->getChamcongList();

        // Gửi dữ liệu ra view
        require __DIR__ . '/../views/chamcong.php';
    }
}
