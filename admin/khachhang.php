<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình bảng và cột
$table = 'khachhang';
$id_col = 'id_khachhang';
$tai_khoan_id_col = 'tai_khoan_khachhang_id';
$full_name_col = 'ho_ten';
$phone_number_col = 'so_dien_thoai';
$email_col = 'email';
$address_col = 'dia_chi';
$ngay_sinh_col = 'ngay_sinh';
$gioi_tinh_col = 'gioi_tinh';
$cccd_col = 'cccd';

// ================== XỬ LÝ THÊM ==================
if (isset($_POST['them'])) {
    $tai_khoan_id = !empty($_POST[$tai_khoan_id_col]) ? (int)$_POST[$tai_khoan_id_col] : NULL;
    $ho_ten = $_POST[$full_name_col];
    $so_dien_thoai = $_POST[$phone_number_col];
    $email = $_POST[$email_col];
    $ngay_sinh = !empty($_POST[$ngay_sinh_col]) ? $_POST[$ngay_sinh_col] : NULL;
    $gioi_tinh = !empty($_POST[$gioi_tinh_col]) ? $_POST[$gioi_tinh_col] : NULL;
    $cccd = !empty($_POST[$cccd_col]) ? $_POST[$cccd_col] : NULL;
    $dia_chi = !empty($_POST[$address_col]) ? $_POST[$address_col] : NULL;

    $sql = "INSERT INTO `$table` 
        (`$tai_khoan_id_col`, `$full_name_col`, `$phone_number_col`, `$email_col`, `$ngay_sinh_col`, `$gioi_tinh_col`, `$cccd_col`, `$address_col`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $tai_khoan_id, $ho_ten, $so_dien_thoai, $email, $ngay_sinh, $gioi_tinh, $cccd, $dia_chi);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm khách hàng thành công!');window.location='khachhang.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi khi thêm: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// ================== XỬ LÝ SỬA ==================
$edit_data = null;
if (isset($_GET['sua'])) {
    $id_edit = (int)$_GET['sua'];
    $sql_edit = "SELECT * FROM `$table` WHERE `$id_col`=?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_data = $result_edit->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['capnhat'])) {
    $id_update = (int)$_POST[$id_col];
    $tai_khoan_id = !empty($_POST[$tai_khoan_id_col]) ? (int)$_POST[$tai_khoan_id_col] : NULL;
    $ho_ten = $_POST[$full_name_col];
    $so_dien_thoai = $_POST[$phone_number_col];
    $email = $_POST[$email_col];
    $ngay_sinh = !empty($_POST[$ngay_sinh_col]) ? $_POST[$ngay_sinh_col] : NULL;
    $gioi_tinh = !empty($_POST[$gioi_tinh_col]) ? $_POST[$gioi_tinh_col] : NULL;
    $cccd = !empty($_POST[$cccd_col]) ? $_POST[$cccd_col] : NULL;
    $dia_chi = !empty($_POST[$address_col]) ? $_POST[$address_col] : NULL;

    $sql = "UPDATE `$table` SET 
        `$tai_khoan_id_col`=?, `$full_name_col`=?, `$phone_number_col`=?, `$email_col`=?, 
        `$ngay_sinh_col`=?, `$gioi_tinh_col`=?, `$cccd_col`=?, `$address_col`=? 
        WHERE `$id_col`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssi", $tai_khoan_id, $ho_ten, $so_dien_thoai, $email, $ngay_sinh, $gioi_tinh, $cccd, $dia_chi, $id_update);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật khách hàng thành công!');window.location='khachhang.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi khi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// ================== TÌM KIẾM ==================
$search = '';
$where = '';
$params = [];
if (isset($_POST['timkiem'])) {
    $search = $_POST['search'];
    $where = "WHERE `$full_name_col` LIKE ? OR `$phone_number_col` LIKE ? OR `$email_col` LIKE ? OR `$address_col` LIKE ?";
    $params = ['ssss', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%'];
}

$sql_select = "SELECT * FROM `$table` " . $where;
$stmt = $conn->prepare($sql_select);
if (isset($_POST['timkiem'])) {
    $stmt->bind_param(...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    body {font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f9; margin: 0; padding: 0;}
    .container {padding: 30px; max-width: 1400px; margin: 0 auto;}
    .header-container {display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 25px;}
    .main-title {color: #1d4d84; font-size: 2.2rem; font-weight: 600; text-transform: uppercase; margin: 0;}
    .search-form {display: flex; gap: 10px; align-items: center;}
    .search-form input {width: 300px; padding: 10px 15px; border: 1px solid #ced4da; border-radius: 25px;}
    .search-form button {padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;}
    .form-box {background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); margin-bottom: 30px;}
    .form-box h3 {color: #1d4d84; margin-top: 0; font-size: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;}
    .form-row {display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;}
    .form-row input, .form-row select {width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;}
    .form-actions {display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px;}
    .form-actions button {padding: 12px 25px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; color: #fff;}
    .btn-them {background-color: #28a745;} .btn-them:hover {background-color: #218838;}
    .btn-capnhat {background-color: #ffc107; color: #000;} .btn-capnhat:hover {background-color: #e0a800;}
    table {width: 100%; border-collapse: collapse; background-color: #fff; border-radius: 12px; overflow: hidden; margin-top: 20px;}
    th, td {padding: 15px; border-bottom: 1px solid #dee2e6;}
    th {background-color: #1d4d84; color: #fff; text-transform: uppercase;}
    tr:nth-child(even) {background-color: #f9fbfd;} tr:hover {background-color: #eaf3fd;}
    .action-btn {display: inline-block; padding: 6px 12px; border-radius: 6px; background-color: #ffc107; color: #000; font-weight: 600; text-decoration: none;}
    .action-btn:hover {background-color: #e0a800;}
</style>

<main class="container">
    <div class="header-container">
        <h2 class="main-title">Quản lý Khách hàng</h2>
        <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm theo họ tên, SĐT..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <!-- Form thêm / sửa khách hàng -->
    <div class="form-box">
        <h3><i class="fas fa-user-plus"></i> <?= $edit_data ? "Sửa Khách hàng" : "Thêm Khách hàng Mới" ?></h3>
        <form method="post">
            <?php if ($edit_data): ?>
                <input type="hidden" name="<?= $id_col ?>" value="<?= $edit_data[$id_col] ?>">
            <?php endif; ?>
            <div class="form-row">
                <input type="number" name="<?= $tai_khoan_id_col ?>" placeholder="ID tài khoản" value="<?= $edit_data[$tai_khoan_id_col] ?? '' ?>">
                <input type="text" name="<?= $full_name_col ?>" placeholder="Họ tên" value="<?= $edit_data[$full_name_col] ?? '' ?>" required>
                <input type="date" name="<?= $ngay_sinh_col ?>" value="<?= $edit_data[$ngay_sinh_col] ?? '' ?>">
                <select name="<?= $gioi_tinh_col ?>">
                    <option value="">Giới tính</option>
                    <option value="Nam" <?= ($edit_data && $edit_data[$gioi_tinh_col]=='Nam')?'selected':'' ?>>Nam</option>
                    <option value="Nữ" <?= ($edit_data && $edit_data[$gioi_tinh_col]=='Nữ')?'selected':'' ?>>Nữ</option>
                    <option value="Khác" <?= ($edit_data && $edit_data[$gioi_tinh_col]=='Khác')?'selected':'' ?>>Khác</option>
                </select>
                <input type="text" name="<?= $phone_number_col ?>" placeholder="Số điện thoại" value="<?= $edit_data[$phone_number_col] ?? '' ?>" required>
                <input type="email" name="<?= $email_col ?>" placeholder="Email" value="<?= $edit_data[$email_col] ?? '' ?>">
                <input type="text" name="<?= $cccd_col ?>" placeholder="CCCD" value="<?= $edit_data[$cccd_col] ?? '' ?>">
                <input type="text" name="<?= $address_col ?>" placeholder="Địa chỉ" value="<?= $edit_data[$address_col] ?? '' ?>">
            </div>
            <div class="form-actions">
                <?php if ($edit_data): ?>
                    <button type="submit" name="capnhat" class="btn-capnhat"><i class="fas fa-save"></i> Cập nhật</button>
                <?php else: ?>
                    <button type="submit" name="them" class="btn-them"><i class="fas fa-plus-circle"></i> Thêm</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Tài khoản</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>CCCD</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row[$id_col]) ?></td>
                    <td><?= htmlspecialchars($row[$tai_khoan_id_col]) ?></td>
                    <td><?= htmlspecialchars($row[$full_name_col]) ?></td>
                    <td><?= htmlspecialchars($row[$ngay_sinh_col]) ?></td>
                    <td><?= htmlspecialchars($row[$gioi_tinh_col]) ?></td>
                    <td><?= htmlspecialchars($row[$phone_number_col]) ?></td>
                    <td><?= htmlspecialchars($row[$email_col]) ?></td>
                    <td><?= htmlspecialchars($row[$cccd_col]) ?></td>
                    <td><?= htmlspecialchars($row[$address_col]) ?></td>
                    <td>
                        <a class="action-btn" href="khachhang.php?sua=<?= $row[$id_col] ?>">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                    </td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center;">Không tìm thấy khách hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include 'footer.php'; ?>
