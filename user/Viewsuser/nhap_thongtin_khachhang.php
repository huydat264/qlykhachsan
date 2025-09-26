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
      <input type="hidden" name="id_phong" value="<?php echo $_GET['id_phong']; ?>">

      <input type="text" name="ho_ten" placeholder="Họ tên" required>
      <input type="date" name="ngay_sinh" required>
      <select name="gioi_tinh" required>
        <option value="">-- Giới tính --</option>
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
        <option value="Khác">Khác</option>
      </select>
      <input type="text" name="so_dien_thoai" placeholder="Số điện thoại" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="cccd" placeholder="CCCD" required>
      <input type="text" name="dia_chi" placeholder="Địa chỉ" required>
      <button type="submit">Lưu thông tin</button>
    </form>
  </div>
</body>
</html>
