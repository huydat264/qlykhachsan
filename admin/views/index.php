
<?php
include __DIR__ . '/layouts/header.php';
require_once __DIR__ . '/../core/Auth.php';
Auth::requireLogin();

$user_role = $_SESSION['user_role'] ?? 'NHANVIEN';
$is_admin = ($user_role === 'ADMIN');
?>
<style>
/* CSS giao diện phối xanh đậm + xanh ngọc */
body {
    background-color: #f5fafd; /* nền sáng hơn chút */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(
        180deg,
        rgba(255, 255, 255, 0.9) 0%,
        rgba(225, 245, 242, 0.6) 100%
    ),
    url('https://images.pexels.com/photos/14011664/pexels-photo-14011664.jpeg')
    no-repeat center center fixed;
    background-size: cover;
    background-blend-mode: overlay;
}

/* Welcome section */
.welcome-section {
    background: linear-gradient(135deg, #002060, #0056b3, #00bfa6);
    color: #fff;
    padding: 30px 50px;
    border-radius: 18px;
    text-align: center;
    box-shadow: 0 10px 26px rgba(0, 0, 0, 0.25);
    margin-bottom: 45px;
    max-width: 850px;
    width: 100%;
    backdrop-filter: blur(6px);
}

.welcome-section h2 {
    font-size: 2.4rem;
    font-weight: bold;
    margin-bottom: 12px;
    color: #ffffff;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.35);
}

.welcome-section p {
    font-size: 1.15rem;
    margin: 0;
    color: #dff9f4; /* xanh ngọc nhạt */
}

/* Dashboard grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 28px;
    width: 100%;
    max-width: 1150px;
}

/* Dashboard card */
.dashboard-card {
    background: #ffffff;
    border: 1px solid #d4eaf3ff;
    border-radius: 14px;
    padding: 28px 20px;
    text-align: center;
    text-decoration: none;
    color: #002060;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
    border-color: #0056b3;
}

.dashboard-card i {
    font-size: 2.8rem;
    margin-bottom: 12px;
    color: #004080;
    transition: color 0.3s ease;
}

.dashboard-card:hover i {
    color: #00bfa6;
}

.dashboard-card h3 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
    color: #074261ff;
}

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<main>
    <div class="welcome-section">
        <h2>Chào mừng <?= htmlspecialchars($_SESSION['user_name']) ?> đến với Hệ thống Quản lý Khách sạn</h2>
        <p>Chọn một chức năng để bắt đầu làm việc.</p>
    </div>

    <div class="dashboard-grid">
        <a href="index.php?controller=phong&action=index" class="dashboard-card">
            <i class="fas fa-bed"></i>
            <h3>Quản lý phòng</h3>
        </a>
        <a href="index.php?controller=datphong&action=index" class="dashboard-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Quản lý đặt phòng</h3>
        </a>
        <a href="index.php?controller=dichvu&action=index" class="dashboard-card">
            <i class="fas fa-bell-concierge"></i>
            <h3>Quản lý dịch vụ</h3>
        </a>
        <a href="index.php?controller=khachhang&action=index" class="dashboard-card">
            <i class="fas fa-users"></i>
            <h3>Quản lý khách hàng</h3>
        </a>

        <a href="index.php?controller=nhanvien&action=index" class="dashboard-card">
            <i class="fas fa-user-tie"></i>
            <h3>Quản lý nhân viên</h3>
        </a>

        <?php if ($is_admin): ?>
        <a href="index.php?controller=baocao&action=index" class="dashboard-card">
            <i class="fas fa-chart-line"></i>
            <h3>Báo cáo thống kê</h3>
        </a>
        <?php endif; ?>

        <a href="index.php?controller=Chamcong&action=index
" class="dashboard-card">
            <i class="fas fa-clock"></i>
            <h3>Quản lý chấm công</h3>
        </a>
        <a href="index.php?controller=thanhtoan&action=index" class="dashboard-card">
            <i class="fas fa-credit-card"></i>
            <h3>Quản lý thanh toán</h3>
        </a>
        <a href="index.php?controller=sudungdichvu&action=index" class="dashboard-card">
            <i class="fas fa-utensils"></i>
            <h3>Quản lý sử dụng dịch vụ</h3>
        </a>

        <?php if ($is_admin): ?>
     <a href="index.php?controller=Quanlybangluong&action=index" class="dashboard-card">
    <i class="fas fa-money-check-dollar"></i>
    <h3>Quản lý bảng lương</h3>
</a>


        <?php endif; ?>
    </div>
</main>
<?php
// Gọi file footer
include __DIR__ . '/layouts/footer.php';
?>
