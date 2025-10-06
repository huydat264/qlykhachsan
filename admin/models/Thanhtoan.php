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
                dp.id_datphong,
                dp.ngay_nhan,
                dp.ngay_tra
            FROM phong p
            LEFT JOIN datphong dp ON p.id_phong = dp.id_phong
            LEFT JOIN sudungdichvu sdv ON dp.id_datphong = sdv.id_datphong
            WHERE LOWER(TRIM(p.trang_thai)) = 'đã đặt' AND dp.id_datphong IS NOT NULL
            GROUP BY p.id_phong, p.so_phong, p.gia_phong, dp.id_datphong, dp.ngay_nhan, dp.ngay_tra
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            // Tính số ngày ở (ngày_tra - ngày_nhan)
            $so_ngay = 1;
            if (!empty($row['ngay_nhan']) && !empty($row['ngay_tra'])) {
                $date1 = new DateTime($row['ngay_nhan']);
                $date2 = new DateTime($row['ngay_tra']);
                $interval = $date1->diff($date2);
                $so_ngay = (int)$interval->days;
                if ($so_ngay < 1) $so_ngay = 1;
            }
            $row['so_ngay_o'] = $so_ngay;
            $row['gia_phong_theo_ngay'] = $row['gia_phong'] * $so_ngay;
            $row['tong_tien_phai_tra'] = $row['gia_phong_theo_ngay'] + $row['tong_tien_dichvu'];
        }
        return $rows;
    }
}
