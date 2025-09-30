<?php
require_once __DIR__ . '/../Modeluser/DichVuDAO.php';
require_once __DIR__ . '/../Modeluser/DichVu.php';

class DichVuController {
    private $dao;

    public function __construct() {
        $this->dao = new DichVuDAO();
    }

    public function list() {
        $dichvus = $this->dao->getAll();
        include __DIR__ . '/../Viewsuser/dichvu.php';
    }

    public function detail($id) {
        $dichvu = $this->dao->getById($id);
        if ($dichvu) {
            include __DIR__ . '/../Viewsuser/chitietdichvu.php';
        } else {
            echo "<p style='color:red; text-align:center;'>Không tìm thấy dịch vụ!</p>";
        }
    }
}

// Xử lý request
$action = $_GET['action'] ?? 'list';   // mặc định gọi list
$controller = new DichVuController();

switch ($action) {
    case 'detail':
        if (!empty($_GET['id'])) {
            $controller->detail($_GET['id']);
        } else {
            echo "Thiếu ID dịch vụ!";
        }
        break;

    case 'list':
    default:
        $controller->list();
        break;
}
