<?php
// models/Chamcong.php
// Sửa để dùng Database::getConnection() (PDO) thay vì getInstance()

require_once __DIR__ . '/../config/database.php';

class Chamcong
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        // Database::getConnection() phải trả về một PDO (theo database.php bạn đã cung cấp trước)
        $this->db = Database::getConnection();
    }

    /**
     * Lấy danh sách nhân viên (id, ho_ten)
     * @return array
     */
    public function getNhanVienList(): array
    {
        $stmt = $this->db->query("SELECT id_nhanvien, ho_ten FROM nhanvien ORDER BY ho_ten");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả bản ghi chấm công kèm tên nhân viên
     * @return array
     */
    public function getChamcongList(): array
    {
        $sql = "SELECT cc.*, nv.ho_ten
                FROM chamcong cc
                JOIN nhanvien nv ON cc.id_nhanvien = nv.id_nhanvien
                ORDER BY cc.nam DESC, cc.thang DESC, nv.ho_ten ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chấm công theo id
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM chamcong WHERE id_chamcong = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Thêm bản ghi chấm công
     * @param array $data
     * @return bool
     * @throws PDOException
     */
    public function insert(array $data): bool
    {
        $sql = "INSERT INTO chamcong
                (id_nhanvien, thang, nam, so_ngay_di_lam, so_ngay_nghi_co_phep, so_ngay_nghi_khong_phep)
                VALUES (:id_nhanvien, :thang, :nam, :so_ngay_di_lam, :so_ngay_nghi_co_phep, :so_ngay_nghi_khong_phep)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_nhanvien' => (int)$data['id_nhanvien'],
            'thang' => (int)$data['thang'],
            'nam' => (int)$data['nam'],
            'so_ngay_di_lam' => (int)$data['so_ngay_di_lam'],
            'so_ngay_nghi_co_phep' => (int)$data['so_ngay_nghi_co_phep'],
            'so_ngay_nghi_khong_phep' => (int)$data['so_ngay_nghi_khong_phep'],
        ]);
    }

    /**
     * Cập nhật bản ghi chấm công
     * @param int $id
     * @param array $data
     * @return bool
     * @throws PDOException
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE chamcong SET
                    id_nhanvien = :id_nhanvien,
                    thang = :thang,
                    nam = :nam,
                    so_ngay_di_lam = :so_ngay_di_lam,
                    so_ngay_nghi_co_phep = :so_ngay_nghi_co_phep,
                    so_ngay_nghi_khong_phep = :so_ngay_nghi_khong_phep
                WHERE id_chamcong = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_nhanvien' => (int)$data['id_nhanvien'],
            'thang' => (int)$data['thang'],
            'nam' => (int)$data['nam'],
            'so_ngay_di_lam' => (int)$data['so_ngay_di_lam'],
            'so_ngay_nghi_co_phep' => (int)$data['so_ngay_nghi_co_phep'],
            'so_ngay_nghi_khong_phep' => (int)$data['so_ngay_nghi_khong_phep'],
            'id' => $id
        ]);
    }
}
