<?php
require_once __DIR__ . '/../models/Quanlybangluong.php';

class QuanlybangluongController1
{
    public function index()
    {
        require_login();
        check_permission(['ADMIN', 'NHANVIEN']);

        $model = new Quanlybangluong();
        $message = '';
        $error = false;

        // --- Lấy danh sách nhân viên cho form ---
        $nhanvien_list = $model->getAllNhanVien();

        // --- Xử lý thêm / cập nhật ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $id_nv  = (int)$_POST['id_nhanvien'];
            $thang  = (int)$_POST['thang'];
            $nam    = (int)$_POST['nam'];
            $thuong = (float)$_POST['thuong'];
            $phat   = (float)$_POST['phat'];
            $id_bangluong = isset($_POST['id_bangluong']) ? (int)$_POST['id_bangluong'] : 0;

            $luong_cb  = $model->getLuongCoBan($id_nv);
            $so_cong   = $model->getSoNgayCong($id_nv, $thang, $nam);
            $tong_luong = 0;

            if ($luong_cb > 0) {
                $days = cal_days_in_month(CAL_GREGORIAN, $thang, $nam);
                $luong_ngay = $luong_cb / max($days,1);
                $tong_luong = ($luong_ngay * $so_cong) + $thuong - $phat;
            } else {
                $message = "Không tìm thấy lương cơ bản hoặc dữ liệu không hợp lệ.";
                $error = true;
            }

            if (!$error) {
                $data = [
                    'id_nhanvien' => $id_nv,
                    'thang'       => $thang,
                    'nam'         => $nam,
                    'so_ngay_cong'=> $so_cong,
                    'thuong'      => $thuong,
                    'phat'        => $phat,
                    'luong_co_ban'=> $luong_cb,
                    'tong_luong'  => $tong_luong
                ];

                if ($id_bangluong > 0) {
                    $model->update($id_bangluong, $data);
                    $message = "Cập nhật thành công! Tổng lương: " . number_format($tong_luong) . " VNĐ";
                } else {
                    try {
                        $model->insert($data);
                        $message = "Thêm thành công! Tổng lương: " . number_format($tong_luong) . " VNĐ";
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $message = "Bản ghi đã tồn tại cho tháng $thang/$nam.";
                        } else {
                            $message = "Lỗi CSDL: " . $e->getMessage();
                        }
                        $error = true;
                    }
                }

                if (!$error) {
                    header("Location: index.php?controller=Quanlybangluong&message=" . urlencode($message));
                    exit;
                }
            }
        }

        // --- Hiển thị thông báo ---
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            $error = strpos($message, 'Lỗi') !== false;
        }

        // --- Lấy dữ liệu edit nếu có ---
        $edit_data = null;
        if (isset($_GET['edit'])) {
            $edit_data = $model->getById((int)$_GET['edit']);
        }

        // --- Lấy danh sách bảng lương ---
        $bangluong_list = $model->getAll();

        // Gọi view
        require __DIR__ . '/../views/quanlybangluong.php';
    }
}
