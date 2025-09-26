<?php
include 'header.php';

// Demo dữ liệu (thay bằng DB)
$services = [
    1 => ['ten'=>'Spa','hinh'=>'spa.jpg','mo_ta'=>'Thư giãn và làm đẹp với liệu trình chuyên nghiệp.','gia'=>500000],
    2 => ['ten'=>'Open Bar','hinh'=>'openbar.jpg','mo_ta'=>'Thưởng thức đồ uống đa dạng tại quầy bar sang trọng.','gia'=>300000],
    3 => ['ten'=>'Bể bơi','hinh'=>'pool.jpg','mo_ta'=>'Bơi lội và thư giãn tại bể bơi ngoài trời.','gia'=>200000],
    4 => ['ten'=>'Massage','hinh'=>'massage.jpg','mo_ta'=>'Massage chuyên nghiệp giúp giảm căng thẳng và mệt mỏi.','gia'=>400000],
];

$id = $_GET['id'] ?? null;
if(!$id || !isset($services[$id])) {
    echo "<p>Dịch vụ không tồn tại.</p>";
    include 'footer.php';
    exit();
}

$svc = $services[$id];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết dịch vụ: <?php echo $svc['ten']; ?></title>
    <style>
        body { font-family:'Roboto',sans-serif; margin:0; padding:0; background:#f8f8f8; }
        .container { max-width:900px; margin:50px auto; padding:20px; background:#fff; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
        .service-title { font-size:2em; color:#b08d57; margin-bottom:20px; }
        .service-image { width:100%; height:300px; object-fit:cover; border-radius:10px; margin-bottom:20px; }
        .service-desc { font-size:1em; color:#555; margin-bottom:15px; }
        .service-price { font-size:1.2em; font-weight:bold; color:#1a1a1a; margin-bottom:20px; }
        .book-btn { display:inline-block; padding:10px 20px; background:#b08d57; color:#fff; border-radius:5px; text-decoration:none; transition:background 0.3s; }
        .book-btn:hover { background:#8c6d3e; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="service-title"><?php echo $svc['ten']; ?></h1>
        <img src="../../images/<?php echo $svc['hinh']; ?>" alt="<?php echo $svc['ten']; ?>" class="service-image">
        <p class="service-desc"><?php echo $svc['mo_ta']; ?></p>
        <p class="service-price">Giá: <?php echo number_format($svc['gia'],0,',','.'); ?> VNĐ</p>
        <a href="dichvu.php" class="book-btn">Quay lại</a>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
