<?php
class DichVu {
    private $id;
    private $ten;
    private $moTa;
    private $gia;
    private $hinhAnh;

    public function __construct($id, $ten, $moTa, $gia, $hinhAnh = null) {
        $this->id = $id;
        $this->ten = $ten;
        $this->moTa = $moTa;
        $this->gia = $gia;
        $this->hinhAnh = $hinhAnh;
    }

    public function getId() {
        return $this->id;
    }

    public function getTen() {
        return $this->ten;
    }

    public function getMoTa() {
        return $this->moTa;
    }

    public function getGia() {
        return $this->gia;
    }

    public function getHinhAnh() {
        return $this->hinhAnh;
    }

    public function setHinhAnh($hinhAnh) {
        $this->hinhAnh = $hinhAnh;
    }
}
?>
