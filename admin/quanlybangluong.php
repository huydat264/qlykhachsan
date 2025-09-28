<?php
include 'header.php';
include 'db.php'; // File này chứa biến $conn để kết nối CSDL
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình để báo cáo ngoại lệ
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Biến để lưu trạng thái thông báo
$message = '';
$error = false;

// Lấy danh sách nhân viên để hiển thị trong form và lưu vào JavaScript
$nhanvien_list = [];
// Lấy thêm Lương cơ bản và Chức vụ để hiển thị ngay trong form (JS sẽ xử lý)
$sql_nhanvien = "SELECT id_nhanvien, ho_ten, luong_co_ban, chuc_vu FROM nhanvien ORDER BY ho_ten";
$result_nhanvien = $conn->query($sql_nhanvien);
if ($result_nhanvien) {
    while ($row = $result_nhanvien->fetch_assoc()) {
        $nhanvien_list[] = $row;
    }
}

// =========================================================================
// Xử lý thêm hoặc cập nhật bản ghi (LOGIC CHÍNH ĐÃ ĐƯỢC CẬP NHẬT)
// =========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Lấy ID nhân viên từ form (dùng $_POST['id_nhanvien'] do đã thêm input hidden khi edit)
    $id_nhanvien = intval($_POST['id_nhanvien']);
    $thang = intval($_POST['thang']);
    $nam = intval($_POST['nam']);
    $thuong = floatval($_POST['thuong']);
    $phat = floatval($_POST['phat']);
    $id_bangluong = isset($_POST['id_bangluong']) ? intval($_POST['id_bangluong']) : 0;

    // 1. Lấy Lương cơ bản từ bảng nhanvien
    $luong_co_ban = 0;
    $sql_luongcb = "SELECT luong_co_ban FROM nhanvien WHERE id_nhanvien = ?";
    $stmt_luongcb = $conn->prepare($sql_luongcb);
    if ($stmt_luongcb) {
        $stmt_luongcb->bind_param("i", $id_nhanvien);
        $stmt_luongcb->execute();
        $result_luongcb = $stmt_luongcb->get_result();
        $luong_co_ban = ($result_luongcb->num_rows > 0) ? $result_luongcb->fetch_assoc()['luong_co_ban'] : 0;
        $stmt_luongcb->close();
    }

    // 2. Lấy Số ngày công từ bảng chamcong
    $so_ngay_cong = 0;
    $sql_cong = "SELECT so_ngay_di_lam FROM chamcong WHERE id_nhanvien = ? AND thang = ? AND nam = ?";
    $stmt_cong = $conn->prepare($sql_cong);
    if ($stmt_cong) {
        $stmt_cong->bind_param("iii", $id_nhanvien, $thang, $nam);
        $stmt_cong->execute();
        $result_cong = $stmt_cong->get_result();
        $so_ngay_cong = ($result_cong->num_rows > 0) ? $result_cong->fetch_assoc()['so_ngay_di_lam'] : 0;
        $stmt_cong->close();
    }


    // 3. Tính Tổng lương dựa trên công thức Lương theo ngày * Ngày công + Thưởng - Phạt
    $tong_luong = 0;
    if ($luong_co_ban > 0 && $thang > 0 && $nam > 0) {
        // Lấy tổng số ngày trong tháng để tính lương
        $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $thang, $nam);
        
        if ($total_days_in_month > 0) {
            $luong_theo_ngay = $luong_co_ban / $total_days_in_month;
            $tong_luong = ($luong_theo_ngay * $so_ngay_cong) + $thuong - $phat;
        } else {
             // Trường hợp tháng không hợp lệ (nên có validate trước)
             $tong_luong = $thuong - $phat;
        }
    } else {
        $message = "Lỗi tính toán: Không tìm thấy Lương cơ bản hoặc tháng/năm không hợp lệ.";
        $error = true;
    }


    if (!$error) {
        if ($id_bangluong > 0) {
            // Cập nhật bản ghi
            $sql = "UPDATE bangluong SET id_nhanvien = ?, thang = ?, nam = ?, so_ngay_cong = ?, thuong = ?, phat = ?, luong_co_ban = ?, tong_luong = ? WHERE id_bangluong = ?";
            try {
                $stmt = $conn->prepare($sql);
                // Chuỗi định dạng: iiiiididi (id_nv, thang, nam, so_cong, thuong, phat, luong_cb, tong_luong, id_bl) - d: double/float cho lương
                $stmt->bind_param("iiiididdi", $id_nhanvien, $thang, $nam, $so_ngay_cong, $thuong, $phat, $luong_co_ban, $tong_luong, $id_bangluong);
                if ($stmt->execute()) {
                    $message = "Cập nhật bảng lương thành công! Tổng lương mới: " . number_format($tong_luong) . " VNĐ";
                } else {
                    $message = "Lỗi khi cập nhật: " . $stmt->error;
                    $error = true;
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $message = "Lỗi CSDL: " . $e->getMessage();
                $error = true;
            }
        } else {
            // Thêm bản ghi mới
            $sql = "INSERT INTO bangluong (id_nhanvien, thang, nam, so_ngay_cong, thuong, phat, luong_co_ban, tong_luong) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            try {
                $stmt = $conn->prepare($sql);
                // Chuỗi định dạng: iiiiidid (id_nv, thang, nam, so_cong, thuong, phat, luong_cb, tong_luong) - d: double/float cho lương
                $stmt->bind_param("iiiididd", $id_nhanvien, $thang, $nam, $so_ngay_cong, $thuong, $phat, $luong_co_ban, $tong_luong);
                if ($stmt->execute()) {
                    $message = "Thêm bảng lương thành công! Tổng lương: " . number_format($tong_luong) . " VNĐ";
                } else {
                    // Lỗi UNIQUE KEY nếu đã tồn tại bản ghi của nhân viên trong tháng/năm đó
                    if ($conn->errno == 1062) {
                        $message = "Lỗi: Bảng lương cho nhân viên này trong tháng $thang/$nam đã tồn tại. Vui lòng sử dụng chức năng 'Sửa'.";
                    } else {
                        $message = "Lỗi khi thêm: " . $stmt->error;
                    }
                    $error = true;
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $message = "Lỗi CSDL: " . $e->getMessage();
                $error = true;
            }
        }
    }


    // Chuyển hướng để tránh gửi lại form khi làm mới trang
    if (!$error) {
        header("Location: quanlybangluong.php?message=" . urlencode($message));
        exit();
    }
}

