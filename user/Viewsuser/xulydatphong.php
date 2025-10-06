<?php 
if (!isset($_SESSION)) session_start(); 

// bắt login (nếu view được mở trực tiếp)
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Bạn cần đăng nhập để đặt phòng'); window.location.href='../Viewsuser/login.php';</script>";
    exit;
}

// ĐƯỜNG DẪN ĐÚNG: từ Viewsuser -> lên 1 cấp -> vào Modeluser
require_once __DIR__ . "/../Modeluser/PhongModel.php";

// Lấy thông tin phòng theo id GET
$phong = null;
if (isset($_GET['id_phong'])) {
    $model = new PhongModel();
    $phong = $model->getRoomById((int)$_GET['id_phong']);
}

if (!$phong) {
    echo "<script>alert('Không tìm thấy phòng'); window.location.href='../Viewsuser/phong.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt phòng</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: url("/doanqlks/admin/views/nen.jpg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 600px;
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      animation: fadeIn 0.6s ease-in-out;
      backdrop-filter: blur(6px);
    }
    @keyframes fadeIn {
      from {opacity:0; transform:translateY(-20px);}
      to   {opacity:1; transform:translateY(0);}
    }
    h2 {
      text-align: center;
      color: #1d3557;
      margin-bottom: 20px;
      font-size: 26px;
      letter-spacing: 1px;
    }
    .info {
      margin-bottom: 20px;
      font-size: 16px;
      line-height: 1.6;
      background: #f1f5f9;
      padding: 12px 16px;
      border-radius: 10px;
      color: #333;
    }
    label {
      display: block;
      margin: 12px 0 6px;
      font-weight: bold;
      color: #333;
    }
    input[type="date"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      transition: 0.3s;
    }
    input[type="date"]:focus {
      border-color: #457b9d;
      box-shadow: 0 0 6px rgba(69,123,157,0.4);
      outline: none;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #457b9d, #1d3557);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: linear-gradient(135deg, #1d3557, #0b2545);
      transform: translateY(-2px);
    }
    .back-btn {
      display: block;
      margin-top: 15px;
      text-align: center;
      padding: 12px;
      background: #ccc;
      color: #333;
      text-decoration: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: bold;
      transition: 0.3s;
    }
    .back-btn:hover {
      background: #999;
      color: white;
    }
  </style>
  <script>
    function validateForm() {
      const nhan = document.querySelector("[name='ngay_nhan']").value;
      const tra  = document.querySelector("[name='ngay_tra']").value;
      if (!nhan || !tra) { alert("Chọn ngày nhận và ngày trả"); return false; }
      if (new Date(nhan) >= new Date(tra)) {
        alert("Ngày trả phải sau ngày nhận!");
        return false;
      }
      return true;
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Đặt phòng</h2>
    <div class="info">
      <p><b>Phòng:</b> <?= htmlspecialchars($phong['so_phong']); ?> - <?= htmlspecialchars($phong['loai_phong']); ?></p>
      <p><b>Giá:</b> <?= number_format($phong['gia_phong']); ?> VNĐ / ngày</p>
    </div>
    <form method="POST" action="../Controlleruser/XuLyDatPhongController.php?action=save" onsubmit="return validateForm()">
      <input type="hidden" name="id_phong" value="<?= $phong['id_phong']; ?>">


  <?php $today = date('Y-m-d'); ?>
  <label>Ngày nhận:</label>
  <input type="date" name="ngay_nhan" required min="<?= $today ?>">

  <label>Ngày trả:</label>
  <input type="date" name="ngay_tra" required min="<?= $today ?>">

      <button type="submit">Xác nhận đặt phòng</button>
    </form>
    <a href="index.php" class="back-btn">⬅ Quay lại trang chủ</a>
  </div>
</body>
</html>
