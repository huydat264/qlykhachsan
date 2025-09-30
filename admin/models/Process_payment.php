<?php
class ProcessPayment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function thanhToan($id_datphong, $tong_tien, $hinh_thuc, $loai_thanh_toan, $ngay_thanh_toan, $id_phong) {
        try {
            $this->conn->beginTransaction();

            // 1. Thêm bản ghi vào bảng thanhtoan
            $sql_insert_thanhtoan = "INSERT INTO thanhtoan (id_datphong, so_tien, hinh_thuc, loai_thanh_toan, ngay_thanh_toan) 
                                     VALUES (:id_datphong, :so_tien, :hinh_thuc, :loai_thanh_toan, :ngay_thanh_toan)";
            $stmt = $this->conn->prepare($sql_insert_thanhtoan);
            $stmt->execute([
                ':id_datphong' => $id_datphong,
                ':so_tien' => $tong_tien,
                ':hinh_thuc' => $hinh_thuc,
                ':loai_thanh_toan' => $loai_thanh_toan,
                ':ngay_thanh_toan' => $ngay_thanh_toan
            ]);

            // 2. Thêm bản ghi vào bảng hoadon
            $sql_insert_hoadon = "INSERT INTO hoadon (id_datphong, tong_tien, ngay_xuat) 
                                  VALUES (:id_datphong, :tong_tien, :ngay_xuat)";
            $stmt = $this->conn->prepare($sql_insert_hoadon);
            $stmt->execute([
                ':id_datphong' => $id_datphong,
                ':tong_tien' => $tong_tien,
                ':ngay_xuat' => $ngay_thanh_toan
            ]);

            // 3. Cập nhật trạng thái phòng thành "Trống"
            $sql_update_phong = "UPDATE phong SET trang_thai = 'Trống' WHERE id_phong = :id_phong";
            $stmt = $this->conn->prepare($sql_update_phong);
            $stmt->execute([':id_phong' => $id_phong]);

            // 4. Cập nhật trạng thái đặt phòng thành "Đã thanh toán"
            $sql_update_datphong = "UPDATE datphong SET trang_thai = 'Đã thanh toán' WHERE id_datphong = :id_datphong";
            $stmt = $this->conn->prepare($sql_update_datphong);
            $stmt->execute([':id_datphong' => $id_datphong]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
