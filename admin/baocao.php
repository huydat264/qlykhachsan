<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN']); // Chỉ ADMIN mới được vào

// Xử lý lọc dữ liệu theo tháng, quý, năm
$filter = 'all';
$query_date_filter = '';

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'month':
            $query_date_filter = "WHERE MONTH(hd.ngay_xuat) = MONTH(CURRENT_DATE()) AND YEAR(hd.ngay_xuat) = YEAR(CURRENT_DATE())";
            break;
        case 'quarter':
            $query_date_filter = "WHERE QUARTER(hd.ngay_xuat) = QUARTER(CURRENT_DATE()) AND YEAR(hd.ngay_xuat) = YEAR(CURRENT_DATE())";
            break;
        case 'year':
            $query_date_filter = "WHERE YEAR(hd.ngay_xuat) = YEAR(CURRENT_DATE())";
            break;
    }
}

// Tổng số phòng
$totalPhongResult = $conn->query("SELECT COUNT(*) as total FROM phong");
$totalPhong = $totalPhongResult ? $totalPhongResult->fetch_assoc()['total'] : 0;

// Trạng thái phòng để tạo biểu đồ
$phongTrongResult = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Trống'");
$phongTrong = $phongTrongResult ? $phongTrongResult->fetch_assoc()['total'] : 0;
$phongDangDatResult = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Đã đặt'");
$phongDangDat = $phongDangDatResult ? $phongDangDatResult->fetch_assoc()['total'] : 0;
$phongBaoTriResult = $conn->query("SELECT COUNT(*) as total FROM phong WHERE trang_thai='Bảo trì'");
$phongBaoTri = $phongBaoTriResult ? $phongBaoTriResult->fetch_assoc()['total'] : 0;

// Doanh thu thực tế (dựa trên bảng hoadon)
$doanhThuThucTeResult = $conn->query("SELECT SUM(tong_tien) as total FROM hoadon hd " . $query_date_filter);
$doanhThuThucTe = $doanhThuThucTeResult ? $doanhThuThucTeResult->fetch_assoc()['total'] : 0;
if (!$doanhThuThucTe) $doanhThuThucTe = 0;

// Thống kê nhân viên theo chức vụ
$nhanVienByRole = $conn->query("SELECT chuc_vu, COUNT(*) as total FROM nhanvien GROUP BY chuc_vu");
$nhanVienRoles = [];
$nhanVienCounts = [];
if ($nhanVienByRole) {
    while ($row = $nhanVienByRole->fetch_assoc()) {
        $nhanVienRoles[] = $row['chuc_vu'];
        $nhanVienCounts[] = $row['total'];
    }
}

// Thống kê khách hàng theo giới tính
$khachHangByGender = $conn->query("SELECT gioi_tinh, COUNT(*) as total FROM khachhang GROUP BY gioi_tinh");
$khachHangGenders = [];
$khachHangCounts = [];
if ($khachHangByGender) {
    while ($row = $khachHangByGender->fetch_assoc()) {
        $khachHangGenders[] = $row['gioi_tinh'];
        $khachHangCounts[] = $row['total'];
    }
}

// Thống kê dịch vụ đã sử dụng (Doanh thu theo dịch vụ)
$dichVuRevenueQuery = "SELECT dv.ten_dich_vu, SUM(sdv.thanh_tien) AS total_revenue
                           FROM sudungdichvu sdv
                           JOIN dichvu dv ON sdv.id_dichvu = dv.id_dichvu
                           GROUP BY dv.ten_dich_vu
                           ORDER BY total_revenue DESC";
$dichVuRevenueResult = $conn->query($dichVuRevenueQuery);
$dichVuRevenueData = [];
if ($dichVuRevenueResult) {
    while ($row = $dichVuRevenueResult->fetch_assoc()) {
        $dichVuRevenueData[] = $row;
    }
}

// Thống kê 3 khách hàng VIP nhất (dựa trên tổng chi tiêu)
$vipKhachHangQuery = "SELECT kh.ho_ten, SUM(hd.tong_tien) AS total_spent
                           FROM hoadon hd
                           JOIN datphong dp ON hd.id_datphong = dp.id_datphong
                           JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
                           GROUP BY kh.id_khachhang, kh.ho_ten
                           ORDER BY total_spent DESC
                           LIMIT 3";
