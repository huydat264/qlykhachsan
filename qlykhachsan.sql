-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 06, 2025 lúc 07:54 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlykhachsan`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bangluong`
--

CREATE TABLE `bangluong` (
  `id_bangluong` int(11) NOT NULL,
  `id_nhanvien` int(11) NOT NULL,
  `thang` int(11) NOT NULL,
  `nam` int(11) NOT NULL,
  `so_ngay_cong` int(11) DEFAULT 0,
  `thuong` decimal(15,2) DEFAULT 0.00,
  `phat` decimal(15,2) DEFAULT 0.00,
  `luong_co_ban` decimal(15,2) NOT NULL,
  `tong_luong` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bangluong`
--

INSERT INTO `bangluong` (`id_bangluong`, `id_nhanvien`, `thang`, `nam`, `so_ngay_cong`, `thuong`, `phat`, `luong_co_ban`, `tong_luong`) VALUES
(3, 3, 9, 2025, 29, 500000.00, 400000.00, 7500000.00, 8465385.00),
(5, 8, 9, 2025, 30, 300000.00, 100000.00, 8100000.00, 9546154.00),
(6, 12, 10, 2025, 0, 200000.00, 0.00, 12000000.00, 200000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chamcong`
--

CREATE TABLE `chamcong` (
  `id_chamcong` int(11) NOT NULL,
  `id_nhanvien` int(11) NOT NULL,
  `thang` int(11) NOT NULL,
  `nam` int(11) NOT NULL,
  `so_ngay_di_lam` int(11) DEFAULT 0,
  `so_ngay_nghi_khong_phep` int(11) DEFAULT 0,
  `so_ngay_nghi_co_phep` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chamcong`
--

INSERT INTO `chamcong` (`id_chamcong`, `id_nhanvien`, `thang`, `nam`, `so_ngay_di_lam`, `so_ngay_nghi_khong_phep`, `so_ngay_nghi_co_phep`) VALUES
(3, 3, 9, 2025, 29, 0, 1),
(7, 8, 9, 2025, 30, 0, 0),
(8, 13, 9, 2025, 24, 0, 2),
(10, 12, 9, 2025, 24, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `datphong`
--

CREATE TABLE `datphong` (
  `id_datphong` int(11) NOT NULL,
  `id_khachhang` int(11) NOT NULL,
  `id_phong` int(11) NOT NULL,
  `ngay_dat` date NOT NULL,
  `ngay_nhan` date NOT NULL,
  `ngay_tra` date DEFAULT NULL,
  `trang_thai` enum('Chờ xác nhận','Đã xác nhận','Đã thanh toán','Đã hủy') DEFAULT 'Chờ xác nhận'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `datphong`
--

INSERT INTO `datphong` (`id_datphong`, `id_khachhang`, `id_phong`, `ngay_dat`, `ngay_nhan`, `ngay_tra`, `trang_thai`) VALUES
(45, 1, 2, '2025-10-05', '2025-10-06', '2025-10-07', 'Đã thanh toán'),
(46, 28, 9, '2025-10-05', '2025-10-07', '2025-10-08', 'Đã thanh toán'),
(52, 29, 4, '2025-10-06', '2025-10-07', '2025-10-08', 'Đã thanh toán'),
(55, 30, 1, '2025-10-06', '2025-10-07', '2025-10-08', 'Đã thanh toán'),
(56, 13, 18, '2025-10-06', '2025-10-08', '2025-10-09', 'Đã thanh toán'),
(59, 28, 9, '2025-10-06', '2025-10-07', '2025-10-09', 'Đã xác nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dichvu`
--

CREATE TABLE `dichvu` (
  `id_dichvu` int(11) NOT NULL,
  `ten_dich_vu` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(15,2) NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dichvu`
--

INSERT INTO `dichvu` (`id_dichvu`, `ten_dich_vu`, `mo_ta`, `gia`, `hinh_anh`) VALUES
(1, 'Spa and Massage', 'Thư giãn toàn thân với liệu trình massage chuyên nghiệp.', 300000.00, '1759747238_pexels-olly-3757657.jpg'),
(2, 'Ăn sáng tại phòng', 'Thực đơn buffet sáng, phục vụ tận phòng.', 111000.00, '1759745315_pexels-julieaagaard-1426715.jpg'),
(3, 'Giặt ủi quần áo', 'Dịch vụ giặt, sấy và ủi, trả trong ngày.', 30000.00, '1759746018_laundry-la-gi.webp'),
(4, 'Thuê xe đưa đón sân bay', 'Xe 4–7 chỗ, đưa đón khách tại sân bay Nội Bài.', 500000.00, '1759748376_1.jpg'),
(7, 'Hồ bơi ngoài trời', 'Sử dụng hồ bơi vô cực ngoài trời, có khăn và nước uống.', 200000.00, '1759746383_pexels-quang-nguyen-vinh-222549-14036440.jpg'),
(8, 'Mini Bar trong phòng', 'Đồ uống, snack có sẵn trong minibar, tính theo sử dụng', 550000.00, '1759746758_pexels-andreevaleksandar-17705729.jpg'),
(9, 'Phòng gym', 'Trang bị máy chạy, tạ và huấn luyện viên hỗ trợ.', 80000.00, '1759746963_pexels-heyho-7031705.jpg'),
(10, 'Thuê phòng hội nghị', 'Phòng hội nghị sức chứa 50 người, trang bị máy chiếu, micro.', 2000000.00, '1759747757_phong-hoi-nghi-tai-Almaz-long-bien.jpg'),
(11, 'Karaoke gia đình', 'Phòng karaoke cách âm, dàn âm thanh hiện đại.', 510000.00, '1759746527_chi-phi-thiet-ke-phong-karaoke-tai-nha-1.jpg'),
(12, 'Dịch vụ trông trẻ', 'Nhân viên trông trẻ chuyên nghiệp, an toàn, có trách nhiệm', 399000.00, '1759745254_pexels-ivan-samkov-8504273.jpg'),
(13, 'Thuê xe đạp tham quan', 'Xe đạp cho khách tham quan quanh khu vực.', 90000.00, '1759748359_snapedit_1759748347766.jpeg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `id_hoadon` int(11) NOT NULL,
  `id_datphong` int(11) NOT NULL,
  `tong_tien` decimal(15,2) NOT NULL,
  `ngay_xuat` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`id_hoadon`, `id_datphong`, `tong_tien`, `ngay_xuat`) VALUES
(38, 45, 640000.00, '2025-10-06'),
(41, 46, 300000.00, '2025-10-06'),
(42, 55, 611000.00, '2025-10-06'),
(43, 52, 0.00, '2025-10-06'),
(44, 56, 180000.00, '2025-10-06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `id_khachhang` int(11) NOT NULL,
  `tai_khoan_khachhang_id` int(11) DEFAULT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `so_dien_thoai` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cccd` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`id_khachhang`, `tai_khoan_khachhang_id`, `ho_ten`, `ngay_sinh`, `gioi_tinh`, `so_dien_thoai`, `email`, `cccd`, `dia_chi`) VALUES
(1, 1, 'Nguyễn Văn A', '1990-01-01', 'Nam', '0987654323', 'b@example.com', '123456789012', 'Hà Nội'),
(2, 2, 'Trần Thị B', '1992-05-15', 'Nữ', '0987654321', 'a@example.com', '210987654321', 'Kiên Giang'),
(13, 22, 'Đặng Thị D', '2001-06-12', 'Nữ', '0938367382', 'dangD63@gmail.com', '039372929224', 'Hải Phòng'),
(24, 24, 'Trần Minh Quân', '2025-09-03', 'Nam', '0337374226', 'tranminhquan4225@gmail.com', '012204001178', 'Lai Châu'),
(25, 25, 'Hoàng Văn C', '2000-01-08', 'Nam', '0933793923', 'vanhoang34@gmail.com', '012204001177', 'Khánh Hòa'),
(27, 18, 'Lê Huy Đạt', '2004-08-16', 'Nam', '0939922923', 'huydat12@gmail.com', '022929393200', 'Hà Nội'),
(28, NULL, 'Nguyễn Diệu Thu', '2000-10-05', 'Nữ', '0922679345', 'dieuthu05@gmail.com', '023647892729', 'Hưng Yên'),
(29, NULL, 'Lê Linh Chi', '2002-09-27', 'Nữ', '0935728972', 'linhchi426@gmail.com', '023456788002', 'Thái Nguyên'),
(30, 26, 'Nguyễn Thị Lệ', '2004-01-07', 'Nữ', '0975350397', 'nguyenthile04@gmail.com', '033228393902', 'Châu Ninh Hưng Yên'),
(31, 27, 'Trần Minh Quân', '2004-02-04', 'Nam', '0927938929', 'minhquan42@gmail.com', '022239344894', 'Lai Châu');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id_nhanvien` int(11) NOT NULL,
  `tai_khoan_nhanvien_id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `chuc_vu` varchar(50) DEFAULT NULL,
  `luong_co_ban` decimal(15,2) NOT NULL,
  `ngay_vao_lam` date DEFAULT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`id_nhanvien`, `tai_khoan_nhanvien_id`, `ho_ten`, `chuc_vu`, `luong_co_ban`, `ngay_vao_lam`, `so_dien_thoai`, `email`) VALUES
(3, 8, 'Lê Thị Hoa', 'nhân viên', 7500000.00, '2025-09-24', '0988877764', 'lethihoa@gmail.com'),
(8, 12, 'Hoàng Văn C', 'nhân viên', 8100000.00, '2025-09-19', '0337374227', 'vanF292@gmail.com'),
(12, 3, 'Lê Văn Lương', 'Quản lý', 12000000.00, '1995-08-17', '0983537828', 'vanluong44@gmail.com'),
(13, 9, 'Đinh Diệu Mai', 'Lễ tân ', 5900000.00, '2002-11-13', '0937382294', 'maimai65@gmail.com'),
(15, 17, 'Nguyễn Quốc Bảo', 'nhân viên', 6900000.00, '1999-07-18', '0937929282', 'quocbao18@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

CREATE TABLE `phong` (
  `id_phong` int(11) NOT NULL,
  `so_phong` varchar(10) NOT NULL,
  `loai_phong` enum('Standard','Deluxe','Suite') NOT NULL,
  `gia_phong` decimal(15,2) NOT NULL,
  `so_luong_nguoi` int(11) NOT NULL,
  `trang_thai` enum('Trống','Đã đặt','Bảo trì') DEFAULT 'Trống',
  `mo_ta` text DEFAULT NULL,
  `anh` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`id_phong`, `so_phong`, `loai_phong`, `gia_phong`, `so_luong_nguoi`, `trang_thai`, `mo_ta`, `anh`) VALUES
(1, '101', 'Standard', 500000.00, 2, 'Trống', 'Phòng Standard nhỏ gọn (~20 m²), 1 giường đôi queen loại cơ bản, có cửa sổ nhìn vào nội khu, điều hoà, TV màn hình phẳng, wifi tốc độ cao, phòng tắm vòi sen, đồ vệ sinh cá nhân tiêu chuẩn.', 'https://images.pexels.com/photos/28272332/pexels-photo-28272332.jpeg'),
(2, '102', 'Standard', 500000.00, 2, 'Trống', 'Phòng tiêu chuẩn tiện nghi, 2 giường đơn, thích hợp cho bạn bè hoặc đồng nghiệp.', 'https://ezcloud.vn/wp-content/uploads/2023/03/phong-standard-la-gi.webp'),
(3, '103', 'Standard', 600000.00, 2, 'Bảo trì', 'Phòng tiêu chuẩn gọn gàng, 1 giường đôi, có ánh sáng tự nhiên, lý tưởng cho kỳ nghỉ ngắn.', 'https://res.cloudinary.com/maistra/image/upload/w_1920,c_lfill,g_auto,q_auto,dpr_auto/f_auto/v1700658053/Proprietes/Select/Zagreb/Hotel%20International/22.11.23/23074-09-18%20Hotel%20International%20Rooms/23074-09-18%20Hotel%20International%20Rooms%20Standard%20Single%20Use/Webres%202000px/23074-09-18_Hotel_International_Rooms_Classic_Queen_1_2000px_sivgq2.jpg'),
(4, '104', 'Deluxe', 1450000.00, 3, 'Trống', 'Phòng cao cấp rộng rãi, thiết kế hiện đại, cửa sổ view thành phố.', 'https://noithaticon.vn/wp-content/uploads/2023/08/kich-thuoc-giuong-don-tan-co-dien-2-1690878432.jpg'),
(5, '105', 'Deluxe', 1500000.00, 3, 'Trống', 'Phòng cao cấp sang trọng, có ban công nhỏ và tầm nhìn thoáng đãng.', 'https://dyf.vn/wp-content/uploads/2021/12/phong-Deluxe-Double.jpg'),
(6, '106', 'Deluxe', 1000000.00, 3, 'Trống', 'Phòng cao cấp tiện nghi, giường lớn, phù hợp nghỉ dưỡng dài ngày.', 'https://statics.vinpearl.com/gia-phong-vinpearl-ha-long-03.jpg'),
(7, '201', 'Suite', 3200000.00, 4, 'Trống', 'Suite cao cấp - VIP: diện tích ~70 m², phòng khách + bếp nhỏ, bàn ăn, bồn tắm jacuzzi + vòi sen đứng, nhiều view đẹp, tiện ích đặc biệt (welcome amenities, đôi giày đi trong nhà,…)', 'https://images.pexels.com/photos/7005295/pexels-photo-7005295.jpeg'),
(8, '202', 'Suite', 2800000.00, 4, 'Trống', 'Suite sang trọng hơn, ban công rộng hoặc cửa sổ panorama, decor sang trọng, có thêm bàn ăn nhỏ nếu có thể, dịch vụ ưu tiên.', 'https://www.hotelscombined.com.au/himg/3f/97/5c/ice-18471-106990353-583520.jpg'),
(9, '203', 'Standard', 670000.00, 2, 'Đã đặt', 'Standard có ban công nhỏ / cửa sổ lớn đón sáng, nội thất gỗ/laminate, phong cách hiện đại.', 'https://sp-ao.shortpixel.ai/client/to_webp,q_glossy,ret_img/https://neworienthoteldanang.com/wp-content/uploads/2023/09/stay9.jpg'),
(10, '204', 'Standard', 700000.00, 2, 'Trống', 'Phòng Standard lớn hơn (~20-22 m²), view hướng đường/nội khu đẹp hơn, bộ đồ uống nóng lạnh miễn phí.', 'https://dulichsaigon.edu.vn/wp-content/uploads/2025/01/8-cac-loai-phong-trong-khach-san.jpg'),
(11, '205', 'Deluxe', 1590000.00, 3, 'Trống', 'Reluxe VIP với phòng khách riêng, thiết kế đẳng cấp, decor cao cấp hơn, có tiện nghi cộng thêm như dịch vụ đặt thức ăn phòng hoặc minibar cao cấp.', 'https://noithattamviet.com.vn/public/images/products/combo-noi-that-phong-ngu-master-go-cong-nghiep-cpn-37-1744164932.jpg'),
(12, '301', 'Deluxe', 990000.00, 3, 'Trống', 'Phòng Reluxe (~28-30 m²), giường Queen size, thiết kế nội thất cao cấp hơn, có minibar, TV lớn, phòng tắm đôi (vòi sen & bồn tắm nếu có).', 'https://images.pexels.com/photos/7018391/pexels-photo-7018391.jpeg'),
(13, '302', 'Suite', 3000000.00, 4, 'Trống', 'Suite hạng cao với tiện nghi đầy đủ, thiết kế hiện đại, có phòng khách, khu tiếp khách, view đẹp, nội thất chất lượng cao.', 'https://images.pexels.com/photos/30587967/pexels-photo-30587967.jpeg'),
(14, '303', 'Standard', 550000.00, 2, 'Trống', 'Standard được trang bị thêm thiết bị vệ sinh cao cấp hơn (vòi sen mưa, máy sấy tóc, áo choàng tắm).', 'https://static.fireant.vn/Upload/20240331/images/phong-mau-khach-san-go-cong-nghiep-01.jpg'),
(15, '304', 'Deluxe', 950000.00, 3, 'Trống', 'Reluxe có phòng khách nhỏ hoặc ghế sofa thư giãn, ban công lớn hoặc cửa sổ hướng đẹp, vật liệu nội thất cao cấp hơn.', 'https://dyf.vn/wp-content/uploads/2021/12/co-nen-chon-phong-deluxe.jpg'),
(16, '401', 'Suite', 3500000.00, 4, 'Trống', 'Suite VIP cao cấp nhất: diện tích ~80-90 m², view toàn cảnh, trang thiết bị cao cấp (cà phê espresso, máy pha cao cấp, minibar loại thượng hạng…), dịch vụ tùy chỉnh (nhận/trả phòng linh hoạt, dịch vụ riêng, decor đặc biệt).', 'https://vanangroup.com.vn/wp-content/uploads/2024/05/President-Suite-phong-dac-biet-danh-cho-nguyen-thu-quoc-gia.jpg'),
(17, '402', 'Standard', 710000.00, 2, 'Trống', 'Standard Premium: không chỉ tiện nghi đầy đủ mà còn có thiết kế decor nội thất bắt mắt hơn, lớp sơn & trang trí tốt hơn.', 'https://noithatvieta.vn/upload/images/thi-cong-noi-that-khach-san-hien-dai.jpg'),
(18, '403', 'Deluxe', 1550000.00, 3, 'Trống', 'Phòng có 1 phòng ngủ lớn hoặc giường king + giường đơn, phù hợp gia đình nhỏ, có thêm sofa bed / khu tiếp khách.', 'https://dyf.vn/wp-content/uploads/2021/12/phong-deluxe-la-gi.png'),
(19, '501', 'Suite', 2500000.00, 4, 'Trống', 'Suite rộng (~50-60 m²), 2 phòng ngủ riêng, view đẹp (phố hoặc hồ), minibar đầy đủ, bồn tắm lớn, thiết bị cao cấp, dịch vụ phòng 24h.', 'https://images.pexels.com/photos/18801087/pexels-photo-18801087.jpeg'),
(20, '502', 'Standard', 750000.00, 2, 'Trống', 'Standard cao cấp với ban công / view nhìn phố, âm thanh cách âm tốt, có khu vực nhỏ tiếp khách / ghế thư giãn.', 'https://hocquanlynhahangkhachsan.wordpress.com/wp-content/uploads/2018/04/86481-nhieu-loai-phong-khach-nhau.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sudungdichvu`
--

CREATE TABLE `sudungdichvu` (
  `id_sudungdv` int(11) NOT NULL,
  `id_datphong` int(11) NOT NULL,
  `id_dichvu` int(11) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  `thanh_tien` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sudungdichvu`
--

INSERT INTO `sudungdichvu` (`id_sudungdv`, `id_datphong`, `id_dichvu`, `so_luong`, `thanh_tien`) VALUES
(30, 46, 1, 1, 300000.00),
(31, 45, 8, 1, 550000.00),
(32, 45, 13, 1, 90000.00),
(35, 55, 4, 1, 500000.00),
(36, 55, 2, 1, 111000.00),
(37, 56, 13, 2, 180000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id_taikhoan` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('USER','NHANVIEN','ADMIN') DEFAULT 'USER',
  `trang_thai` enum('ACTIVE','BLOCKED') DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id_taikhoan`, `username`, `password`, `role`, `trang_thai`, `created_at`) VALUES
(1, 'khachhang1', 'kh_pass1', 'USER', 'ACTIVE', '2025-09-18 14:00:00'),
(2, 'khachhang2', 'kh_pass2', 'USER', 'ACTIVE', '2025-09-18 14:01:00'),
(3, 'nhanvien1', 'nv_pass1', 'NHANVIEN', 'ACTIVE', '2025-09-18 14:02:00'),
(4, 'admin1', 'ad_pass1', 'ADMIN', 'ACTIVE', '2025-09-18 14:03:00'),
(7, 'kh71171', '$2y$10$M41gzkfLnxi6VL.4KUYJZuBwwyJR03wH7deIdbWtVCxEDv/8A8Od6', 'USER', 'ACTIVE', '2025-09-18 14:53:02'),
(8, 'nv_lethihoa', 'lethihoa123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(9, 'nv_tranvanan', 'tranvanan123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(10, 'nv_nguyenthibinh', 'nguyenthibinh123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(11, 'nv_phamhung', 'phamhung123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(12, 'nv_hoangthihong', 'hoangthihong123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(13, 'nv_vuvanthanh', 'vuvanthanh123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(14, 'nv_dothihue', 'dothihue123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(15, 'nv_dangminhkhue', 'dangminhkhue123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(16, 'nv_nguyenvietcuong', 'nguyenvietcuong123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(17, 'nv_truongthianh', 'truongthianh123', 'NHANVIEN', 'ACTIVE', '2025-09-18 18:31:06'),
(18, 'kh80038', '$2y$10$nzxNC1eAB/BxKyU3NHvOueoszygGXhzI/yU99Qu/ye5LZVAlD2Q4y', 'USER', 'ACTIVE', '2025-09-27 09:02:30'),
(19, 'kh75671', '$2y$10$G3NVJ63RK0z0eEGG9ls37u2ldWGd5R8911ZGCdWb.FvmQTtW/jHo2', 'USER', 'ACTIVE', '2025-09-27 09:03:58'),
(20, 'kh51259', '$2y$10$6YWxDZtL.P2QMp4aMxrc1ex2eLuJUFjLhK/5Lb/ugeCszkSrgfTCS', 'USER', 'ACTIVE', '2025-09-27 10:08:51'),
(21, 'kh39639', '$2y$10$jlpvyftKG.JBuNRJjmu94OyWh4V0cOmFWFWrlCdONLoR6kGU2WJte', 'USER', 'ACTIVE', '2025-09-28 17:08:00'),
(22, 'kh63747', '$2y$10$f9IMcEHwhNicFi/G1b8dweNf0kcSwStracBamWzKuF2rrPGqrxsyy', 'USER', 'ACTIVE', '2025-09-29 15:41:37'),
(23, 'kh70587', '$2y$10$du9Dizcz0VFjb7q.DBB/6OKZrn/adPoOT1Hkz.pWU.t.Z0dKdB91y', 'USER', 'ACTIVE', '2025-10-04 14:51:54'),
(24, 'kh48262', '$2y$10$eb62DAEQyvkr2RRQOuPQ5Omr3EdvLKAwKFGoEdA.y.2d2iAkPJPJ6', 'USER', 'ACTIVE', '2025-10-04 14:54:31'),
(25, 'kh23594', '$2y$10$LKQx4OrHFo/ze5Tdh45ECuPZZgNQ5l3aZxDk86bj4NVu1PCXe7wOG', 'USER', 'ACTIVE', '2025-10-04 15:52:05'),
(26, 'nguyenle', 'nguyenle123@', 'USER', 'ACTIVE', '2025-10-06 09:49:36'),
(27, 'Minh Quân', 'mquan123@', 'USER', 'ACTIVE', '2025-10-06 15:19:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `id_thanhtoan` int(11) NOT NULL,
  `id_datphong` int(11) NOT NULL,
  `ngay_thanh_toan` datetime DEFAULT current_timestamp(),
  `so_tien` decimal(15,2) NOT NULL,
  `hinh_thuc` enum('Tiền mặt','Chuyển khoản') NOT NULL,
  `loai_thanh_toan` enum('Thanh toán cuối') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhtoan`
--

INSERT INTO `thanhtoan` (`id_thanhtoan`, `id_datphong`, `ngay_thanh_toan`, `so_tien`, `hinh_thuc`, `loai_thanh_toan`) VALUES
(39, 45, '2025-10-06 11:37:10', 640000.00, 'Tiền mặt', 'Thanh toán cuối'),
(44, 46, '2025-10-06 15:37:23', 300000.00, 'Tiền mặt', 'Thanh toán cuối'),
(45, 55, '2025-10-06 00:00:00', 500000.00, 'Chuyển khoản', ''),
(46, 55, '2025-10-06 16:42:33', 611000.00, 'Chuyển khoản', 'Thanh toán cuối'),
(47, 52, '2025-10-06 16:44:01', 0.00, 'Tiền mặt', 'Thanh toán cuối'),
(48, 56, '2025-10-06 16:59:09', 180000.00, 'Tiền mặt', 'Thanh toán cuối');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bangluong`
--
ALTER TABLE `bangluong`
  ADD PRIMARY KEY (`id_bangluong`),
  ADD UNIQUE KEY `id_nhanvien` (`id_nhanvien`,`thang`,`nam`);

--
-- Chỉ mục cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  ADD PRIMARY KEY (`id_chamcong`),
  ADD UNIQUE KEY `id_nhanvien` (`id_nhanvien`,`thang`,`nam`);

--
-- Chỉ mục cho bảng `datphong`
--
ALTER TABLE `datphong`
  ADD PRIMARY KEY (`id_datphong`),
  ADD KEY `id_khachhang` (`id_khachhang`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `dichvu`
--
ALTER TABLE `dichvu`
  ADD PRIMARY KEY (`id_dichvu`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`id_hoadon`),
  ADD KEY `id_datphong` (`id_datphong`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`id_khachhang`),
  ADD UNIQUE KEY `tai_khoan_khachhang_id` (`tai_khoan_khachhang_id`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id_nhanvien`),
  ADD UNIQUE KEY `tai_khoan_nhanvien_id` (`tai_khoan_nhanvien_id`);

--
-- Chỉ mục cho bảng `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`id_phong`),
  ADD UNIQUE KEY `so_phong` (`so_phong`);

--
-- Chỉ mục cho bảng `sudungdichvu`
--
ALTER TABLE `sudungdichvu`
  ADD PRIMARY KEY (`id_sudungdv`),
  ADD KEY `id_datphong` (`id_datphong`),
  ADD KEY `id_dichvu` (`id_dichvu`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id_taikhoan`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`id_thanhtoan`),
  ADD KEY `id_datphong` (`id_datphong`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bangluong`
--
ALTER TABLE `bangluong`
  MODIFY `id_bangluong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  MODIFY `id_chamcong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `datphong`
--
ALTER TABLE `datphong`
  MODIFY `id_datphong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT cho bảng `dichvu`
--
ALTER TABLE `dichvu`
  MODIFY `id_dichvu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `id_hoadon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `id_khachhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id_nhanvien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `phong`
--
ALTER TABLE `phong`
  MODIFY `id_phong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `sudungdichvu`
--
ALTER TABLE `sudungdichvu`
  MODIFY `id_sudungdv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id_taikhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `id_thanhtoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bangluong`
--
ALTER TABLE `bangluong`
  ADD CONSTRAINT `bangluong_ibfk_1` FOREIGN KEY (`id_nhanvien`) REFERENCES `nhanvien` (`id_nhanvien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chamcong`
--
ALTER TABLE `chamcong`
  ADD CONSTRAINT `chamcong_ibfk_1` FOREIGN KEY (`id_nhanvien`) REFERENCES `nhanvien` (`id_nhanvien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `datphong`
--
ALTER TABLE `datphong`
  ADD CONSTRAINT `datphong_ibfk_1` FOREIGN KEY (`id_khachhang`) REFERENCES `khachhang` (`id_khachhang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `datphong_ibfk_2` FOREIGN KEY (`id_phong`) REFERENCES `phong` (`id_phong`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`id_datphong`) REFERENCES `datphong` (`id_datphong`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`tai_khoan_khachhang_id`) REFERENCES `taikhoan` (`id_taikhoan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`tai_khoan_nhanvien_id`) REFERENCES `taikhoan` (`id_taikhoan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `sudungdichvu`
--
ALTER TABLE `sudungdichvu`
  ADD CONSTRAINT `sudungdichvu_ibfk_1` FOREIGN KEY (`id_datphong`) REFERENCES `datphong` (`id_datphong`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sudungdichvu_ibfk_2` FOREIGN KEY (`id_dichvu`) REFERENCES `dichvu` (`id_dichvu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`id_datphong`) REFERENCES `datphong` (`id_datphong`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
