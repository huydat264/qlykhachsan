<?php include __DIR__ . '/layouts/header.php'; ?>
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
        font-size: 1rem;
    }
    .search-form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
    }
    .search-form button:hover {
        background-color: #0056b3;
    }
    .form-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    }
    .form-row textarea {
        height: 100px;
        resize: vertical;
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
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            <input type="text" name="search" placeholder="Tìm kiếm dịch vụ..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <div class="form-box">
        <h3>
            <i class="fas fa-tools"></i>
            <?= $edit_data ? 'Chỉnh sửa dịch vụ #' . htmlspecialchars($edit_data['id_dichvu']) : 'Thêm Dịch vụ Mới' ?>
        </h3>
        <form method="post">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_dichvu" value="<?= htmlspecialchars($edit_data['id_dichvu']) ?>">
            <?php endif; ?>
            <div class="form-row">
                <input type="text" name="ten_dich_vu" placeholder="Tên dịch vụ" value="<?= $edit_data ? htmlspecialchars($edit_data['ten_dich_vu']) : '' ?>" required>
                <input type="number" step="0.01" name="gia" placeholder="Giá" value="<?= $edit_data ? htmlspecialchars($edit_data['gia']) : '' ?>" required>
            </div>
            <div class="form-row">
                <textarea name="mo_ta" placeholder="Mô tả"><?= $edit_data ? htmlspecialchars($edit_data['mo_ta']) : '' ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?= $edit_data ? 'luu' : 'them' ?>">
                    <i class="fas fa-<?= $edit_data ? 'save' : 'plus' ?>"></i> 
                    <?= $edit_data ? 'Lưu Sửa' : 'Thêm' ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="index.php?controller=dichvu"><i class="fas fa-times-circle"></i> Hủy</a>
                <?php endif; ?>
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
            <?php if (!empty($dichvuList)): ?>
                <?php foreach ($dichvuList as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_dichvu']) ?></td>
                    <td><?= htmlspecialchars($row['ten_dich_vu']) ?></td>
                    <td><?= number_format($row['gia'], 0, ",", ".") ?> VNĐ</td>
                    <td><?= htmlspecialchars($row['mo_ta']) ?></td>
                    <td class="action-links">
                        <a href="index.php?controller=dichvu&edit=<?= htmlspecialchars($row['id_dichvu']) ?>"><i class="fas fa-edit"></i> Sửa</a> |
                        <a href="#" class="delete-link" data-id="<?= htmlspecialchars($row['id_dichvu']) ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
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
            <!-- Nút xác nhận xóa -->
            <button id="confirm-delete"><i class="fas fa-trash-alt"></i> Xóa</button>
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
            // Redirect đúng tới controller + action xóa
            window.location.href = `index.php?controller=dichvu&xoa=${currentId}`;
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
<?php include __DIR__ . '/layouts/footer.php'; ?>