=<?php
include 'header.php';
include 'db.php';

// Các hàm trợ giúp
function get_columns($conn, $table) {
    $cols = [];
    $res = $conn->query("SHOW COLUMNS FROM `$table`");
    if ($res === false) return false;
    while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];
    return $cols;
}

$table = 'KhachHang';
$cols = get_columns($conn, $table);
if ($cols === false) {
    echo "<p style='color:red'>Bảng <strong>$table</strong> không tồn tại. Lỗi MySQL: " . $conn->error . "</p>";
    include 'footer.php'; exit;
}

// Cột DB
$id_col = 'id';
$tai_khoan_id_col = 'tai_khoan_id';
$full_name_col = 'ho_ten';
$phone_number_col = 'so_dien_thoai';
$email_col = 'email';
$address_col = 'dia_chi';
$ngay_sinh_col = 'ngay_sinh';
$gioi_tinh_col = 'gioi_tinh';
$cccd_col = 'cccd';

// Thêm khách hàng
if (isset($_POST['them'])) {
    $tai_khoan_id = (int)$_POST['tai_khoan_id'];
    $ho_ten = $conn->real_escape_string($_POST['ho_ten']);
    $so_dien_thoai = $conn->real_escape_string($_POST['so_dien_thoai']);
    $email = $conn->real_escape_string($_POST['email']);
    $ngay_sinh = !empty($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : NULL;
    $gioi_tinh = !empty($_POST['gioi_tinh']) ? $_POST['gioi_tinh'] : NULL;
    $cccd = !empty($_POST['cccd']) ? $_POST['cccd'] : NULL;
    $dia_chi = !empty($_POST['dia_chi']) ? $_POST['dia_chi'] : NULL;

    $sql = "INSERT INTO `KhachHang` 
            (`tai_khoan_id`, `ho_ten`, `so_dien_thoai`, `email`, `ngay_sinh`, `gioi_tinh`, `cccd`, `dia_chi`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $tai_khoan_id, $ho_ten, $so_dien_thoai, $email, $ngay_sinh, $gioi_tinh, $cccd, $dia_chi);
    if ($stmt->execute()) {
        echo "<script>alert('Thêm khách hàng thành công!');window.location='khachhang.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi thêm: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Sửa khách hàng
if (isset($_POST['luu'])) {
    $id = (int)$_POST['id'];
    $ho_ten = $conn->real_escape_string($_POST['ho_ten']);
    $so_dien_thoai = $conn->real_escape_string($_POST['so_dien_thoai']);
    $email = $conn->real_escape_string($_POST['email']);
    $ngay_sinh = !empty($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : NULL;
    $gioi_tinh = !empty($_POST['gioi_tinh']) ? $_POST['gioi_tinh'] : NULL;
    $cccd = !empty($_POST['cccd']) ? $_POST['cccd'] : NULL;
    $dia_chi = !empty($_POST['dia_chi']) ? $_POST['dia_chi'] : NULL;

    $sql = "UPDATE `KhachHang` 
            SET `ho_ten`=?, `so_dien_thoai`=?, `email`=?, `ngay_sinh`=?, `gioi_tinh`=?, `cccd`=?, `dia_chi`=? 
            WHERE `id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $ho_ten, $so_dien_thoai, $email, $ngay_sinh, $gioi_tinh, $cccd, $dia_chi, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!');window.location='khachhang.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xóa khách hàng
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $conn->query("DELETE FROM `KhachHang` WHERE `id`=$id");
    header("Location: khachhang.php"); exit;
}

// Tìm kiếm
$search = '';
$where = '';
if (isset($_POST['timkiem'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $where_parts = [];
    if (in_array($full_name_col, $cols)) $where_parts[] = "`$full_name_col` LIKE '%$search%'";
    if (in_array($phone_number_col, $cols)) $where_parts[] = "`$phone_number_col` LIKE '%$search%'";
    if (in_array($email_col, $cols)) $where_parts[] = "`$email_col` LIKE '%$search%'";
    if (in_array($address_col, $cols)) $where_parts[] = "`$address_col` LIKE '%$search%'";
    $where = $where_parts ? "WHERE " . implode(' OR ', $where_parts) : '';
}
$result = $conn->query("SELECT * FROM `$table` $where");
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background:#f4f6f9;
    margin:0;
}
.container { max-width:1200px; margin:20px auto; padding:20px; }
.page-title { font-size:24px; font-weight:bold; color:#002060; margin-bottom:20px; border-left:6px solid #002060; padding-left:10px; }

.card {
    background:#fff;
    padding:20px;
    border-radius:12px; /* bo góc */
    margin-bottom:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 16px rgba(0,0,0,0.15);
}

.card h3 { margin-top:0; margin-bottom:15px; color:#444; }

.search-box { text-align:right; margin-bottom:15px; }
.search-box input {
    padding:10px 14px;
    border:1px solid #ccc;
    border-radius:8px;
    width:250px;
    transition:border-color 0.2s ease;
}
.search-box input:focus {
    outline:none;
    border-color:#002060;
}
.search-box button {
    padding:10px 16px;
    background:#002060;
    border:none;
    color:#fff;
    border-radius:8px;
    cursor:pointer;
    margin-left:6px;
    transition:background 0.2s ease;
}
.search-box button:hover { background:#003399; }

.grid-form {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
}
.grid-form input, .grid-form select {
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
    transition:border-color 0.2s ease, box-shadow 0.2s ease;
}
.grid-form input:focus, .grid-form select:focus {
    outline:none;
    border-color:#007bff;
    box-shadow:0 0 4px rgba(0,123,255,0.3);
}
.form-actions { grid-column:1/-1; text-align:right; }

.btn {
    padding:8px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    text-decoration:none;
    font-size:14px;
    transition:background 0.2s ease, transform 0.1s ease;
}
.btn:active { transform: scale(0.96); }
.btn-primary { background:#007bff; color:#fff; }
.btn-success { background:#28a745; color:#fff; }
.btn-danger { background:#dc3545; color:#fff; }
.btn-light { background:#e0e0e0; color:#333; }
.btn:hover { opacity:0.9; }

.data-table { width:100%; border-collapse:collapse; margin-top:10px; border-radius:12px; overflow:hidden; }
.data-table th {
    background:#002060;
    color:#fff;
    padding:12px;
    text-align:center;
}
.data-table td {
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
    background:#fff;
}
.data-table tr:nth-child(even) { background:#f9f9f9; }
.data-table tr:hover { background:#eef3ff; transition:background 0.2s ease; }
</style>


<main class="container">
    <h2 class="page-title">Quản lý Khách hàng</h2>

    <!-- Tìm kiếm -->
    <form method="post" class="search-box">
        <input type="text" name="search" placeholder="🔍 Tìm kiếm khách hàng..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" name="timkiem">Tìm</button>
    </form>

    <!-- Form thêm -->
    <div class="card">
        <h3>➕ Thêm khách hàng mới</h3>
        <form method="post" class="grid-form">
            <input type="number" name="tai_khoan_id" placeholder="ID tài khoản" required>
            <input type="text" name="ho_ten" placeholder="Họ tên" required>
            <input type="date" name="ngay_sinh">
            <select name="gioi_tinh">
                <option value="">Giới tính</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
            </select>
            <input type="text" name="so_dien_thoai" placeholder="Số điện thoại" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="cccd" placeholder="CCCD">
            <input type="text" name="dia_chi" placeholder="Địa chỉ">
            <div class="form-actions">
                <button type="submit" name="them" class="btn btn-success">Thêm</button>
            </div>
        </form>
    </div>

    <!-- Danh sách -->
    <div class="card">
        <h3>📋 Danh sách khách hàng</h3>
        <table class="data-table">
            <tr>
                <th>ID</th>
                <th>ID Tài khoản</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>CCCD</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                <?php if ($edit_id == $row['id']) { ?>
                    <form method="post">
                        <td><?= $row['id'] ?><input type="hidden" name="id" value="<?= $row['id'] ?>"></td>
                        <td><?= $row['tai_khoan_id'] ?></td>
                        <td><input type="text" name="ho_ten" value="<?= htmlspecialchars($row['ho_ten']) ?>"></td>
                        <td><input type="date" name="ngay_sinh" value="<?= htmlspecialchars($row['ngay_sinh']) ?>"></td>
                        <td>
                            <select name="gioi_tinh">
                                <option value="Nam" <?= ($row['gioi_tinh']=='Nam')?'selected':'' ?>>Nam</option>
                                <option value="Nữ" <?= ($row['gioi_tinh']=='Nữ')?'selected':'' ?>>Nữ</option>
                                <option value="Khác" <?= ($row['gioi_tinh']=='Khác')?'selected':'' ?>>Khác</option>
                            </select>
                        </td>
                        <td><input type="text" name="so_dien_thoai" value="<?= htmlspecialchars($row['so_dien_thoai']) ?>"></td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                        <td><input type="text" name="cccd" value="<?= htmlspecialchars($row['cccd']) ?>"></td>
                        <td><input type="text" name="dia_chi" value="<?= htmlspecialchars($row['dia_chi']) ?>"></td>
                        <td>
                            <button type="submit" name="luu" class="btn btn-primary">Lưu</button>
                            <a href="khachhang.php" class="btn btn-light">Hủy</a>
                        </td>
                    </form>
                <?php } else { ?>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['tai_khoan_id'] ?></td>
                    <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                    <td><?= htmlspecialchars($row['ngay_sinh']) ?></td>
                    <td><?= htmlspecialchars($row['gioi_tinh']) ?></td>
                    <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['cccd']) ?></td>
                    <td><?= htmlspecialchars($row['dia_chi']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-primary">Sửa</a>
                        <a href="?xoa=<?= $row['id'] ?>" onclick="return confirm('Xóa khách hàng này?')" class="btn btn-danger">Xóa</a>
                    </td>
                <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>
