<?php
// Bắt buộc đăng nhập và quyền ADMIN
include __DIR__ . '/layouts/header.php';

Auth::requireLogin();
Auth::checkPermission(['ADMIN']);


// Nếu controller chưa tách sẵn, ta tách ngay tại view
$khachHangGenders = array_column($khachHangByGender ?? [], 'gioi_tinh');
$khachHangCounts  = array_column($khachHangByGender ?? [], 'total');

$nhanVienRoles    = array_column($nhanVienByRole ?? [], 'chuc_vu');
$nhanVienCounts   = array_column($nhanVienByRole ?? [], 'total');
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Thống Kê</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<div class="flex-grow w-full">
<main class="w-full max-w-7xl mx-auto px-4 py-8">

    <!-- Tiêu đề + nút lọc -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-4xl font-extrabold text-blue-800 tracking-tight leading-tight mb-4 md:mb-0">Báo Cáo Thống Kê</h1>
        <div class="flex flex-wrap gap-2 sm:gap-4">
            <a href="index.php?controller=baocao&action=index&filter=all"
               class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
               <?= $filter=='all'?'bg-blue-600 text-white shadow-md':'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
               Toàn thời gian</a>

            <a href="index.php?controller=baocao&action=index&filter=month"
               class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
               <?= $filter=='month'?'bg-blue-600 text-white shadow-md':'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
               Theo tháng</a>

            <a href="index.php?controller=baocao&action=index&filter=quarter"
               class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
               <?= $filter=='quarter'?'bg-blue-600 text-white shadow-md':'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
               Theo quý</a>

            <a href="index.php?controller=baocao&action=index&filter=year"
               class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
               <?= $filter=='year'?'bg-blue-600 text-white shadow-md':'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
               Theo năm</a>
        </div>
    </div>

    <!-- Các thẻ thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center hover:scale-105 transition-transform">
            <div class="bg-blue-100 text-blue-500 rounded-full p-3 mb-2"><i class="ri-door-line text-2xl"></i></div>
            <h3 class="text-lg font-semibold text-gray-600">Tổng số phòng</h3>
            <p class="text-4xl font-bold text-blue-800 mt-1"><?= $totalPhong ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center hover:scale-105 transition-transform">
            <div class="bg-green-100 text-green-500 rounded-full p-3 mb-2"><i class="ri-user-line text-2xl"></i></div>
            <h3 class="text-lg font-semibold text-gray-600">Tổng số khách hàng</h3>
            <p class="text-4xl font-bold text-green-700 mt-1"><?= $totalKhachHang ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center hover:scale-105 transition-transform">
            <div class="bg-purple-100 text-purple-500 rounded-full p-3 mb-2"><i class="ri-briefcase-line text-2xl"></i></div>
            <h3 class="text-lg font-semibold text-gray-600">Tổng số dịch vụ</h3>
            <p class="text-4xl font-bold text-purple-700 mt-1"><?= $totalDichVu ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center hover:scale-105 transition-transform">
            <div class="bg-yellow-100 text-yellow-500 rounded-full p-3 mb-2"><i class="ri-team-line text-2xl"></i></div>
            <h3 class="text-lg font-semibold text-gray-600">Tổng số nhân viên</h3>
            <p class="text-4xl font-bold text-yellow-700 mt-1"><?= $totalNhanVien ?></p>
        </div>
        <div class="bg-blue-800 text-white p-6 rounded-2xl shadow-lg flex flex-col items-center text-center hover:scale-105 transition-transform">
            <div class="bg-white bg-opacity-20 rounded-full p-3 mb-2"><i class="ri-wallet-line text-2xl"></i></div>
            <h3 class="text-lg font-semibold">Tổng doanh thu thực tế</h3>
            <p class="text-3xl lg:text-4xl font-bold mt-1"><?= number_format($doanhThuThucTe,0,",",".") ?> VND</p>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ trạng thái phòng</h3>
            <div class="w-full max-w-sm"><canvas id="roomStatusChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ khách hàng theo giới tính</h3>
            <div class="w-full max-w-sm"><canvas id="khachHangChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h3 class="text-2xl font-bold mb-4 text-gray-700">Doanh thu theo Dịch vụ</h3>
            <div class="w-full max-w-sm"><canvas id="dichVuChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ nhân viên theo chức vụ</h3>
            <div class="w-full max-w-sm"><canvas id="nhanVienChart"></canvas></div>
        </div>
    </div>

    <!-- Bảng doanh thu dịch vụ -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
        <h3 class="text-2xl font-bold text-gray-700 text-center mb-6">Thống kê doanh thu theo Dịch vụ</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tên Dịch Vụ</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tổng Doanh Thu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($dichVuRevenueData)): ?>
                    <?php foreach ($dichVuRevenueData as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($item['ten_dich_vu']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($item['total_revenue'],0,',','.') ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2" class="px-6 py-4 text-sm text-gray-500">Chưa có dữ liệu.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bảng khách hàng VIP -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
        <h3 class="text-2xl font-bold text-gray-700 text-center mb-6">Top 3 Khách hàng VIP</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Hạng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tên Khách Hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tổng Chi Tiêu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($vipKhachHangData)): $rank=1; ?>
                    <?php foreach ($vipKhachHangData as $vip): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $rank++ ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($vip['ho_ten']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= number_format($vip['total_spent'],0,',','.') ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="px-6 py-4 text-sm text-gray-500">Chưa có đủ dữ liệu.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    Chart.register(ChartDataLabels);
    const bg = ['#4CAF50','#FFC107','#2196F3','#F44336','#9C27B0','#673AB7','#FF9800','#00BCD4','#03A9F4','#FF5722','#607D8B'];

    function pie(id,labels,data){
        new Chart(document.getElementById(id), {
            type: 'doughnut',
            data: {labels: labels, datasets: [{data: data, backgroundColor: bg, borderWidth:0}]},
            options: {
                plugins: {
                    legend:{position:'bottom'},
                    datalabels:{
                        color:'#fff',font:{weight:'bold'},formatter:(v,c)=>{
                            const total=c.dataset.data.reduce((a,b)=>a+b,0);
                            return total?((v/total*100).toFixed(1)+'%'):'';
                        },
                        display:(ctx)=>ctx.dataset.data[ctx.dataIndex]>0
                    }
                }
            }
        });
    }

    pie('roomStatusChart',['Trống','Đã đặt','Bảo trì'],[<?= $phongTrong ?>,<?= $phongDangDat ?>,<?= $phongBaoTri ?>]);
    pie('khachHangChart',<?= json_encode($khachHangGenders) ?>,<?= json_encode($khachHangCounts) ?>);
    pie('nhanVienChart',<?= json_encode($nhanVienRoles) ?>,<?= json_encode($nhanVienCounts) ?>);
    pie('dichVuChart',<?= json_encode(array_column($dichVuRevenueData,'ten_dich_vu')) ?>,<?= json_encode(array_column($dichVuRevenueData,'total_revenue')) ?>);
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>
