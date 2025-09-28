<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Phòng đã đặt</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f4f4f9; }
    h2 { color: #002060; margin-bottom: 20px; }
    table {
      width: 100%; border-collapse: collapse; background: #fff; 
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
    th { background: #002060; color: #fff; }
    tr:hover { background: #f1f1f1; }
  </style>
</head>
<body>
    <?php include 'header.php'; ?>
  <h2>Danh sách phòng bạn đã đặt</h2>

  <?php if ($dsDatPhong && count($dsDatPhong) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Mã đặt phòng</th>
          <th>Số phòng</th>
          <th>Loại phòng</th>
          <th>Ngày đặt</th>
          <th>Ngày trả</th>
          <th>Trạng thái</th>
          <th>Tổng tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dsDatPhong as $dp): 
          $phong = $phongModel->getRoomById($dp['id_phong']);
          $tt    = $thanhToanModel->getByDatPhongId($dp['id_datphong']);
          $tongTien = $tt['so_tien'] ?? 0;
        ?>
          <tr>
            <td><?= $dp['id_datphong']; ?></td>
            <td><?= htmlspecialchars($phong['so_phong'] ?? ''); ?></td>
            <td><?= htmlspecialchars($phong['loai_phong'] ?? ''); ?></td>
            <td><?= $dp['ngay_dat']; ?></td>
            <td><?= $dp['ngay_tra']; ?></td>
            <td><?= $dp['trang_thai']; ?></td>
            <td><?= number_format($tongTien, 0, ',', '.'); ?> VND</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Bạn chưa đặt phòng nào.</p>
  <?php endif; ?>
<?php include 'footer.php'; ?>
</body>
</html>
