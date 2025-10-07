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

    <?php $today = date('Y-m-d'); ?>
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
                            $loai_phong = isset($phong['loai_phong']) ? $phong['loai_phong'] : '';
                        ?>
                            <option value="<?= $phong['id_phong'] ?>" <?= $selected ?>>Phòng <?= $phong['so_phong'] ?> - <?= $loai_phong ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="edit_ngay_nhan">Ngày nhận:</label>
                    <input type="date" id="edit_ngay_nhan" name="ngay_nhan" value="<?= htmlspecialchars($editData['ngay_nhan']) ?>" required min="<?= $today ?>">
                </div>
                <div>
                    <label for="edit_ngay_tra">Ngày trả:</label>
                    <input type="date" id="edit_ngay_tra" name="ngay_tra" value="<?= htmlspecialchars($editData['ngay_tra']) ?>" required min="<?= $today ?>">
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
    <form method="post" id="datphong-form">
        <div class="form-row">
            <div>
                <label>Chọn phòng:</label>
                <select name="id_phong" required>
                    <option value="">Chọn phòng trống...</option>
                    <?php foreach ($phongTrong as $phong): ?>
                        <option value="<?= $phong['id_phong'] ?>">Phòng <?= $phong['so_phong'] ?> - <?= $phong['loai_phong'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Chọn khách hàng:</label>
                <select id="chon_khachhang" name="id_khachhang">
    <option value="">-- Khách hàng mới --</option>
    <?php foreach ($khachHangList as $kh): ?>
        <option value="<?= $kh['id_khachhang'] ?>" 
            data-info='<?= json_encode($kh) ?>'>
            <?= $kh['ho_ten'] ?> - <?= $kh['cccd'] ?>
        </option>
    <?php endforeach; ?>
</select>

            </div>
        </div>

        <div class="form-row">
            <input type="text" name="ho_ten" id="ho_ten" placeholder="Họ và tên khách hàng" required>
            <input type="text" name="cccd" id="cccd" placeholder="CCCD" required>
            <input type="date" name="ngay_sinh" id="ngay_sinh" required>
        </div>
        <div class="form-row">
            <select name="gioi_tinh" id="gioi_tinh" required>
                <option value="">Giới tính...</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
            </select>
            <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Số điện thoại" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div class="form-row">
            <input type="text" name="dia_chi" id="dia_chi" placeholder="Địa chỉ">
        </div>
        <div class="form-row">
            <label for="ngay_nhan">Ngày nhận phòng:</label>
            <input type="date" id="ngay_nhan" name="ngay_nhan" required min="<?= $today ?>">
            <label for="ngay_tra">Ngày trả phòng:</label>
            <input type="date" id="ngay_tra" name="ngay_tra" required min="<?= $today ?>">
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
                   <a href="#" class="delete-link" onclick="huyDatPhong(<?= $row['id_datphong'] ?>)">
    <i class="fas fa-trash-alt"></i> Hủy
</a>

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
// Tự động fill thông tin khách hàng
document.getElementById('chon_khachhang').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const info = selected.getAttribute('data-info');
    if (info) {
        const kh = JSON.parse(info);
        document.getElementById('ho_ten').value = kh.ho_ten;
        document.getElementById('cccd').value = kh.cccd;
        document.getElementById('ngay_sinh').value = kh.ngay_sinh;
        document.getElementById('gioi_tinh').value = kh.gioi_tinh;
        document.getElementById('so_dien_thoai').value = kh.so_dien_thoai;
        document.getElementById('email').value = kh.email;
        document.getElementById('dia_chi').value = kh.dia_chi;
    } else {
        document.querySelectorAll('#ho_ten,#cccd,#ngay_sinh,#gioi_tinh,#so_dien_thoai,#email,#dia_chi')
            .forEach(el => el.value = '');
    }
});

// Đảm bảo ngày trả > ngày nhận và ngày nhận không được nhỏ hơn hôm nay (theo trình duyệt)
function setMinDateInputs() {
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var dd = String(today.getDate()).padStart(2, '0');
    var todayStr = yyyy + '-' + mm + '-' + dd;
    var nhan = document.getElementById('ngay_nhan');
    var tra = document.getElementById('ngay_tra');
    if (nhan) nhan.min = todayStr;
    if (tra) tra.min = nhan && nhan.value ? nhan.value : todayStr;
}
var nhanInput = document.getElementById('ngay_nhan');
var traInput = document.getElementById('ngay_tra');
if (nhanInput && traInput) {
    nhanInput.addEventListener('change', setMinDateInputs);
    setMinDateInputs();
}

// Kiểm tra khi submit form
var datphongForm = document.getElementById('datphong-form');
if (datphongForm) {
    datphongForm.addEventListener('submit', function(e) {
        var nhan = nhanInput.value;
        var tra = traInput.value;
        if (!nhan || !tra) return;
        if (tra <= nhan) {
            alert('Ngày trả phải sau ngày nhận!');
            e.preventDefault();
        }
    });
}
</script>
<!-- Thư viện SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function huyDatPhong(id) {
    Swal.fire({
        title: 'Xác nhận hủy đặt phòng?',
        text: "Hành động này sẽ xóa đặt phòng khỏi hệ thống!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hủy đặt phòng',
        cancelButtonText: 'Quay lại'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Đã hủy!',
                text: 'Đặt phòng đã được hủy thành công.',
                icon: 'success',
                showConfirmButton: false,
                timer: 1200
            });
            setTimeout(() => {
                window.location.href = 'index.php?controller=datphong&xoa=' + id;
            }, 1300);
        }
    });
}
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>