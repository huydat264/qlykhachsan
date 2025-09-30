<?php include __DIR__ . '/layouts/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
 body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }
    .container {
        padding: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .main-title {
        color: #1d4d84;
        font-size: 2.2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }
    .form-box {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border-left: 5px solid #1d4d84;
    }
    .form-box.edit-form {
         border-left: 5px solid #ffc107; /* Màu vàng cho form sửa */
    }
    .form-box h3 {
        color: #1d4d84;
        margin-top: 0;
        font-size: 1.5rem;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-box.edit-form h3 {
        color: #ffc107;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-row .full-width {
        grid-column: 1 / -1; 
    }
    .form-row input, .form-row select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-row input:focus, .form-row select:focus {
        border-color: #1d4d84;
        box-shadow: 0 0 5px rgba(29, 77, 132, 0.3);
        outline: none;
    }
    .form-row label {
        align-self: center;
        font-weight: 600;
        color: #555;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
    }
    .form-actions button {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
    }
    .form-actions button[name="dat_phong"] {
        background-color: #28a745;
    }
    .form-actions button[name="dat_phong"]:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    .form-actions button[name="cap_nhat_dat_phong"] {
        background-color: #ffc107;
        color: #333;
    }
     .form-actions button[name="cap_nhat_dat_phong"]:hover {
        background-color: #e0a800;
        transform: translateY(-2px);
    }
    .form-actions a.btn-huy {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
     .form-actions a.btn-huy:hover {
        background-color: #5a6268;
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
        text-decoration: none;
        font-weight: 600;
        margin-right: 15px;
        transition: color 0.2s;
    }
    .action-links a.edit-link {
        color: #ffc107;
    }
    .action-links a.edit-link:hover {
        color: #e0a800;
    }
    .action-links a.delete-link {
        color: #dc3545;
    }
    .action-links a.delete-link:hover {
        color: #c82333;
        text-decoration: underline;
    }
    /* Modal styles remain the same for consistency */
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
    <h2 class="main-title">Quản lý Đặt phòng</h2>

    <?php if ($editData): ?>
    <?php
        $phongOptions = array_merge([$editData], $phongTrong);
    ?>
    <div class="form-box edit-form">
        <h3><i class="fas fa-edit"></i> Sửa đặt phòng #<?= htmlspecialchars($editData['id_datphong']) ?></h3>
        <form method="post">
            <input type="hidden" name="id_datphong" value="<?= htmlspecialchars($editData['id_datphong']) ?>">
            <div class="form-row">
                <div class="full-width">
                    <label>Khách hàng:</label>
                    <input type="text" value="<?= htmlspecialchars($editData['ho_ten']) ?>" disabled>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label>Chọn phòng:</label>
                    <select name="id_phong" required>
                        <?php foreach ($phongOptions as $phong): 
                            $selected = ($editData['id_phong'] == $phong['id_phong']) ? 'selected' : '';
                        ?>
                            <option value="<?= $phong['id_phong'] ?>" <?= $selected ?>>Phòng <?= $phong['so_phong'] ?> - <?= $phong['loai_phong'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="edit_ngay_nhan">Ngày nhận:</label>
                    <input type="date" id="edit_ngay_nhan" name="ngay_nhan" value="<?= htmlspecialchars($editData['ngay_nhan']) ?>" required>
                </div>
                <div>
                    <label for="edit_ngay_tra">Ngày trả:</label>
                    <input type="date" id="edit_ngay_tra" name="ngay_tra" value="<?= htmlspecialchars($editData['ngay_tra']) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="edit_trang_thai">Trạng thái:</label>
                    <select id="edit_trang_thai" name="trang_thai" required>
                        <?php foreach (['Đã xác nhận', 'Đã hủy', 'Hoàn thành'] as $tt): 
                            $selected = ($editData['trang_thai'] == $tt) ? 'selected' : ''; ?>
                            <option value="<?= $tt ?>" <?= $selected ?>><?= $tt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <a href="index.php?controller=datphong" class="btn-huy"><i class="fas fa-times"></i> Hủy bỏ Sửa</a>
                <button type="submit" name="cap_nhat_dat_phong"><i class="fas fa-save"></i> Cập nhật</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="form-box">
    <h3><i class="fas fa-plus-circle"></i> Thông tin đặt phòng mới</h3>
    <form method="post">
        <div class="form-row">
            <select name="id_phong" required>
                <option value="">Chọn phòng trống...</option>
                <?php foreach ($phongTrong as $phong): ?>
                    <option value="<?= $phong['id_phong'] ?>">Phòng <?= $phong['so_phong'] ?> - <?= $phong['loai_phong'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Dropdown chọn khách hàng -->
            <select id="chon_khachhang" name="id_khachhang">
    <option value="">-- Chọn khách hàng --</option>
    <?php foreach ($khachhangList as $kh): ?>
        <option value="<?= $kh['id_khachhang'] ?>"
            data-hoten="<?= htmlspecialchars($kh['ho_ten']) ?>"
            data-cccd="<?= htmlspecialchars($kh['cccd']) ?>"
            data-sdt="<?= htmlspecialchars($kh['so_dien_thoai']) ?>"
            data-email="<?= htmlspecialchars($kh['email']) ?>"
            data-diachi="<?= htmlspecialchars($kh['dia_chi']) ?>"
            data-ngaysinh="<?= htmlspecialchars($kh['ngay_sinh']) ?>"
            data-gioitinh="<?= htmlspecialchars($kh['gioi_tinh']) ?>">
            <?= htmlspecialchars($kh['ho_ten']) ?> (<?= $kh['so_dien_thoai'] ?>)
        </option>
    <?php endforeach; ?>
</select>

        </div>

        <!-- Thông tin khách hàng -->
        <div class="form-row">
            <input type="text" id="ho_ten" name="ho_ten" placeholder="Họ và tên khách hàng" required>
            <input type="text" id="cccd" name="cccd" placeholder="CCCD" required>
        </div>
        <div class="form-row">
            <input type="date" name="ngay_sinh">
            <select name="gioi_tinh">
                <option value="">Giới tính...</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
            </select>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" placeholder="Số điện thoại" required>
        </div>
        <div class="form-row">
            <input type="email" id="email" name="email" placeholder="Email">
            <input type="text" id="dia_chi" name="dia_chi" placeholder="Địa chỉ">
        </div>

        <!-- Ngày nhận - trả -->
        <div class="form-row">
            <label for="ngay_nhan">Ngày nhận phòng:</label>
            <input type="date" id="ngay_nhan" name="ngay_nhan" required>
            <label for="ngay_tra">Ngày trả phòng:</label>
            <input type="date" id="ngay_tra" name="ngay_tra" required>
        </div>

        <div class="form-actions">
            <button type="submit" name="dat_phong"><i class="fas fa-calendar-check"></i> Đặt phòng</button>
        </div>
    </form>
</div>

    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID Đặt phòng</th>
                <th>Số phòng</th>
                <th>Tên Khách hàng</th>
                <th>CCCD</th>
                <th>SĐT</th>
                <th>Ngày nhận</th>
                <th>Ngày trả</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datPhongList as $row): ?>
            <tr>
                <td><?= $row['id_datphong'] ?></td>
                <td><?= $row['so_phong'] ?></td>
                <td><?= $row['ho_ten'] ?></td>
                <td><?= $row['cccd'] ?></td>
                <td><?= $row['so_dien_thoai'] ?></td>
                <td><?= $row['ngay_nhan'] ?></td>
                <td><?= $row['ngay_tra'] ?></td>
                <td><?= $row['trang_thai'] ?></td>
                <td class="action-links">
                    <a href="index.php?controller=datphong&sua=<?= $row['id_datphong'] ?>" class="edit-link"><i class="fas fa-edit"></i> Sửa</a>
                    <a href="#" class="delete-link" data-id="<?= $row['id_datphong'] ?>"><i class="fas fa-trash-alt"></i> Hủy</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<div id="delete-modal" class="modal">
    <div class="modal-content">
        <h4><i class="fas fa-exclamation-triangle"></i> Xác nhận hủy đặt phòng</h4>
        <p>Bạn có chắc chắn muốn hủy đặt phòng này? Hành động này không thể hoàn tác.</p>
        <div class="modal-buttons">
            <button id="confirm-delete"><i class="fas fa-check"></i> Hủy đặt phòng</button>
            <button id="cancel-delete"><i class="fas fa-times"></i> Hủy bỏ</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Xử lý xóa đặt phòng ---
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
            window.location.href = `index.php?controller=datphong&xoa=${currentId}`;
        }
    });

    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(e) {
        if (e.target === modal) modal.style.display = 'none';
    });

    // --- Xử lý auto-fill khách hàng ---
    const chonKH = document.getElementById('chon_khachhang');
    if (chonKH) {
        chonKH.addEventListener('change', function() {
            let opt = this.options[this.selectedIndex];
            if (opt.value !== "") {
                document.getElementById('ho_ten').value = opt.getAttribute('data-hoten') || '';
                document.getElementById('cccd').value = opt.getAttribute('data-cccd') || '';
                document.getElementById('so_dien_thoai').value = opt.getAttribute('data-sdt') || '';
                document.getElementById('email').value = opt.getAttribute('data-email') || '';
                document.getElementById('dia_chi').value = opt.getAttribute('data-diachi') || '';
                
                // ✅ Thêm ngày sinh + giới tính
                const ngaySinhInput = document.querySelector('input[name="ngay_sinh"]');
                const gioiTinhSelect = document.querySelector('select[name="gioi_tinh"]');
                if (ngaySinhInput) ngaySinhInput.value = opt.getAttribute('data-ngaysinh') || '';
                if (gioiTinhSelect) gioiTinhSelect.value = opt.getAttribute('data-gioitinh') || '';
            }
        });
    }
});
</script>


<?php include __DIR__ . '/layouts/footer.php'; ?>