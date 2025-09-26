<?php
include 'header.php';
include_once '../Modeluser/PhongModel.php';

if (!isset($_GET['id'])) {
    echo "<p>Không tìm thấy phòng.</p>";
    include 'footer.php';
    exit();
}

$model = new PhongModel();
$room = $model->getRoomById($_GET['id']);

if (!$room) {
    echo "<p>Phòng không tồn tại.</p>";
    include 'footer.php';
    exit();
}

$trangthai = trim($room['trang_thai']); // loại bỏ khoảng trắng dư
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết phòng <?php echo $room['so_phong']; ?></title>
    <style>
        .room-detail {
            max-width: 1000px;
            margin: 50px auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .room-detail img {
            width: 100%;
            border-radius: 10px;
        }
        .room-info h2 {
            margin: 0 0 15px;
            color: #1a1a1a;
        }
        .room-info p {
            margin: 8px 0;
            color: #555;
        }
        .room-price {
            font-size: 1.2em;
            color: #b08d57;
            font-weight: bold;
        }
        .book-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #b08d57;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        .book-btn:hover {
            background: #8c6d3e;
        }
        .room-description {
            grid-column: span 2;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="room-detail">
        <div class="room-image">
            <img src="../../images/<?php echo $room['hinh_anh']; ?>" alt="<?php echo $room['so_phong']; ?>">
        </div>
        <div class="room-info">
            <h2>Phòng <?php echo $room['so_phong']; ?></h2>
            <p><strong>Loại phòng:</strong> <?php echo $room['loai_phong']; ?></p>
            <p><strong>Số lượng người ở:</strong> <?php echo $room['so_luong_nguoi']; ?></p>
            <p><strong>Trạng thái:</strong> <?php echo $trangthai; ?></p>
            <p class="room-price">Giá: <?php echo number_format($room['gia_phong'], 0, ',', '.'); ?> VNĐ / đêm</p>

            <?php if (strcasecmp($trangthai, 'Trống') === 0): ?>
                <a href="../Controlleruser/DatPhongController.php?action=check&id_phong=<?php echo $room['id_phong']; ?>" class="book-btn">
                    Đặt phòng
                </a>
            <?php else: ?>
                <a class="book-btn" style="background-color: grey; cursor: not-allowed; pointer-events: none;">
                    <?php echo $trangthai; ?>
                </a>
            <?php endif; ?>

        </div>
        <div class="room-description">
            <h3>Mô tả</h3>
            <p><?php echo $room['mo_ta'] ?? 'Chưa có mô tả'; ?></p>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
