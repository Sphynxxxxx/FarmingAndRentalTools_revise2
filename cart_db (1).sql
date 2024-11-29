-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 07:54 PM
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
-- Database: `cart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact_number`, `address`, `email`, `password`, `images`, `status`, `profile_image`) VALUES
(8, 'Larry Denver', '09123456781', 'Batuan', 'larrydenverbiaco@gmail.com', '$2y$10$2GJDD2AKlhXYIv.FWiN1HuqWFufnA3AV27HFQiQkSLyn2SJp3Flpm', 'Cus_uploads/6748b0f92557b4.40703348_download.png', 'approved', NULL),
(9, 'larry', '09123456781', 'Cahaguichican', 'lry4750@gmail.com', '$2y$10$3Njb6zLIZidlG7znJii11.LrOwJXOSz1WisXKUbFlK5sK615NtDBi', 'Cus_uploads/6748b127931539.41849396_download.png', 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lender`
--

CREATE TABLE `lender` (
  `id` int(11) NOT NULL,
  `lender_name` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `lender`
--

INSERT INTO `lender` (`id`, `lender_name`, `contact_number`, `address`, `email`, `password`, `images`, `status`, `profile_image`) VALUES
(9, 'Patrick Catalan', '09123456781', 'Cahaguichican', 'larrydenverbiaco@gmail.com', '$2y$10$lSAg0cBbxwRl.ho35OZ5KuPTyXoiZARaJ6KRTbRGMMHm2A0S4T2hK', 'Cus_uploads/6748b09b1d14f9.26615684_download.png', 'approved', NULL),
(10, 'Patrick john Catalan', '09123456781', 'Bongco', 'lry4750@gmail.com', '$2y$10$04NENO/8KU7IRo62EbeaA.uw7d8i6jOtmJIJBE3ovnVUVBQzSl7SG', 'Cus_uploads/6748b0cedd16f7.89933388_download.png', 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `lender_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_method` enum('pickup','cod') NOT NULL DEFAULT 'pickup',
  `reference_number` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `lender_id`, `order_date`, `delivery_method`, `reference_number`, `total_price`) VALUES
(1, 9, 0, '2024-11-28 18:26:23', 'cod', 'REF-20241128-9F32A1', 25.00),
(2, 9, 0, '2024-11-28 18:33:57', 'pickup', 'REF-20241128-FECA4F', 24.00);

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
  `shippingfee` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `shippingfee`, `start_date`, `end_date`) VALUES
(1, 1, 24, 2, 12.00, 1.00, NULL, NULL),
(2, 2, 24, 2, 12.00, 0.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `lender_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `shippingfee` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `categories` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `rent_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `lender_name`, `location`, `price`, `image`, `description`, `status`, `shippingfee`, `created_at`, `categories`, `quantity`, `rent_days`) VALUES
(24, 'tool 331', 'patrickkkkkk', 'Amamaros', 12.00, 'notebooks.jpg', 'qwregfgdfgdf', 'approved', 1.00, '2024-11-28 18:07:39', 'Seeding Tools', 7, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `lender`
--
ALTER TABLE `lender`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lender`
--
ALTER TABLE `lender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

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
