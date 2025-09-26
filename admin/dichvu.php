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

$table = 'dichvu';
$cols = get_columns($conn, $table);
if ($cols === false) {
    echo "<p style='color:red'>Bảng $table không tồn tại. Lỗi: ".$conn->error."</p>";
    include 'footer.php'; exit;
}

$id_col = first_exists($cols, ['id','maDV','ma_dv']);
$name_col = first_exists($cols, ['tenDichVu','ten_dich_vu','ten','name']);
$price_col = first_exists($cols, ['gia','price','cost']);
$desc_col = first_exists($cols, ['mota','mo_ta','moTa','description']);

// bắt buộc id + name + price (nếu price không có thì vẫn cho chạy nhưng sẽ cảnh báo)
if (!$id_col || !$name_col) {
    echo "<p style='color:red'>Cần cột id và tên trong bảng $table. Các cột: ".implode(', ',$cols)."</p>";
    include 'footer.php'; exit;
}

// thêm
if (isset($_POST['them'])) {
    $ten = $conn->real_escape_string($_POST['tenDV']);
    $gia = $price_col ? $conn->real_escape_string($_POST['gia']) : 0;
    $mota = $desc_col ? $conn->real_escape_string($_POST['mota']) : '';

    $sql = "INSERT INTO `$table` (`$name_col`" . ($price_col ? ", `$price_col`" : "") . ($desc_col ? ", `$desc_col`" : "") . ") VALUES ('$ten'" .
           ($price_col ? ", '$gia'" : "") . ($desc_col ? ", '$mota'" : "") . ")";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm dịch vụ thành công!');window.location='dichvu.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi: ".$conn->error."</p>";
    }
}

// lưu
if (isset($_POST['luu'])) {
    $id = (int)$_POST['id'];
    $ten = $conn->real_escape_string($_POST['tenDV']);
    $gia = $price_col ? $conn->real_escape_string($_POST['gia']) : 0;
    $mota = $desc_col ? $conn->real_escape_string($_POST['mota']) : '';

    $sql = "UPDATE `$table` SET `$name_col`='$ten'";
    if ($price_col) $sql .= ", `$price_col`='$gia'";
    if ($desc_col) $sql .= ", `$desc_col`='$mota'";
    $sql .= " WHERE `$id_col`=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật dịch vụ thành công!');window.location='dichvu.php';</script>";
        exit;
    } else {
        echo "<p style='color:red'>Lỗi: ".$conn->error."</p>";
    }
}

// xóa
if (isset($_GET['xoa'])) {
    $id = (int)$_GET['xoa'];
    $conn->query("DELETE FROM `$table` WHERE `$id_col`=$id");
    header("Location: dichvu.php"); exit;
}

// tìm kiếm
$search = '';
$where = '';
if (isset($_POST['timkiem'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $parts = [];
    if ($name_col) $parts[] = "`$name_col` LIKE '%$search%'";
    if ($desc_col) $parts[] = "`$desc_col` LIKE '%$search%'";
    $where = $parts ? "WHERE ".implode(' OR ', $parts) : '';
}

$result = $conn->query("SELECT * FROM `$table` $where");
if ($result === false) { echo "<p style='color:red'>SQL lỗi: ".$conn->error."</p>"; include 'footer.php'; exit; }

$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
?>

<main style="padding:20px;">
  <h2>Quản lý Dịch vụ</h2>

  <form method="post" style="margin-bottom:12px;">
    <input type="text" name="tenDV" placeholder="Tên dịch vụ" required>
    <?php if ($price_col) echo '<input type="number" step="0.01" name="gia" placeholder="Giá">'; ?>
    <?php if ($desc_col) echo '<input type="text" name="mota" placeholder="Mô tả">'; ?>
    <button type="submit" name="them">Thêm</button>
  </form>

  <form method="post" style="margin-bottom:14px;">
    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" name="timkiem">Tìm</button>
  </form>

  <table border="1" cellpadding="8" cellspacing="0" style="width:100%;">
    <tr style="background:#002060;color:#fff;">
      <th><?= $id_col ?></th>
      <th>Tên dịch vụ</th>
      <?php if ($price_col) echo '<th>Giá</th>'; ?>
      <?php if ($desc_col) echo '<th>Mô tả</th>'; ?>
      <th>Hành động</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <?php if ($edit_id == $row[$id_col]) { ?>
        <form method="post">
          <td><?= $row[$id_col] ?><input type="hidden" name="id" value="<?= $row[$id_col] ?>"></td>
          <td><input type="text" name="tenDV" value="<?= htmlspecialchars($row[$name_col]) ?>"></td>
          <?php if ($price_col) echo '<td><input type="number" step="0.01" name="gia" value="'.htmlspecialchars($row[$price_col]).'"></td>'; ?>
          <?php if ($desc_col) echo '<td><input type="text" name="mota" value="'.htmlspecialchars($row[$desc_col]).'"></td>'; ?>
          <td>
            <button type="submit" name="luu">Lưu</button>
            <a href="dichvu.php">Hủy</a>
          </td>
        </form>
        <?php } else { ?>
          <td><?= $row[$id_col] ?></td>
          <td><?= htmlspecialchars($row[$name_col]) ?></td>
          <?php if ($price_col) echo '<td>'.htmlspecialchars($row[$price_col]).'</td>'; ?>
          <?php if ($desc_col) echo '<td>'.htmlspecialchars($row[$desc_col]).'</td>'; ?>
          <td>
            <a href="?edit=<?= $row[$id_col] ?>">Sửa</a> |
            <a href="?xoa=<?= $row[$id_col] ?>" onclick="return confirm('Xóa dịch vụ này?')">Xóa</a>
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
  </table>
</main>

<?php include 'footer.php'; ?>
