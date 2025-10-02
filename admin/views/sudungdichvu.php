<?php include __DIR__ . '/layouts/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
        color: #106796ff;
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
        align-items: center; /* Thêm để căn giữa nút và link */
        gap: 15px; /* Khoảng cách giữa nút và link */
        margin-top: 20px;
    }
    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .btn-them { background-color: #28a745; color: white; }
    .btn-them:hover { background-color: #218838; }
    .btn-capnhat { background-color: #ffc107; color: #212529; }
    .btn-capnhat:hover { background-color: #e0a800; }
    .btn-huy { color: #6c757d; }
    .table-container {
        background-color: #fff;
        border-radius: 12px;
        overflow-x: auto; /* Thêm để bảng không bị vỡ trên mobile */
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
        white-space: nowrap; /* Chống xuống dòng */
    }
    th {
        background-color: #124070ff;
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
    .action-btn { color: #0056b3; font-weight: 600; text-decoration: none; }
    .action-btn:hover { text-decoration: underline; }
    .alert-message {
        padding: 15px; border-radius: 8px; margin-bottom: 20px;
        font-weight: 500; text-align: center;
    }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
<!-- TÌM KIẾM DỊCH VỤ -->
<div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
    <form method="get" action="" style="display: flex; gap: 5px; align-items: center;">
        <input type="hidden" name="controller" value="sudungdichvu">
        <input type="hidden" name="action" value="index">

        <input type="text" name="search" placeholder="Tìm dịch vụ..." 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
               style="padding: 8px 12px; font-size: 14px; border-radius: 8px; border: 1px solid #ccc; width: 200px; transition: all 0.3s;">
        
        <button type="submit" 
                style="padding: 8px 12px; font-size: 14px; border-radius: 8px; border: none; background-color: #124070ff; color: #fff; cursor: pointer; transition: all 0.3s;">
            <i class="fas fa-search"></i> Tìm
        </button>
    </form>
</div>

<style>
    /* Hiệu ứng hover */
    form input[name="search"]:focus {
        border-color: #124070ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(18, 64, 112, 0.2);
    }
    form button:hover {
        background-color: #0d3055ff;
    }
</style>

    <div class="form-box">
        <!-- TIÊU ĐỀ FORM SẼ THAY ĐỔI TÙY THEO THÊM HAY SỬA -->
        <h3><i class="fas fa-concierge-bell"></i> <?= isset($edit_data) ? 'Sửa dịch vụ đã sử dụng' : 'Thêm dịch vụ cho phòng' ?></h3>

        <form method="post" action="index.php?controller=sudungdichvu&action=process">
            <!-- NẾU LÀ SỬA THÌ CẦN TRƯỜNG ID ẨN NÀY -->
            <?php if (isset($edit_data)): ?>
                <input type="hidden" name="id_sudungdv" value="<?= htmlspecialchars($edit_data['id_sudungdv']) ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label>Phòng & Khách hàng</label>
                    <select name="id_datphong" required>
                        <option value="">-- Chọn phòng & khách hàng --</option>
                        <?php foreach ($phong_dat_result as $phong): ?>
                            <option value="<?= htmlspecialchars($phong['id_datphong']) ?>" 
                                <?= (isset($edit_data) && $edit_data['id_datphong'] == $phong['id_datphong']) ? 'selected' : '' ?>>
                                Phòng <?= htmlspecialchars($phong['so_phong']) ?> (Khách: <?= htmlspecialchars($phong['ho_ten']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dịch vụ</label>
                    <select name="id_dichvu" required>
                        <option value="">-- Chọn dịch vụ --</option>
                        <?php foreach ($dichvu_result as $dv): ?>
                            <option value="<?= htmlspecialchars($dv['id_dichvu']) ?>"
                                <?= (isset($edit_data) && $edit_data['id_dichvu'] == $dv['id_dichvu']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dv['ten_dich_vu']) ?> (<?= number_format($dv['gia'],0,",",".") ?> VND)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" name="so_luong" min="1" required 
                           value="<?= isset($edit_data) ? htmlspecialchars($edit_data['so_luong']) : '1' ?>">
                </div>
            </div>
            <div class="form-actions">
                <!-- NÚT BẤM CŨNG SẼ THAY ĐỔI TÙY THEO THÊM HAY SỬA -->
                <?php if (isset($edit_data)): ?>
                    <a href="index.php?controller=sudungdichvu" class="btn-huy">Hủy</a>
                    <button type="submit" name="capnhat_sudungdv" class="btn btn-capnhat">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                <?php else: ?>
                    <button type="submit" name="them_sudungdv" class="btn btn-them">
                        <i class="fas fa-plus-circle"></i> Thêm
                    </button>
                <?php endif; ?>
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
                    <th>Hành động</th> <!-- CỘT MỚI -->
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sudungdv_result)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Chưa có dịch vụ nào được sử dụng.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sudungdv_result as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_sudungdv']) ?></td>
                            <td><?= htmlspecialchars($row['so_phong']) ?></td>
                            <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($row['ten_dich_vu']) ?></td>
                            <td><?= htmlspecialchars($row['so_luong']) ?></td>
                            <td><?= number_format($row['thanh_tien'], 0, ",", ".") ?> VND</td>
                            <td>
                                <!-- NÚT SỬA MỚI -->
                                <a class="action-btn" href="index.php?controller=sudungdichvu&action=index&sua=<?= $row['id_sudungdv'] ?>">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>

