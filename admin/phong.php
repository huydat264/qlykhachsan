<?php
include 'header.php';
include 'db.php';

// Xử lý thêm phòng
if (isset($_POST['them'])) {
    $so_phong = $_POST['so_phong'];
    $loai_phong = $_POST['loai_phong'];
    $gia_phong = $_POST['gia_phong'];
    $trang_thai = $_POST['trang_thai'];

    $sql = "INSERT INTO `Phong` (`so_phong`, `loai_phong`, `gia_phong`, `trang_thai`) 
             VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $so_phong, $loai_phong, $gia_phong, $trang_thai);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm phòng thành công!');window.location='phong.php';</script>";
    } else {
        echo "<p style='color:red'>Lỗi khi thêm: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý lưu sửa
if (isset($_POST['luu'])) {
    $id = (int)$_POST['id'];
    $so_phong = $_POST['so_phong'];
    $loai_phong = $_POST['loai_phong'];
    $gia_phong = $_POST['gia_phong'];
    $trang_thai = $_POST['trang_thai'];

    $sql = "UPDATE `Phong` 
             SET `so_phong`=?, `loai_phong`=?, `gia_phong`=?, `trang_thai`=?
             WHERE `id`=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $so_phong, $loai_phong, $gia_phong, $trang_thai, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật phòng thành công!');window.location='phong.php';</script>";
    } else {
        echo "<p style='color:red'>Lỗi khi cập nhật: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Xử lý xóa
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $sql = "DELETE FROM `Phong` WHERE `id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: phong.php");
    exit;
}

// Tìm kiếm
$search = '';
$where = '';
if (isset($_POST['timkiem'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $where = "WHERE `so_phong` LIKE ? OR `loai_phong` LIKE ?";
}
$sql_select = "SELECT * FROM `Phong` " . $where;
$stmt = $conn->prepare($sql_select);
if (isset($_POST['timkiem'])) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo "<p style='color:red'>SQL lỗi: " . $conn->error . "</p>";
    include 'footer.php'; exit;
}

// Kiểm tra ID đang sửa
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
?>

<main style="padding:20px;">
<h2>Quản lý Phòng</h2>

<form method="post" style="margin-bottom:12px;">
    <input type="text" name="so_phong" placeholder="Số phòng" required>
    <select name="loai_phong">
        <option value="Standard">Standard</option>
        <option value="Deluxe">Deluxe</option>
        <option value="Suite">Suite</option>
    </select>
    <input type="number" name="gia_phong" placeholder="Giá phòng" required>
    <select name="trang_thai">
        <option value="Trống">Trống</option>
        <option value="Đang đặt">Đang đặt</option>
        <option value="Đã đặt">Đã đặt</option>
        <option value="Bảo trì">Bảo trì</option>
    </select>
    <button type="submit" name="them">Thêm</button>
</form>

<form method="post" style="margin-bottom:14px;">
    <input type="text" name="search" placeholder="Tìm kiếm theo số phòng hoặc loại phòng..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" name="timkiem">Tìm</button>
</form>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%;">
    <tr style="background:#002060;color:#fff;">
        <th>ID</th>
        <th>Số phòng</th>
        <th>Loại phòng</th>
        <th>Giá phòng</th>
        <th>Tình trạng</th>
        <th>Hành động</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <?php if ($edit_id == $row['id']) { ?>
        <form method="post">
            <td><?= $row['id'] ?></td>
            <td><input type="text" name="so_phong" value="<?= htmlspecialchars($row['so_phong']) ?>"></td>
            <td>
                <select name="loai_phong">
                    <option value="Standard" <?= ($row['loai_phong'] == 'Standard') ? 'selected' : '' ?>>Standard</option>
                    <option value="Deluxe" <?= ($row['loai_phong'] == 'Deluxe') ? 'selected' : '' ?>>Deluxe</option>
                    <option value="Suite" <?= ($row['loai_phong'] == 'Suite') ? 'selected' : '' ?>>Suite</option>
                </select>
            </td>
            <td><input type="number" name="gia_phong" value="<?= htmlspecialchars($row['gia_phong']) ?>"></td>
            <td>
                <select name="trang_thai">
                    <option value="Trống" <?= ($row['trang_thai'] == 'Trống') ? 'selected' : '' ?>>Trống</option>
                    <option value="Đang đặt" <?= ($row['trang_thai'] == 'Đang đặt') ? 'selected' : '' ?>>Đang đặt</option>
                    <option value="Đã đặt" <?= ($row['trang_thai'] == 'Đã đặt') ? 'selected' : '' ?>>Đã đặt</option>
                    <option value="Bảo trì" <?= ($row['trang_thai'] == 'Bảo trì') ? 'selected' : '' ?>>Bảo trì</option>
                </select>
            </td>
            <td>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="luu">Lưu</button>
                <a href="phong.php">Hủy</a>
            </td>
        </form>
        <?php } else { ?>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['so_phong']) ?></td>
        <td><?= htmlspecialchars($row['loai_phong']) ?></td>
        <td><?= number_format($row['gia_phong'], 0, ",", ".") ?></td>
        <td><?= htmlspecialchars($row['trang_thai']) ?></td>
        <td>
            <a href="?edit=<?= $row['id'] ?>">Sửa</a> |
            <a href="?xoa=<?= $row['id'] ?>" onclick="return confirm('Xóa phòng này?')">Xóa</a>
        </td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</main>

<?php include 'footer.php'; ?>