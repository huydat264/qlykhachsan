<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Chuyển hướng về trang đăng nhập
exit();
?>
