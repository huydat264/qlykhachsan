<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth

// 1. ÁP DỤNG CƠ CHẾ KHÓA TRUY CẬP VÀ PHÂN QUYỀN TRANG
require_login(); // Khóa trang, yêu cầu đăng nhập
// Cho phép cả ADMIN và NHANVIEN truy cập trang này (nhưng NHANVIEN bị giới hạn chức năng bên dưới)
check_permission(['ADMIN', 'NHANVIEN']); 

// Lấy role của người dùng hiện tại để dùng trong logic kiểm tra bên dưới
$user_role = $_SESSION['user_role'] ?? 'NHANVIEN'; 
$is_admin = ($user_role === 'ADMIN');
// ----------------------------------------------------

// Cấu hình để báo cáo ngoại lệ
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Biến để lưu trạng thái thông báo
$message = '';
$error = false;

// Xử lý thêm hoặc cập nhật bản ghi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // 2. KIỂM TRA QUYỀN HẠN TRƯỚC KHI THỰC HIỆN THAO TÁC THÊM/SỬA
    if (!$is_admin) {
        // Nếu không phải ADMIN, báo lỗi và dừng
        $message = "Bạn không có quyền Thêm/Sửa chấm công!";
        $error = true;
    } else {
    // ------------------------------------------------------------------

        $id_nhanvien = $_POST['id_nhanvien'];
        $thang = $_POST['thang'];
        $nam = $_POST['nam'];
        $so_ngay_di_lam = $_POST['so_ngay_di_lam'];
        $so_ngay_nghi_co_phep = $_POST['so_ngay_nghi_co_phep'];
        $so_ngay_nghi_khong_phep = $_POST['so_ngay_nghi_khong_phep'];
        $id_chamcong = isset($_POST['id_chamcong']) ? intval($_POST['id_chamcong']) : 0;

        if ($id_chamcong > 0) {
            // Cập nhật bản ghi
            $sql = "UPDATE chamcong SET id_nhanvien = ?, thang = ?, nam = ?, so_ngay_di_lam = ?, so_ngay_nghi_co_phep = ?, so_ngay_nghi_khong_phep = ? WHERE id_chamcong = ?";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiiiiii", $id_nhanvien, $thang, $nam, $so_ngay_di_lam, $so_ngay_nghi_co_phep, $so_ngay_nghi_khong_phep, $id_chamcong);
                if ($stmt->execute()) {
                    $message = "Cập nhật chấm công thành công!";
                } else {
                    $message = "Lỗi khi cập nhật: " . $stmt->error;
                    $error = true;
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $message = "Lỗi: " . $e->getMessage();
                $error = true;
            }
        } else {
            // Thêm bản ghi mới
            $sql = "INSERT INTO chamcong (id_nhanvien, thang, nam, so_ngay_di_lam, so_ngay_nghi_co_phep, so_ngay_nghi_khong_phep) VALUES (?, ?, ?, ?, ?, ?)";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiiiii", $id_nhanvien, $thang, $nam, $so_ngay_di_lam, $so_ngay_nghi_co_phep, $so_ngay_nghi_khong_phep);
                if ($stmt->execute()) {
                    $message = "Thêm chấm công thành công!";
                } else {
                    $message = "Lỗi khi thêm: " . $stmt->error;
                    $error = true;
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $message = "Lỗi: " . $e->getMessage();
                $error = true;
            }
        }
    } // End else của if (!$is_admin)
    
    // Chuyển hướng để tránh gửi lại form khi làm mới trang
    // Chỉ chuyển hướng nếu không có lỗi hoặc lỗi do không có quyền (đã được set message)
    if (isset($_POST['id_chamcong']) && intval($_POST['id_chamcong']) > 0) {
        // Nếu là update, không chuyển hướng, hiển thị thông báo ngay
    } else if (!$error) {
        header("Location: chamcong.php?message=" . urlencode($message));
        exit();
    }
}


// Lấy thông báo từ URL (nếu có)
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $error = strpos($message, 'Lỗi') !== false || strpos($message, 'không có quyền') !== false;
}

