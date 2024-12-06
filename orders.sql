-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 07:27 AM
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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `lender_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_method` enum('pickup','cod') NOT NULL DEFAULT 'pickup',
  `reference_number` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `lender_id`, `order_date`, `delivery_method`, `reference_number`, `total_price`) VALUES
(1, 9, NULL, '2024-12-06 03:03:03', 'pickup', 'REF-20241206-902A47', 300.00),
(2, 9, NULL, '2024-12-06 03:17:46', 'pickup', 'REF-20241206-5F4308', 33.00),
(3, 9, NULL, '2024-12-06 03:42:15', 'pickup', 'REF-20241206-BC3424', 22.00),
(4, 9, NULL, '2024-12-06 03:49:22', 'pickup', 'REF-20241206-596C9F', 200.00),
(5, 9, NULL, '2024-12-06 03:55:18', 'pickup', 'REF-20241206-64D5ED', 100.00),
(6, 9, NULL, '2024-12-06 04:00:01', 'pickup', 'REF-20241206-680C85', 11.00),
(10, 9, NULL, '2024-12-06 04:18:25', 'pickup', 'REF-20241206-2F555F', 300.00),
(12, 9, NULL, '2024-12-06 04:24:11', 'pickup', 'REF-20241206-8862B3', 22.00),
(14, 9, NULL, '2024-12-06 04:25:12', 'pickup', 'REF-20241206-CCF799', 300.00),
(15, 9, NULL, '2024-12-06 04:44:42', 'pickup', 'REF-20241206-6C0D8F', 33.00),
(16, 8, NULL, '2024-12-06 06:10:34', 'pickup', 'REF-20241206-AB9571', 400.00),
(17, 8, NULL, '2024-12-06 06:18:24', 'pickup', 'REF-20241206-D15FDE', 300.00),
(18, 8, NULL, '2024-12-06 06:20:09', 'pickup', 'REF-20241206-229596', 200.00),
(19, 8, NULL, '2024-12-06 06:26:50', 'pickup', 'REF-20241206-635F6D', 300.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
