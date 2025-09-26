<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Phòng Khách Sạn</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body {
    font-family: 'Roboto', sans-serif;
    background: url('https://quayletanhd.com/wp-content/uploads/2023/04/quay-le-tan-khach-san-5.jpg') no-repeat center center fixed;
    background-size: cover; /* cho full màn hình */
    margin: 0;
    padding: 0;
    color: #333;
        }

        .booking-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .room-card {
            display: flex;
            align-items: center;
            margin-bottom: 50px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .room-card:nth-child(even) {
            flex-direction: row-reverse;
        }

        .room-image {
            width: 50%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .room-card:hover .room-image {
            transform: scale(1.05);
        }

        .room-info {
            padding: 40px;
            width: 50%;
        }

        .room-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .room-description {
            font-size: 1.1em;
            margin-bottom: 20px;
            color: #666;
            line-height: 1.6;
        }

        .room-price {
            font-size: 1.5em;
            font-weight: bold;
            color: #b08d57;
            margin-bottom: 20px;
        }

        .detail-button {
            background-color: #b08d57;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .detail-button:hover {
            background-color: #8c6d3e;
        }

        @media (max-width: 768px) {
            .room-card, .room-card:nth-child(even) {
                flex-direction: column;
            }

            .room-image, .room-info {
                width: 100%;
            }

            .room-image {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <section class="booking-section">
        <div class="room-card">
            <img src="https://www.thereveriesaigon.com/wp-content/uploads/2021/11/The-Grand-Deluxe-Header-Top-of-page-1920x1080-1.jpg" alt="Phòng Deluxe" class="room-image">
            <div class="room-info">
                <h2 class="room-title">Phòng Deluxe</h2>
                <p class="room-description">Phòng Deluxe mang đến không gian rộng rãi với nội thất cao cấp, view đẹp và tiện nghi đầy đủ.</p>
                <p class="room-price">2.500.000 VNĐ / đêm</p>
                <a href="phong.php?loai=Deluxe" class="detail-button">Xem Chi Tiết</a>
            </div>
        </div>

        <div class="room-card">
            <img src="https://phuquoc.crowneplaza.com/wp-content/uploads/2020/11/B1118206.jpg" alt="Phòng Suite" class="room-image">
            <div class="room-info">
                <h2 class="room-title">Phòng Suite</h2>
                <p class="room-description">Phòng Suite sang trọng với khu vực riêng biệt, dịch vụ cao cấp và thiết bị hiện đại.</p>
                <p class="room-price">4.000.000 VNĐ / đêm</p>
                <a href="phong.php?loai=Suite" class="detail-button">Xem Chi Tiết</a>
            </div>
        </div>

        <div class="room-card">
            <img src="https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img/https://neworienthoteldanang.com/wp-content/uploads/2023/09/New-Orient-Superior-1-1.jpg" alt="Phòng Standard" class="room-image">
            <div class="room-info">
                <h2 class="room-title">Phòng Standard</h2>
                <p class="room-description">Phòng Standard tiện nghi cơ bản, sạch sẽ và thoải mái, phù hợp cho du khách tiết kiệm.</p>
                <p class="room-price">1.500.000 VNĐ / đêm</p>
                <a href="phong.php?loai=Standard" class="detail-button">Xem Chi Tiết</a>
            </div>
        </div>
    </section>
</body>
</html>

<?php
include 'footer.php';
?>