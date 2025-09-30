<?php
class Datphong {
    private $conn;

    public function __construct($db) {
        $this->conn = $db; // PDO
    }

    // Lấy tất cả đặt phòng
    public function getAll() {
        $sql = "SELECT dp.*, p.so_phong, kh.ho_ten, kh.cccd, kh.so_dien_thoai
                FROM DatPhong dp
                JOIN Phong p ON dp.id_phong = p.id_phong
                JOIN KhachHang kh ON dp.id_khachhang = kh.id_khachhang
                ORDER BY dp.ngay_dat DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy dữ liệu để sửa
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

    // Thêm đặt phòng mới
    public function add($data) {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra khách hàng
            $stmt_kh = $this->conn->prepare("SELECT id_khachhang FROM KhachHang WHERE cccd = :cccd");
            $stmt_kh->execute(['cccd' => $data['cccd']]);
            $kh = $stmt_kh->fetch(PDO::FETCH_ASSOC);

            if ($kh) {
                $id_kh = $kh['id_khachhang'];
            } else {
                // Tạo tài khoản và khách hàng mới
                $username = 'kh'.rand(10000,99999);
                $password = password_hash('123456', PASSWORD_DEFAULT);
                $stmt_acc = $this->conn->prepare("INSERT INTO TaiKhoan (username,password,role) VALUES (:username, :password, 'USER')");
                $stmt_acc->execute(['username'=>$username,'password'=>$password]);
                $id_acc = $this->conn->lastInsertId();

                $stmt_kh_insert = $this->conn->prepare(
                    "INSERT INTO KhachHang (tai_khoan_khachhang_id, ho_ten, ngay_sinh, gioi_tinh, so_dien_thoai, email, cccd, dia_chi)
                     VALUES (:id_acc, :ho_ten, :ngay_sinh, :gioi_tinh, :so_dien_thoai, :email, :cccd, :dia_chi)"
                );
                $stmt_kh_insert->execute([
                    'id_acc' => $id_acc,
                    'ho_ten' => $data['ho_ten'],
                    'ngay_sinh' => $data['ngay_sinh'],
                    'gioi_tinh' => $data['gioi_tinh'],
                    'so_dien_thoai' => $data['so_dien_thoai'],
                    'email' => $data['email'],
                    'cccd' => $data['cccd'],
                    'dia_chi' => $data['dia_chi']
                ]);
                $id_kh = $this->conn->lastInsertId();
            }

            // Thêm DatPhong
            $stmt_dp = $this->conn->prepare(
                "INSERT INTO DatPhong (id_khachhang, id_phong, ngay_dat, ngay_nhan, ngay_tra, trang_thai)
                 VALUES (:id_kh, :id_phong, CURDATE(), :ngay_nhan, :ngay_tra, 'Đã xác nhận')"
            );
            $stmt_dp->execute([
                'id_kh' => $id_kh,
                'id_phong' => $data['id_phong'],
                'ngay_nhan' => $data['ngay_nhan'],
                'ngay_tra' => $data['ngay_tra']
            ]);

            // Cập nhật trạng thái phòng
            $stmt_p = $this->conn->prepare("UPDATE Phong SET trang_thai='Đã đặt' WHERE id_phong=:id_phong");
            $stmt_p->execute(['id_phong' => $data['id_phong']]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Cập nhật đặt phòng
    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            $stmt_old = $this->conn->prepare("SELECT id_phong FROM DatPhong WHERE id_datphong=:id");
            $stmt_old->execute(['id'=>$id]);
            $old = $stmt_old->fetch(PDO::FETCH_ASSOC);
            $id_phong = $old['id_phong'];

            $stmt_up = $this->conn->prepare("UPDATE DatPhong SET ngay_nhan=:ngay_nhan, ngay_tra=:ngay_tra, trang_thai=:trang_thai WHERE id_datphong=:id");
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

    // Xóa đặt phòng
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

    // Lấy phòng trống
    public function getPhongTrong() {
        $stmt = $this->conn->query("SELECT id_phong, so_phong, loai_phong FROM Phong WHERE trang_thai='Trống'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
