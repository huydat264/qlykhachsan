<?php
include __DIR__ . '/layouts/header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    body {font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f9; margin: 0; padding: 0;}
    .container {padding: 30px; max-width: 1400px; margin: 0 auto;}
    .header-container {display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 25px;}
    .main-title {color: #1d4d84; font-size: 2.2rem; font-weight: 600; text-transform: uppercase; margin: 0;}
    .search-form {display: flex; gap: 10px; align-items: center;}
    .search-form input {width: 300px; padding: 10px 15px; border: 1px solid #ced4da; border-radius: 25px;}
    .search-form button {padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;}
    .form-box {background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px;}
    .form-box h3 {color: #1d4d84; margin-top: 0; font-size: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;}
    .form-row {display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;}
    .form-row input, .form-row select {width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px;}
    .form-actions {display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px;}
    .form-actions button {padding: 12px 25px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; color: #fff;}
    .btn-them {background-color: #28a745;} .btn-them:hover {background-color: #218838;}
    .btn-capnhat {background-color: #ffc107; color: #000;} .btn-capnhat:hover {background-color: #e0a800;}
    table {width: 100%; border-collapse: collapse; background-color: #fff; border-radius: 12px; overflow: hidden; margin-top: 20px;}
    th, td {padding: 15px; border-bottom: 1px solid #dee2e6;}
    th {background-color: #1d4d84; color: #fff; text-transform: uppercase;}
    tr:nth-child(even) {background-color: #f9fbfd;} tr:hover {background-color: #eaf3fd;}
    .action-btn {display: inline-block; padding: 6px 12px; border-radius: 6px; background-color: #ffc107; color: #000; font-weight: 600; text-decoration: none;}
    .action-btn:hover {background-color: #e0a800;}
</style>

<main class="container">
     <!-- Thông báo lỗi / thành công -->
    <?php if (isset($_SESSION['error'])): ?>
        <div style="padding: 12px; margin-bottom: 15px; border-radius: 8px; background-color: #f8d7da; color: #721c24; font-weight: 600;">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px; margin-bottom: 15px; border-radius: 8px; background-color: #d4edda; color: #155724; font-weight: 600;">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <div class="header-container">
        <h2 class="main-title">Quản lý Khách hàng</h2>
        <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Tìm kiếm theo họ tên, SĐT..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" name="timkiem"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>

    <div class="form-box">
        <h3><i class="fas fa-user-plus"></i> <?= (isset($edit_data['id_khachhang']) && $edit_data['id_khachhang']) ? "Sửa Khách hàng" : "Thêm Khách hàng Mới" ?></h3>
        <form method="post" action="index.php?controller=khachhang&action=createOrUpdate">
    <?php if (isset($edit_data['id_khachhang']) && $edit_data['id_khachhang']): ?>
        <input type="hidden" name="id_khachhang" value="<?= $edit_data['id_khachhang'] ?>">
    <?php endif; ?>

    <div class="form-row">
        <input type="number" name="tai_khoan_khachhang_id" placeholder="ID tài khoản" value="<?= $edit_data['tai_khoan_khachhang_id'] ?? '' ?>">
        <input type="text" name="ho_ten" placeholder="Họ tên" value="<?= $edit_data['ho_ten'] ?? '' ?>" required>
        <input type="date" name="ngay_sinh" value="<?= $edit_data['ngay_sinh'] ?? '' ?>">
        <select name="gioi_tinh">
            <option value="">Giới tính</option>
            <option value="Nam" <?= ($edit_data && $edit_data['gioi_tinh']=='Nam')?'selected':'' ?>>Nam</option>
            <option value="Nữ" <?= ($edit_data && $edit_data['gioi_tinh']=='Nữ')?'selected':'' ?>>Nữ</option>
            <option value="Khác" <?= ($edit_data && $edit_data['gioi_tinh']=='Khác')?'selected':'' ?>>Khác</option>
        </select>
        <input type="tel" name="so_dien_thoai" placeholder="Số điện thoại" 
   value="<?= $edit_data['so_dien_thoai'] ?? '' ?>" 
   oninput="this.value=this.value.replace(/[^0-9]/g,'');" 
   required>


        <input type="email" name="email" placeholder="Email" value="<?= $edit_data['email'] ?? '' ?>">
        <input type="text" name="cccd" placeholder="CCCD" value="<?= $edit_data['cccd'] ?? '' ?>">
       <input type="text" 
       name="dia_chi" 
       placeholder="Địa chỉ (quê quán)" 
       value="<?= $edit_data['dia_chi'] ?? '' ?>" 
       required>

    </div>

    <div class="form-actions">
        <?php if (isset($edit_data['id_khachhang']) && $edit_data['id_khachhang']): ?>
            <button type="submit" name="capnhat" class="btn-capnhat">
                <i class="fas fa-edit"></i> Cập nhật
            </button>
        <?php else: ?>
            <button type="submit" name="them" class="btn-them">
                <i class="fas fa-plus-circle"></i> Thêm
            </button>
        <?php endif; ?>
    </div>
</form>

    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>ID Tài khoản</th><th>Họ tên</th><th>Ngày sinh</th>
                <th>Giới tính</th><th>SĐT</th><th>Email</th><th>CCCD</th><th>Địa chỉ</th><th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($khachhangs)): ?>
                <?php foreach($khachhangs as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_khachhang']) ?></td>
                        <td><?= htmlspecialchars($row['tai_khoan_khachhang_id']) ?></td>
                        <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                        <td><?= htmlspecialchars($row['ngay_sinh']) ?></td>
                        <td><?= htmlspecialchars($row['gioi_tinh']) ?></td>
                        <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['cccd']) ?></td>
                        <td><?= htmlspecialchars($row['dia_chi']) ?></td>
                        <td>
                            <a class="action-btn" href="index.php?controller=khachhang&action=index&sua=<?= $row['id_khachhang'] ?>">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;">Không tìm thấy khách hàng</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/layouts/footer.php'; ?>