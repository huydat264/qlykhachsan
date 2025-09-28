<?php
// footer.php
?>
<footer>
  <div class="footer-container">
    
    <!-- Cột 1: Thông tin khách sạn -->
    <div class="footer-col">
      <h3>THE GRAND ELEGANCE HOTEL</h3>
      <p>📍 The Grand Elegance Hotel, 32 Lò Sũ, Hoàn Kiếm, Hà Nội</p>
      <p>📞 +84 24 3935 1632</p>
      <p>✉  info@theelghotel.com</p>
    </div>

    <!-- Cột 2: Liên kết nhanh -->
    <div class="footer-col">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="/doanqlks/user/Viewsuser/trangchu.php"> Trang chủ </a></li>
        <li><a href="/doanqlks/user/Viewsuser/Datphong.php">Đặt phòng </a></li>
        <li><a href="/doanqlks/user/Viewsuser/dichvu.php">Dịch Vụ </a></li>
        <li><a href="/doanqlks/user/Viewsuser/Lienlac.php">Spa & Liên lạc </a></li>
  
      </ul>
    </div>

    <!-- Cột 3: Mạng xã hội -->
    <div class="footer-col">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="#"><i>🌐</i></a>
        <a href="#"><i>🐦</i></a>
        <a href="#"><i>📷</i></a>
        <a href="#"><i>📌</i></a>
      </div>
    </div>

    <!-- Cột 4: Thanh toán -->
    <div class="footer-col">
      <h4>We Accept</h4>
      <div class="payment-icons">
        <img src="https://i.ibb.co/4JybxQJ/paypal.png" alt="PayPal">
        <img src="https://i.ibb.co/nQGh0pB/visa.png" alt="Visa">
        <img src="https://i.ibb.co/YbJX2wv/mastercard.png" alt="MasterCard">
        <img src="https://i.ibb.co/kQ2wvPX/amex.png" alt="Amex">
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© 2025 THE GRAND ELEGANCE HOTEL. All rights reserved.</p>
  </div>
</footer>

<style>
  footer {
    background: #001540;
    color: #eee;
    font-family: "Segoe UI", sans-serif;
    padding: 40px 60px 20px;
  }

  .footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 30px;
    margin-bottom: 20px;
  }

  .footer-col h3, 
  .footer-col h4 {
    color: #ffcc00;
    margin-bottom: 15px;
  }

  .footer-col p {
    margin: 5px 0;
    font-size: 14px;
  }

  .footer-col ul {
    list-style: none;
    padding: 0;
  }

  .footer-col ul li {
    margin: 8px 0;
  }

  .footer-col ul li a {
    color: #eee;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
  }

  .footer-col ul li a:hover {
    color: #ffcc00;
  }

  .social-icons a {
    font-size: 20px;
    margin-right: 10px;
    color: #eee;
    transition: transform 0.3s, color 0.3s;
  }

  .social-icons a:hover {
    transform: scale(1.2);
    color: #ffcc00;
  }

  .payment-icons img {
    height: 30px;
    margin-right: 8px;
    filter: brightness(0.9);
    transition: transform 0.3s;
  }

  .payment-icons img:hover {
    transform: scale(1.1);
    filter: brightness(1.2);
  }

  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    padding-top: 10px;
    font-size: 13px;
    color: #bbb;
  }
</style>
