<?php
class Datphong {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllKhachHang() {
        $sql = "SELECT id_khachhang, ho_ten, gioi_tinh, cccd, so_dien_thoai, email, dia_chi, ngay_sinh
                FROM KhachHang
                ORDER BY ho_ten ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT dp.*, p.so_phong, kh.ho_ten, kh.cccd, kh.so_dien_thoai
                FROM DatPhong dp
                JOIN Phong p ON dp.id_phong = p.id_phong
                JOIN KhachHang kh ON dp.id_khachhang = kh.id_khachhang
                ORDER BY dp.ngay_dat DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT dp.*, p.so_phong, kh.ho_ten
                FROM DatPhong dp
                JOIN Phong p ON dp.id_phong = p.id_phong
                JOIN KhachHang kh ON dp.id_khachhang = kh.id_khachhang
                WHERE dp.id_datphong = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Chỉ đặt phòng, không kiểm tra trùng khách hàng
    public function add($data) {
        try {
            $this->conn->beginTransaction();

            // Nếu đã có khách hàng
            if (!empty($data['id_khachhang'])) {
                $id_kh = $data['id_khachhang'];
            } else {
                // Nếu là khách hàng mới → thêm mới
                $stmt_kh = $this->conn->prepare("
                    INSERT INTO KhachHang (ho_ten, ngay_sinh, gioi_tinh, so_dien_thoai, email, cccd, dia_chi)
                    VALUES (:ho_ten, :ngay_sinh, :gioi_tinh, :so_dien_thoai, :email, :cccd, :dia_chi)
                ");
                $stmt_kh->execute([
                    'ho_ten' => $data['ho_ten'],
                    'ngay_sinh' => $data['ngay_sinh'] ?? null,
                    'gioi_tinh' => $data['gioi_tinh'] ?? '',
                    'so_dien_thoai' => $data['so_dien_thoai'],
                    'email' => $data['email'],
                    'cccd' => $data['cccd'],
                    'dia_chi' => $data['dia_chi'] ?? ''
                ]);
                $id_kh = $this->conn->lastInsertId();
            }

            // ✅ Thêm thông tin đặt phòng
            $stmt_dp = $this->conn->prepare("
                INSERT INTO DatPhong (id_khachhang, id_phong, ngay_dat, ngay_nhan, ngay_tra, trang_thai)
                VALUES (:id_kh, :id_phong, CURDATE(), :ngay_nhan, :ngay_tra, 'Đã xác nhận')
            ");
            $stmt_dp->execute([
                'id_kh' => $id_kh,
                'id_phong' => $data['id_phong'],
                'ngay_nhan' => $data['ngay_nhan'],
                'ngay_tra' => $data['ngay_tra']
            ]);

            // ✅ Cập nhật trạng thái phòng
            $stmt_p = $this->conn->prepare("UPDATE Phong SET trang_thai='Đã đặt' WHERE id_phong=:id_phong");
            $stmt_p->execute(['id_phong' => $data['id_phong']]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // ✅ Dành riêng cho khách hàng đã có
    public function addForExistingCustomer($data) {
        try {
            $this->conn->beginTransaction();

            $stmt_dp = $this->conn->prepare("
                INSERT INTO DatPhong (id_khachhang, id_phong, ngay_dat, ngay_nhan, ngay_tra, trang_thai)
                VALUES (:id_khachhang, :id_phong, CURDATE(), :ngay_nhan, :ngay_tra, 'Đã xác nhận')
            ");
            $stmt_dp->execute([
                'id_khachhang' => $data['id_khachhang'],
                'id_phong' => $data['id_phong'],
                'ngay_nhan' => $data['ngay_nhan'],
                'ngay_tra' => $data['ngay_tra']
            ]);

            $stmt_p = $this->conn->prepare("UPDATE Phong SET trang_thai='Đã đặt' WHERE id_phong=:id_phong");
            $stmt_p->execute(['id_phong' => $data['id_phong']]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();
            $stmt_old = $this->conn->prepare("SELECT id_phong FROM DatPhong WHERE id_datphong=:id");
            $stmt_old->execute(['id'=>$id]);
            $old = $stmt_old->fetch(PDO::FETCH_ASSOC);
            $id_phong = $old['id_phong'];

            $stmt_up = $this->conn->prepare("UPDATE DatPhong 
                                             SET ngay_nhan=:ngay_nhan, ngay_tra=:ngay_tra, trang_thai=:trang_thai 
                                             WHERE id_datphong=:id");
            $stmt_up->execute([
                'ngay_nhan'=>$data['ngay_nhan'],
                'ngay_tra'=>$data['ngay_tra'],
                'trang_thai'=>$data['trang_thai'],
                'id'=>$id
            ]);

            $trang_thai_phong = null;
            if ($data['trang_thai']=='Đã hủy'||$data['trang_thai']=='Hoàn thành') $trang_thai_phong='Trống';
            elseif ($data['trang_thai']=='Đã xác nhận') $trang_thai_phong='Đã đặt';

            if ($trang_thai_phong){
                $stmt_p = $this->conn->prepare("UPDATE Phong SET trang_thai=:trang_thai WHERE id_phong=:id_phong");
                $stmt_p->execute(['trang_thai'=>$trang_thai_phong,'id_phong'=>$id_phong]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            $stmt_old = $this->conn->prepare("SELECT id_phong FROM DatPhong WHERE id_datphong=:id");
            $stmt_old->execute(['id'=>$id]);
            $phong = $stmt_old->fetch(PDO::FETCH_ASSOC);
            $id_phong = $phong['id_phong'];

            $stmt_del = $this->conn->prepare("DELETE FROM DatPhong WHERE id_datphong=:id");
            $stmt_del->execute(['id'=>$id]);

            $stmt_p = $this->conn->prepare("UPDATE Phong SET trang_thai='Trống' WHERE id_phong=:id_phong");
            $stmt_p->execute(['id_phong'=>$id_phong]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function getPhongTrong() {
        $stmt = $this->conn->query("SELECT id_phong, so_phong, loai_phong FROM Phong WHERE trang_thai='Trống'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
