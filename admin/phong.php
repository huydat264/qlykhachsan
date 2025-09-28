<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Xử lý lưu sửa phòng
if (isset($_POST['luu'])) {
    $id = (int)$_POST['id_phong'];
    $so_phong = $_POST['so_phong'];
    $loai_phong = $_POST['loai_phong'];
    $gia_phong = $_POST['gia_phong'];
    $trang_thai = $_POST['trang_thai'];
    $so_luong_nguoi = $_POST['so_luong_nguoi'];
    $mo_ta = $_POST['mo_ta'];
    $anh = $_POST['anh'];

    $sql = "UPDATE `Phong` 
            SET `so_phong`=?, `loai_phong`=?, `gia_phong`=?, `so_luong_nguoi`=?, `mo_ta`=?, `anh`=?, `trang_thai`=?
            WHERE `id_phong`=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssi", $so_phong, $loai_phong, $gia_phong, $so_luong_nguoi, $mo_ta, $anh, $trang_thai, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật phòng thành công!');window.location='phong.php';</script>";
    } else {
        echo "<p style='color:red'>Lỗi khi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý xóa thông tin khách hàng khỏi phòng
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];

    $conn->begin_transaction();

    try {
        $sql_datphong = "DELETE FROM `DatPhong` WHERE `id_phong`=?";
        $stmt_datphong = $conn->prepare($sql_datphong);
        $stmt_datphong->bind_param("i", $id);
        $stmt_datphong->execute();
        $stmt_datphong->close();

        $sql_phong = "UPDATE `Phong` SET `trang_thai`='Trống' WHERE `id_phong`=?";
        $stmt_phong = $conn->prepare($sql_phong);
        $stmt_phong->bind_param("i", $id);
        $stmt_phong->execute();
        $stmt_phong->close();
        
        $conn->commit();
        echo "<script>alert('Xóa thông tin khách hàng và cập nhật trạng thái phòng thành công!');window.location='phong.php';</script>";
        exit;
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo "<p style='color:red'>Lỗi khi xóa: " . $e->getMessage() . "</p>";
    }
}

