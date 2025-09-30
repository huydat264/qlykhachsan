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
        $sql = "SELECT kh.ho_ten, SUM(hd.tong_tien) AS total_spent
                FROM hoadon hd
                JOIN datphong dp ON hd.id_datphong = dp.id_datphong
                JOIN khachhang kh ON dp.id_khachhang = kh.id_khachhang
                GROUP BY kh.id_khachhang, kh.ho_ten
                ORDER BY total_spent DESC
                LIMIT 3";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalKhachHang()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM khachhang");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalDichVu()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM dichvu");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalNhanVien()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM nhanvien");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
