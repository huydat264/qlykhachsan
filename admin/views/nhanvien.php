
<?php
include __DIR__ . '/layouts/header.php';
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
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm theo họ tên, chức vụ..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
            <input type="hidden" name="controller" value="nhanvien">
            <input type="hidden" name="action" value="index">
        </form>
    </div>

    <?php if ($this->is_admin): ?>
    <!-- FORM chỉ hiện với ADMIN -->
    <div class="form-box">
        <h3>
            <?= $edit_data 
                ? '<i class="fas fa-user-edit"></i> Chỉnh sửa nhân viên #' . htmlspecialchars($edit_data['id_nhanvien']) 
                : '<i class="fas fa-user-plus"></i> Thêm Nhân viên Mới' ?>
        </h3>
        <form method="post">
            <?php if ($edit_data) { ?>
                <input type="hidden" name="id_nhanvien" value="<?= htmlspecialchars($edit_data['id_nhanvien']) ?>">
            <?php } ?>
            <div class="form-row">
                <input type="number" name="tai_khoan_nhanvien_id" placeholder="ID Tài khoản" value="<?= $edit_data['tai_khoan_nhanvien_id'] ?? '' ?>" required>
                <input type="text" name="ho_ten" placeholder="Họ tên" value="<?= $edit_data['ho_ten'] ?? '' ?>" required>
                <input type="text" name="chuc_vu" placeholder="Chức vụ" value="<?= $edit_data['chuc_vu'] ?? '' ?>">
                <input type="number" step="0.01" name="luong_co_ban" placeholder="Lương cơ bản" value="<?= $edit_data['luong_co_ban'] ?? '' ?>" required>
            </div>
            <div class="form-row">
                <input type="date" name="ngay_vao_lam" value="<?= $edit_data['ngay_vao_lam'] ?? '' ?>">
                <input type="text" name="so_dien_thoai" placeholder="SĐT" value="<?= $edit_data['so_dien_thoai'] ?? '' ?>">
                <input type="email" name="email" placeholder="Email" value="<?= $edit_data['email'] ?? '' ?>">
            </div>
            <div class="form-actions">
                <button type="submit" name="<?= $edit_data ? 'luu' : 'them' ?>">
                    <?= $edit_data ? '<i class="fas fa-save"></i> Lưu Sửa' : '<i class="fas fa-plus-circle"></i> Thêm' ?>
                </button>
                <?php if ($edit_data) { ?>
                    <a href="index.php?controller=nhanvien&action=index" class="btn-cancel"><i class="fas fa-times-circle"></i> Hủy</a>
                <?php } ?>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Bảng danh sách -->
    <table>
        <thead>
            <tr>
                <th>ID</th><th>ID Tài khoản</th><th>Họ tên</th><th>Chức vụ</th>
                <th>Lương</th><th>Ngày vào</th><th>SĐT</th><th>Email</th>
                <?php if ($this->is_admin): ?>
                    <th>Hành động</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($nhanviens): ?>
                <?php foreach ($nhanviens as $nv): ?>
                    <tr>
                        <td><?= $nv['id_nhanvien'] ?></td>
                        <td><?= $nv['tai_khoan_nhanvien_id'] ?></td>
                        <td><?= htmlspecialchars($nv['ho_ten']) ?></td>
                        <td><?= htmlspecialchars($nv['chuc_vu']) ?></td>
                        <td><?= number_format($nv['luong_co_ban'],0,",",".") ?></td>
                        <td><?= $nv['ngay_vao_lam'] ?></td>
                        <td><?= $nv['so_dien_thoai'] ?></td>
                        <td><?= $nv['email'] ?></td>
                        <?php if ($this->is_admin): ?>
                            <td class="action-links">
                                <a href="?controller=nhanvien&action=index&edit=<?= $nv['id_nhanvien'] ?>" class="edit-btn">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $this->is_admin ? 9 : 8 ?>" style="text-align:center;">
                        Không tìm thấy nhân viên nào.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>