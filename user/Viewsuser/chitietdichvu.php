<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($dichvu->getTen()) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0; /* bỏ padding body */
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* đảm bảo footer ở cuối */
        }

        /* Chỉ áp dụng cho phần chi tiết dịch vụ */
        .detail-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            margin: 40px auto; /* cách header và footer */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .detail-container h1 {
            margin-top: 20px;
            font-size: 28px;
            color: #333;
        }
        .detail-container p {
            margin: 15px 0;
            font-size: 16px;
            color: #555;
        }
        .detail-container .price {
            font-size: 20px;
            font-weight: bold;
            color: #e63946;
        }
        .detail-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: #007BFF;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
        }
        .detail-container a:hover {
            background: #0056b3;
        }

        footer {
            margin-top: auto; /* đẩy footer xuống cuối */
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
