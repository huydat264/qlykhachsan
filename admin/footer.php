<?php
// footer.php
?>
<footer>
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