// Lấy danh sách nhân viên để hiển thị trong form
$nhanvien_list = [];
$sql_nhanvien = "SELECT id_nhanvien, ho_ten FROM nhanvien ORDER BY ho_ten";
$result_nhanvien = $conn->query($sql_nhanvien);
if ($result_nhanvien) {
    while ($row = $result_nhanvien->fetch_assoc()) {
        $nhanvien_list[] = $row;
    }
}

// Lấy dữ liệu cho chế độ chỉnh sửa (nếu có)
$edit_data = null;
if (isset($_GET['edit'])) {
    // 3. CHỈ ADMIN MỚI ĐƯỢC LOAD DỮ LIỆU ĐỂ SỬA
    if ($is_admin) {
        $id_chamcong = intval($_GET['edit']);
        $sql_edit = "SELECT * FROM chamcong WHERE id_chamcong = ?";
        $stmt_edit = $conn->prepare($sql_edit);
        if ($stmt_edit) {
            $stmt_edit->bind_param("i", $id_chamcong);
            $stmt_edit->execute();
            $result_edit = $stmt_edit->get_result();
            if ($result_edit && $result_edit->num_rows > 0) {
                $edit_data = $result_edit->fetch_assoc();
            }
            $stmt_edit->close();
        }
    } else {
        // Nếu NHANVIEN cố gắng truy cập ?edit=
        $message = "Bạn không có quyền chỉnh sửa dữ liệu chấm công.";
        $error = true;
    }
    // ---------------------------------------------
}

