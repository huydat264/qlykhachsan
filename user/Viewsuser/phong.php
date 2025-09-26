<?php
include 'header.php';
include_once '../Modeluser/PhongModel.php';

$model = new PhongModel();
$rooms = $model->getAllRooms(); // Lấy tất cả phòng
$initialCategory = isset($_GET['loai']) ? strtolower($_GET['loai']) : 'all';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Phòng</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        /* Giữ nguyên CSS cũ */
        body { font-family: 'Roboto', sans-serif; margin:0; padding:0; color:#333; background:url('../../images/bg-hotel.jpg') no-repeat center center fixed; background-size:cover; }
        .container { max-width:1200px; margin:50px auto; padding:20px; }
        .category-buttons { margin-bottom:30px; text-align:center; }
        .category-btn { background-color:#b08d57; color:#fff; border:none; padding:12px 25px; margin:5px; font-size:1em; border-radius:5px; cursor:pointer; transition:background-color 0.3s ease; }
        .category-btn:hover { background-color:#8c6d3e; }
        .room-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:50px; align-items:stretch; }
        .room-item { display:flex; flex-direction:column; justify-content:space-between; background-color:rgba(255,255,255,0.95); border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1); padding:20px; text-align:center; transition: transform 0.3s ease, box-shadow 0.3s ease; height:100%; }
        .room-item:hover { transform: scale(1.05); box-shadow:0 8px 20px rgba(0,0,0,0.15); }
        .room-link { text-decoration:none; color:inherit; }
        .room-image { width:100%; height:200px; object-fit:cover; border-radius:5px; }
        .room-name { font-family:'Playfair Display', serif; font-size:1.3em; margin:10px 0; color:#1a1a1a; }
        .room-description { font-size:0.9em; color:#666; margin-bottom:10px; flex-grow:1; }
        .room-price { font-size:1.2em; font-weight:bold; color:#b08d57; margin-bottom:10px; }
        .room-status { font-size:0.9em; margin-bottom:10px; }
        .room-status.trong { color:green; font-weight:bold; }
        .room-status.dadat { color:red; font-weight:bold; }
        .action-buttons { margin-top:auto; }
        .book-btn { background-color:#b08d57; color:#fff; border:none; padding:10px 20px; font-size:0.9em; border-radius:5px; cursor:pointer; transition: background-color 0.3s ease; }
        .book-btn:hover { background-color:#8c6d3e; }
        @media (max-width:768px) { .room-grid { grid-template-columns:1fr; } }
    </style>
    <script>
        function filterRooms(category) {
            const rooms = document.querySelectorAll('.room-item');
            rooms.forEach(room => {
                const roomCategory = room.getAttribute('data-category');
                if (category === 'all' || roomCategory === category.toLowerCase()) {
                    room.style.display = 'flex';
                } else {
                    room.style.display = 'none';
                }
            });
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('loai');
            filterRooms(category ? category.toLowerCase() : 'all');
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="category-buttons">
            <button class="category-btn" onclick="filterRooms('all')">Tất Cả</button>
            <button class="category-btn" onclick="filterRooms('Deluxe')">Deluxe</button>
            <button class="category-btn" onclick="filterRooms('Suite')">Suite</button>
            <button class="category-btn" onclick="filterRooms('Standard')">Standard</button>
        </div>

        <div class="room-grid">
            <?php
            if (!empty($rooms)) {
                foreach ($rooms as $room) {
                    $statusClass = (trim($room['trang_thai']) === 'Trống') ? 'trong' : 'dadat';
                    $trangthai = trim($room['trang_thai']); // Loại bỏ khoảng trắng dư
                    ?>
                    <div class="room-item" data-category="<?php echo strtolower($room['loai_phong']); ?>">
                        <a href="chitietphong.php?id=<?php echo $room['id_phong']; ?>" class="room-link">
                            <img src="../../images/<?php echo $room['hinh_anh']; ?>" alt="<?php echo $room['so_phong']; ?>" class="room-image">
                            <h3 class="room-name">Phòng <?php echo $room['so_phong']; ?> - <?php echo $room['loai_phong']; ?></h3>
                        </a>
                        <p class="room-description"><?php echo $room['mo_ta'] ?? 'Chưa có mô tả'; ?></p>
                        <p class="room-price"><?php echo number_format($room['gia_phong'], 0, ',', '.'); ?> VNĐ / đêm</p>
                        <p class="room-status <?php echo $statusClass; ?>">Trạng thái: <?php echo $trangthai ?? 'Chưa cập nhật'; ?></p>
                        <div class="action-buttons">
                            <?php if (strcasecmp($trangthai, 'Trống') === 0): ?>
                                <button class="book-btn" onclick="window.location.href='../Controlleruser/DatPhongController.php?action=check&id_phong=<?php echo $room['id_phong']; ?>'">Đặt phòng</button>
                            <?php else: ?>
                                <button class="book-btn" disabled style="background-color: grey; cursor: not-allowed;"><?php echo $trangthai; ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p>Không có phòng nào để hiển thị.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
include 'footer.php';
?>
