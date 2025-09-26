<?php
//Đây là file xử lý đặt phòng sau nhập tt khách hàng
class ThanhToanModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($data) {
        $sql = "INSERT INTO thanhtoan (id_datphong, ngay_thanh_toan, so_tien, hinh_thuc, loai_thanh_toan)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isdss",
            $data['id_datphong'],
            $data['ngay_thanh_toan'],
            $data['so_tien'],
            $data['hinh_thuc'],
            $data['loai_thanh_toan']
        );
        return $stmt->execute();
    }
}
?>
