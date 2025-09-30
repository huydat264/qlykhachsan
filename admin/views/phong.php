<?php include __DIR__ . '/layouts/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- CSS giữ nguyên như bạn đã viết -->
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
        <form method="post" action="?controller=phong&action=index" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm phòng..." 
                   value="<?= htmlspecialchars($data['search'] ?? '') ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <!-- Form chỉnh sửa -->
<?php if (!empty($data['edit_data'])): ?>
<div class="form-box">
    <h3><i class="fas fa-edit"></i> Chỉnh sửa phòng #<?= htmlspecialchars($data['edit_data']['id_phong']) ?></h3>
    <form method="post" action="?controller=phong&action=update">
        <input type="hidden" name="id_phong" value="<?= htmlspecialchars($data['edit_data']['id_phong']) ?>">
        <div class="form-row">
            <input type="text" name="so_phong" placeholder="Số phòng" 
                   value="<?= htmlspecialchars($data['edit_data']['so_phong']) ?>" required>
            <select name="loai_phong">
                <option value="Standard" <?= $data['edit_data']['loai_phong']=='Standard'?'selected':'' ?>>Standard</option>
                <option value="Deluxe" <?= $data['edit_data']['loai_phong']=='Deluxe'?'selected':'' ?>>Deluxe</option>
                <option value="Suite" <?= $data['edit_data']['loai_phong']=='Suite'?'selected':'' ?>>Suite</option>
            </select>
            <input type="number" name="gia_phong" placeholder="Giá phòng" 
                   value="<?= htmlspecialchars($data['edit_data']['gia_phong']) ?>" required>
            <input type="number" name="so_luong_nguoi" placeholder="Số người tối đa" 
                   value="<?= htmlspecialchars($data['edit_data']['so_luong_nguoi']) ?>" required>
            <select name="trang_thai">
                <option value="Trống" <?= $data['edit_data']['trang_thai']=='Trống'?'selected':'' ?>>Trống</option>
                <option value="Đang đặt" <?= $data['edit_data']['trang_thai']=='Đang đặt'?'selected':'' ?>>Đang đặt</option>
                <option value="Đã đặt" <?= $data['edit_data']['trang_thai']=='Đã đặt'?'selected':'' ?>>Đã đặt</option>
                <option value="Bảo trì" <?= $data['edit_data']['trang_thai']=='Bảo trì'?'selected':'' ?>>Bảo trì</option>
            </select>
        </div>
        <div class="form-row">
            <textarea name="mo_ta" placeholder="Mô tả phòng"><?= htmlspecialchars($data['edit_data']['mo_ta']) ?></textarea>
            <input type="text" name="anh" placeholder="Link ảnh phòng" 
                   value="<?= htmlspecialchars($data['edit_data']['anh']) ?>">
        </div>
        <div class="form-actions">
            <button type="submit" name="luu"><i class="fas fa-save"></i> Lưu Sửa</button>
            <a href="?controller=phong&action=index"><i class="fas fa-times-circle"></i> Hủy</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Bảng danh sách phòng -->
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
        <?php if (!empty($data['phongs'])): ?>
            <?php foreach ($data['phongs'] as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_phong']) ?></td>
                <td>
                    <?php if (!empty($row['anh'])): ?>
                        <img src="<?= htmlspecialchars($row['anh']) ?>" alt="Ảnh phòng" class="table-image">
                    <?php else: ?>
                        <span>(Chưa có)</span>
                    <?php endif; ?>
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
                <td><?= htmlspecialchars($row['ten_khach_hang'] ?? '(Không có)') ?></td>
                <td class="action-links">
                    <a href="?controller=phong&id=<?= $row['id_phong'] ?>"><i class="fas fa-edit"></i> Sửa</a> 
                    <?php if ($row['trang_thai'] != 'Trống'): ?>
                        | <a href="#" class="delete-link" data-id="<?= $row['id_phong'] ?>"><i class="fas fa-trash-alt"></i> Xóa</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="10" style="text-align: center;">Không tìm thấy phòng nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</main>

<!-- Modal xác nhận xóa -->
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
                window.location.href = `?controller=phong&action=delete&id=${currentId}`;
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
