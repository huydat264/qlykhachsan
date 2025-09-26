<?php
include 'header.php';
include 'db.php';

function get_columns($conn, $table) {
    $cols = [];
    $res = $conn->query("SHOW COLUMNS FROM `$table`");
    if ($res === false) return false;
    while ($r = $res->fetch_assoc()) $cols[] = $r['Field'];
    return $cols;
}
function first_exists($cols, $candidates) {
    foreach ($candidates as $c) if (in_array($c, $cols)) return $c;
    return null;
}

$table = 'nhanvien';
$cols = get_columns($conn, $table);
if ($cols === false) {
    echo "<p style='color:red'>Bảng <strong>$table</strong> không tồn tại. Lỗi: ".$conn->error."</p>";
    include 'footer.php'; exit;
}

$id_col    = first_exists($cols, ['id','maNV','ma_nv']);
$name_col  = first_exists($cols, ['ten','ho_ten','hoTen','name']);
$role_col  = first_exists($cols, ['chucvu','chuc_vu','position','chucVu']);
$phone_col = first_exists($cols, ['sdt','so_dt','soDT','phone']);

if (!$id_col || !$name_col) {
    echo "<p style='color:red'>Cần cột id và tên trong bảng $table. Cột hiện có: ".implode(', ',$cols)."</p>";
    include 'footer.php'; exit;
}

// thêm
if (isset($_POST['them'])) {
    $ten = $conn->real_escape_string($_POST['ten']);
    $chucvu = $conn->real_escape_string($_POST['chucvu']);
    $sdt = $conn->real_escape_string($_POST['sdt']);

    $sql = "INSERT INTO `$table` (`$name_col`" . ($role_col ? ", `$role_col`" : "") . ($phone_col ? ", `$phone_col`" : "") . ") VALUES ('$ten'" .
           ($role_col ? ", '$chucvu'" : "") . ($phone_col ? ", '$sdt'" : "") . ")";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm nhân viên thành công!');window.location='nhanvien.php';</script>"; exit;
    } else {
        echo "<p style='color:red'>Lỗi: ".$conn->error."</p>";
    }
}

// lưu
if (isset($_POST['luu'])) {
    $id = (int)$_POST['id'];
    $ten = $conn->real_escape_string($_POST['ten']);
    $chucvu = $conn->real_escape_string($_POST['chucvu']);
    $sdt = $conn->real_escape_string($_POST['sdt']);

    $sql = "UPDATE `$table` SET `$name_col`='$ten'";
    if ($role_col) $sql .= ", `$role_col`='$chucvu'";
    if ($phone_col) $sql .= ", `$phone_col`='$sdt'";
    $sql .= " WHERE `$id_col`=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật nhân viên thành công!');window.location='nhanvien.php';</script>"; exit;
    } else {
        echo "<p style='color:red'>Lỗi: ".$conn->error."</p>";
    }
}

// xóa
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $conn->query("DELETE FROM `$table` WHERE `$id_col`=$id");
    header("Location: nhanvien.php"); exit;
}

// tìm kiếm
$search = '';
$where = '';
if (isset($_POST['timkiem'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $parts = [];
    if ($name_col) $parts[] = "`$name_col` LIKE '%$search%'";
    if ($role_col) $parts[] = "`$role_col` LIKE '%$search%'";
    if ($phone_col) $parts[] = "`$phone_col` LIKE '%$search%'";
    $where = $parts ? "WHERE ".implode(' OR ', $parts) : '';
}

$result = $conn->query("SELECT * FROM `$table` $where");
if ($result === false) { echo "<p style='color:red'>SQL lỗi: ".$conn->error."</p>"; include 'footer.php'; exit; }

$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
?>

<main style="padding:20px;">
  <h2>Quản lý Nhân viên</h2>

  <form method="post" style="margin-bottom:12px;">
    <input type="text" name="ten" placeholder="Tên nhân viên" required>
    <?php if ($role_col) echo '<input type="text" name="chucvu" placeholder="Chức vụ">'; ?>
    <?php if ($phone_col) echo '<input type="text" name="sdt" placeholder="Số điện thoại">'; ?>
    <button type="submit" name="them">Thêm</button>
  </form>

  <form method="post" style="margin-bottom:14px;">
    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" name="timkiem">Tìm</button>
  </form>

  <table border="1" cellpadding="8" cellspacing="0" style="width:100%;">
    <tr style="background:#002060;color:#fff;">
      <th><?= $id_col ?></th>
      <th>Tên</th>
      <?php if ($role_col) echo '<th>Chức vụ</th>'; ?>
      <?php if ($phone_col) echo '<th>SĐT</th>'; ?>
      <th>Hành động</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <?php if ($edit_id == $row[$id_col]) { ?>
        <form method="post">
          <td><?= $row[$id_col] ?><input type="hidden" name="id" value="<?= $row[$id_col] ?>"></td>
          <td><input type="text" name="ten" value="<?= htmlspecialchars($row[$name_col]) ?>"></td>
          <?php if ($role_col) echo '<td><input type="text" name="chucvu" value="'.htmlspecialchars($row[$role_col]).'"></td>'; ?>
          <?php if ($phone_col) echo '<td><input type="text" name="sdt" value="'.htmlspecialchars($row[$phone_col]).'"></td>'; ?>
          <td>
            <button type="submit" name="luu">Lưu</button>
            <a href="nhanvien.php">Hủy</a>
          </td>
        </form>
        <?php } else { ?>
          <td><?= $row[$id_col] ?></td>
          <td><?= htmlspecialchars($row[$name_col]) ?></td>
          <?php if ($role_col) echo '<td>'.htmlspecialchars($row[$role_col]).'</td>'; ?>
          <?php if ($phone_col) echo '<td>'.htmlspecialchars($row[$phone_col]).'</td>'; ?>
          <td>
            <a href="?edit=<?= $row[$id_col] ?>">Sửa</a> |
            <a href="?xoa=<?= $row[$id_col] ?>" onclick="return confirm('Xóa nhân viên này?')">Xóa</a>
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
  </table>
</main>

<?php include 'footer.php'; ?>