// KHÔNG XỬ LÝ XÓA BẢN GHI (theo yêu cầu của bạn)


// Lấy thông báo từ URL (nếu có)
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $error = strpos($message, 'Lỗi') !== false;
}

// Lấy dữ liệu cho chế độ chỉnh sửa (nếu có)
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_bangluong = intval($_GET['edit']);
    // Truy vấn dữ liệu để hiển thị lên form
    $sql_edit = "SELECT bl.*, nv.ho_ten, nv.luong_co_ban, nv.chuc_vu FROM bangluong bl JOIN nhanvien nv ON bl.id_nhanvien = nv.id_nhanvien WHERE bl.id_bangluong = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    if ($stmt_edit) {
        $stmt_edit->bind_param("i", $id_bangluong);
        $stmt_edit->execute();
        $result_edit = $stmt_edit->get_result();
        if ($result_edit && $result_edit->num_rows > 0) {
            $edit_data = $result_edit->fetch_assoc();
        }
        $stmt_edit->close();
    }
}

// Lấy danh sách bảng lương để hiển thị
$bangluong_list = [];
$sql_bangluong = "SELECT bl.*, nv.ho_ten, nv.chuc_vu FROM bangluong bl JOIN nhanvien nv ON bl.id_nhanvien = nv.id_nhanvien ORDER BY bl.nam DESC, bl.thang DESC, nv.ho_ten ASC";
$result_bangluong = $conn->query($sql_bangluong);
if ($result_bangluong) {
    while ($row = $result_bangluong->fetch_assoc()) {
        $bangluong_list[] = $row;
    }
}
?>

