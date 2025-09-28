<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình mysqli để báo cáo ngoại lệ
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Xử lý thêm dịch vụ đã sử dụng
$message = '';
if (isset($_POST['them_sudungdv'])) {
    $id_datphong = $_POST['id_datphong'];
    $id_dichvu = $_POST['id_dichvu'];
    $so_luong = (int)$_POST['so_luong'];

    try {
        // Lấy giá của dịch vụ
        $sql_gia = "SELECT `gia` FROM `dichvu` WHERE `id_dichvu` = ?";
        $stmt_gia = $conn->prepare($sql_gia);
        $stmt_gia->bind_param("i", $id_dichvu);
        $stmt_gia->execute();
        $result_gia = $stmt_gia->get_result();
        $dichvu_data = $result_gia->fetch_assoc();
        $gia_dichvu = $dichvu_data['gia'];
        $stmt_gia->close();

        // Tính toán thành tiền
        $thanh_tien = $so_luong * $gia_dichvu;

        // Thêm bản ghi vào bảng sudungdichvu
        $sql_insert = "INSERT INTO `sudungdichvu` (`id_datphong`, `id_dichvu`, `so_luong`, `thanh_tien`) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iidi", $id_datphong, $id_dichvu, $so_luong, $thanh_tien);
        $stmt_insert->execute();
        $stmt_insert->close();
        
        $message = "Thêm dịch vụ sử dụng thành công!";

    } catch (mysqli_sql_exception $e) {
        $message = "Lỗi khi thêm dịch vụ: " . $e->getMessage();
    }
}

// Lấy danh sách các phòng đang được đặt (trạng thái "Đã đặt")
$sql_phong = "SELECT `datphong`.*, `phong`.`so_phong`, `khachhang`.`ho_ten` FROM `datphong` JOIN `phong` ON `datphong`.`id_phong` = `phong`.`id_phong` JOIN `khachhang` ON `datphong`.`id_khachhang` = `khachhang`.`id_khachhang` WHERE `phong`.`trang_thai` = 'Đã đặt'";
$stmt_phong = $conn->prepare($sql_phong);
$stmt_phong->execute();
$phong_dat_result = $stmt_phong->get_result();
$stmt_phong->close();

// Lấy danh sách tất cả các dịch vụ
$sql_dichvu = "SELECT * FROM `dichvu` ORDER BY `ten_dich_vu`";
$stmt_dichvu = $conn->prepare($sql_dichvu);
$stmt_dichvu->execute();
$dichvu_result = $stmt_dichvu->get_result();
$stmt_dichvu->close();

// Lấy danh sách các dịch vụ đã sử dụng (đã sửa lỗi)
$sql_sudungdv = "SELECT `sudungdichvu`.*, `phong`.`so_phong`, `dichvu`.`ten_dich_vu`, `dichvu`.`gia`, `khachhang`.`ho_ten`
                 FROM `sudungdichvu`
                 JOIN `datphong` ON `sudungdichvu`.`id_datphong` = `datphong`.`id_datphong`
                 JOIN `dichvu` ON `sudungdichvu`.`id_dichvu` = `dichvu`.`id_dichvu`
                 JOIN `phong` ON `datphong`.`id_phong` = `phong`.`id_phong`
                 JOIN `khachhang` ON `datphong`.`id_khachhang` = `khachhang`.`id_khachhang`
                 ORDER BY `sudungdichvu`.`id_sudungdv` DESC";

$stmt_sudungdv = $conn->prepare($sql_sudungdv);
$stmt_sudungdv->execute();
$sudungdv_result = $stmt_sudungdv->get_result();
$stmt_sudungdv->close();
?>

<style>
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
    }
    .form-row .form-group {
        flex: 1;
        min-width: 200px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }
    .form-row input, .form-row select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-row input:focus, .form-row select:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }
    .form-actions button {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        background-color: #28a745;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .form-actions button:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
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
        <h2 class="main-title">Quản lý Sử dụng Dịch vụ</h2>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert-message <?= strpos($message, 'Lỗi') !== false ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-box">
        <h3>Thêm dịch vụ đã sử dụng</h3>
        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="id_datphong">Phòng & Khách hàng</label>
                    <select name="id_datphong" id="id_datphong" required>
                        <option value="">-- Chọn phòng & khách hàng --</option>
                        <?php while ($phong = $phong_dat_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($phong['id_datphong']) ?>">Phòng <?= htmlspecialchars($phong['so_phong']) ?> (Khách: <?= htmlspecialchars($phong['ho_ten']) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_dichvu">Dịch vụ</label>
                    <select name="id_dichvu" id="id_dichvu" required>
                        <option value="">-- Chọn dịch vụ --</option>
                        <?php while ($dichvu = $dichvu_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($dichvu['id_dichvu']) ?>">
                                <?= htmlspecialchars($dichvu['ten_dich_vu']) ?> (<?= number_format($dichvu['gia'], 0, ",", ".") ?> VND)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="so_luong">Số lượng</label>
                    <input type="number" name="so_luong" id="so_luong" placeholder="Nhập số lượng" required min="1">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="them_sudungdv">Thêm</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Phòng</th>
                    <th>Khách hàng</th>
                    <th>Tên Dịch vụ</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sudungdv_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_sudungdv']) ?></td>
                        <td><?= htmlspecialchars($row['so_phong']) ?></td>
                        <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                        <td><?= htmlspecialchars($row['ten_dich_vu']) ?></td>
                        <td><?= htmlspecialchars($row['so_luong']) ?></td>
                        <td><?= number_format($row['thanh_tien'], 0, ",", ".") ?> VND</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
if ($conn) {
    // Chỉ đóng kết nối nếu nó đang mở
    // $conn->close();
}
include 'footer.php'; 
?>
