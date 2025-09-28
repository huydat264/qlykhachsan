<?php
include 'header.php';

// Demo dữ liệu (thay bằng DB)
$services = [
    1 => ['ten'=>'Spa','hinh'=>'spa.jpg','mo_ta'=>'Trải nghiệm sự thư giãn tuyệt đối tại Khu Spa cao cấp của khách sạn, nơi kết hợp hoàn hảo giữa không gian yên bình và liệu pháp chăm sóc chuyên nghiệp. Với đội ngũ kỹ thuật viên giàu kinh nghiệm và sản phẩm thiên nhiên cao cấp, chúng tôi mang đến cho bạn hành trình hồi phục cả thể chất lẫn tinh thần.
💆 Các dịch vụ nổi bật:
- Massage toàn thân: Giúp giảm căng thẳng, cải thiện tuần hoàn máu và mang lại cảm giác thư thái.
- Chăm sóc da mặt chuyên sâu: Làm sạch, cấp ẩm và tái tạo làn da với công nghệ hiện đại.
- Xông hơi thảo dược: Thanh lọc cơ thể, hỗ trợ thải độc và tăng cường sức khỏe.
- Liệu pháp đá nóng: Giảm đau nhức cơ thể, kích thích năng lượng tích cực.
- Gói spa cặp đôi: Trải nghiệm lãng mạn và thư giãn cùng người thân yêu.
🕰️ Thời gian hoạt động:
Từ 8:00 sáng đến 10:00 tối mỗi ngày.
📍 Vị trí:
Tầng 3 - Khu tiện ích khách sạn, với không gian riêng tư và tầm nhìn hướng vườn xanh mát.
.','gia'=>500000],



    2 => ['ten'=>'Bể Bơi','hinh'=>'openbar.jpg','mo_ta'=>'Chào đón bạn đến với bể bơi ngoài trời hiện đại của khách sạn – nơi lý tưởng để tận hưởng ánh nắng, làn nước trong xanh và không khí trong lành. Được thiết kế theo phong cách nghỉ dưỡng cao cấp, khu vực bể bơi mang đến trải nghiệm thư giãn hoàn hảo cho mọi lứa tuổi.
🌟 Tiện ích nổi bật:
- Bể bơi người lớn: Rộng rãi, sạch sẽ, có khu vực ghế nằm thư giãn và quầy bar phục vụ đồ uống mát lạnh.
- Bể bơi trẻ em: Thiết kế an toàn, độ sâu phù hợp, có trò chơi nước vui nhộn.
- Khu vực tắm nắng: Ghế dài, dù che, khăn tắm miễn phí và phục vụ nước uống tận nơi.
- Dịch vụ huấn luyện viên bơi (theo yêu cầu): Hỗ trợ học bơi hoặc luyện tập kỹ thuật chuyên sâu.
🕰️ Thời gian mở cửa:
Từ 6:00 sáng đến 9:00 tối hàng ngày.
📍 Vị trí:
Tầng trệt - Khu sân vườn phía sau khách sạn, gần spa và phòng gym.
','gia'=>500000],



    3 => ['ten'=>'Opening Bar','hinh'=>'pool.jpg','mo_ta'=>'Khám phá Opening Bar – điểm đến lý tưởng để khởi đầu một buổi tối đầy cảm hứng hoặc thư giãn sau ngày dài. Với thiết kế mở, không gian sang trọng và thực đơn đồ uống phong phú, Opening Bar là nơi hội tụ của những cuộc trò chuyện thú vị, âm nhạc nhẹ nhàng và nghệ thuật pha chế đỉnh cao.
🍷 Điểm nổi bật:
- Thực đơn cocktail sáng tạo: Từ những công thức cổ điển đến các loại cocktail đặc trưng của khách sạn.
- Rượu vang và bia nhập khẩu: Tuyển chọn từ các nhà sản xuất danh tiếng trên thế giới.
- Đồ uống không cồn & mocktail: Phù hợp cho mọi đối tượng, kể cả trẻ em và người không dùng cồn.
- Không gian mở: View hướng vườn hoặc hồ bơi, kết hợp ánh sáng dịu và âm nhạc chill.
- Sự kiện đặc biệt: Happy Hour, đêm nhạc acoustic, tiệc cocktail theo chủ đề.
🕰️ Thời gian hoạt động:
Từ 5:00 chiều đến 12:00 đêm, mỗi ngày.
📍 Vị trí:
Tầng trệt - Khu vực sảnh chính, gần lối ra hồ bơi và nhà hàng.
','gia'=>200000],



    4 => ['ten'=>'Nhà Hàng','hinh'=>'massage.jpg','mo_ta'=>'Chào mừng quý khách đến với nhà hàng cao cấp của khách sạn, nơi hội tụ những hương vị đặc sắc từ khắp nơi trên thế giới. Với không gian sang trọng, thực đơn đa dạng và đội ngũ đầu bếp chuyên nghiệp, chúng tôi cam kết mang đến trải nghiệm ẩm thực tinh tế và đáng nhớ.
🌟 Điểm nổi bật:
- Buffet sáng phong phú: Hơn 50 món ăn Âu - Á, trái cây tươi, bánh ngọt và đồ uống dinh dưỡng.
- Thực đơn gọi món: Các món ăn truyền thống Việt Nam, đặc sản địa phương và món quốc tế được chế biến tinh tế.
- Góc ẩm thực chay: Dành riêng cho thực khách ăn chay với nguyên liệu sạch và công thức thanh đạm.
- Không gian riêng tư: Phòng VIP cho tiệc gia đình, gặp gỡ đối tác hoặc dịp đặc biệt.
- Dịch vụ tận tâm: Nhân viên phục vụ chuyên nghiệp, sẵn sàng tư vấn món ăn và chế độ dinh dưỡng phù hợp.
🕰️ Thời gian phục vụ:
- Buffet sáng: 6:30 ~ 10:00
- Bữa trưa: 11:30 ~ 14:00
- Bữa tối: 18:00 ~ 22:00
📍 Vị trí:
Tầng 2 - Khu vực trung tâm khách sạn, gần sảnh lễ tân và có tầm nhìn ra hồ bơi.
','gia'=>400000],
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