// Lấy danh sách chấm công để hiển thị
$chamcong_list = [];
$sql_chamcong = "SELECT cc.*, nv.ho_ten FROM chamcong cc JOIN nhanvien nv ON cc.id_nhanvien = nv.id_nhanvien ORDER BY cc.nam DESC, cc.thang DESC, nv.ho_ten ASC";
$result_chamcong = $conn->query($sql_chamcong);
if ($result_chamcong) {
    while ($row = $result_chamcong->fetch_assoc()) {
        $chamcong_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chấm công</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide-vue@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1d4d84', // Blue
                        secondary: '#eaf3fd', // Light Blue
                        accent: '#fde047', // Yellow
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <main class="container mx-auto p-4 sm:p-8">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-4 sm:mb-0">Quản lý Chấm công</h2>
        </div>

        <?php if ($message): ?>
            <div id="alert-message" class="p-4 mb-6 rounded-lg font-medium <?= $error ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($is_admin): ?>
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8">
            <h3 class="text-xl sm:text-2xl font-semibold text-primary mb-6">
                <?= $edit_data ? 'Chỉnh Sửa Chấm Công' : 'Thêm Chấm Công Mới' ?>
            </h3>
            <form method="post" action="chamcong.php">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_chamcong" value="<?= htmlspecialchars($edit_data['id_chamcong']) ?>">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col">
                        <label for="id_nhanvien" class="mb-2 text-sm font-medium text-gray-700">Nhân viên:</label>
                        <select id="id_nhanvien" name="id_nhanvien" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary">
                            <option value="">-- Chọn nhân viên --</option>
                            <?php foreach ($nhanvien_list as $nv): ?>
                                <option value="<?= htmlspecialchars($nv['id_nhanvien']) ?>"
                                    <?= $edit_data && $edit_data['id_nhanvien'] == $nv['id_nhanvien'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nv['ho_ten']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="thang" class="mb-2 text-sm font-medium text-gray-700">Tháng:</label>
                        <input type="number" id="thang" name="thang" placeholder="Tháng" min="1" max="12" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary"
                               value="<?= $edit_data ? htmlspecialchars($edit_data['thang']) : date('m') ?>">
                    </div>
                    <div class="flex flex-col">
                        <label for="nam" class="mb-2 text-sm font-medium text-gray-700">Năm:</label>
                        <input type="number" id="nam" name="nam" placeholder="Năm" min="2000" max="2100" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary"
                               value="<?= $edit_data ? htmlspecialchars($edit_data['nam']) : date('Y') ?>">
                    </div>
                    <div class="flex flex-col">
                        <label for="so_ngay_di_lam" class="mb-2 text-sm font-medium text-gray-700">Số ngày đi làm:</label>
                        <input type="number" id="so_ngay_di_lam" name="so_ngay_di_lam" placeholder="Số ngày đi làm" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary"
                               value="<?= $edit_data ? htmlspecialchars($edit_data['so_ngay_di_lam']) : '' ?>">
                    </div>
                    <div class="flex flex-col">
                        <label for="so_ngay_nghi_co_phep" class="mb-2 text-sm font-medium text-gray-700">Số ngày nghỉ có phép:</label>
                        <input type="number" id="so_ngay_nghi_co_phep" name="so_ngay_nghi_co_phep" placeholder="Số ngày nghỉ có phép" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary"
                               value="<?= $edit_data ? htmlspecialchars($edit_data['so_ngay_nghi_co_phep']) : '' ?>">
                    </div>
                    <div class="flex flex-col">
                        <label for="so_ngay_nghi_khong_phep" class="mb-2 text-sm font-medium text-gray-700">Số ngày nghỉ không phép:</label>
                        <input type="number" id="so_ngay_nghi_khong_phep" name="so_ngay_nghi_khong_phep" placeholder="Số ngày nghỉ không phép" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-primary focus:border-primary"
                               value="<?= $edit_data ? htmlspecialchars($edit_data['so_ngay_nghi_khong_phep']) : '' ?>">
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-4 mt-6">
                    <button type="submit" name="submit"
                            class="px-6 py-3 rounded-full text-white font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 shadow-md
                                     bg-primary hover:bg-indigo-700">
                        <?= $edit_data ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <?php if ($edit_data): ?>
                    <a href="chamcong.php"
                        class="px-6 py-3 rounded-full text-primary bg-gray-200 font-semibold transition-all duration-300 ease-in-out transform hover:scale-105 shadow-md hover:bg-gray-300">
                        Hủy
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-8 border-l-4 border-yellow-500">
            <h3 class="text-xl sm:text-2xl font-semibold text-yellow-600 mb-4">Thông báo Quyền hạn</h3>
            <p class="text-gray-700">Chức năng **Thêm** và **Chỉnh sửa** dữ liệu chấm công chỉ dành cho tài khoản có quyền **ADMIN**.</p>
        </div>
        <?php endif; ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-4 font-semibold text-left rounded-tl-xl">STT</th>
                            <th class="p-4 font-semibold text-left">Nhân viên</th>
                            <th class="p-4 font-semibold text-left">Tháng</th>
                            <th class="p-4 font-semibold text-left">Năm</th>
                            <th class="p-4 font-semibold text-left">Số ngày đi làm</th>
                            <th class="p-4 font-semibold text-left">Nghỉ có phép</th>
                            <th class="p-4 font-semibold text-left">Nghỉ không phép</th>
                            <th class="p-4 font-semibold text-left rounded-tr-xl">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($chamcong_list)): ?>
                            <?php $stt = 1; ?>
                            <?php foreach ($chamcong_list as $cc): ?>
                                <tr class="border-b last:border-b-0 even:bg-gray-50 hover:bg-secondary transition-colors duration-200">
                                    <td class="p-4 text-gray-800"><?= $stt++ ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['ho_ten']) ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['thang']) ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['nam']) ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_di_lam']) ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_nghi_co_phep']) ?></td>
                                    <td class="p-4 text-gray-800"><?= htmlspecialchars($cc['so_ngay_nghi_khong_phep']) ?></td>
                                    <td class="p-4 space-x-2 flex items-center">
                                        <?php if ($is_admin): ?>
                                            <a href="chamcong.php?edit=<?= htmlspecialchars($cc['id_chamcong']) ?>" title="Sửa"
                                               class="text-blue-500 hover:text-blue-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a2.121 2.121 0 0 1 3 3L19 7 17 5l1.375-1.375z"/></svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm">(Xem)</span>
                                        <?php endif; ?>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">Chưa có dữ liệu chấm công nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // Hide alert message after a few seconds
        const alertMessage = document.getElementById('alert-message');
        if (alertMessage) {
            setTimeout(() => {
                alertMessage.style.transition = 'opacity 0.5s ease-out';
                alertMessage.style.opacity = '0';
                setTimeout(() => {
                    alertMessage.remove();
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>

<?php include 'footer.php'; ?>