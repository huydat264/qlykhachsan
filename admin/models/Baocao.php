<?php
require_once __DIR__ . '/../config/database.php';

class Baocao
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTotalPhong()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM phong");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPhongByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM phong WHERE trang_thai = :status");
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getDoanhThuThucTe($filter = '')
    {
        $where = '';
        switch ($filter) {
            case 'month':
                $where = "WHERE MONTH(hd.ngay_xuat)=MONTH(CURRENT_DATE()) AND YEAR(hd.ngay_xuat)=YEAR(CURRENT_DATE())";
                break;
            case 'quarter':
                $where = "WHERE QUARTER(hd.ngay_xuat)=QUARTER(CURRENT_DATE()) AND YEAR(hd.ngay_xuat)=YEAR(CURRENT_DATE())";
                break;
            case 'year':
                $where = "WHERE YEAR(hd.ngay_xuat)=YEAR(CURRENT_DATE())";
                break;
        }
        $sql = "SELECT SUM(tong_tien) AS total FROM hoadon hd $where";
        $stmt = $this->db->query($sql);
        return (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getNhanVienByRole()
    {
        $stmt = $this->db->query("SELECT chuc_vu, COUNT(*) AS total FROM nhanvien GROUP BY chuc_vu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKhachHangByGender()
    {
        $stmt = $this->db->query("SELECT gioi_tinh, COUNT(*) AS total FROM khachhang GROUP BY gioi_tinh");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getDichVuRevenue()
{
    $sql = "SELECT dv.ten_dich_vu,
                   COALESCE(SUM(sdv.thanh_tien),0) AS total_revenue
            FROM dichvu dv
            LEFT JOIN sudungdichvu sdv ON sdv.id_dichvu = dv.id_dichvu
            GROUP BY dv.ten_dich_vu
            ORDER BY total_revenue DESC";
    $stmt = $this->db->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ép kiểu float để chắc chắn ra số
    foreach ($rows as &$r) {
        $r['total_revenue'] = (float)$r['total_revenue'];
    }
    return $rows;
}



   public function getVipKhachHang()
{
    $sql = "
        SELECT 
            kh.ho_ten,
            SUM(
                (p.gia_phong * 
                    GREATEST(
                        DATEDIFF(dp.ngay_tra, dp.ngay_nhan),
                        1
                    )
                ) 
                + COALESCE(SUM_DV.total_dv, 0)
            ) AS total_spent
        FROM datphong dp
        JOIN phong p ON dp.id_phong = p.id_phong
        JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
        LEFT JOIN (
            SELECT id_datphong, SUM(thanh_tien) AS total_dv
            FROM sudungdichvu
            GROUP BY id_datphong
        ) AS SUM_DV ON SUM_DV.id_datphong = dp.id_datphong
        GROUP BY kh.id_khachhang, kh.ho_ten
        ORDER BY total_spent DESC
        LIMIT 3
    ";

    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getTongChiTieu()
{
    $sql = "
        SELECT 
            SUM(
                (p.gia_phong * GREATEST(DATEDIFF(dp.ngay_tra, dp.ngay_nhan), 1))
                + COALESCE(SUM_DV.total_dv, 0)
            ) AS total
        FROM datphong dp
        JOIN phong p ON dp.id_phong = p.id_phong
        LEFT JOIN (
            SELECT id_datphong, SUM(thanh_tien) AS total_dv
            FROM sudungdichvu
            GROUP BY id_datphong
        ) AS SUM_DV ON dp.id_datphong = SUM_DV.id_datphong
    ";
    $stmt = $this->db->query($sql);
    return (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
}
// Đếm tổng số khách hàng
public function getTotalKhachHang()
{
    $sql = "SELECT COUNT(*) AS total FROM khachhang";
    $stmt = $this->db->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['total'] ?? 0);
}
// Đếm tổng số dịch vụ
public function getTotalDichVu()
{
    $sql = "SELECT COUNT(*) AS total FROM dichvu";
    $stmt = $this->db->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['total'] ?? 0);
}

// Đếm tổng số nhân viên
public function getTotalNhanVien()
{
    $sql = "SELECT COUNT(*) AS total FROM nhanvien";
    $stmt = $this->db->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['total'] ?? 0);
}


}
