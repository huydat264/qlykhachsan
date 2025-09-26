<?php
include 'header.php';

// Danh sách dịch vụ
$services = [
    [
        'id' => 1,
        'ten' => 'Spa',
        'mo_ta' => 'Thư giãn với liệu trình massage chuyên nghiệp và các liệu pháp chăm sóc da cao cấp.',
        'hinh' => 'https://acihome.vn/uploads/15/thiet-ke-spa-tai-khach-san-nam-2023.jpeg'
    ],
    [
        'id' => 2,
        'ten' => 'Bể bơi',
        'mo_ta' => 'Bể bơi ngoài trời rộng rãi, nước trong xanh và view đẹp, phù hợp cả gia đình và bạn bè.',
        'hinh' => 'https://thewatsonpremiumhalonghotel.com/uploads/photos/full_1682151095_1545_0164ca8fbd6cd13b628b7d34936e865f.jpg'
    ],
    [
        'id' => 3,
        'ten' => 'Opening Bar',
        'mo_ta' => 'Thưởng thức các loại cocktail, mocktail và đồ uống đa dạng trong không gian sang trọng.',
        'hinh' => 'https://www.hotel-lechapitre.com/bases/restaurant_image/grande/47/BestWesternPremierleChapitreHotelSpa_CecileLanglois__HD_19.jpg'
    ],
    [
        'id' => 4,
        'ten' => 'Nhà Hàng Buffet',
        'mo_ta' => 'Trải nghiệm massage thư giãn cơ thể, giảm căng thẳng với kỹ thuật chuyên nghiệp.',
        'hinh' => 'https://hotelnikkosaigon.com.vn/images/upload/230719/1689740457_la-brasserie-2.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dịch vụ</title>
<style>
body {
    font-family: 'Roboto', sans-serif;
    margin:0;
    padding:0;
    background: url('https://images.lasvit.com/cdn-cgi/image/quality=100/assets/2022/07/04/010_Lasvit_Shangri-la-Fort_Manila_11US011_Photo_2016_full_4096_uid_62c34e9f11eea.jpg') no-repeat center center fixed;
    background-size: cover;
    color:#333;
}

.container {
    max-width:1200px;
    margin:50px auto;
    padding:20px;
}
.service-link {
    text-decoration:none;
    color:inherit;
}
.service-item {
    display:flex;
    flex-direction:row;
    align-items:center;
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    margin-bottom:50px;
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
    min-height:250px;
    opacity:0;
    transform: translateY(50px);
    transition: all 0.8s ease, transform 0.3s ease, box-shadow 0.3s ease;
}
.service-item.show {
    opacity:1;
    transform: translateY(0);
    outline: 3px solid #b08d57; /* highlight viền khi hiện */
}
.service-item:hover {
    transform: scale(1.05);
    box-shadow:0 15px 35px rgba(0,0,0,0.25);
}
.service-image {
    width:400px;
    height:100%;
    object-fit:cover;
    flex-shrink:0;
}
.service-content {
    padding:25px 30px;
}
.service-title {
    font-family:'Playfair Display', serif;
    font-size:1.8em;
    margin-bottom:15px;
    color:#1a1a1a;
}
.service-desc {
    font-size:1em;
    color:#555;
    line-height:1.6;
}
@media(max-width:900px) {
    .service-item {
        flex-direction:column;
        min-height:auto;
    }
    .service-image {
        width:100%;
        height:250px;
    }
    .service-content {
        padding:20px;
    }
}
</style>
</head>
<body>
<div class="container">
    <?php foreach($services as $svc): ?>
        <a href="chitietdichvu.php?id=<?php echo $svc['id']; ?>" class="service-link">
            <div class="service-item">
                <img src="<?php echo $svc['hinh']; ?>" alt="<?php echo $svc['ten']; ?>" class="service-image">
                <div class="service-content">
                    <div class="service-title"><?php echo $svc['ten']; ?></div>
                    <div class="service-desc"><?php echo $svc['mo_ta']; ?></div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
window.addEventListener('scroll', function() {
    const items = document.querySelectorAll('.service-item');
    const windowBottom = window.innerHeight + window.scrollY;
    items.forEach(item => {
        if (windowBottom > item.offsetTop + 50) {
            item.classList.add('show');
        }
    });
});
</script>
</body>
</html>

<?php include 'footer.php'; ?>
