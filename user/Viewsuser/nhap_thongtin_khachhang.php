<?php
// phải gọi session_start trước mọi output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy lỗi và giá trị cũ (nếu có) từ session, tránh truy cập $_SESSION trực tiếp trong HTML
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Nhập thông tin khách hàng</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: url("https://images.unsplash.com/photo-1582719478250-c89cae4dc85b") no-repeat center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .form-box {
      background: rgba(255,255,255,0.9);
      padding: 40px;
      border-radius: 12px;
      width: 450px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      animation: fadeIn 1s ease;
    }
    .form-box h2 {
      text-align: center;
      color: #002060;
      margin-bottom: 25px;
    }
    .form-box input, .form-box select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .form-box button {
      width: 100%;
      padding: 12px;
      background: #002060;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    .form-box button:hover {
      background: #001040;
      transform: scale(1.05);
    }
    .error {
      color: red;
      font-size: 13px;
      margin-top: -8px;
      margin-bottom: 8px;
      text-align: left;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>Thông tin khách hàng</h2>
    <form method="POST" action="../Controlleruser/KhachHangController.php?action=save">
      <input type="hidden" name="id_phong" value="<?php echo htmlspecialchars($_GET['id_phong'] ?? ''); ?>">

      <input type="text" name="ho_ten" placeholder="Họ tên"
             value="<?php echo htmlspecialchars($old['ho_ten'] ?? ''); ?>" required>
      <?php if (!empty($errors['ho_ten'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['ho_ten']); ?></div>
      <?php endif; ?>

      <input type="date" name="ngay_sinh"
             value="<?php echo htmlspecialchars($old['ngay_sinh'] ?? ''); ?>" required>
      <?php if (!empty($errors['ngay_sinh'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['ngay_sinh']); ?></div>
      <?php endif; ?>

      <select name="gioi_tinh" required>
        <option value="">-- Giới tính --</option>
        <option value="Nam" <?php if (($old['gioi_tinh'] ?? '') === 'Nam') echo 'selected'; ?>>Nam</option>
        <option value="Nữ"  <?php if (($old['gioi_tinh'] ?? '') === 'Nữ')  echo 'selected'; ?>>Nữ</option>
        <option value="Khác"<?php if (($old['gioi_tinh'] ?? '') === 'Khác')echo 'selected'; ?>>Khác</option>
      </select>
      <?php if (!empty($errors['gioi_tinh'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['gioi_tinh']); ?></div>
      <?php endif; ?>

      <input type="text" name="so_dien_thoai" placeholder="Số điện thoại"
             value="<?php echo htmlspecialchars($old['so_dien_thoai'] ?? ''); ?>" required>
      <?php if (!empty($errors['so_dien_thoai'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['so_dien_thoai']); ?></div>
      <?php endif; ?>

      <input type="email" name="email" placeholder="Email"
             value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
      <?php if (!empty($errors['email'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['email']); ?></div>
      <?php endif; ?>

      <input type="text" name="cccd" placeholder="CCCD"
             value="<?php echo htmlspecialchars($old['cccd'] ?? ''); ?>" required>
      <?php if (!empty($errors['cccd'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['cccd']); ?></div>
      <?php endif; ?>

      <input type="text" name="dia_chi" placeholder="Địa chỉ"
             value="<?php echo htmlspecialchars($old['dia_chi'] ?? ''); ?>" required>
      <?php if (!empty($errors['dia_chi'])): ?>
        <div class="error"><?php echo htmlspecialchars($errors['dia_chi']); ?></div>
      <?php endif; ?>

      <button type="submit">Lưu thông tin</button>
    </form>
  </div>
</body>
</html>

<?php
// clear session errors/old sau khi đã hiển thị
if (session_status() === PHP_SESSION_ACTIVE) {
    unset($_SESSION['errors'], $_SESSION['old']);
}
?>
