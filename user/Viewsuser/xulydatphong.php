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
    body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
    .container { width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1); animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from {opacity:0; transform:translateY(-20px);} to {opacity:1; transform:translateY(0);} }
    h2 { text-align:center; color:#333; }
    .info { margin-bottom:15px; font-size:16px; }
    label { display:block; margin:10px 0 5px; font-weight:bold; }
    input[type="date"] { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; font-size:14px; }
    button { margin-top:20px; width:100%; padding:12px; background:#007bff; color:#fff; border:none; border-radius:6px; font-size:16px; cursor:pointer; transition:0.3s; }
    button:hover { background:#0056b3; }
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

      <label>Ngày nhận:</label>
      <input type="date" name="ngay_nhan" required>

      <label>Ngày trả:</label>
      <input type="date" name="ngay_tra" required>

      <button type="submit">Xác nhận đặt phòng</button>
    </form>
  </div>
</body>
</html>
