<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
  
 <div class="logo">THE GRAND ELEGANCE HOTEL</div>
 <nav> <a href="index.php" class="nav-button <?= ($current_page == 'index.php') ? 'active-button' : '' ?>">Trang chủ</a>
 </nav>
 <div class="user-info">
 <?php if (isset($_SESSION['user_name'])): ?>
 <span style="margin-right: 15px; color: #ffcc00; font-weight: bold;">Chào, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
<a href="index.php?controller=logout" class="logout-button">Đăng xuất</a>


 <?php else: ?>
<a href="index.php?controller=login&action=index" class="login-button">Đăng nhập</a>

 <?php endif; ?>
 </div>
</header>

<style>
 body {
 margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 }

 header {
 background: #002060;
 color: #fff;
 padding: 15px 30px; display: flex;
 justify-content: space-between;
 align-items: center;
 position: sticky;
 top: 0;
 z-index: 999;
 }

 header .logo {
 font-size: 22px;
 font-weight: bold;
 letter-spacing: 1px;
 white-space: nowrap;
 }

 nav {
 display: flex;
 flex-wrap: nowrap;
 margin: 0 10px;
 }

 .nav-button {
 color: #fff;
 text-decoration: none;
 margin: 0 8px;
 font-weight: 500;
 transition: color 0.3s, background-color 0.3s;
 padding: 8px 12px;
 border-radius: 6px;
 white-space: nowrap;
 }
 
 .nav-button:hover {
 background-color: rgba(255, 255, 255, 0.2);
 }
  
  .active-button {
    background-color: #c77992ff;
    color: #002060;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

 .user-info {
 display: flex;
 align-items: center;
 white-space: nowrap;
 }

 .login-button, .logout-button {
 background-color: #ffcc00;
 color: #002060;
 padding: 8px 15px;
 text-decoration: none;
 border-radius: 6px;
 font-weight: bold;
 transition: background-color 0.3s, transform 0.2s;
 }

 .login-button:hover, .logout-button:hover {
 background-color: #e6b800;
 transform: translateY(-2px);
 }
</style>
