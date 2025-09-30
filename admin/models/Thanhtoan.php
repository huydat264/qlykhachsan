<?php
class Thanhtoan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách phòng có trạng thái 'Đã đặt' kèm tổng tiền dịch vụ
    public function getPhongList() {
        $sql = "
            SELECT 
                p.id_phong,
                p.so_phong,
                p.gia_phong,
                COALESCE(SUM(sdv.thanh_tien), 0) AS tong_tien_dichvu,
                dp.id_datphong
            FROM phong p
            LEFT JOIN datphong dp ON p.id_phong = dp.id_phong
            LEFT JOIN sudungdichvu sdv ON dp.id_datphong = sdv.id_datphong
            WHERE LOWER(TRIM(p.trang_thai)) = 'đã đặt' AND dp.id_datphong IS NOT NULL
            GROUP BY p.id_phong, p.so_phong, p.gia_phong, dp.id_datphong
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['tong_tien_phai_tra'] = $row['gia_phong'] + $row['tong_tien_dichvu'];
        }
        return $rows;
    }
}
