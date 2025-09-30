<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($dichvu->getTen()) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }
        .detail-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .detail-container img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 12px;
        }
        h1 {
            margin-top: 20px;
            font-size: 28px;
            color: #333;
        }
        p {
            margin: 15px 0;
            font-size: 16px;
            color: #555;
        }
        .price {
            font-size: 20px;
            font-weight: bold;
            color: #e63946;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: #007BFF;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
<div class="detail-container">
    <h1><?= htmlspecialchars($dichvu->getTen()) ?></h1>
    <p><?= nl2br(htmlspecialchars($dichvu->getMoTa())) ?></p>
    <p class="price">Giá: <?= number_format($dichvu->getGia(), 0, ',', '.') ?> VND</p>
    <a href="DichVuController.php?action=list">Quay lại danh sách</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