// Tìm kiếm
$search = '';
$where = '';
$params = [];
if (isset($_POST['timkiem'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $where = "WHERE `Phong`.`so_phong` LIKE ? OR `Phong`.`loai_phong` LIKE ? OR `KhachHang`.`ho_ten` LIKE ?";
    $params = ['sss', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%'];
}
$sql_select = "SELECT 
                    `Phong`.*, 
                    `KhachHang`.`ho_ten` AS `ten_khach_hang`,
                    `KhachHang`.`cccd` AS `cccd_khach_hang`,
                    `KhachHang`.`so_dien_thoai` AS `sdt_khach_hang`
                FROM `Phong` 
                LEFT JOIN `DatPhong` ON `Phong`.`id_phong` = `DatPhong`.`id_phong`
                LEFT JOIN `KhachHang` ON `DatPhong`.`id_khachhang` = `KhachHang`.`id_khachhang`
                " . $where . " ORDER BY `Phong`.`so_phong`";
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

// Kiểm tra ID đang sửa
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_data = null;
if ($edit_id) {
    $sql_edit = "SELECT * FROM `Phong` WHERE `id_phong` = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $edit_id);
    $stmt_edit->execute();
    $edit_result = $stmt_edit->get_result();
    $edit_data = $edit_result->fetch_assoc();
    $stmt_edit->close();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
    }
    .container {
        padding: 30px;
        max-width: 1300px;
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    .form-row textarea {
        height: 100px;
        resize: vertical;
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
    .form-actions button, .form-actions a {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        cursor: pointer;
        color: #fff;
    }
    .form-actions button[name="luu"] {
        background-color: #28a745;
    }
    .form-actions button[name="luu"]:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    .form-actions a {
        background-color: #dc3545;
    }
    .form-actions a:hover {
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
    .action-links {
        white-space: nowrap;
    }
    .action-links a {
        margin-right: 15px;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .action-links a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
        color: #fff;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .status-badge.trong { background-color: #28a745; }
    .status-badge.da-dat { background-color: #007bff; }
    .status-badge.dang-dat { background-color: #ffc107; color: #333; }
    .status-badge.bao-tri { background-color: #dc3545; }
    .table-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        transition: transform 0.3s;
    }
    .table-image:hover {
        transform: scale(1.5);
    }
    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center;
    }
    .modal-content {
        background-color: #fff; padding: 30px; border-radius: 10px; max-width: 450px;
        text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }
    .modal-content h4 {
        color: #333; margin-top: 0; font-size: 1.5rem;
    }
    .modal-content p {
        color: #666; font-size: 1rem; line-height: 1.5;
    }
    .modal-buttons {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .modal-buttons button {
        padding: 12px 25px; border-radius: 8px; font-weight: 600; cursor: pointer;
        border: none; transition: background-color 0.3s, transform 0.2s;
    }
    #confirm-delete {
        background-color: #dc3545; color: white;
    }
    #confirm-delete:hover {
        background-color: #c82333; transform: scale(1.05);
    }
    #cancel-delete {
        background-color: #6c757d; color: white;
    }
    #cancel-delete:hover {
        background-color: #5a6268; transform: scale(1.05);
    }
</style>

<main class="container">
    <div class="header-container">
        <h2 class="main-title">Quản lý Phòng</h2>
        <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm phòng..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <?php if ($edit_data) { ?>
    <div class="form-box">
        <h3><i class="fas fa-edit"></i> Chỉnh sửa phòng #<?= htmlspecialchars($edit_data['id_phong']) ?></h3>
        <form method="post">
            <input type="hidden" name="id_phong" value="<?= htmlspecialchars($edit_data['id_phong']) ?>">
            <div class="form-row">
                <input type="text" name="so_phong" placeholder="Số phòng" value="<?= htmlspecialchars($edit_data['so_phong']) ?>" required>
                <select name="loai_phong">
                    <option value="Standard" <?= $edit_data['loai_phong'] == 'Standard' ? 'selected' : '' ?>>Standard</option>
                    <option value="Deluxe" <?= $edit_data['loai_phong'] == 'Deluxe' ? 'selected' : '' ?>>Deluxe</option>
                    <option value="Suite" <?= $edit_data['loai_phong'] == 'Suite' ? 'selected' : '' ?>>Suite</option>
                </select>
                <input type="number" name="gia_phong" placeholder="Giá phòng" value="<?= htmlspecialchars($edit_data['gia_phong']) ?>" required>
                <input type="number" name="so_luong_nguoi" placeholder="Số người tối đa" value="<?= htmlspecialchars($edit_data['so_luong_nguoi']) ?>" required>
                <select name="trang_thai">
                    <option value="Trống" <?= $edit_data['trang_thai'] == 'Trống' ? 'selected' : '' ?>>Trống</option>
                    <option value="Đang đặt" <?= $edit_data['trang_thai'] == 'Đang đặt' ? 'selected' : '' ?>>Đang đặt</option>
                    <option value="Đã đặt" <?= $edit_data['trang_thai'] == 'Đã đặt' ? 'selected' : '' ?>>Đã đặt</option>
                    <option value="Bảo trì" <?= $edit_data['trang_thai'] == 'Bảo trì' ? 'selected' : '' ?>>Bảo trì</option>
                </select>
            </div>
            <div class="form-row">
                <textarea name="mo_ta" placeholder="Mô tả phòng"><?= htmlspecialchars($edit_data['mo_ta']) ?></textarea>
                <input type="text" name="anh" placeholder="Link ảnh phòng" value="<?= htmlspecialchars($edit_data['anh']) ?>">
            </div>
            <div class="form-actions">
                <button type="submit" name="luu"><i class="fas fa-save"></i> Lưu Sửa</button>
                <a href="phong.php"><i class="fas fa-times-circle"></i> Hủy</a>
            </div>
        </form>
    </div>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Số phòng</th>
                <th>Loại phòng</th>
                <th>Giá phòng</th>
                <th>Số người</th>
                <th>Mô tả</th>
                <th>Tình trạng</th>
                <th>Tên Khách hàng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_phong']) ?></td>
                    <td>
                        <?php if (!empty($row['anh'])) { ?>
                            <img src="<?= htmlspecialchars($row['anh']) ?>" alt="Ảnh phòng" class="table-image">
                        <?php } else { ?>
                            <span>(Chưa có)</span>
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($row['so_phong']) ?></td>
                    <td><?= htmlspecialchars($row['loai_phong']) ?></td>
                    <td><?= number_format($row['gia_phong'], 0, ",", ".") ?> VNĐ</td>
                    <td><?= htmlspecialchars($row['so_luong_nguoi']) ?></td>
                    <td><?= htmlspecialchars($row['mo_ta']) ?></td>
                    <td>
                        <?php
                            $status_class = '';
                            switch ($row['trang_thai']) {
                                case 'Trống': $status_class = 'trong'; break;
                                case 'Đang đặt': $status_class = 'dang-dat'; break;
                                case 'Đã đặt': $status_class = 'da-dat'; break;
                                case 'Bảo trì': $status_class = 'bao-tri'; break;
                            }
                        ?>
                        <span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($row['trang_thai']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($row['ten_khach_hang']) ?: '(Không có)' ?></td>
                    <td class="action-links">
                        <a href="?edit=<?= htmlspecialchars($row['id_phong']) ?>"><i class="fas fa-edit"></i> Sửa</a> 
                        <?php if ($row['trang_thai'] != 'Trống') { ?>
                            | <a href="#" class="delete-link" data-id="<?= htmlspecialchars($row['id_phong']) ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center;">Không tìm thấy phòng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="delete-modal" class="modal">
    <div class="modal-content">
        <h4><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h4>
        <p>Bạn có chắc chắn muốn xóa thông tin khách hàng khỏi phòng này?</p>
        <div class="modal-buttons">
            <button id="confirm-delete"><i class="fas fa-check"></i> Xóa</button>
            <button id="cancel-delete"><i class="fas fa-times"></i> Hủy</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteLinks = document.querySelectorAll('.delete-link');
        const modal = document.getElementById('delete-modal');
        const confirmBtn = document.getElementById('confirm-delete');
        const cancelBtn = document.getElementById('cancel-delete');

        let currentId = null;

        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentId = this.getAttribute('data-id');
                modal.style.display = 'flex';
            });
        });

        confirmBtn.addEventListener('click', function() {
            if (currentId) {
                window.location.href = `?xoa=${currentId}`;
            }
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

<?php include 'footer.php'; ?>