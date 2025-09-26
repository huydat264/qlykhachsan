<?php
$conn = mysqli_connect('localhost', 'root', '', 'quan_ly_khach_san');

if (!$conn) {
    die('Không thể kết nối MySQL: ' . mysqli_connect_error());
}
?>
