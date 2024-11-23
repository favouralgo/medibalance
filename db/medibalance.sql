-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 10:25 PM
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
-- Database: `medibalance`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_firstname` varchar(100) NOT NULL,
  `customer_lastname` varchar(100) NOT NULL,
  `customer_password` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_phonenumber` varchar(20) NOT NULL,
  `customer_city` varchar(100) NOT NULL,
  `customer_country` varchar(100) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_firstname`, `customer_lastname`, `customer_password`, `customer_address`, `customer_phonenumber`, `customer_city`, `customer_country`, `customer_email`, `created_at`, `updated_at`) VALUES
(1, 'Fancy', 'Name', 'test12', '21 Streetpass', '+233203456789', 'Accra', 'Ghana', 'fancy@gmail.com', '2024-11-19 15:56:21', '2024-11-19 15:56:21'),
(2, 'Obey', 'Now', '$2y$10$F.AEaZI6flua86xqvQUiOuNRiL2h8y0c91Jx90YRPoSPlGQIfoK9K', '1 Univer', '+233203456789', 'Accra', 'Ghana', 'obynow@gmail.com', '2024-11-20 23:50:29', '2024-11-20 23:50:29'),
(5, 'Favour', 'mdev', '$2y$10$j3yI/t1E98LPVEoHQF3UAe90rRtJagCL6bP4Xt/lHt1BnUz3vOuci', 'universe street', '+233203456789', 'Accra', 'Naija', 'favourmdev@gmail.com', '2024-11-21 14:24:12', '2024-11-21 14:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `customer_products`
--

CREATE TABLE `customer_products` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_products`
--

INSERT INTO `customer_products` (`id`, `customer_id`, `product_id`, `created_at`) VALUES
(1, 5, 5, '2024-11-23 14:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `facility_id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`facility_id`, `facility_name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Kofi Labs', 1, '2024-11-19 16:00:39', '2024-11-19 16:00:39'),
(4, 'Vican Chemist', 4, '2024-11-20 23:11:58', '2024-11-20 23:11:58');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `invoice_date_start` date NOT NULL,
  `invoice_date_due` date NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `invoice_vat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `invoice_total` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `status_id`, `invoice_date_start`, `invoice_date_due`, `invoice_number`, `invoice_discount`, `invoice_vat`, `invoice_total`, `user_id`, `customer_id`, `facility_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2024-11-21', '2024-12-21', 'MED/24/259540791', 0.30, 0.30, 30.00, 4, 2, 4, '2024-11-21 17:45:18', '2024-11-21 17:45:18'),
