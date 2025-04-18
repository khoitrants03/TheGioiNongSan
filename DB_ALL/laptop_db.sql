-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 30, 2024 lúc 03:34 PM
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
-- Cơ sở dữ liệu: `laptop_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bacsi`
--

CREATE TABLE `bacsi` (
  `id` int(11) NOT NULL,
  `imge` text NOT NULL,
  `ten` text NOT NULL,
  `chuyenkhoa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bacsi`
--

INSERT INTO `bacsi` (`id`, `imge`, `ten`, `chuyenkhoa`) VALUES
(1, 'download (2).jfif', 'BS.CKII : Trần Văn A', 'Chuyên Khoa : Tai - Mũi - Họng'),
(2, 'download (2).jfif', 'BS.CKII : Trần Văn Toàn', 'Chuyên Khoa: Nhi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(2, 0, 'Phan Thiên Khải', 'phanthienkhai111@gmail.com', '0384104942', 'Yêu em');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `new`
--

CREATE TABLE `new` (
  `title` varchar(225) NOT NULL,
  `image` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `new`
--

INSERT INTO `new` (`title`, `image`, `content`, `id`) VALUES
('', 'tgdd-asus-vivobook-gaming-k3605-01.jpg', 'ASUS Gaming Vivobook K3605ZF hoàn hảo cho game thủ với giá chỉ 17.99 triệu đồng\r\nASUS Gaming Vivobook K3605ZF được trang bị Intel Core i5-12500H thế hệ 12, mang lại hiệu năng vượt trội để xử lý mượt mà mọi tác vụ. Với 12 nhân và 16 luồng, xung nhịp tối đa lên đến 4.5 GHz, con chip này đảm bảo bạn sẽ không gặp phải bất kỳ tình trạng giật lag nào, ngay cả khi đang chạy các ứng dụng nặng.\r\n\r\nBên cạnh đó, card đồ họa rời NVIDIA GeForce RTX 2050 không chỉ giúp bạn chiến mượt các tựa game AAA ở cài đặt cao mà còn hỗ trợ tăng tốc quá trình làm việc với các ứng dụng đồ họa, chỉnh sửa video và dựng hình 3D.', 1),
('', 'laptop-2345.jpg', 'Khám phá ngay dòng laptop HP trang bị vi xử lý Ryzen 5 và Ryzen 7 với giá cực hấp dẫn chỉ từ 13.2 triệu tại Thế Giới Di Động. Ưu đãi trả góp 0% lãi suất, duyệt nhanh chóng, trả trước chỉ từ 0 đồng, giúp bạn dễ dàng sở hữu. Đặc biệt, học sinh sinh viên còn được giảm thêm lên đến 400K, cơ hội tuyệt vời để trang bị một chiếc laptop mạnh mẽ cho việc học tập và làm việc.\r\nThời gian khuyến mãi: Dự kiến đến hết 30/09/2024.\r\n\r\nLưu ý:\r\n\r\nKhuyến mãi có thể kết thúc sớm trước thời hạn nếu hết số lượng sản phẩm hoặc thông tin khuyến mãi có thay đổi.\r\nÔ sản phẩm chưa hiển thị ưu đãi chính xác, để hiện ưu đãi chính xác, khách cần bấm Xem chi tiết.\r\nƯu đãi tặng kèm:\r\n\r\nHSSV giảm thêm đến 400K.\r\nGiảm thêm đến 3 triệu cho tân sinh viên.\r\nTrả góp 0%, trả trước từ 0 đồng.', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(3, 3, 'Phan Thiên Khải', '0384104942', 'phanthienkhai111@gmail.com', 'MoMo', '12, Nguyễn Văn Bảo, Phường 4, TP.Hồ Chí Minh, Hồ Chí Minh, Việt Nam - 1234', 'Razer Blade 14 Gaming (52.130.000<span>đ</span> x 1)', 52130000, '2024-09-26', 'completed'),
(4, 3, 'Phan Thiên Khải', '0384104942', 'phanthienkhai111@gmail.com', 'ZaloPay', '12, Nguyễn Văn Bảo, Phường 4, TP.Hồ Chí Minh, Hồ Chí Minh, Việt Nam - 1234', 'Laptop Asus Vivobook X415EA-EB640W (12.999.000<span>đ</span> x 1)', 12999000, '2024-09-28', 'completed'),
(5, 3, 'Phan Thiên Khải', '0384104942', 'phanthienkhai111@gmail.com', 'MoMo', '12, Nguyễn Văn Bảo, Phường 4, TP.Hồ Chí Minh, Hồ Chí Minh, Việt Nam - 1234', 'Razer Blade 14 Gaming (52.130.000<span>đ</span> x 1)', 52130000, '2024-10-05', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `khoa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `text`, `image`, `description`, `khoa`) VALUES
(14, 'THẠC SĨ, BS LÊ THỊ THU HÀ', 'Khoa nhi', 'Bác sĩ uy tín nhất bệnh viện', 'BS-HA-KHOA-DD.png', NULL, ''),
(15, 'BSCKII. TRẦN ĐĂNG KHOA', 'Khoa tổng quát', 'Bác sĩ uy tín nhất bệnh viện', 'bs-Khoa-4x6-1-433x650.jpg', NULL, ''),
(16, 'BSCKII ĐỖ HỮU LƯƠNG', 'Khoa tai mũi họng', 'Bác sĩ uy tín nhất bệnh viện haha', 'CNK_YHTT.jpg', NULL, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuc`
--

CREATE TABLE `tintuc` (
  `id` int(1) NOT NULL,
  `imge` text NOT NULL,
  `name` text NOT NULL,
  `name1` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tintuc`
--

INSERT INTO `tintuc` (`id`, `imge`, `name`, `name1`) VALUES
(1, 'k.jpg', 'Khai giảng Khóa 4: Hồi sức cấp cứu cơ bản', 'Ngày 14/10/2024 Bệnh viện Nhân dân 115 tiếp tục tổ chức Khai giảng Lớp đào tạo Hồi sức cấp cứu cơ bản Khóa 4 ngắn hạn cho nhân viên y tế đang công tác tại các Cơ sở khám chữa bệnh trên địa bàn Thành phố Hồ Chí Minh.'),
(2, 'khoi1.jpg', 'Nhân 1 trường hợp đột ngột ngưng tim khi nội soi, Bác sĩ cảnh báo điều gì?', 'Tại khoa Cấp cứu BV Nhân dân 115, Người bệnh tỉnh táo, được hỗ trợ bóp bóng qua nội khí quản, sinh hiệu ổn. Điện tâm đồ đo tại Khoa Cấp cứu ghi nhận ST chênh lên ở chuyển đạo aVR....'),
(3, 'khoi2.jpg', 'Cập nhật kiến thức y khoa liên tục về Bệnh viêm ruột tại Bệnh viện  \r\n', 'Bệnh viêm ruột (IBD) là một trong những bệnh lý tiêu hóa ngày càng trở nên phổ biến và phức tạp. Để nâng cao chất lượng chẩn đoán và điều trị bệnh lý này, Bệnh viện Nhân dân 115 tổ chức chương trình cập nhật kiến thức y khoa liên tục');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`) VALUES
(3, 'Phan Thiên Khải', 'phanthienkhai111@gmail.com', '0384104942', '722efb822db49574f7cdc65bafc8436d4b0e2acd', '12, Nguyễn Văn Bảo, Phường 4, TP.Hồ Chí Minh, Hồ Chí Minh, Việt Nam - 1234');



CREATE TABLE bhyt_table (
    bhyt_id VARCHAR(20) PRIMARY KEY, -- Mã BHYT hoặc CCCD
    start_date DATE NOT NULL,        -- Ngày bắt đầu hiệu lực
    end_date DATE NOT NULL           -- Ngày hết hạn
);

INSERT INTO bhyt_table (bhyt_id, start_date, end_date) VALUES
('BHYT001', '2024-01-01', '2024-12-31'),
('BHYT002', '2023-05-01', '2024-04-30'),
('BHYT003', '2024-06-01', '2025-05-31'),
('CCCD123456789', '2023-09-01', '2024-08-31'),
('CCCD987654321', '2022-11-01', '2023-10-31');


CREATE TABLE hoadon (
    maHoaDon INT AUTO_INCREMENT PRIMARY KEY, -- Mã hóa đơn tự động tăng
    patient_id NVARCHAR(50) NOT NULL,        -- Mã bệnh nhân
    name NVARCHAR(100) NOT NULL,             -- Tên bệnh nhân
    department NVARCHAR(100),                -- Khoa khám
    consultation_fee DECIMAL(10, 2),         -- Phí khám bệnh
    medicine_fee DECIMAL(10, 2),             -- Tiền thuốc
    insurance_id NVARCHAR(50),               -- Mã BHYT
    total_amount DECIMAL(10, 2) NOT NULL,    -- Tổng số tiền
    payment_method NVARCHAR(50),             -- Hình thức thanh toán
    ngayLap DATE DEFAULT CURRENT_DATE        -- Ngày lập hóa đơn
);

INSERT INTO hoadon (patient_id, name, department, consultation_fee, medicine_fee, insurance_id, total_amount, payment_method)
VALUES 
('BN001', 'Nguyễn Văn A', 'Nội khoa', 200000, 150000, 'BHYT001', 450000, 'Tiền mặt'),
('BN002', 'Trần Thị B', 'Nhi khoa', 300000, 200000, NULL, 500000, 'Chuyển khoản');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bacsi`
--
ALTER TABLE `bacsi`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `new`
--
ALTER TABLE `new`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bhyt_table`
  ADD PRIMARY KEY (`bhyt_id`);

ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`maHoaDon`);
--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `new`
--
ALTER TABLE `new`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

<<<<<<< HEAD
CREATE TABLE `medical_record` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma_dinh_danh` VARCHAR(50) NOT NULL UNIQUE,
    `ho_ten` VARCHAR(100) NOT NULL,
    `ngay_sinh` DATE NOT NULL,
    `gioi_tinh` VARCHAR(10) NOT NULL,
    `dia_chi` VARCHAR(255),
    `so_dien_thoai` VARCHAR(15) NOT NULL,
    `thong_tin_benh_an` TEXT
);
=======
>>>>>>> 65a130f5bf3401fca62121d374e23c2a54484b1f
