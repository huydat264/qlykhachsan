<?php
// Gọi file header
include 'header.php';
// THÊM: Gọi file auth và kiểm tra đăng nhập
include 'auth.php'; 
require_login(); 

// THÊM: Lấy role của người dùng hiện tại
$user_role = $_SESSION['user_role'] ?? 'NHANVIEN'; 
$is_admin = ($user_role === 'ADMIN');
?>

<style>
/* CSS cho giao diện mới */
body {
    background-color: #f0f2f5;
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
    background: linear-gradient(180deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 100%), url('https://png.pngtree.com/background/20240112/original/pngtree-timeless-design-3d-rendering-of-a-hotel-lobby-with-classic-styling-picture-image_7231679.jpg') no-repeat center center fixed;
    background-size: cover;
    background-blend-mode: overlay;
}

.welcome-section {
    background-color: rgba(0, 32, 96, 0.85);
    color: #fff;
    padding: 30px 50px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    margin-bottom: 40px;
    max-width: 800px;
    width: 100%;
    backdrop-filter: blur(5px);
}

.welcome-section h2 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.welcome-section p {
    font-size: 1.2rem;
    margin: 0;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    width: 100%;
    max-width: 1100px;
}

.dashboard-card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    text-decoration: none;
    color: #002060;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.dashboard-card i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #0056b3;
}

.dashboard-card h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: bold;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<main>
    <div class="welcome-section">
        <h2>Chào mừng đến với Hệ thống Quản lý Khách sạn</h2>
        <p>Chọn một chức năng để bắt đầu làm việc.</p>
    </div>

    <div class="dashboard-grid">
        <a href="phong.php" class="dashboard-card">
            <i class="fas fa-bed"></i>
            <h3>Quản lý phòng</h3>
        </a>
        <a href="datphong.php" class="dashboard-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Quản lý đặt phòng</h3>
        </a>
        <a href="dichvu.php" class="dashboard-card">
            <i class="fas fa-bell-concierge"></i>
            <h3>Quản lý dịch vụ</h3>
        </a>
        <a href="khachhang.php" class="dashboard-card">
            <i class="fas fa-users"></i>
            <h3>Quản lý khách hàng</h3>
        </a>
        
        <?php if ($is_admin): ?>
        <a href="nhanvien.php" class="dashboard-card">
            <i class="fas fa-user-tie"></i>
            <h3>Quản lý nhân viên</h3>
        </a>
        <?php endif; ?>

        <?php if ($is_admin): ?>
        <a href="baocao.php" class="dashboard-card">
            <i class="fas fa-chart-line"></i>
            <h3>Báo cáo thống kê</h3>
        </a>
        <?php endif; ?>
        
        <a href="chamcong.php" class="dashboard-card">
            <i class="fas fa-clock"></i>
            <h3>Quản lý chấm công</h3>
        </a>
        <a href="thanhtoan.php" class="dashboard-card">
            <i class="fas fa-credit-card"></i>
            <h3>Quản lý thanh toán</h3>
        </a>
        <a href="sudungdichvu.php" class="dashboard-card">
            <i class="fas fa-utensils"></i>
            <h3>Quản lý sử dụng dịch vụ</h3>
        </a>

        <?php if ($is_admin): ?>
        <a href="quanlybangluong.php" class="dashboard-card">
            <i class="fas fa-money-check-dollar"></i>
            <h3>Quản lý bảng lương</h3>
        </a>
        <?php endif; ?>
    </div>
</main>

<?php
// Gọi file footer
include 'footer.php';
?>