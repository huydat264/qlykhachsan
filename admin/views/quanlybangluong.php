<?php
 include __DIR__ . '/layouts/header.php'; 
require_login();
check_permission(['ADMIN','NHANVIEN']);
?>
<style>
    /* CSS giữ nguyên, thêm icon cho đẹp */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
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
        margin-bottom: 15px;
    }
   
.form-group {
    flex: 1;
    min-width: 200px;
    margin-bottom: 16px;
    position: relative;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
    transition: color 0.3s ease;
}

.form-group:hover label {
    color: #c80f91ff; /* nổi bật label khi hover */
}


/* Rút ngắn select nhân viên */
select[name="id_nhanvien"] {
    width: 560px;       /* chỉnh độ dài mong muốn */
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px 16px;
}

/* Hover & Focus vẫn giữ nguyên */
select[name="id_nhanvien"]:hover {
    border-color: #a2104fff;
    background-color: #f9fbff;
}

select[name="id_nhanvien"]:focus {
    border-color: #00ffe5ff;
    box-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
}




    .form-row input, .form-row select, .form-row textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-row input:focus, .form-row select:focus, .form-row textarea:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }
    /* Thêm style cho trường bị disabled/readonly */
    .form-row input[readonly], .form-row select:disabled {
        background-color: #e9ecef;
        opacity: 0.8;
        cursor: not-allowed;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }
    .form-actions button, .form-actions a {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .form-actions button[name="submit"] { background-color: #108544ff; }
    .form-actions button[name="submit"]:hover { background-color: #9b5d0bff; transform: translateY(-2px); }
    .form-actions a.cancel-btn { background-color: #6c757d; }
    .form-actions a.cancel-btn:hover { background-color: #5a6268; transform: translateY(-2px); }
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
        background-color: #1d3484ff;
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
    td a { color: #ab1d0bff; text-decoration: none; font-weight: 600; transition: color 0.3s;}
    td a:hover { color: #e11797ff; text-decoration: underline;}
    .currency { text-align: right; }
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
        <h2 class="main-title">Quản lý Bảng Lương</h2>
    </div>

    <?php if ($message): ?>
        <div class="alert-message <?= $error ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-box">
        <h3><i class="fas fa-calculator"></i> <?= $edit_data ? 'Chỉnh Sửa Bảng Lương' : 'Thêm Bảng Lương Mới' ?></h3>
        <form method="post" action="index.php?controller=quanlybangluong">

            <?php if ($edit_data): ?>
                <input type="hidden" name="id_bangluong" value="<?= htmlspecialchars($edit_data['id_bangluong']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="id_nhanvien">Nhân viên:</label>
                <select id="id_nhanvien" name="id_nhanvien" required <?= $edit_data ? 'disabled' : '' ?>>
                    <option value="">-- Chọn nhân viên --</option>
                    <?php foreach ($nhanvien_list as $nv): ?>
                        <option value="<?= htmlspecialchars($nv['id_nhanvien']) ?>"
                            <?= $edit_data && $edit_data['id_nhanvien'] == $nv['id_nhanvien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nv['ho_ten']) ?> (<?= htmlspecialchars($nv['chuc_vu']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_nhanvien" value="<?= htmlspecialchars($edit_data['id_nhanvien']) ?>">
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ho_ten">Họ Tên</label>
                    <input type="text" id="ho_ten" placeholder="Họ Tên" value="<?= $edit_data ? htmlspecialchars($edit_data['ho_ten']) : '' ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="chuc_vu">Chức vụ</label>
                    <input type="text" id="chuc_vu" placeholder="Chức vụ" value="<?= $edit_data ? htmlspecialchars($edit_data['chuc_vu']) : '' ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="thang">Tháng</label>
                    <input type="number" name="thang" placeholder="Tháng" min="1" max="12" required 
                        value="<?= $edit_data ? htmlspecialchars($edit_data['thang']) : date('m') ?>" <?= $edit_data ? 'readonly' : '' ?>>
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="thang" value="<?= htmlspecialchars($edit_data['thang']) ?>">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="nam">Năm</label>
                    <input type="number" name="nam" placeholder="Năm" min="2000" max="2100" required 
                        value="<?= $edit_data ? htmlspecialchars($edit_data['nam']) : date('Y') ?>" <?= $edit_data ? 'readonly' : '' ?>>
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="nam" value="<?= htmlspecialchars($edit_data['nam']) ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="luong_co_ban">Lương cơ bản (VNĐ)</label>
                    <input type="text" id="luong_co_ban" placeholder="Lương cơ bản" value="<?= $edit_data ? number_format($edit_data['luong_co_ban']) : '' ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="thuong">Tiền thưởng (VNĐ)</label>
                    <input type="number" name="thuong" placeholder="Tiền thưởng" min="0" required value="<?= $edit_data ? htmlspecialchars($edit_data['thuong']) : 0 ?>">
                </div>
                <div class="form-group">
                    <label for="phat">Tiền phạt (VNĐ)</label>
                    <input type="number" name="phat" placeholder="Tiền phạt" min="0" required value="<?= $edit_data ? htmlspecialchars($edit_data['phat']) : 0 ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit"><i class="fas fa-save"></i> <?= $edit_data ? 'Cập nhật' : 'Thêm mới' ?></button>
                <?php if ($edit_data): ?>
                    <a href="index.php?controller=quanlybangluong" class="cancel-btn"><i class="fas fa-times"></i> Hủy</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nhân viên</th>
                    <th>Chức vụ</th>
                    <th>Tháng/Năm</th>
                    <th>Ngày công</th>
                    <th class="currency">Thưởng</th>
                    <th class="currency">Phạt</th>
                    <th class="currency">Lương cơ bản</th>
                    <th class="currency">Tổng lương</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bangluong_list)): ?>
                    <?php $stt = 1; ?>
                    <?php foreach ($bangluong_list as $bl): ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= htmlspecialchars($bl['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($bl['chuc_vu']) ?></td>
                            <td><?= htmlspecialchars($bl['thang']) ?>/<?= htmlspecialchars($bl['nam']) ?></td>
                            <td><?= htmlspecialchars($bl['so_ngay_cong']) ?></td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['thuong'])) ?> VNĐ</td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['phat'])) ?> VNĐ</td>
                            <td class="currency"><?= htmlspecialchars(number_format($bl['luong_co_ban'])) ?> VNĐ</td>
                            <td class="currency"><strong><?= htmlspecialchars(number_format($bl['tong_luong'])) ?> VNĐ</strong></td>
                            <td>
                              <a href="index.php?controller=quanlybangluong&edit=<?= htmlspecialchars($bl['id_bangluong']) ?>"><i class="fas fa-edit"></i> Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">Chưa có dữ liệu bảng lương nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    // Chuyển danh sách nhân viên từ PHP sang JavaScript
    const nhanVienList = <?= json_encode($nhanvien_list) ?>;

    // Hàm định dạng tiền tệ (thêm dấu phẩy)
    function formatCurrency(number) {
        if (number === null || number === undefined) return '';
        // Đảm bảo số là một chuỗi hoặc số và chuyển sang số
        const num = parseFloat(number); 
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    // Lắng nghe sự kiện khi chọn nhân viên
    document.getElementById('id_nhanvien').addEventListener('change', function() {
        const selectedId = this.value;
        const hoTenInput = document.getElementById('ho_ten');
        const chucVuInput = document.getElementById('chuc_vu');
        const luongCoBanInput = document.getElementById('luong_co_ban');

        // Reset các trường khi không chọn gì
        if (!selectedId) {
            hoTenInput.value = '';
            chucVuInput.value = '';
            luongCoBanInput.value = '';
            return;
        }

        // Tìm nhân viên trong danh sách
        // Dùng == thay vì === vì ID từ JS là string, từ PHP có thể là number
        const selectedNhanVien = nhanVienList.find(nv => nv.id_nhanvien == selectedId);

        if (selectedNhanVien) {
            hoTenInput.value = selectedNhanVien.ho_ten;
            chucVuInput.value = selectedNhanVien.chuc_vu;
            // Hiển thị lương cơ bản đã định dạng
            luongCoBanInput.value = formatCurrency(selectedNhanVien.luong_co_ban);
        }
    });

    // Gọi sự kiện khi trang tải xong để điền dữ liệu nếu đang ở chế độ chỉnh sửa
    window.addEventListener('load', function() {
        // Nếu ở chế độ chỉnh sửa, các trường đã được điền sẵn qua PHP,
        // chỉ cần định dạng lại Lương cơ bản cho đẹp
        const luongCoBanElement = document.getElementById('luong_co_ban');
        if (luongCoBanElement && luongCoBanElement.value) {
            // Lấy giá trị hiện tại (chưa định dạng)
            const rawValue = luongCoBanElement.value.replace(/[^0-9.]/g, ''); 
            luongCoBanElement.value = formatCurrency(rawValue);
        }

        // Kích hoạt sự kiện change nếu đang ở chế độ thêm mới (để điền tự động)
        const selectedId = document.getElementById('id_nhanvien').value;
        if (selectedId && !document.querySelector('input[name="id_bangluong"]')) {
             document.getElementById('id_nhanvien').dispatchEvent(new Event('change'));
        }
    });
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>