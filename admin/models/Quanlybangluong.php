<?php
require_once __DIR__ . '/../config/Database.php';

class Quanlybangluong
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /** Lấy danh sách nhân viên (id, tên, lương cơ bản, chức vụ) */
    public function getAllNhanVien(): array
    {
        $sql = "SELECT id_nhanvien, ho_ten, luong_co_ban, chuc_vu 
                FROM nhanvien ORDER BY ho_ten";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy lương cơ bản của 1 nhân viên */
    public function getLuongCoBan(int $id_nhanvien): float
    {
        $stmt = $this->conn->prepare(
            "SELECT luong_co_ban FROM nhanvien WHERE id_nhanvien = :id"
        );
        $stmt->execute([':id' => $id_nhanvien]);
        return (float)($stmt->fetchColumn() ?: 0);
    }

    /** Lấy số ngày công */
    public function getSoNgayCong(int $id_nhanvien, int $thang, int $nam): int
    {
        $stmt = $this->conn->prepare(
            "SELECT so_ngay_di_lam FROM chamcong 
             WHERE id_nhanvien = :id AND thang = :thang AND nam = :nam"
        );
        $stmt->execute([
            ':id'    => $id_nhanvien,
            ':thang' => $thang,
            ':nam'   => $nam
        ]);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    /** Tính tổng lương dựa vào bảng chấm công, thưởng và phạt */
    public function tinhTongLuong(int $id_nhanvien, int $thang, int $nam, float $thuong = 0, float $phat = 0): float
{
    $luong_cb = $this->getLuongCoBan($id_nhanvien);

    $stmt = $this->conn->prepare(
        "SELECT so_ngay_di_lam, so_ngay_nghi_co_phep, so_ngay_nghi_khong_phep
         FROM chamcong
         WHERE id_nhanvien = :id AND thang = :thang AND nam = :nam"
    );
    $stmt->execute([
        ':id' => $id_nhanvien,
        ':thang' => $thang,
        ':nam' => $nam
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $so_ngay_di_lam = (int)($row['so_ngay_di_lam'] ?? 0);
    $so_ngay_nghi_co_phep = (int)($row['so_ngay_nghi_co_phep'] ?? 0);
    $so_ngay_nghi_khong_phep = (int)($row['so_ngay_nghi_khong_phep'] ?? 0);

    $luong_ngay = $luong_cb / 26;

    // Nghỉ phép vượt 1 ngày sẽ bị trừ
    $tien_tru_nghi_phep = max($so_ngay_nghi_co_phep - 1, 0) * $luong_ngay;

    // Nghỉ không phép trừ đầy đủ
    $tien_tru_nghi_khong_phep = $so_ngay_nghi_khong_phep * $luong_ngay;

    $tong = ($luong_ngay * $so_ngay_di_lam) - $tien_tru_nghi_phep - $tien_tru_nghi_khong_phep + $thuong - $phat;

    return round($tong, 0);
}


    /** Thêm bản ghi bảng lương */
    public function insert(array $data): bool
    {
        $sql = "INSERT INTO bangluong
                (id_nhanvien, thang, nam, so_ngay_cong, thuong, phat, luong_co_ban, tong_luong)
                VALUES (:id_nv, :thang, :nam, :so_cong, :thuong, :phat, :luong_cb, :tong)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_nv'    => $data['id_nhanvien'],
            ':thang'    => $data['thang'],
            ':nam'      => $data['nam'],
            ':so_cong'  => $data['so_ngay_cong'],
            ':thuong'   => $data['thuong'],
            ':phat'     => $data['phat'],
            ':luong_cb' => $data['luong_co_ban'],
            ':tong'     => $data['tong_luong'],
        ]);
    }

    /** Cập nhật bản ghi */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE bangluong 
                SET id_nhanvien=:id_nv, thang=:thang, nam=:nam, so_ngay_cong=:so_cong,
                    thuong=:thuong, phat=:phat, luong_co_ban=:luong_cb, tong_luong=:tong
                WHERE id_bangluong=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'      => $id,
            ':id_nv'   => $data['id_nhanvien'],
            ':thang'   => $data['thang'],
            ':nam'     => $data['nam'],
            ':so_cong' => $data['so_ngay_cong'],
            ':thuong'  => $data['thuong'],
            ':phat'    => $data['phat'],
            ':luong_cb'=> $data['luong_co_ban'],
            ':tong'    => $data['tong_luong']
        ]);
    }

    /** Lấy 1 bản ghi để chỉnh sửa */
    public function getById(int $id): ?array
    {
        $sql = "SELECT bl.*, nv.ho_ten, nv.luong_co_ban, nv.chuc_vu
                FROM bangluong bl
                JOIN nhanvien nv ON bl.id_nhanvien = nv.id_nhanvien
                WHERE bl.id_bangluong = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Lấy danh sách bảng lương */
    public function getAll(): array
    {
        $sql = "SELECT bl.*, nv.ho_ten, nv.chuc_vu
                FROM bangluong bl
                JOIN nhanvien nv ON bl.id_nhanvien = nv.id_nhanvien
                ORDER BY bl.nam DESC, bl.thang DESC, nv.ho_ten ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
