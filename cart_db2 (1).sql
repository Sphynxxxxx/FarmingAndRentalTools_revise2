-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 05:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cart_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `profile_image` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `contact_number`, `address`, `email`, `password`, `images`, `status`, `profile_image`, `reset_token`, `reset_token_expiry`) VALUES
(17, 'Larry Denver', '09123456781', 'Bongco', 'larrydenverbiaco@gmail.com', '$2y$10$rQBTdcRYD86RC/JC0.UFku.HHsTWO2wkeZQitnzKomRwUWEnu0ITG', 'Cus_uploads/6758a7b1c4ab84.28534139_person1.jpg', 'approved', 'person 1.jpg', NULL, NULL),
(18, 'Patrick john Catalan', '09123456781', 'Callan', 'lry4750@gmail.com', '$2y$10$o90KqN7jadCh2tzcII/g0uM4zvc/ECDDII5Mo1r.mfhqemdQZ1hrC', 'Cus_uploads/6759b5ee8c97e6.38105638_person1.jpg', 'approved', 'person 2.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` datetime NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `customer_id`, `title`, `message`, `date_created`, `order_id`) VALUES
(12, 17, 'Order Status Update', 'You have successfully placed your order. Your order status is: pending.', '2024-12-11 14:13:34', 22),
(13, 17, 'Order Status Update', 'You have successfully placed your order.', '2024-12-11 14:26:58', 23),
(14, 17, 'Order Status Update', 'You have successfully placed your order.', '2024-12-11 15:36:11', 24),
(15, 18, 'Order Status Update', 'You have successfully placed your order.', '2024-12-11 16:56:11', 25),
(16, 18, 'Order Status Update', 'You have successfully placed your order.', '2024-12-11 16:57:44', 26),
(17, 18, 'Order Status Update', 'You have successfully placed your order.', '2024-12-11 17:10:23', 27);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference_number` varchar(255) NOT NULL,
  `delivery_method` enum('pickup','cod') NOT NULL DEFAULT 'pickup',
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','ready_to_pick_up','canceled','received') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `reference_number`, `delivery_method`, `total_price`, `status`) VALUES
(22, 17, '2024-12-11 13:13:34', 'REF-20241211-5CE962', 'pickup', 11.00, 'ready_to_pick_up'),
(23, 17, '2024-12-11 13:26:58', 'REF-20241211-E252AB', 'pickup', 11.00, 'ready_to_pick_up'),
(24, 17, '2024-12-11 14:36:11', 'REF-20241211-74164A', 'pickup', 111.00, 'received'),
(25, 18, '2024-12-11 15:56:11', 'REF-20241211-1F5B3F', 'pickup', 11.00, 'received'),
(26, 18, '2024-12-11 15:57:43', 'REF-20241211-C15931', 'pickup', 900.00, 'received'),
(27, 18, '2024-12-11 16:10:23', 'REF-20241211-6959D6', 'pickup', 291.00, 'received');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `start_date`, `end_date`) VALUES
(14, 22, 23, 1, 11.00, '2024-12-11', '2024-12-13'),
(15, 23, 23, 1, 11.00, '2024-12-11', '2024-12-14'),
(16, 24, 23, 1, 11.00, '2024-12-11', '2024-12-14'),
(17, 24, 24, 1, 100.00, '2024-12-11', '2024-12-14'),
(18, 25, 23, 1, 11.00, '2024-12-11', '2024-12-14'),
(19, 26, 24, 9, 100.00, '2024-12-11', '2024-12-14'),
(20, 27, 23, 1, 11.00, '2024-12-12', '2024-12-16'),
(21, 27, 25, 1, 180.00, '2024-12-12', '2024-12-16'),
(22, 27, 26, 2, 50.00, '2024-12-12', '2024-12-16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `rent_days` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `location`, `description`, `quantity`, `categories`, `price`, `image`, `status`, `rent_days`, `created_at`) VALUES
(23, 'sasas', 'Bongco', 'Amazing Product', 90, 'Hand Tools', 11.00, 'garden tool2.jpg', 'approved', 11, '2024-12-11 10:31:01'),
(24, 'sasas', 'Bongco', 'Original', 0, 'Seeding Tools', 100.00, 'seedingtool2.jpg', 'approved', 11, '2024-12-11 14:35:58'),
(25, 'tool 6', 'Callan', 'Excellent', 9, 'Ploughs', 180.00, 'ploughs2.jpg', 'approved', 4, '2024-12-11 14:52:03'),
(26, 'tool 10', 'Cansilayan', 'New', 16, 'Garden Tools', 50.00, 'garden tool2.jpg', 'approved', 10, '2024-12-11 14:54:21'),
(27, 'tool 55', 'Bongco', 'Brand New', 5, 'Tilling Tools', 90.00, 'tilling2.jpg', 'approved', 60, '2024-12-11 14:55:30'),
(28, 'tool 100', 'Cato-ogan', 'wowow', 11, 'Seeding Tools', 100.00, 'seedingtool2.jpg', 'approved', 5, '2024-12-11 16:17:05'),
(29, 'tool 31', 'Cahaguichican', 'qwregfgdfgdf', 11, 'Harvesting Tools', 90.00, 'harvesting tool1.jpg', 'approved', 99, '2024-12-11 16:20:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `fk_order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
