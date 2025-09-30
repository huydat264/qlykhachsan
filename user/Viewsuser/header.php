  <?php
  
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  ?>

  <header>
    <div class="logo">THE GRAND ELEGANCE HOTEL</div>
    <nav>
      <a href="/doanqlks/user/Viewsuser/trangchu.php">Trang Chủ</a>
      <a href="/doanqlks/user/Viewsuser/Datphong.php">Đặt Phòng</a>
      <a href="../Controlleruser/DichVuController.php?action=list">Dịch vụ</a>

      <a href="/doanqlks/user/Viewsuser/Lienlac.php">Liên Lạc</a>


    </nav>
    <div class="auth-buttons">
      <?php if (isset($_SESSION["user"])): ?>
        <span class="welcome">👤 <?php echo htmlspecialchars($_SESSION["user"]["username"]); ?></span>
        <a href="../Controlleruser/XemPhongDaDatController.php" class="btn-auth">Phòng đã đặt</a>
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
      <?php else: ?>
        <a href="register.php" class="btn-auth">Đăng ký</a>
        <a href="login.php" class="btn-auth login">Đăng nhập</a>
      <?php endif; ?>
    </div>
  </header>

  <style>
    header {
      background: #002060;
      color: #fff;
      padding: 15px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 999;
      animation: slideDown 0.8s ease;
    }

    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    header .logo {
      font-size: 22px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    nav a {
      position: relative;
      color: #fff;
      text-decoration: none;
      margin: 0 12px;
      font-weight: 500;
      transition: color 0.3s;
      padding-bottom: 5px;
    }

    nav a::after {
      content: "";
      position: absolute;
      width: 0;
      height: 2px;
      left: 0;
      bottom: 0;
      background: #ffcc00;
      transition: width 0.3s ease;
    }

    nav a:hover {
      color: #ffcc00;
      text-decoration: none;
    }

    nav a:hover::after {
      width: 100%;
    }

    .auth-buttons {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .btn-auth {
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      border: 2px solid #fff;
      color: #fff;
      transition: all 0.3s ease;
    }

    .btn-auth:hover {
      background: #fff;
      color: #002060;
    }

    .btn-auth.login {
      background: #ffcc00;
      border-color: #ffcc00;
      color: #002060;
    }

    .btn-auth.login:hover {
      background: #002060;
      color: #ffcc00;
      border-color: #ffcc00;
    }

    .welcome {
      font-weight: bold;
      color: #ffcc00;
    }

    .logout-btn {
      display: inline-block;
      padding: 10px 18px;
      background: #c0392b;   /* đỏ */
      color: #fff;
      font-size: 14px;
      font-weight: bold;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-left: 15px;
    }

    .logout-btn:hover {
      background: #922b21;   /* đỏ đậm khi hover */
      transform: scale(1.05);
    }
  </style>
