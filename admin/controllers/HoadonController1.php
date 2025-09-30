<?php
require_once __DIR__ . '/../models/Hoadon.php';

class HoadonController1 {
    private $model;

    public function __construct($db) {
        $this->model = new Hoadon($db);
    }

    public function showInvoice($id_datphong) {
        if ($id_datphong == 0) {
            return [null, [], "Không tìm thấy thông tin đặt phòng."];
        }

        try {
            $hoa_don = $this->model->getHoadonByDatphong($id_datphong);
            $dichvu_sudung = $this->model->getDichvuSudung($id_datphong);

            if ($hoa_don) {
                return [$hoa_don, $dichvu_sudung, null];
            } else {
                return [null, [], "Không tìm thấy thông tin hóa đơn."];
            }
        } catch (Exception $e) {
            return [null, [], "Lỗi: " . $e->getMessage()];
        }
    }
}
