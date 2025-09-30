<?php
class DichVu {
    private $id;
    private $ten;
    private $moTa;
    private $gia;

    public function __construct($id, $ten, $moTa, $gia) {
        $this->id = $id;
        $this->ten = $ten;
        $this->moTa = $moTa;
        $this->gia = $gia;
    }

    public function getId() { return $this->id; }
    public function getTen() { return $this->ten; }
    public function getMoTa() { return $this->moTa; }
    public function getGia() { return $this->gia; }
}


