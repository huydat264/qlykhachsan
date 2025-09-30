<?php
// Nếu controller chưa truyền biến $dichvus thì tạo rỗng để tránh báo lỗi
if (!isset($dichvus)) {
    $dichvus = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dịch vụ khách sạn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #002060;
        }
        .service-item {
            display: flex;
            align-items: center;
            background: #fff;
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .service-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        .service-info {
            padding: 20px;
        }
        .service-info h2 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #333;
        }
        .service-info p {
            margin: 0 0 15px;
            color: #555;
        }
        .service-info a {
            display: inline-block;
            padding: 10px 15px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .service-info a:hover {
            background: #0056b3;
        }
        .no-service {
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
<div class="container">
    <h1>Dịch vụ khách sạn</h1>

    <?php if (!empty($dichvus)): ?>
        <?php foreach ($dichvus as $dv): ?>
            <div class="service-item">
                <div class="service-info">
                    <h2><?= htmlspecialchars($dv->getTen()) ?></h2>
                    <p><?= htmlspecialchars($dv->getMoTa()) ?></p>
                    <p><strong>Giá: </strong><?= number_format($dv->getGia(), 0, ',', '.') ?> VND</p>
                    <a href="../Controlleruser/DichVuController.php?action=detail&id=<?= $dv->getId() ?>">Xem chi tiết</a>

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-service">Chưa có dịch vụ nào để hiển thị.</p>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
