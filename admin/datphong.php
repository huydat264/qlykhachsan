<?php
include 'header.php';
include 'db.php';
include 'auth.php'; // Gọi file auth
require_login();    // Khóa trang, yêu cầu đăng nhập
check_permission(['ADMIN', 'NHANVIEN']); // Cả 2 role đều được vào

// Xử lý khi nhấn nút Đặt phòng (GIỮ NGUYÊN)
if (isset($_POST['dat_phong'])) {
    $id_phong = $_POST['id_phong'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $cccd = $_POST['cccd'];
    $dia_chi = $_POST['dia_chi'];
    $ngay_nhan = $_POST['ngay_nhan'];
    $ngay_tra = $_POST['ngay_tra'];

    // Kiểm tra ngày nhận phòng không thể lớn hơn ngày trả phòng
    $date_ngay_nhan = new DateTime($ngay_nhan);
    $date_ngay_tra = new DateTime($ngay_tra);

    if ($date_ngay_tra < $date_ngay_nhan) {
        echo "<script>alert('Lỗi: Ngày trả phòng không thể nhỏ hơn ngày nhận phòng.');</script>";
    } else {
        $conn->begin_transaction();

        try {
            // Kiểm tra xem khách hàng đã tồn tại chưa
            $sql_khachhang = "SELECT `id_khachhang` FROM `KhachHang` WHERE `cccd` = ?";
            $stmt_khachhang = $conn->prepare($sql_khachhang);
            $stmt_khachhang->bind_param("s", $cccd);
            $stmt_khachhang->execute();
            $result_khachhang = $stmt_khachhang->get_result();
            
            if ($result_khachhang->num_rows > 0) {
                $khachhang = $result_khachhang->fetch_assoc();
                $id_khachhang = $khachhang['id_khachhang'];
            } else {
                // Nếu chưa có, tạo tài khoản và khách hàng mới
                $username = 'kh' . rand(10000, 99999);
                $password = password_hash('123456', PASSWORD_DEFAULT); // Mật khẩu mặc định
                $sql_taikhoan = "INSERT INTO `TaiKhoan` (`username`, `password`, `role`) VALUES (?, ?, 'USER')";
                $stmt_taikhoan = $conn->prepare($sql_taikhoan);
                $stmt_taikhoan->bind_param("ss", $username, $password);
                $stmt_taikhoan->execute();
                $id_taikhoan = $stmt_taikhoan->insert_id;
                $stmt_taikhoan->close();

                $sql_khachhang_insert = "INSERT INTO `KhachHang` (`tai_khoan_khachhang_id`, `ho_ten`, `ngay_sinh`, `gioi_tinh`, `so_dien_thoai`, `email`, `cccd`, `dia_chi`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_khachhang_insert = $conn->prepare($sql_khachhang_insert);
                $stmt_khachhang_insert->bind_param("isssssss", $id_taikhoan, $ho_ten, $ngay_sinh, $gioi_tinh, $so_dien_thoai, $email, $cccd, $dia_chi);
                $stmt_khachhang_insert->execute();
                $id_khachhang = $stmt_khachhang_insert->insert_id;
                $stmt_khachhang_insert->close();
            }
            $stmt_khachhang->close();

            // Thêm bản ghi vào bảng DatPhong
            $sql_datphong = "INSERT INTO `DatPhong` (`id_khachhang`, `id_phong`, `ngay_dat`, `ngay_nhan`, `ngay_tra`, `trang_thai`) VALUES (?, ?, CURDATE(), ?, ?, 'Đã xác nhận')";
            $stmt_datphong = $conn->prepare($sql_datphong);
            $stmt_datphong->bind_param("iiss", $id_khachhang, $id_phong, $ngay_nhan, $ngay_tra);
            $stmt_datphong->execute();
            $stmt_datphong->close();

            // Cập nhật trạng thái phòng thành 'Đã đặt'
            $sql_phong = "UPDATE `Phong` SET `trang_thai` = 'Đã đặt' WHERE `id_phong` = ?";
            $stmt_phong = $conn->prepare($sql_phong);
            $stmt_phong->bind_param("i", $id_phong);
            $stmt_phong->execute();
            $stmt_phong->close();

            $conn->commit();
            echo "<script>alert('Đặt phòng thành công!');window.location='datphong.php';</script>";
            exit;
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
        }
    }
}

// ----------------------------------------------------------------------
## Xử lý Cập nhật đặt phòng (Chức năng mới)
// ----------------------------------------------------------------------
if (isset($_POST['cap_nhat_dat_phong'])) {
    $id_datphong = $_POST['id_datphong'];
    $ngay_nhan = $_POST['ngay_nhan'];
    $ngay_tra = $_POST['ngay_tra'];
    $trang_thai_moi = $_POST['trang_thai'];

    $date_ngay_nhan = new DateTime($ngay_nhan);
    $date_ngay_tra = new DateTime($ngay_tra);

    if ($date_ngay_tra < $date_ngay_nhan) {
        echo "<script>alert('Lỗi: Ngày trả phòng không thể nhỏ hơn ngày nhận phòng.');</script>";
    } else {
        $conn->begin_transaction();
        try {
            // 1. Lấy thông tin đặt phòng cũ (chủ yếu là id_phong)
            $sql_get_old = "SELECT id_phong, trang_thai FROM `DatPhong` WHERE id_datphong=?";
            $stmt_get_old = $conn->prepare($sql_get_old);
            $stmt_get_old->bind_param("i", $id_datphong);
            $stmt_get_old->execute();
            $old_data = $stmt_get_old->get_result()->fetch_assoc();
            $id_phong = $old_data['id_phong'];
            $trang_thai_cu = $old_data['trang_thai'];
            $stmt_get_old->close();

            // 2. Cập nhật bản ghi DatPhong
            $sql_update_dp = "UPDATE `DatPhong` SET `ngay_nhan`=?, `ngay_tra`=?, `trang_thai`=? WHERE `id_datphong`=?";
            $stmt_update_dp = $conn->prepare($sql_update_dp);
            $stmt_update_dp->bind_param("sssi", $ngay_nhan, $ngay_tra, $trang_thai_moi, $id_datphong);
            $stmt_update_dp->execute();
            $stmt_update_dp->close();

            // 3. Cập nhật trạng thái phòng (Phong) nếu trạng thái đặt phòng thay đổi
            $trang_thai_phong_moi = NULL;
            
            // Nếu trạng thái mới là Hủy hoặc Hoàn thành, phòng phải là Trống
            if ($trang_thai_moi == 'Đã hủy' || $trang_thai_moi == 'Hoàn thành') {
                $trang_thai_phong_moi = 'Trống';
            } 
            // Nếu trạng thái mới là Đã xác nhận, phòng phải là Đã đặt
            else if ($trang_thai_moi == 'Đã xác nhận') {
                 $trang_thai_phong_moi = 'Đã đặt';
            }

            if ($trang_thai_phong_moi) {
                $sql_update_p = "UPDATE `Phong` SET `trang_thai`=? WHERE `id_phong`=?";
                $stmt_update_p = $conn->prepare($sql_update_p);
                $stmt_update_p->bind_param("si", $trang_thai_phong_moi, $id_phong);
                $stmt_update_p->execute();
                $stmt_update_p->close();
            }

            $conn->commit();
            echo "<script>alert('Cập nhật đặt phòng thành công!');window.location='datphong.php';</script>";
            exit;
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            echo "<script>alert('Lỗi khi cập nhật: " . $e->getMessage() . "');</script>";
        }
    }
}
// ----------------------------------------------------------------------


// Xử lý Xóa đặt phòng (GIỮ NGUYÊN)
if (isset($_GET['xoa'])) {
    $id_datphong = (int)$_GET['xoa'];

    $conn->begin_transaction();
    try {
        // Lấy id_phong từ id_datphong
        $sql_get_phong = "SELECT id_phong FROM `DatPhong` WHERE id_datphong = ?";
        $stmt_get_phong = $conn->prepare($sql_get_phong);
        $stmt_get_phong->bind_param("i", $id_datphong);
        $stmt_get_phong->execute();
        $result_get_phong = $stmt_get_phong->get_result();
        $phong_data = $result_get_phong->fetch_assoc();
        $id_phong = $phong_data['id_phong'];
        $stmt_get_phong->close();
        
        // Xóa bản ghi đặt phòng
        $sql_delete = "DELETE FROM `DatPhong` WHERE `id_datphong`=?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_datphong);
        $stmt_delete->execute();
        $stmt_delete->close();
        
        // Cập nhật trạng thái phòng
        $sql_update_phong = "UPDATE `Phong` SET `trang_thai`='Trống' WHERE `id_phong`=?";
        $stmt_update_phong = $conn->prepare($sql_update_phong);
        $stmt_update_phong->bind_param("i", $id_phong);
        $stmt_update_phong->execute();
        $stmt_update_phong->close();

        $conn->commit();
        echo "<script>alert('Hủy đặt phòng thành công!');window.location='datphong.php';</script>";
        exit;
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        echo "<script>alert('Lỗi khi hủy: " . $e->getMessage() . "');</script>";
    }
}


// ----------------------------------------------------------------------
## Lấy dữ liệu cho Form Sửa (Chức năng mới)
// ----------------------------------------------------------------------
$editData = null;
if (isset($_GET['sua'])) {
    $id_edit = (int)$_GET['sua'];
    $sql_edit = "SELECT dp.*, p.so_phong, kh.ho_ten
                 FROM `DatPhong` dp
                 JOIN `Phong` p ON dp.id_phong = p.id_phong
                 JOIN `KhachHang` kh ON dp.id_khachhang = kh.id_khachhang
                 WHERE dp.id_datphong = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id_edit);
    $stmt_edit->execute();
    $editData = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();
}


