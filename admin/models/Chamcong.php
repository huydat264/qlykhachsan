<?php
// models/Chamcong.php
require_once __DIR__ . '/../config/database.php';

class Chamcong
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getNhanVienList(): array
    {
        $stmt = $this->db->query("SELECT id_nhanvien, ho_ten FROM nhanvien ORDER BY ho_ten");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChamcongList(): array
    {
        $sql = "SELECT cc.*, nv.ho_ten
                FROM chamcong cc
                JOIN nhanvien nv ON cc.id_nhanvien = nv.id_nhanvien
                ORDER BY cc.nam DESC, cc.thang DESC, nv.ho_ten ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM chamcong WHERE id_chamcong = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào (tránh chuỗi rỗng -> (int) = 0)
     */
    private function sanitize(array $data): array
    {
        return [
            'id_nhanvien' => isset($data['id_nhanvien']) ? (int)$data['id_nhanvien'] : 0,
            'thang' => isset($data['thang']) ? (int)$data['thang'] : 0,
            'nam' => isset($data['nam']) ? (int)$data['nam'] : 0,
            'so_ngay_di_lam' => isset($data['so_ngay_di_lam']) ? (int)$data['so_ngay_di_lam'] : 0,
            'so_ngay_nghi_co_phep' => isset($data['so_ngay_nghi_co_phep']) ? (int)$data['so_ngay_nghi_co_phep'] : 0,
            'so_ngay_nghi_khong_phep' => isset($data['so_ngay_nghi_khong_phep']) ? (int)$data['so_ngay_nghi_khong_phep'] : 0,
        ];
    }

    /**
     * Kiểm tra tháng/năm hợp lệ
     */
    private function validateMonthYear(int $thang, int $nam): void
    {
        if ($thang < 1 || $thang > 12) {
            throw new Exception("Tháng không hợp lệ. Vui lòng chọn tháng từ 1 đến 12.");
        }
        if ($nam < 1900 || $nam > 3000) {
            throw new Exception("Năm không hợp lệ.");
        }
    }

    /**
     * Validate số ngày (không âm, tổng <= số ngày thực tế của tháng)
     */
    private function validateDays(array $data): void
    {
        $thang = $data['thang'];
        $nam = $data['nam'];

        // kiểm tra tháng/năm hợp lệ trước
        $this->validateMonthYear($thang, $nam);

        // số ngày thực tế của tháng (tự xử lý leap year)
        $max_days = cal_days_in_month(CAL_GREGORIAN, $thang, $nam);

        $di_lam = $data['so_ngay_di_lam'];
        $nghi_co = $data['so_ngay_nghi_co_phep'];
        $nghi_khong = $data['so_ngay_nghi_khong_phep'];

        // không cho âm
        if ($di_lam < 0 || $nghi_co < 0 || $nghi_khong < 0) {
            throw new Exception("Số ngày không được là số âm.");
        }

        // kiểm tra từng trường có hợp lý (ví dụ 100 ngày là vô lý)
        if ($di_lam > $max_days || $nghi_co > $max_days || $nghi_khong > $max_days) {
            throw new Exception("Một trong các trường số ngày vượt quá số ngày tối đa của tháng ($max_days).");
        }

        $tong = $di_lam + $nghi_co + $nghi_khong;

        if ($tong > $max_days) {
            throw new Exception("Tổng số ngày ($tong) vượt quá số ngày của tháng $thang/$nam ($max_days).");
        }
    }

    /**
     * Thêm bản ghi chấm công
     */
    public function insert(array $data): bool
    {
        $data = $this->sanitize($data);

        // validate trước khi insert
        $this->validateDays($data);

        $sql = "INSERT INTO chamcong
                (id_nhanvien, thang, nam, so_ngay_di_lam, so_ngay_nghi_co_phep, so_ngay_nghi_khong_phep)
                VALUES (:id_nhanvien, :thang, :nam, :so_ngay_di_lam, :so_ngay_nghi_co_phep, :so_ngay_nghi_khong_phep)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_nhanvien' => $data['id_nhanvien'],
            'thang' => $data['thang'],
            'nam' => $data['nam'],
            'so_ngay_di_lam' => $data['so_ngay_di_lam'],
            'so_ngay_nghi_co_phep' => $data['so_ngay_nghi_co_phep'],
            'so_ngay_nghi_khong_phep' => $data['so_ngay_nghi_khong_phep'],
        ]);
    }

    /**
     * Cập nhật bản ghi chấm công
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->sanitize($data);

        // validate trước khi update
        $this->validateDays($data);

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
            'id_nhanvien' => $data['id_nhanvien'],
            'thang' => $data['thang'],
            'nam' => $data['nam'],
            'so_ngay_di_lam' => $data['so_ngay_di_lam'],
            'so_ngay_nghi_co_phep' => $data['so_ngay_nghi_co_phep'],
            'so_ngay_nghi_khong_phep' => $data['so_ngay_nghi_khong_phep'],
            'id' => $id
        ]);
    }
}
