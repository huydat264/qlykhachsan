<?php
// index.php (dòng 1)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>The Great Mission Hotel</title>
  <style>
    body {margin:0;font-family:"Segoe UI",sans-serif;background:#f4f4f4;color:#222;}
    header {background:#002060;color:#fff;padding:15px 50px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:999;}
    header .logo {font-size:22px;font-weight:bold;letter-spacing:1px;}
    nav a {color:#fff;text-decoration:none;margin:0 15px;font-weight:500;}
    nav a:hover {text-decoration:underline;}

    /* Hero */
    .hero {position:relative;height:100vh;background:url('https://imgcdn.tapchicongthuong.vn/tcct-media/25/3/6/a--nh-2_67c907ace21e7.jpg') no-repeat center/cover;display:flex;justify-content:space-between;align-items:center;color:#fff;padding:0 60px;}
    .hero::before {
      content:"";
      position:absolute;
      top:0; left:0;
      width:100%; height:100%;
      background:rgba(0,0,50,0.15);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: 900px;
      margin: 0 auto;
    }
    .hero-content h1 {
      font-family: "Times New Roman", Georgia, serif;
      font-size: 48px;
      font-weight: bold;
      text-transform: uppercase;
      text-align: center;
      color: #ffffff;
      line-height: 1.3;
      letter-spacing: 2px;
      margin-bottom: 25px;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeUp 1.5s ease forwards;
    }
    .hero-content a {
      display: inline-block;
      padding: 12px 25px;
      background: #002060;
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      border-radius: 5px;
      transition: all 0.3s ease;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeUp 1.5s ease forwards;
      animation-delay: 0.5s;
    }
    .hero-content a:hover {
      background: #fff;
      color: #002060;
      box-shadow: 0 0 15px rgba(255,255,255,0.6);
    }
    @keyframes fadeUp {
      to {opacity: 1; transform: translateY(0);}
    }

    .booking-form {
      background:rgba(255,255,255,0.85);
      padding:20px;
      border-radius:8px;
      position:relative;
      z-index:2;
      width:250px;
      transform: scale(0.9);
      opacity: 0;
      transition: all 0.8s ease;
    }
    .booking-form.show {
      transform: scale(1);
      opacity: 1;
    }
    .booking-form h3 {margin:0 0 15px;color:#002060;}
    .booking-form input,.booking-form select,.booking-form button {width:100%;padding:8px;margin:6px 0;border:1px solid #ccc;border-radius:4px;}
    .booking-form button {background:#002060;color:#fff;border:none;cursor:pointer;transition:all 0.3s ease;}
    .booking-form button:hover {background:#001040;transform:scale(1.05);}

    /* Unique Room Style */
    .unique-room {
      display: flex; align-items: center; justify-content: center;
      padding: 80px 100px; background: #fff;
    }
    .unique-room .container {
      display: flex; align-items: center; max-width: 1200px; gap: 50px;
    }
    .unique-room .text {flex: 1; text-align: center;}
    .unique-room .text h2 {font-size: 28px; color: #0a2370; margin-bottom: 0;}
    .unique-room .text h3 {font-size: 32px; font-family: "Brush Script MT", cursive; color: #0a2370; margin-top: 5px;}
    .unique-room .text p {font-size: 16px; margin: 20px 0; line-height: 1.6; color: #333;}
    .unique-room .read-more {display: inline-block; margin-top: 15px; color: #0a2370; font-weight: bold; text-decoration: none; font-size: 16px; transition:all 0.3s ease;}
    .unique-room .read-more:hover {color:#fff; background:#0a2370; padding:8px 18px; border-radius:4px;}
    .unique-room .image {flex: 1; text-align: right;}
    .unique-room .image img {max-width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.6s ease, box-shadow 0.6s ease;}
    .unique-room .image img:hover {transform: scale(1.05); box-shadow:0 8px 25px rgba(0,0,0,0.3);}

    /* Luxury Travel */
    .luxury-travel {text-align: center; padding: 80px 20px; background: #fff;}
    .luxury-travel h2 {font-family:"Times New Roman",Georgia,serif; font-size:28px; font-style:italic; font-weight:normal; color:#002060; margin-bottom:50px;}
    .luxury-container {display:flex; flex-direction:column; gap:60px; max-width:1200px; margin:0 auto;}
    .luxury-item {display:flex; align-items:center; gap:40px; opacity:0; transform:translateY(60px); transition:all 1s ease;}
    .luxury-item.show {opacity:1; transform:translateY(0);}
    .luxury-item.reverse {flex-direction: row-reverse;}
    .luxury-image img {width:100%; max-width:500px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); transition:transform 0.7s ease, box-shadow 0.5s ease;}
    .luxury-image img:hover {transform:scale(1.07); box-shadow:0 10px 30px rgba(0,0,0,0.3);}
    .luxury-text {flex:1; text-align:justify;}
    .luxury-text h3 {font-size:22px; color:#002060; margin-bottom:15px;}
    .luxury-text p {font-size:16px; color:#444; line-height:1.6;}
    .luxury-text a {display:inline-block; margin-top:10px; padding:8px 20px; border:1px solid #002060; color:#002060; text-decoration:none; border-radius:4px; transition:all 0.3s ease;}
    .luxury-text a:hover {background:#002060; color:#fff; box-shadow:0 0 12px rgba(0,32,96,0.6);}

    /* Services */
    .cards {display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-top:30px;}
    .card {background:#fff;border:1px solid #ddd;border-radius:8px;overflow:hidden;text-align:center;box-shadow:0 2px 5px rgba(0,0,0,0.1);}
    .card img {width:100%;height:180px;object-fit:cover;transition:transform 0.6s ease;}
    .card img:hover {transform:scale(1.05);}
    .card h3 {color:#002060;margin:15px 0 10px;}
    .card p {padding:0 15px 15px;font-size:14px;color:#555;}
    .card a {display:inline-block;margin-bottom:20px;padding:8px 20px;border:1px solid #002060;color:#002060;text-decoration:none;border-radius:4px;transition:all 0.3s ease;}
    .card a:hover {background:#002060;color:#fff;box-shadow:0 0 10px rgba(0,32,96,0.5);}

    footer {background:#002060;color:#fff;text-align:center;padding:20px;margin-top:40px;}
    footer .icons {margin:10px 0;}
    footer img {height:25px;margin:0 5px;vertical-align:middle;}

    /* Scroll Animations */
    .scroll-fade {opacity:0; transform:translateY(50px); transition:all 1.2s ease-out;}
    .scroll-fade.show {opacity:1; transform:translateY(0);}
    .scroll-left {opacity:0; transform:translateX(-80px); transition:all 1.2s ease-out;}
    .scroll-left.show {opacity:1; transform:translateX(0);}
    .scroll-right {opacity:0; transform:translateX(80px); transition:all 1.2s ease-out;}
    .scroll-right.show {opacity:1; transform:translateX(0);}
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <!-- Hero -->
  <section class="hero scroll-fade">
    <div class="booking-form">
      <h3>Book Your Stay</h3>
      <form>
        <label>Check In</label>
        <input type="date" name="checkin" required>
        <label>Check Out</label>
        <input type="date" name="checkout" required>
        
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="hero-content">
      <h1>CHÀO MỪNG ĐẾN VỚI KHÁCH SẠN GRAND ELEGANCE<br>MỞ RA VỚI MỘT THẾ GIỚI SANG TRỌNG </h1>
      <a href="Datphong.php">See our rooms ></a>
    </div>
  </section>

  <!-- Unique Room Style -->
  <section class="unique-room scroll-fade">
    <div class="container">
      <div class="text">
        <h2>Phong cách </h2>
        <h3>Phòng độc đáo </h3>
        <p>Bước vào thế giới nơi mỗi căn phòng đều mang trong mình một câu chuyện riêng. Phong cách phòng độc đáo của chúng tôi không chỉ là nơi để ngủ, mà còn là một phần trong hành trình của bạn. Mỗi không gian đều được thiết kế tỉ mỉ với những chủ đề riêng biệt, từ phong cách tối giản sang trọng đến nét quyến rũ cổ điển, đảm bảo kỳ nghỉ của bạn là một trải nghiệm đáng nhớ, chứ không chỉ là một đêm. Hãy tìm một căn phòng phù hợp với bạn và biến chuyến đi của bạn thành một kỷ niệm khó quên.</p>
        <a href="#" class="read-more">Read More ></a>
      </div>
      <div class="image">
        <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/2d/13/48/45/executive-ocean-front.jpg?w=1200&h=700&s=1" alt="Room Style">
      </div>
    </div>
  </section>

  <!-- Defining Luxury Travel -->
  <section class="luxury-travel scroll-fade">
    <h2>Định nghĩa về du lịch sang trọng</h2>
    <div class="luxury-container">
      <div class="luxury-item">
        <div class="luxury-image"><img src="https://danangxanh.vn/data/images/phong-hoi-nghi-eden.jpg" alt="event"></div>
        <div class="luxury-text"><h3>Không gian sự kiện hoàn hảo </h3><p>Sự kiện hoàn hảo của bạn bắt đầu tại đây. Không gian đa năng của chúng tôi lý tưởng cho mọi dịp, từ những buổi tiệc thân mật đến các sự kiện công ty lớn. Chúng tôi cung cấp bố cục linh hoạt và công nghệ hiện đại để biến ý tưởng của bạn thành hiện thực. Đội ngũ tận tâm của chúng tôi sẽ lo liệu mọi chi tiết, đảm bảo sự kiện của bạn diễn ra suôn sẻ và đáng nhớ.</p></div>
      </div>
      <div class="luxury-item reverse">
        <div class="luxury-image"><img src="https://media.vinmic.vn/files/ngobich20030904/2022/09/06/thiet-ke-phong-khach-biet-thu-thong-tang-vinmic-10jpg-1457.jpg" alt="decor"></div>
        <div class="luxury-text"><h3>Nội thất đương đại</h3><p>Thiết kế hiện đại, tinh tế là trọng tâm của chúng tôi. Với những đường nét gọn gàng và màu sắc trang nhã, không gian của bạn sẽ luôn tràn ngập ánh sáng tự nhiên và cảm giác thoáng đãng. Nội thất được chọn lựa kỹ lưỡng để vừa tiện nghi vừa mang lại vẻ đẹp sang trọng, giúp bạn tận hưởng một kỳ nghỉ thoải mái và đầy phong cách.</p></div>
      </div>
      <div class="luxury-item">
        <div class="luxury-image"><img src="https://statics.vinpearl.com/kinh-nghiem-tu-a-z-an-o-choi-gi-o-vinpearl-condotel-riverfront-da-nang4.jpg" alt="location"></div>
        <div class="luxury-text"><h3>Vị trí Trung tâm</h3><p>Khám phá sự thuận tiện tối đa. Tọa lạc tại một vị trí đắc địa, khách sạn của chúng tôi là điểm khởi đầu lý tưởng để bạn dễ dàng tiếp cận những địa điểm nổi bật nhất của thành phố. Từ các khu trung tâm thương mại sầm uất, danh lam thắng cảnh nổi tiếng đến các nhà hàng, quán bar thời thượng, tất cả đều nằm trong tầm tay. Dù bạn đến đây vì công việc hay du lịch, vị trí trung tâm của chúng tôi sẽ giúp bạn tiết kiệm thời gian di chuyển và tận hưởng trọn vẹn mọi trải nghiệm.</p></div>
      </div>
      <div class="luxury-item reverse">
        <div class="luxury-image"><img src="https://fusionresorts.com/danang/wp-content/uploads/2024/12/Untitled-Session2394-1-scaled-e1733806470825.jpg" alt="cuisine"></div>
        <div class="luxury-text"><h3>Tinh hoa ẩm thực</h3><p>Hãy bắt đầu một hành trình khám phá vị giác tại nhà hàng của chúng tôi, nơi ẩm thực được nâng tầm thành một loại hình nghệ thuật. Đội ngũ đầu bếp tài năng luôn sẵn sàng phục vụ những món ăn tuyệt hảo, kết hợp hoàn hảo giữa hương vị truyền thống và phong cách chế biến hiện đại.

Chúng tôi cam kết sử dụng những nguyên liệu tươi ngon nhất, được tuyển chọn kỹ lưỡng để mang đến cho bạn những bữa ăn không chỉ ngon miệng mà còn giàu dinh dưỡng. Từ bữa sáng thịnh soạn, bữa trưa thư giãn đến bữa tối lãng mạn, mỗi món ăn đều là một trải nghiệm khó quên.</p></div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    const scrollElements = document.querySelectorAll(".scroll-fade, .scroll-left, .scroll-right, .booking-form, .luxury-item");
    const elementInView = (el, offset = 150) => {
      const elementTop = el.getBoundingClientRect().top;
      return elementTop <= ((window.innerHeight || document.documentElement.clientHeight) - offset);
    };
    const displayScrollElement = (element) => element.classList.add("show");
    const hideScrollElement = (element) => element.classList.remove("show");
    const handleScrollAnimation = () => {
      scrollElements.forEach((el) => {
        if (elementInView(el, 150)) {displayScrollElement(el);}
        else {hideScrollElement(el);}
      });
    };
    window.addEventListener("scroll", handleScrollAnimation);
    handleScrollAnimation();
  </script>
</body>
</html>
