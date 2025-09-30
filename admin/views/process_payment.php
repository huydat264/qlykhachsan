<?php
require_once __DIR__ . '/../core/Auth.php';
require_login();
check_permission(['ADMIN','NHANVIEN']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
</head>
<body>
    <h2>Form thanh toán</h2>
    <form method="POST" action="../controllers/Process_payment.Controller1.php">
        <input type="hidden" name="id_datphong" value="<?= htmlspecialchars($_GET['id_datphong'] ?? '') ?>">
        <input type="hidden" name="id_phong" value="<?= htmlspecialchars($_GET['id_phong'] ?? '') ?>">

        <label>Số tiền:</label>
        <input type="text" name="so_tien" required><br>

        <label>Hình thức:</label>
        <select name="hinh_thuc" required>
            <option value="Tiền mặt">Tiền mặt</option>
            <option value="Chuyển khoản">Chuyển khoản</option>
        </select><br>

        <label>Loại thanh toán:</label>
        <select name="loai_thanh_toan" required>
            <option value="Đặt cọc">Đặt cọc</option>
            <option value="Thanh toán toàn bộ">Thanh toán toàn bộ</option>
        </select><br>

        <button type="submit" name="submit_thanh_toan">Xác nhận thanh toán</button>
    </form>
</body>
</html>
