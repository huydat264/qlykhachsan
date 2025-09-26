<?php
// header.php
?>
<header>
  <div class="logo">THE GRAND ELEGANCE HOTEL</div>
  <nav>
    <a href="index.php">Trang chủ</a>
    <a href="phong.php">Quản lý phòng</a>
    <a href="dichvu.php">Quản lý dịch vụ</a>
    <a href="khachhang.php">Quản lý khách hàng</a>
    <a href="nhanvien.php">Quản lý nhân viên</a>
    <a href="baocao.php">Báo cáo thống kê</a>
  </nav>
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
  }

  header .logo {
    font-size: 22px;
    font-weight: bold;
    letter-spacing: 1px;
  }

  nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 12px;
    font-weight: 500;
    transition: color 0.3s;
  }

  nav a:hover {
    text-decoration: underline;
    color: #ffcc00;
  }
</style>
