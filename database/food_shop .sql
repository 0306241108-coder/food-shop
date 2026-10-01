-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 29, 2026 lúc 01:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `food&shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `username`, `password`) VALUES
(1, 'admin', '123456');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `created_at`) VALUES
(1, 2, '2026-08-14 09:44:47'),
(2, 3, '2026-08-14 14:27:18'),
(3, 4, '2026-09-12 04:25:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`) VALUES
(35, 2, 8, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `created_at`) VALUES
(1, 'Coffee', 'coffee.jpg', '2026-08-07 15:25:18'),
(2, 'Trà sữa', 'trasua.jpg', '2026-08-07 15:25:18'),
(3, 'Đồ ăn vặt', 'doanvat.jpg', '2026-08-07 15:25:18'),
(4, 'Bánh ngọt', 'banhngot.jpg', '2026-08-07 15:25:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_history`
--

CREATE TABLE `inventory_history` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_history`
--

INSERT INTO `inventory_history` (`id`, `product_id`, `type`, `quantity`, `note`, `created_at`) VALUES
(1, 10, 'import', 10, 'Nhập hàng', '2026-08-15 05:59:54'),
(2, 9, 'export', 40, 'Xuất hàng - Đơn #19', '2026-08-15 06:07:33'),
(3, 8, 'export', 23, 'Xuất hàng - Đơn #1', '2026-08-15 06:13:01'),
(4, 10, 'export', 1, 'Xuất hàng - Đơn #21', '2026-09-07 08:44:43'),
(5, 10, 'import', 30, 'Nhập hàng', '2026-09-07 08:45:49'),
(6, 8, 'export', 9, 'Xuất hàng - Đơn #22', '2026-09-12 03:40:25'),
(7, 7, 'import', 20, 'Nhập hàng', '2026-09-12 03:42:18'),
(8, 1, 'import', 10, 'Nhập hàng', '2026-09-12 03:45:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `receiver_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `receiver_name`, `phone`, `address`, `note`) VALUES
(1, 2, 1035000.00, 'completed', '2026-08-14 10:02:53', '', '', '', NULL),
(2, 2, 60000.00, 'pending', '2026-08-14 10:03:10', '', '', '', NULL),
(3, 2, 50000.00, 'pending', '2026-08-14 10:04:07', '', '', '', NULL),
(4, 3, 90000.00, 'pending', '2026-08-14 14:27:24', '', '', '', NULL),
(5, 3, 40000.00, 'confirmed', '2026-08-14 14:29:37', '', '', '', NULL),
(6, 3, 30000.00, 'pending', '2026-08-14 14:29:49', '', '', '', NULL),
(7, 3, 675000.00, 'pending', '2026-08-14 14:43:16', '', '', '', NULL),
(8, 3, 75000.00, 'pending', '2026-08-14 14:45:48', '', '', '', NULL),
(9, 3, 180000.00, 'confirmed', '2026-08-14 14:46:41', '', '', '', NULL),
(10, 3, 45000.00, 'cancelled', '2026-08-14 14:49:07', '', '', '', NULL),
(11, 3, 45000.00, 'completed', '2026-08-14 14:49:23', '', '', '', NULL),
(12, 3, 385000.00, 'confirmed', '2026-08-14 15:56:02', 'Trần Phước Hoàng', '0373050742', '60/19 Lâm Văn Bền', ''),
(13, 3, 50000.00, 'completed', '2026-08-14 16:32:58', 'Trần Phước Hoàng', '0373050742', 'Mai Văn Vĩnh', 'giao sáng'),
(14, 3, 590000.00, 'completed', '2026-08-14 16:34:48', 'Trần Phước Hoàng', '0373050742', 'Nguyễn Văn Linh', ''),
(15, 3, 300000.00, 'completed', '2026-08-14 16:37:49', 'Trần Phước Hoàng', '0373050742', 'An Ninh', 'sáng'),
(16, 3, 360000.00, 'completed', '2026-08-14 16:43:54', 'Trần Phước Hoàng', '0373050742', 'Paster', 'chiều'),
(17, 3, 360000.00, 'completed', '2026-08-14 16:51:34', 'Trần Phước Hoàng', '0373050742', 'Võ Văn Kiệt', 'chiều'),
(18, 3, 360000.00, 'completed', '2026-08-14 16:59:44', 'Trần Phước Hoàng', '0373050742', 'Trần xuân soạn', 'tối'),
(19, 3, 1600000.00, 'confirmed', '2026-08-15 06:07:02', 'Trần Phước Hoàng', '0373050742', 'Lâm văn bền', 'sáng 10h'),
(20, 3, 100000.00, 'cancelled', '2026-08-15 06:25:29', 'Trần Phước Hoàng', '0373050742', 'ZXC', 'QƯERTYU'),
(21, 3, 50000.00, 'shipping', '2026-09-07 08:44:20', 'Trần Phước Hoàng', '0373050742', 'ggg', 'kjh'),
(22, 3, 405000.00, 'confirmed', '2026-09-12 03:40:04', 'Trần Phước Hoàng', '0373050742', '60', 'l'),
(23, 4, 30000.00, 'cancelled', '2026-09-12 04:25:22', 'Lương Minh Khôi', '0373050742', 'bj', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 8, 23, 45000.00),
(2, 2, 7, 2, 30000.00),
(3, 3, 10, 1, 50000.00),
(4, 4, 8, 2, 45000.00),
(5, 5, 9, 1, 40000.00),
(6, 6, 7, 1, 30000.00),
(7, 7, 10, 2, 50000.00),
(8, 7, 7, 1, 30000.00),
(9, 7, 5, 1, 35000.00),
(10, 7, 6, 5, 40000.00),
(11, 7, 9, 7, 40000.00),
(12, 7, 2, 1, 30000.00),
(13, 8, 8, 1, 45000.00),
(14, 8, 7, 1, 30000.00),
(15, 9, 7, 6, 30000.00),
(16, 10, 4, 1, 45000.00),
(17, 11, 4, 1, 45000.00),
(18, 12, 10, 6, 50000.00),
(19, 12, 4, 1, 45000.00),
(20, 12, 9, 1, 40000.00),
(21, 13, 10, 1, 50000.00),
(22, 14, 10, 1, 50000.00),
(23, 14, 4, 12, 45000.00),
(24, 15, 10, 6, 50000.00),
(25, 16, 9, 9, 40000.00),
(26, 17, 8, 8, 45000.00),
(27, 18, 9, 9, 40000.00),
(28, 19, 9, 40, 40000.00),
(29, 20, 8, 1, 45000.00),
(30, 20, 7, 1, 30000.00),
(31, 20, 1, 1, 25000.00),
(32, 21, 10, 1, 50000.00),
(33, 22, 8, 9, 45000.00),
(34, 23, 7, 1, 30000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `quantity`, `image`, `created_at`) VALUES
(1, 1, 'Cà phê đen', 'Cà phê đen truyền thống đậm đà', 25000.00, 12, 'capheden.png', '2026-08-07 15:25:26'),
(2, 1, 'Cà phê sữa', 'Cà phê sữa thơm ngon', 30000.00, 50, 'caphesua.png', '2026-08-07 15:25:26'),
(3, 1, 'Coffee Latte', 'Latte thơm béo, vị cà phê nhẹ', 45000.00, 30, 'coffeelatte.png', '2026-08-07 15:25:26'),
(4, 1, 'Cappuccino', 'Cappuccino thơm ngon', 45000.00, 29, 'cappuccino.png', '2026-08-07 15:25:26'),
(5, 2, 'Trà sữa trân châu', 'Trà sữa kèm trân châu', 35000.00, 40, 'trasua_tranchau.png', '2026-08-07 15:25:26'),
(6, 2, 'Trà sữa matcha', 'Trà sữa matcha thơm béo', 40000.00, 30, 'trasua_matcha.png', '2026-08-07 15:25:26'),
(7, 3, 'Khoai tây chiên', 'Khoai tây chiên giòn', 30000.00, 54, 'khoaitaychien.png', '2026-08-07 15:25:26'),
(8, 3, 'Gà rán', 'Gà rán giòn ngon', 45000.00, 70, 'garan.png', '2026-08-07 15:25:26'),
(9, 4, 'Bánh Tiramisu', 'Bánh Tiramisu mềm thơm', 40000.00, 59, 'tiramisu.png', '2026-08-07 15:25:26'),
(10, 4, 'Bánh kem', 'Bánh kem ngọt mềm', 60000.00, 40, 'banhkem.png', '2026-08-07 15:25:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Lê Minh Hiệp', '0306241106@caothang.edu.vn', '$2y$10$iLWwI1bt7GzhqZlfJMlqEepfCwzJNgR6.mHZ8AaBfH5Yybu0g8UBu', 'user', '2026-08-09 13:05:00'),
(3, 'Trần Phước Hoàng', '0306241108@caothang.edu.vn', '$2y$10$a04hWfr2U32NY6ou7WUi2OeGttNkZpW4kVvbjoFMf3qKZoaL8T5zm', 'user', '2026-08-14 14:26:05'),
(4, 'Lương Minh Khôi', '0306241110@caothang.edu.vn', '$2y$10$QiuewePv0QuOFNm44vGhVO9rkUOVc6YBnB/26xd47ND4iixVgRsOa', 'user', '2026-09-12 03:54:08');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `inventory_history`
--
ALTER TABLE `inventory_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventory_history`
--
ALTER TABLE `inventory_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