// Hiển thị danh sách đặt phòng (GIỮ NGUYÊN)
$sql_select = "SELECT 
                    dp.*, 
                    p.so_phong, 
                    kh.ho_ten,
                    kh.cccd,
                    kh.so_dien_thoai
                FROM `DatPhong` dp
                JOIN `Phong` p ON dp.id_phong = p.id_phong
                JOIN `KhachHang` kh ON dp.id_khachhang = kh.id_khachhang
                ORDER BY dp.ngay_dat DESC";
$result = $conn->query($sql_select);
?>
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
    <div class="form-box edit-form">
        <h3><i class="fas fa-edit"></i> Sửa đặt phòng #<?= htmlspecialchars($editData['id_datphong']) ?> (Phòng <?= htmlspecialchars($editData['so_phong']) ?>)</h3>
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
                        <?php 
                        $trang_thai_options = ['Đã xác nhận', 'Đã hủy', 'Hoàn thành'];
                        foreach ($trang_thai_options as $tt) {
                            $selected = ($editData['trang_thai'] == $tt) ? 'selected' : '';
                            echo "<option value='{$tt}' {$selected}>{$tt}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div></div>
            </div>

            <div class="form-actions">
                <a href="datphong.php" class="btn-huy"><i class="fas fa-times"></i> Hủy bỏ Sửa</a>
                <button type="submit" name="cap_nhat_dat_phong"><i class="fas fa-save"></i> Cập nhật</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!$editData): ?>
    <div class="form-box">
        <h3><i class="fas fa-plus-circle"></i> Thông tin đặt phòng mới</h3>
        <form method="post">
            <div class="form-row">
                <select name="id_phong" required>
                    <option value="">Chọn phòng trống...</option>
                    <?php
                    $sql_phong = "SELECT `id_phong`, `so_phong`, `loai_phong` FROM `Phong` WHERE `trang_thai` = 'Trống'";
                    $result_phong = $conn->query($sql_phong);
                    while ($phong = $result_phong->fetch_assoc()) {
                        echo "<option value='{$phong['id_phong']}'>Phòng {$phong['so_phong']} - {$phong['loai_phong']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="ho_ten" placeholder="Họ và tên khách hàng" required>
                <input type="text" name="cccd" placeholder="CCCD" required>
            </div>
            <div class="form-row">
                <input type="date" name="ngay_sinh" required>
                <select name="gioi_tinh" required>
                    <option value="">Giới tính...</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
                <input type="text" name="so_dien_thoai" placeholder="Số điện thoại" required>
            </div>
            <div class="form-row">
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="dia_chi" placeholder="Địa chỉ">
            </div>
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
            <?php 
            // Reset result pointer nếu form sửa được hiển thị
            if ($editData) $result->data_seek(0);

            while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id_datphong']) ?></td>
                <td><?= htmlspecialchars($row['so_phong']) ?></td>
                <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                <td><?= htmlspecialchars($row['cccd']) ?></td>
                <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                <td><?= htmlspecialchars($row['ngay_nhan']) ?></td>
                <td><?= htmlspecialchars($row['ngay_tra']) ?></td>
                <td><?= htmlspecialchars($row['trang_thai']) ?></td>
                <td class="action-links">
                    <a href="?sua=<?= htmlspecialchars($row['id_datphong']) ?>" class="edit-link"><i class="fas fa-edit"></i> Sửa</a>
                    <a href="#" class="delete-link" data-id="<?= htmlspecialchars($row['id_datphong']) ?>"><i class="fas fa-trash-alt"></i> Hủy</a>
                </td>
            </tr>
            <?php } ?>
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