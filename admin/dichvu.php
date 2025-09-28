<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Cấu hình bảng và cột
$table = 'dichvu';
$id_col = 'id_dichvu';
$name_col = 'ten_dich_vu';
$price_col = 'gia';
$desc_col = 'mo_ta';

// Xử lý thêm dịch vụ
if (isset($_POST['them'])) {
    $ten = $_POST[$name_col];
    $gia = $_POST[$price_col];
    $mota = $_POST[$desc_col];

    $sql = "INSERT INTO `$table` (`$name_col`, `$price_col`, `$desc_col`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $ten, $gia, $mota);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm dịch vụ thành công!');window.location='dichvu.php';</script>";
    } else {
        echo "<p style='color:red'>Lỗi khi thêm: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý lưu sửa dịch vụ
if (isset($_POST['luu'])) {
    $id = (int)$_POST[$id_col];
    $ten = $_POST[$name_col];
    $gia = $_POST[$price_col];
    $mota = $_POST[$desc_col];

    $sql = "UPDATE `$table` SET `$name_col`=?, `$price_col`=?, `$desc_col`=? WHERE `$id_col`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $ten, $gia, $mota, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật dịch vụ thành công!');window.location='dichvu.php';</script>";
    } else {
        echo "<p style='color:red'>Lỗi khi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý xóa dịch vụ
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $sql = "DELETE FROM `$table` WHERE `$id_col`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa dịch vụ thành công!');window.location='dichvu.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi khi xóa: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Tìm kiếm
$search = '';
$where = '';
$params = [];
if (isset($_POST['timkiem'])) {
    $search = $_POST['search'];
    $where = "WHERE `$name_col` LIKE ? OR `$desc_col` LIKE ?";
    $params = ['ss', '%' . $search . '%', '%' . $search . '%'];
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

// Kiểm tra ID đang sửa
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_data = null;
if ($edit_id) {
    $sql_edit = "SELECT * FROM `$table` WHERE `$id_col` = ?";
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
        max-width: 1200px;
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
    .form-row input, .form-row textarea {
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
    .form-row input:focus, .form-row textarea:focus {
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
    .form-actions button[name="them"], .form-actions button[name="luu"] {
        background-color: #28a745;
    }
    .form-actions button[name="them"]:hover, .form-actions button[name="luu"]:hover {
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
        color: #007bff;
    }
    .action-links a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    .action-links a.delete-link {
        color: #dc3545;
    }
    .action-links a.delete-link:hover {
        color: #c82333;
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
        <h2 class="main-title">Quản lý Dịch vụ</h2>
        <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm dịch vụ..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <div class="form-box">
        <h3>
            <i class="fas fa-tools"></i>
            <?= $edit_data ? 'Chỉnh sửa dịch vụ #' . htmlspecialchars($edit_data[$id_col]) : 'Thêm Dịch vụ Mới' ?>
        </h3>
        <form method="post">
            <?php if ($edit_data) { ?>
                <input type="hidden" name="<?= htmlspecialchars($id_col) ?>" value="<?= htmlspecialchars($edit_data[$id_col]) ?>">
            <?php } ?>
            <div class="form-row">
                <input type="text" name="<?= htmlspecialchars($name_col) ?>" placeholder="Tên dịch vụ" value="<?= $edit_data ? htmlspecialchars($edit_data[$name_col]) : '' ?>" required>
                <input type="number" step="0.01" name="<?= htmlspecialchars($price_col) ?>" placeholder="Giá" value="<?= $edit_data ? htmlspecialchars($edit_data[$price_col]) : '' ?>" required>
            </div>
            <div class="form-row">
                <textarea name="<?= htmlspecialchars($desc_col) ?>" placeholder="Mô tả"><?= $edit_data ? htmlspecialchars($edit_data[$desc_col]) : '' ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?= $edit_data ? 'luu' : 'them' ?>">
                    <i class="fas fa-<?= $edit_data ? 'save' : 'plus' ?>"></i> 
                    <?= $edit_data ? 'Lưu Sửa' : 'Thêm' ?>
                </button>
                <?php if ($edit_data) { ?>
                    <a href="dichvu.php"><i class="fas fa-times-circle"></i> Hủy</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên dịch vụ</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row[$id_col]) ?></td>
                    <td><?= htmlspecialchars($row[$name_col]) ?></td>
                    <td><?= number_format($row[$price_col], 0, ",", ".") ?> VNĐ</td>
                    <td><?= htmlspecialchars($row[$desc_col]) ?></td>
                    <td class="action-links">
                        <a href="?edit=<?= htmlspecialchars($row[$id_col]) ?>"><i class="fas fa-edit"></i> Sửa</a> |
                        <a href="#" class="delete-link" data-id="<?= htmlspecialchars($row[$id_col]) ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                    </td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Không tìm thấy dịch vụ nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<div id="delete-modal" class="modal">
    <div class="modal-content">
        <h4><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h4>
        <p>Bạn có chắc chắn muốn xóa dịch vụ này? Hành động này không thể hoàn tác.</p>
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