(2, 2, '2024-11-21', '2024-12-21', 'MED/24/908852812', 0.96, 0.41, 41.45, 4, 1, 4, '2024-11-21 22:36:36', '2024-11-21 22:36:36'),
(3, 2, '2024-11-23', '2024-12-23', 'MED/24/975959307', 0.05, 0.14, 14.59, 4, 5, 4, '2024-11-23 11:49:06', '2024-11-23 11:49:06'),
(4, 2, '2024-11-23', '2024-12-23', 'MED/24/656137610', 0.00, 0.20, 20.20, 4, 5, 4, '2024-11-23 14:19:38', '2024-11-23 14:19:38'),
(5, 2, '2024-11-23', '2024-12-23', 'MED/24/891262574', 0.76, 0.37, 37.61, 4, 5, 4, '2024-11-23 14:40:06', '2024-11-23 14:40:06');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_product`
--

CREATE TABLE `invoice_product` (
  `invoiceproduct_id` int(11) NOT NULL,
  `invoiceproduct_price` decimal(10,2) NOT NULL,
  `invoiceproduct_quantity` int(11) NOT NULL,
  `invoiceproduct_description` text DEFAULT NULL,
  `invoiceproduct_name` varchar(255) NOT NULL,
  `status_id` int(11) NOT NULL,
  `invoiceproduct_subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_product`
--

INSERT INTO `invoice_product` (`invoiceproduct_id`, `invoiceproduct_price`, `invoiceproduct_quantity`, `invoiceproduct_description`, `invoiceproduct_name`, `status_id`, `invoiceproduct_subtotal`, `created_at`, `updated_at`, `product_id`, `invoice_id`) VALUES
(1, 30.00, 1, 'A good arm bandage', 'Bandages', 2, 29.00, '2024-11-21 17:45:18', '2024-11-21 17:45:18', 1, 1),
(2, 30.00, 1, 'A good arm bandage', 'Bandages', 2, 29.00, '2024-11-21 22:36:36', '2024-11-21 22:36:36', 1, 2),
(3, 12.00, 1, 'Skincare made easy', 'FunbactA', 2, 11.00, '2024-11-21 22:36:36', '2024-11-21 22:36:36', 2, 2),
(4, 2.50, 1, NULL, 'Vitamin C', 2, 2.00, '2024-11-23 11:49:06', '2024-11-23 11:49:06', 3, 3),
(5, 12.00, 1, NULL, 'FunbactA', 2, 12.00, '2024-11-23 11:49:06', '2024-11-23 11:49:06', 2, 3),
(6, 20.00, 1, NULL, 'Arthemeter', 2, 20.00, '2024-11-23 14:19:38', '2024-11-23 14:19:38', 6, 4),
(7, 38.00, 1, 'For asthmatic patients', 'Nebulizer', 2, 37.00, '2024-11-23 14:40:06', '2024-11-23 14:40:06', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `product_price`, `product_quantity`, `created_at`, `updated_at`) VALUES
(1, 'Bandages', 'A good arm bandage', 30.00, 12, '2024-11-19 15:57:35', '2024-11-19 15:57:35'),
(2, 'FunbactA', 'Skincare made easy', 12.00, 23, '2024-11-19 23:44:03', '2024-11-19 23:44:03'),
(3, 'Vitamin C', 'Vitamin for body', 2.50, 10, '2024-11-19 23:46:00', '2024-11-19 23:46:00'),
(4, 'Insulin', 'An insulin package for critical patients', 200.00, 5, '2024-11-21 08:10:22', '2024-11-21 08:10:22'),
(5, 'Nebulizer', 'For asthmatic patients', 38.00, 7, '2024-11-23 11:51:42', '2024-11-23 12:22:29'),
(6, 'Arthemeter', 'Malaria drug', 20.00, 3, '2024-11-23 12:36:04', '2024-11-23 12:36:04'),
(7, 'Suture check', 'Suture dressing', 2.00, 1, '2024-11-23 14:04:48', '2024-11-23 14:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'PAID'),
(2, 'UNPAID');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_firstname` varchar(100) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_phonenumber` varchar(20) NOT NULL,
  `user_country` varchar(100) NOT NULL,
  `user_city` varchar(100) NOT NULL,
  `user_facilityname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_firstname`, `user_lastname`, `user_password`, `user_phonenumber`, `user_country`, `user_city`, `user_facilityname`, `user_email`, `user_address`, `created_at`, `updated_at`) VALUES
(1, 'Test', 'User', 'test12', '+233203456781', 'Ghana', 'Accra', 'Kofi Labs', 'kofi@gmail.com', '1 Koforidua Street', '2024-11-19 15:59:17', '2024-11-19 15:59:17'),
(4, 'Yelarge', 'Kwasi', '$2y$10$gE3WKRHatU4JIUeNFU911OIsRY9cLgTnqvwa4ZHpIVvzsshMeo71y', '+233203456789', 'Ghana', 'Accra', 'Vican Chemist', 'kwasiyelarge@gmail.com', '1 University Avenue', '2024-11-20 23:11:58', '2024-11-20 23:11:58');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `wallet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD KEY `idx_customer_email` (`customer_email`);

--
-- Indexes for table `customer_products`
--
ALTER TABLE `customer_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`facility_id`),
  ADD UNIQUE KEY `facility_name` (`facility_name`,`user_id`),
  ADD KEY `idx_facility_user` (`user_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `facility_id` (`facility_id`),
  ADD KEY `idx_invoice_number` (`invoice_number`),
  ADD KEY `idx_invoice_dates` (`invoice_date_start`,`invoice_date_due`),
  ADD KEY `idx_invoice_status` (`status_id`);

--
-- Indexes for table `invoice_product`
--
ALTER TABLE `invoice_product`
  ADD PRIMARY KEY (`invoiceproduct_id`),
  ADD KEY `idx_invoice_product_status` (`status_id`),
  ADD KEY `idx_invoice_product_id` (`product_id`),
  ADD KEY `idx_invoice_product_invoice` (`invoice_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `idx_user_email` (`user_email`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`wallet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_products`
--
ALTER TABLE `customer_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facility`
--
ALTER TABLE `facility`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_product`
--
ALTER TABLE `invoice_product`
  MODIFY `invoiceproduct_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_products`
--
ALTER TABLE `customer_products`
  ADD CONSTRAINT `customer_products_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `customer_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `facility`
--
ALTER TABLE `facility`
  ADD CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_4` FOREIGN KEY (`facility_id`) REFERENCES `facility` (`facility_id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice_product`
--
ALTER TABLE `invoice_product`
  ADD CONSTRAINT `fk_invoice_product_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`),
  ADD CONSTRAINT `idx_invoice_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_product_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON UPDATE CASCADE;

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
