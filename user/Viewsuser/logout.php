<?php
session_start();
session_destroy();

// Quay về trang chủ
header("Location: trangchu.php");
exit();