$vipKhachHangResult = $conn->query($vipKhachHangQuery);
$vipKhachHangData = [];
if ($vipKhachHangResult) {
    while ($row = $vipKhachHangResult->fetch_assoc()) {
        $vipKhachHangData[] = $row;
    }
}

// Các thống kê khác
$totalKhachHangResult = $conn->query("SELECT COUNT(*) as total FROM khachhang");
$totalKhachHang = $totalKhachHangResult ? $totalKhachHangResult->fetch_assoc()['total'] : 0;
$totalDichVuResult = $conn->query("SELECT COUNT(*) as total FROM dichvu");
$totalDichVu = $totalDichVuResult ? $totalDichVuResult->fetch_assoc()['total'] : 0;
$totalNhanVienResult = $conn->query("SELECT COUNT(*) as total FROM nhanvien");
$totalNhanVien = $totalNhanVienResult ? $totalNhanVienResult->fetch_assoc()['total'] : 0;

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Thống Kê</title>
    <!-- Thư viện Chart.js và plugin Datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remixicon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<div class="flex-grow w-full">
    <main class="w-full max-w-7xl mx-auto px-4 py-8">
        
        <!-- Tiêu đề và Tùy chọn lọc báo cáo -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-800 tracking-tight leading-tight mb-4 md:mb-0">Báo Cáo Thống Kê</h1>
            <div class="flex flex-wrap gap-2 sm:gap-4">
                <a href="?filter=all" class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
                    <?= $filter == 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">Toàn thời gian</a>
                <a href="?filter=month" class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
                    <?= $filter == 'month' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">Theo tháng</a>
                <a href="?filter=quarter" class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
                    <?= $filter == 'quarter' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">Theo quý</a>
                <a href="?filter=year" class="px-5 py-2 rounded-full font-medium transition-transform duration-200 hover:scale-105
                    <?= $filter == 'year' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">Theo năm</a>
            </div>
        </div>

        <!-- Các thẻ thống kê chính -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 flex flex-col items-center justify-center text-center">
                <div class="bg-blue-100 text-blue-500 rounded-full p-3 mb-2">
                    <i class="ri-door-line text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600">Tổng số phòng</h3>
                <p class="text-4xl font-bold text-blue-800 mt-1"><?= $totalPhong ?></p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 flex flex-col items-center justify-center text-center">
                <div class="bg-green-100 text-green-500 rounded-full p-3 mb-2">
                    <i class="ri-user-line text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600">Tổng số khách hàng</h3>
                <p class="text-4xl font-bold text-green-700 mt-1"><?= $totalKhachHang ?></p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 flex flex-col items-center justify-center text-center">
                <div class="bg-purple-100 text-purple-500 rounded-full p-3 mb-2">
                    <i class="ri-briefcase-line text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600">Tổng số dịch vụ</h3>
                <p class="text-4xl font-bold text-purple-700 mt-1"><?= $totalDichVu ?></p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 flex flex-col items-center justify-center text-center">
                <div class="bg-yellow-100 text-yellow-500 rounded-full p-3 mb-2">
                    <i class="ri-team-line text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600">Tổng số nhân viên</h3>
                <p class="text-4xl font-bold text-yellow-700 mt-1"><?= $totalNhanVien ?></p>
            </div>
            
            <div class="bg-blue-800 text-white p-6 rounded-2xl shadow-lg transition-transform duration-300 hover:scale-105 flex flex-col items-center justify-center col-span-1 lg:col-span-1 text-center">
                <div class="bg-white bg-opacity-20 rounded-full p-3 mb-2">
                    <i class="ri-wallet-line text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold">Tổng doanh thu thực tế</h3>
                <p class="text-3xl lg:text-4xl font-bold mt-1"><?= number_format($doanhThuThucTe, 0, ",", ".") ?> VND</p>
            </div>
        </div>
        
        <!-- Phần biểu đồ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ trạng thái phòng</h3>
                <div class="w-full max-w-sm h-auto">
                    <canvas id="roomStatusChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ khách hàng theo giới tính</h3>
                <div class="w-full max-w-sm h-auto">
                    <canvas id="khachHangChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Thống kê doanh thu theo Dịch vụ</h3>
                <div class="w-full max-w-sm h-auto">
                    <canvas id="dichVuChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
                <h3 class="text-2xl font-bold mb-4 text-gray-700">Tỷ lệ nhân viên theo chức vụ</h3>
                <div class="w-full max-w-sm h-auto">
                    <canvas id="nhanVienChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê doanh thu dịch vụ -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h3 class="text-2xl font-bold text-gray-700 text-center mb-6">Thống kê doanh thu theo Dịch vụ</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên Dịch Vụ</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tổng Doanh Thu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($dichVuRevenueData)): ?>
                            <?php foreach ($dichVuRevenueData as $item): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($item['ten_dich_vu']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['total_revenue'], 0, ',', '.') ?> VND</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="2">Chưa có dịch vụ nào được sử dụng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Bảng khách hàng VIP -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
            <h3 class="text-2xl font-bold text-gray-700 text-center mb-6">Top 3 Khách hàng VIP (Tổng chi tiêu)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hạng</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên Khách Hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tổng Chi Tiêu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($vipKhachHangData)): ?>
                            <?php $rank = 1; ?>
                            <?php foreach ($vipKhachHangData as $vip): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $rank++ ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($vip['ho_ten']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($vip['total_spent'], 0, ',', '.') ?> VND</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="3">Chưa có đủ dữ liệu để xếp hạng khách hàng VIP.</td>
                            </tr>
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

        const backgroundColors = [
            '#4CAF50', '#FFC107', '#2196F3', '#F44336', '#9C27B0', '#673AB7',
            '#FF9800', '#00BCD4', '#03A9F4', '#FF5722', '#607D8B'
        ];

        function createPieChart(elementId, labels, data) {
            const ctx = document.getElementById(elementId).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', // Thay đổi từ pie sang doughnut để trông hiện đại hơn
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        hoverOffset: 4,
                        borderWidth: 0 // Loại bỏ đường viền
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14,
                                    family: 'Inter'
                                },
                                boxWidth: 20,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (context.parsed !== null) {
                                        const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                        const percentage = (context.parsed / total * 100).toFixed(1);
                                        label = context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                    return label;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: (value, context) => {
                                const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = (value / total * 100).toFixed(1);
                                return percentage > 0 ? percentage + '%' : ''; // Chỉ hiển thị % khi > 0
                            },
                            display: function(context) {
                                // Hiển thị datalabels chỉ khi giá trị > 0
                                return context.dataset.data[context.dataIndex] > 0;
                            }
                        }
                    }
                },
            });
        }

        // Dữ liệu PHP được truyền vào JavaScript
        const phongData = {
            labels: [
                'Trống',
                'Đã đặt',
                'Bảo trì'
            ],
            data: [
                <?= $phongTrong ?>,
                <?= $phongDangDat ?>,
                <?= $phongBaoTri ?>
            ]
        };

        const khachHangData = {
            labels: JSON.parse('<?= json_encode($khachHangGenders) ?>'),
            data: JSON.parse('<?= json_encode($khachHangCounts) ?>')
        };
        
        const nhanVienData = {
            labels: JSON.parse('<?= json_encode($nhanVienRoles) ?>'),
            data: JSON.parse('<?= json_encode($nhanVienCounts) ?>')
        };
        
        const dichVuRevenueLabels = [<?php foreach ($dichVuRevenueData as $item) echo "'" . addslashes($item['ten_dich_vu']) . "',"; ?>];
        const dichVuRevenueValues = [<?php foreach ($dichVuRevenueData as $item) echo $item['total_revenue'] . ","; ?>];
        createPieChart('roomStatusChart', phongData.labels, phongData.data);
        createPieChart('khachHangChart', khachHangData.labels, khachHangData.data);
        createPieChart('nhanVienChart', nhanVienData.labels, nhanVienData.data);
        createPieChart('dichVuChart', dichVuRevenueLabels, dichVuRevenueValues);
    });
</script>

</body>
</html>

<?php include 'footer.php'; ?>
