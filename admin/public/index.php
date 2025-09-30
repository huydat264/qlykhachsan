<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Auth.php';

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Ghép tên class controller, ví dụ: nhanvien -> NhanvienController1
$controllerName = ucfirst($controller) . 'Controller1';
$controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

// Kiểm tra file controller
if (!file_exists($controllerFile)) {
    die("❌ Không tìm thấy file controller: $controllerFile");
}
require_once $controllerFile;

// Tạo kết nối PDO
try {
    $db = new Database();
    $pdo = $db->getConnection(); // PDO
} catch (PDOException $e) {
    die("❌ Lỗi kết nối database: " . $e->getMessage());
}

// Kiểm tra class controller
if (!class_exists($controllerName)) {
    die("❌ Không tìm thấy class $controllerName");
}

// Khởi tạo controller
$controllerObj = new $controllerName($pdo);

// Nếu POST và controller là nhanvien, gọi createOrUpdate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $controller === 'nhanvien') {
    $controllerObj->createOrUpdate();
}

// Thực thi action (index, create, update...)
if (method_exists($controllerObj, $action)) {
    $controllerObj->$action();
} elseif (method_exists($controllerObj, 'index')) {
    $controllerObj->index();
} else {
    echo "❌ Không tìm thấy phương thức <b>$action</b> trong class <b>$controllerName</b>";
}
