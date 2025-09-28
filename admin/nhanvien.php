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

// Cấu hình bảng và cột
$table = 'nhanvien';
$id_col = 'id_nhanvien';
$tai_khoan_col = 'tai_khoan_nhanvien_id';
$name_col = 'ho_ten';
$role_col = 'chuc_vu';
$salary_col = 'luong_co_ban';
$date_col = 'ngay_vao_lam';
$phone_col = 'so_dien_thoai';
$email_col = 'email';

// Xử lý thêm nhân viên
if (isset($_POST['them'])) {
    // 2. KIỂM TRA QUYỀN HẠN TRƯỚC KHI THỰC HIỆN THAO TÁC THÊM
    if (!$is_admin) {
        echo "<script>alert('Bạn không có quyền Thêm nhân viên!');window.location='nhanvien.php';</script>";
        exit;
    }
    // ------------------------------------------------------------------

    $tai_khoan = (int)$_POST[$tai_khoan_col];
    $ten = $_POST[$name_col];
    $chucvu = $_POST[$role_col];
    $luong = $_POST[$salary_col];
    $ngayvao = !empty($_POST[$date_col]) ? $_POST[$date_col] : NULL;
    $sdt = $_POST[$phone_col];
    $email = $_POST[$email_col];

    $sql = "INSERT INTO `$table` (`$tai_khoan_col`, `$name_col`, `$role_col`, `$salary_col`, `$date_col`, `$phone_col`, `$email_col`)
             VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdsss", $tai_khoan, $ten, $chucvu, $luong, $ngayvao, $sdt, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm nhân viên thành công!');window.location='nhanvien.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi khi thêm: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý cập nhật nhân viên
if (isset($_POST['luu'])) {
    // 2. KIỂM TRA QUYỀN HẠN TRƯỚC KHI THỰC HIỆN THAO TÁC SỬA (LƯU)
    if (!$is_admin) {
        echo "<script>alert('Bạn không có quyền Sửa nhân viên!');window.location='nhanvien.php';</script>";
        exit;
    }
    // ------------------------------------------------------------------

    $id = (int)$_POST[$id_col];
    $ten = $_POST[$name_col];
    $chucvu = $_POST[$role_col];
    $luong = $_POST[$salary_col];
    $ngayvao = !empty($_POST[$date_col]) ? $_POST[$date_col] : NULL;
    $sdt = $_POST[$phone_col];
    $email = $_POST[$email_col];

    $sql = "UPDATE `$table` 
             SET `$name_col`=?, `$role_col`=?, `$salary_col`=?, `$date_col`=?, `$phone_col`=?, `$email_col`=? 
             WHERE `$id_col`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssi", $ten, $chucvu, $luong, $ngayvao, $sdt, $email, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật nhân viên thành công!');window.location='nhanvien.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi khi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// ❌ Bỏ toàn bộ phần xử lý xóa nhân viên

// Tìm kiếm
$search = '';
$where = '';
$params = [];
if (isset($_POST['timkiem'])) {
    // NHANVIEN được phép tìm kiếm (Không cần check quyền)
    $search = $_POST['search'];
    $where = "WHERE `$name_col` LIKE ? OR `$role_col` LIKE ? OR `$phone_col` LIKE ? OR `$email_col` LIKE ?";
    $params = ['ssss', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%'];
}

$sql_select = "SELECT * FROM `$table` " . $where . " ORDER BY `$name_col`";
$stmt = $conn->prepare($sql_select);
if (isset($_POST['timkiem'])) {
    $stmt->bind_param(...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result === false) {
    echo "<p style='color:red'>SQL lỗi: " . $conn->error . "</p>";
    include 'footer.php'; exit;
}

// Chỉ ADMIN mới được lấy dữ liệu để sửa
$edit_id = null;
$edit_data = null;
if ($is_admin && isset($_GET['edit'])) { 
    $edit_id = $_GET['edit'];
    if ($edit_id) {
        $stmt_edit = $conn->prepare("SELECT * FROM `$table` WHERE `$id_col`=?");
        $stmt_edit->bind_param("i", $edit_id);
        $stmt_edit->execute();
        $edit_data = $stmt_edit->get_result()->fetch_assoc();
        $stmt_edit->close();
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    /* ... CSS giữ nguyên ... */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
    }
    .container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .main-title {
        color: #1d4d84;
        font-size: 2.2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }
    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .search-form input {
        width: 300px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 25px;
        transition: border-color 0.3s, box-shadow 0.3s;
        font-size: 1rem;
    }
    .search-form input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        outline: none;
    }
    .search-form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
    }
    .search-form button:hover {
        background-color: #0056b3;
    }
    .form-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border-left: 5px solid #1d4d84;
    }
    .form-box h3 {
        color: #1d4d84;
        margin-top: 0;
        font-size: 1.5rem;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-row input, .form-row select, .form-row textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
    }
    .form-actions button {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        cursor: pointer;
        color: #fff;
    }
    button[name="them"], button[name="luu"] {
        background-color: #28a745;
    }
    button[name="them"]:hover, button[name="luu"]:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    a.btn-cancel {
        background-color: #dc3545;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.2s;
    }
    a.btn-cancel:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    th {
        background-color: #1d4d84;
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    tr:nth-child(even) {
        background-color: #f9fbfd;
    }
    tr:hover {
        background-color: #eaf3fd;
    }
    .action-links a {
        margin-right: 10px;
        text-decoration: none;
        font-weight: bold;
    }
    .action-links .edit-btn {
        color: #007bff;
    }
</style>

<main class="container">
    <div class="header-container">
        <h2 class="main-title">Quản lý Nhân viên</h2>
        <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm theo họ tên, chức vụ..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <?php if ($is_admin): ?>
    <div class="form-box">
        <h3><?= $edit_data ? '<i class="fas fa-user-edit"></i> Chỉnh sửa nhân viên #' . htmlspecialchars($edit_data[$id_col]) : '<i class="fas fa-user-plus"></i> Thêm Nhân viên Mới' ?></h3>
        <form method="post">
            <?php if ($edit_data) { ?>
                <input type="hidden" name="<?= htmlspecialchars($id_col) ?>" value="<?= htmlspecialchars($edit_data[$id_col]) ?>">
            <?php } ?>
            <div class="form-row">
                <input type="number" name="<?= htmlspecialchars($tai_khoan_col) ?>" placeholder="ID tài khoản" value="<?= $edit_data ? htmlspecialchars($edit_data[$tai_khoan_col]) : '' ?>" required>
                <input type="text" name="<?= htmlspecialchars($name_col) ?>" placeholder="Họ tên" value="<?= $edit_data ? htmlspecialchars($edit_data[$name_col]) : '' ?>" required>
                <input type="text" name="<?= htmlspecialchars($role_col) ?>" placeholder="Chức vụ" value="<?= $edit_data ? htmlspecialchars($edit_data[$role_col]) : '' ?>">
                <input type="number" step="0.01" name="<?= htmlspecialchars($salary_col) ?>" placeholder="Lương cơ bản" value="<?= $edit_data ? htmlspecialchars($edit_data[$salary_col]) : '' ?>" required>
            </div>
            <div class="form-row">
                <input type="date" name="<?= htmlspecialchars($date_col) ?>" value="<?= $edit_data ? htmlspecialchars($edit_data[$date_col]) : '' ?>">
                <input type="text" name="<?= htmlspecialchars($phone_col) ?>" placeholder="Số điện thoại" value="<?= $edit_data ? htmlspecialchars($edit_data[$phone_col]) : '' ?>">
                <input type="email" name="<?= htmlspecialchars($email_col) ?>" placeholder="Email" value="<?= $edit_data ? htmlspecialchars($edit_data[$email_col]) : '' ?>">
            </div>
            <div class="form-actions">
                <button type="submit" name="<?= $edit_data ? 'luu' : 'them' ?>"><?= $edit_data ? '<i class="fas fa-save"></i> Lưu Sửa' : '<i class="fas fa-plus-circle"></i> Thêm' ?></button>
                <?php if ($edit_data) { ?>
                    <a href="nhanvien.php" class="btn-cancel"><i class="fas fa-times-circle"></i> Hủy</a>
                <?php } ?>
            </div>
        </form>
    </div>
    <?php else: ?>
        <div class="form-box" style="border-left: 5px solid #ffc107;">
            <h3 style="color: #ffc107;"><i class="fas fa-info-circle"></i> Thông báo</h3>
            <p>Chức năng Thêm và Chỉnh sửa Nhân viên chỉ dành cho tài khoản có quyền **ADMIN**.</p>
        </div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Tài khoản</th>
                <th>Họ tên</th>
                <th>Chức vụ</th>
                <th>Lương cơ bản</th>
                <th>Ngày vào làm</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row[$id_col]) ?></td>
                    <td><?= htmlspecialchars($row[$tai_khoan_col]) ?></td>
                    <td><?= htmlspecialchars($row[$name_col]) ?></td>
                    <td><?= htmlspecialchars($row[$role_col]) ?></td>
                    <td><?= number_format($row[$salary_col], 0, ",", ".") ?> VND</td>
                    <td><?= htmlspecialchars($row[$date_col]) ?></td>
                    <td><?= htmlspecialchars($row[$phone_col]) ?></td>
                    <td><?= htmlspecialchars($row[$email_col]) ?></td>
                    <td class="action-links">
                        <?php if ($is_admin): ?>
                        <a href="?edit=<?= htmlspecialchars($row[$id_col]) ?>" class="edit-btn"><i class="fas fa-edit"></i> Sửa</a>
                        <?php else: ?>
                        <span>(Xem)</span>
                        <?php endif; ?>
                        </td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Không tìm thấy nhân viên nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include 'footer.php'; ?>