<?php
// Nếu controller chưa truyền biến $dichvus thì tạo rỗng để tránh báo lỗi
if (!isset($dichvus)) {
    $dichvus = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dịch vụ khách sạn</title>
    <style>
        /* ====== TOÀN TRANG ====== */
        body {
            font-family: "Poppins", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://cdn.vietnambiz.vn/2019/11/4/hotel-security-london-15728559445071765810802.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #002060;
            font-size: 36px;
            font-weight: 700;
            position: relative;
            letter-spacing: 1px;
        }

        h1::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: #007BFF;
            border-radius: 2px;
        }

        /* ====== SERVICE ITEM ====== */
        .service-item {
            position: relative;
            display: flex;
            align-items: flex-end;
            background-size: cover;
            background-position: center;
            margin-bottom: 25px;
            border-radius: 14px;
            overflow: hidden;
            height: 240px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .service-item::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0.1));
            transition: background 0.3s ease;
        }

        .service-item:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .service-item:hover::before {
            background: linear-gradient(to top, rgba(0,0,0,0.75), rgba(0,0,0,0.2));
        }

        /* ====== TEXT TRÊN ẢNH ====== */
        .service-info {
            position: relative;
            z-index: 2;
            padding: 25px;
            color: #fff;
        }

        .service-info h2 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 600;
            color: #fff;
        }

        .service-info p {
            margin: 0 0 8px;
            color: #eaeaea;
            font-size: 15px;
            line-height: 1.5;
        }

        .service-info strong {
            color: #ffd966;
        }

        /* ====== NO SERVICE ====== */
        .no-service {
            text-align: center;
            color: #777;
            margin-top: 50px;
            font-size: 18px;
        }

        /* ====== HIỆU ỨNG NHẤN ====== */
        .service-item:active {
            transform: scale(0.99);
        }
    </style>
    <script>
        // cho phép nhấn cả thẻ service-item để vào chi tiết
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".service-item").forEach(item => {
                item.addEventListener("click", () => {
                    const link = item.getAttribute("data-link");
                    if (link) window.location.href = link;
                });
            });
        });
    </script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h1>Dịch vụ khách sạn</h1>

    <?php if (!empty($dichvus)): ?>
        <?php foreach ($dichvus as $dv): ?>
            <?php
                // Nếu có ảnh thì tạo đường dẫn tới thư mục admin/uploads/dichvu
                $hinhAnh = $dv->getHinhAnh();
                if ($hinhAnh) {
                    $duongDanAnh = "/doanqlks/admin/uploads/dichvu/" . htmlspecialchars($hinhAnh);
                } else {
                    $duongDanAnh = "images/no-image.jpg"; // ảnh mặc định
                }
                $linkChiTiet = "../Controlleruser/DichVuController.php?action=detail&id=" . $dv->getId();
            ?>

            <div class="service-item" 
                 style="background-image: url('<?= $duongDanAnh ?>');" 
                 data-link="<?= htmlspecialchars($linkChiTiet) ?>">
                <div class="service-info">
                    <h2><?= htmlspecialchars($dv->getTen()) ?></h2>
                    <p><?= htmlspecialchars($dv->getMoTa()) ?></p>
                    <p><strong>Giá: </strong><?= number_format($dv->getGia(), 0, ',', '.') ?> VND</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-service">Chưa có dịch vụ nào để hiển thị.</p>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