<style>
    /* CSS giữ nguyên, thêm icon cho đẹp */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        color: #333;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .header-container {
        text-align: center;
        margin-bottom: 30px;
    }
    .main-title {
        color: #1a237e;
        font-size: 2.5rem;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        margin: 0;
    }
    .form-box {
        padding: 30px;
        border-radius: 12px;
        background-color: #f8f9fa;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        margin-bottom: 30px;
    }
    .form-box h3 {
        color: #0056b3;
        margin-top: 0;
        margin-bottom: 20px;
        font-weight: 500;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }
    .form-row input, .form-row select, .form-row textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }
    /* Thêm style cho trường bị disabled/readonly */
    .form-row input[readonly], .form-row select:disabled {
        background-color: #e9ecef;
        opacity: 0.8;
        cursor: not-allowed;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
    .form-actions button, .form-actions a {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .form-actions button[name="submit"] { background-color: #007bff; }
    .form-actions button[name="submit"]:hover { background-color: #0056b3; transform: translateY(-2px); }
    .form-actions a.cancel-btn { background-color: #6c757d; }
    .form-actions a.cancel-btn:hover { background-color: #5a6268; transform: translateY(-2px); }
    .table-container {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    th {
        background-color: #0056b3;
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    tr:nth-child(even) {
        background-color: #f5f5f5;
    }
    tr:hover {
        background-color: #e8f0fe;
    }
    td a { color: #007bff; text-decoration: none; font-weight: 600; transition: color 0.3s;}
    td a:hover { color: #0056b3; text-decoration: underline;}
    .currency { text-align: right; }
    .alert-message {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        text-align: center;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<main class="container">
    <div class="header-container">
        <h2 class="main-title">Quản lý Bảng Lương</h2>
    </div>

    <?php if ($message): ?>
        <div class="alert-message <?= $error ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-box">
        <h3><i class="fas fa-calculator"></i> <?= $edit_data ? 'Chỉnh Sửa Bảng Lương' : 'Thêm Bảng Lương Mới' ?></h3>
        <form method="post" action="quanlybangluong.php">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_bangluong" value="<?= htmlspecialchars($edit_data['id_bangluong']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="id_nhanvien">Nhân viên:</label>
                <select id="id_nhanvien" name="id_nhanvien" required <?= $edit_data ? 'disabled' : '' ?>>
                    <option value="">-- Chọn nhân viên --</option>
                    <?php foreach ($nhanvien_list as $nv): ?>
                        <option value="<?= htmlspecialchars($nv['id_nhanvien']) ?>"
                            <?= $edit_data && $edit_data['id_nhanvien'] == $nv['id_nhanvien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nv['ho_ten']) ?> (<?= htmlspecialchars($nv['chuc_vu']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_nhanvien" value="<?= htmlspecialchars($edit_data['id_nhanvien']) ?>">
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ho_ten">Họ Tên</label>
                    <input type="text" id="ho_ten" placeholder="Họ Tên" value="<?= $edit_data ? htmlspecialchars($edit_data['ho_ten']) : '' ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="chuc_vu">Chức vụ</label>
                    <input type="text" id="chuc_vu" placeholder="Chức vụ" value="<?= $edit_data ? htmlspecialchars($edit_data['chuc_vu']) : '' ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="thang">Tháng</label>
                    <input type="number" name="thang" placeholder="Tháng" min="1" max="12" required 
                        value="<?= $edit_data ? htmlspecialchars($edit_data['thang']) : date('m') ?>" <?= $edit_data ? 'readonly' : '' ?>>
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="thang" value="<?= htmlspecialchars($edit_data['thang']) ?>">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="nam">Năm</label>
                    <input type="number" name="nam" placeholder="Năm" min="2000" max="2100" required 
                        value="<?= $edit_data ? htmlspecialchars($edit_data['nam']) : date('Y') ?>" <?= $edit_data ? 'readonly' : '' ?>>
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="nam" value="<?= htmlspecialchars($edit_data['nam']) ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="luong_co_ban">Lương cơ bản (VNĐ)</label>
                    <input type="text" id="luong_co_ban" placeholder="Lương cơ bản" value="<?= $edit_data ? number_format($edit_data['luong_co_ban']) : '' ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="thuong">Tiền thưởng (VNĐ)</label>
                    <input type="number" name="thuong" placeholder="Tiền thưởng" min="0" required value="<?= $edit_data ? htmlspecialchars($edit_data['thuong']) : 0 ?>">
                </div>
                <div class="form-group">
                    <label for="phat">Tiền phạt (VNĐ)</label>
                    <input type="number" name="phat" placeholder="Tiền phạt" min="0" required value="<?= $edit_data ? htmlspecialchars($edit_data['phat']) : 0 ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit"><i class="fas fa-save"></i> <?= $edit_data ? 'Cập nhật' : 'Thêm mới' ?></button>
                <?php if ($edit_data): ?>
                    <a href="quanlybangluong.php" class="cancel-btn"><i class="fas fa-times"></i> Hủy</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nhân viên</th>
                    <th>Chức vụ</th>
                    <th>Tháng/Năm</th>
                    <th>Ngày công</th>
                    <th class="currency">Thưởng</th>
                    <th class="currency">Phạt</th>
                    <th class="currency">Lương cơ bản</th>
                    <th class="currency">Tổng lương</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bangluong_list)): ?>
                    <?php $stt = 1; ?>
                    <?php foreach ($bangluong_list as $bl): ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= htmlspecialchars($bl['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($bl['chuc_vu']) ?></td>
                            <td><?= htmlspecialchars($bl['thang']) ?>/<?= htmlspecialchars($bl['nam']) ?></td>
                            <td><?= htmlspecialchars($bl['so_ngay_cong']) ?></td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['thuong'])) ?> VNĐ</td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['phat'])) ?> VNĐ</td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['luong_co_ban'])) ?> VNĐ</td>
                            <td class="currency"><strong><?= htmlspecialchars(number_format($bl['tong_luong'])) ?> VNĐ</strong></td>
                            <td>
                                <a href="quanlybangluong.php?edit=<?= htmlspecialchars($bl['id_bangluong']) ?>"><i class="fas fa-edit"></i> Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">Chưa có dữ liệu bảng lương nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    // Chuyển danh sách nhân viên từ PHP sang JavaScript
    const nhanVienList = <?= json_encode($nhanvien_list) ?>;

    // Hàm định dạng tiền tệ (thêm dấu phẩy)
    function formatCurrency(number) {
        if (number === null || number === undefined) return '';
        // Đảm bảo số là một chuỗi hoặc số và chuyển sang số
        const num = parseFloat(number); 
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    // Lắng nghe sự kiện khi chọn nhân viên
    document.getElementById('id_nhanvien').addEventListener('change', function() {
        const selectedId = this.value;
        const hoTenInput = document.getElementById('ho_ten');
        const chucVuInput = document.getElementById('chuc_vu');
        const luongCoBanInput = document.getElementById('luong_co_ban');

        // Reset các trường khi không chọn gì
        if (!selectedId) {
            hoTenInput.value = '';
            chucVuInput.value = '';
            luongCoBanInput.value = '';
            return;
        }

        // Tìm nhân viên trong danh sách
        // Dùng == thay vì === vì ID từ JS là string, từ PHP có thể là number
        const selectedNhanVien = nhanVienList.find(nv => nv.id_nhanvien == selectedId);

        if (selectedNhanVien) {
            hoTenInput.value = selectedNhanVien.ho_ten;
            chucVuInput.value = selectedNhanVien.chuc_vu;
            // Hiển thị lương cơ bản đã định dạng
            luongCoBanInput.value = formatCurrency(selectedNhanVien.luong_co_ban);
        }
    });

    // Gọi sự kiện khi trang tải xong để điền dữ liệu nếu đang ở chế độ chỉnh sửa
    window.addEventListener('load', function() {
        // Nếu ở chế độ chỉnh sửa, các trường đã được điền sẵn qua PHP,
        // chỉ cần định dạng lại Lương cơ bản cho đẹp
        const luongCoBanElement = document.getElementById('luong_co_ban');
        if (luongCoBanElement && luongCoBanElement.value) {
            // Lấy giá trị hiện tại (chưa định dạng)
            const rawValue = luongCoBanElement.value.replace(/[^0-9.]/g, ''); 
            luongCoBanElement.value = formatCurrency(rawValue);
        }

        // Kích hoạt sự kiện change nếu đang ở chế độ thêm mới (để điền tự động)
        const selectedId = document.getElementById('id_nhanvien').value;
        if (selectedId && !document.querySelector('input[name="id_bangluong"]')) {
             document.getElementById('id_nhanvien').dispatchEvent(new Event('change'));
        }
    });
</script>

<?php include 'footer.php'; ?>