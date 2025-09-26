<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../Modeluser/DatPhongModel.php";

$model = new DatPhongModel($conn);
$datPhong = $model->getById($_GET['id_datphong']);

$ngayNhan = new DateTime($datPhong['ngay_nhan']);
$ngayTra  = new DateTime($datPhong['ngay_tra']);
$soNgay   = $ngayTra->diff($ngayNhan)->days;
$tongTien = $soNgay * $datPhong['gia_phong'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác nhận thanh toán</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f2f6;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 700px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      animation: slideUp 0.6s ease-in-out;
    }
    @keyframes slideUp {
      from {opacity: 0; transform: translateY(40px);}
      to {opacity: 1; transform: translateY(0);}
    }
    h2, h3 {
      text-align: center;
      color: #333;
    }
    .info p {
      font-size: 16px;
      margin: 8px 0;
    }
    .total {
      font-size: 18px;
      color: #d9534f;
      font-weight: bold;
    }
    .qr {
      text-align: center;
      margin-top: 20px;
    }
    .btn-back {
      display: block;
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      text-align: center;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-size: 16px;
      transition: 0.3s;
    }
    .btn-back:hover {
      background: #1e7e34;
    }
  </style>
  <script>
    function copyAmount() {
      navigator.clipboard.writeText("<?= $tongTien; ?>")
        .then(() => alert("Đã copy số tiền vào clipboard!"));
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Xác nhận thanh toán</h2>
    <div class="info">
      <p><b>Mã đặt phòng:</b> <?= $datPhong['id_datphong']; ?></p>
      <p><b>Khách hàng:</b> <?= $datPhong['ho_ten']; ?> (<?= $datPhong['email']; ?>)</p>
      <p><b>Phòng:</b> <?= $datPhong['so_phong']; ?> - <?= $datPhong['loai_phong']; ?></p>
      <p><b>Giá phòng:</b> <?= number_format($datPhong['gia_phong']); ?> VNĐ/ngày</p>
      <p><b>Ngày nhận:</b> <?= $datPhong['ngay_nhan']; ?></p>
      <p><b>Ngày trả:</b> <?= $datPhong['ngay_tra']; ?></p>
      <p class="total">Tổng tiền: <?= number_format($tongTien); ?> VNĐ</p>
    </div>

    <h3>QR thanh toán</h3>
    <div class="qr">
      <img src="https://img.vietqr.io/image/970422-123456789-qr_only.png?amount=<?= $tongTien; ?>&addInfo=Dat%20phong%20<?= $datPhong['id_datphong']; ?>" width="250">
      <p><button onclick="copyAmount()">Copy số tiền</button></p>
    </div>

    <a href="index.php" class="btn-back">Quay lại trang chủ</a>
  </div>
</body>
</html>
