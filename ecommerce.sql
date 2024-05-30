-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 05:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `item_img` varchar(255) NOT NULL,
  `price` float(9,2) NOT NULL,
  `stocks` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_status` varchar(1) NOT NULL DEFAULT 'A',
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item`, `item_img`, `price`, `stocks`, `date_added`, `item_status`, `category`) VALUES
(1, 'Motorcycle Handle Grip Rubber Universal 1pair', '../img/663e457c00a1c_Motorcycle Handle Grip Rubber Universal 1pair.jpg', 100.00, '50', '2024-03-06 08:09:37', 'A', 'Lights and Electrical'),
(2, 'Mini Driving Light 2PCS Set Led Lights Motorcycle', '../img/Mini Driving Light 2PCS Set Led Lights Motorcycle.jpg', 195.00, '25', '2024-03-06 08:09:37', 'A', 'Lights and Electrical'),
(3, 'Domino Honeywell Switch Left & Right Handle Bar Mount Plug & Play Made', '../img/Domino Honeywell Switch Left & Right Handle Bar.jpg', 370.00, '6', '2024-03-06 08:09:37', 'A', 'Lights and Electrical'),
(4, 'NGK Spark Plug for Motorcycle ', '../img/NGK Spark Plug for Motorcycle.jpg', 120.00, '9', '2024-03-06 08:09:37', 'A', 'Performance Parts'),
(5, 'Domino Brake Master Clutch Lever set Universal', '../img/Domino Brake Master Clutch Lever set Universal.jpg', 499.00, '15', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(6, 'Motorcycle Heavy Duty Roller Chain 428-110 / 132 (Chrome)', '../img/Motorcycle Heavy Duty Roller Chain.jpg', 255.00, '30', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(7, 'Motorcycle Side Mirror Stock', '../img/Motorcycle Side Mirror Stock.jpg', 145.00, '35', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(8, '310mm Rear Set Suspension Shock Absorber', '../img/310mm Rear Set Suspension Shock Absorber.jpg', 365.00, '25', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(9, 'Rear Tire Hugger Carbon White for Motorcycle ', '../img/Rear Tire Hugger Carbon White for Motorcycle.jpg', 450.00, '18', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(10, 'Front Brake Master Caliper Assembly', '../img/Front Brake Master Caliper Assembly.jpg', 650.00, '23', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(11, 'Domino Hydraulic Switch Brake Tail Light Switch ', '../img/Domino Hydraulic Switch Brake Tail Light Switch.jpg', 320.00, '30', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(12, 'Anti Scratch Series Alloy Top Box 45L w/ Base Plate and Backrest', '../img/Anti Scratch Series Alloy Top Box 45L.jpg', 5499.00, '10', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(13, 'MTR Universal Alloy Swing Arm ', '../img/MTR Universal Alloy Swing Arm.jpg', 1949.00, '11', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(14, 'Motorcycle Handlebar Retro Black Modified', '../img/Motorcycle Handlebar Retro Black Modified.jpg', 430.00, '7', '2024-03-06 08:09:37', 'A', 'Accessories and Add-ons'),
(15, 'Racing Boy Alloy Rim 1.2/1.4 X17', '../img/Racing Boy Alloy Rim.jpg', 1500.00, '2', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(16, 'Motorcycle LCD Digital Gauge Indicator Speedometer', '../img/Motorcycle LCD Digital Gauge Indicator Speedometer.jpg', 1080.00, '0', '2024-03-06 08:09:37', 'A', 'Performance Parts'),
(17, 'Motorcycle Radiator Grille Guard Protector Cover Motor Bike', '../img/Motorcycle Radiator Grille Guard Protector Cover.jpg', 516.00, '15', '2024-03-06 08:09:37', 'A', 'Engine and Internal Parts'),
(18, 'MOTORCYCLE STATOR COILS PURE COPPER', '../img/MOTORCYCLE STATOR COILS PURE COPPER.jpg', 250.00, '5', '2024-03-06 08:09:37', 'A', 'Engine and Internal Parts'),
(19, 'Pipe Cover and Heat Guard ', '../img/Pipe Cover and Heat Guard.jpg', 585.00, '2', '2024-03-06 08:09:37', 'D', 'Frame and Body Parts'),
(21, 'Motorcycle Exhaust Muffler Akrapovic', '../img/Motorcycle Exhaust Muffler Akrapovic.jpg', 1099.00, '0', '2024-03-06 08:09:37', 'A', 'Performance Parts'),
(22, ' Rear Bracket Top Box Bracket', '../img/Rear Bracket Top Box Bracket.jpg', 529.00, '7', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(23, 'Modified Side Stand Shoes Flat Foot Extension Kickstand Pad', '../img/Modified Side Stand Shoes Flat Foot Extension Kick.jpg', 159.00, '13', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(24, 'SC Universal project Tailpipe 51mm Motorcycle', '../img/SC Universal project Tailpipe 51mm Motorcycle.jpg', 749.00, '8', '2024-03-06 08:09:37', 'A', 'Performance Parts'),
(25, 'Universal Motorcycle Winglet Side Wings Fittings', '../img/663e446145579_Universal Motorcycle Winglet Side Wings Fittings.jpg', 370.00, '7', '2024-03-06 08:09:37', 'A', 'Frame and Body Parts'),
(45, 'Bride White Leather Seat Cover', '../img/Bride White Leather Seat Cover.jpg', 800.00, '36', '2024-05-11 03:58:19', 'D', 'Frame and Body Parts'),
(46, 'Frame Honda Msx 125 / Grom 125', '../img/frame-honda-msx-125-grom-125.jpg', 30000.00, '2', '2024-05-11 09:20:53', 'A', 'Frame and Body Parts'),
(48, 'Motorcycle Bluetooth Helmet Intercom', '../img/Motorcycle Bluetooth Helmet Intercom.png', 2000.00, '9', '2024-05-28 16:24:27', 'A', 'Accessories and Add-ons');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_phase` int(11) NOT NULL DEFAULT 1,
  `payment_method` int(11) NOT NULL,
  `order_ref_number` varchar(50) NOT NULL,
  `alternate_receiver` varchar(100) DEFAULT NULL,
  `alternate_address` text DEFAULT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `gcash_ref_num` varchar(50) DEFAULT NULL,
  `gcash_account_name` varchar(100) DEFAULT NULL,
  `gcash_account_number` varchar(50) DEFAULT NULL,
  `gcash_amount_sent` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `order_type` varchar(255) NOT NULL DEFAULT 'individual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `order_phase`, `payment_method`, `order_ref_number`, `alternate_receiver`, `alternate_address`, `shipper_id`, `gcash_ref_num`, `gcash_account_name`, `gcash_account_number`, `gcash_amount_sent`, `total_amount`, `date_added`, `updated_at`, `item_id`, `quantity`, `status`, `order_type`) VALUES
(129, 31, 1, 1, '95TFG0M2', 'Lee By', 'Oas', 2, '2121', '21212', '2122', 555.00, 555.00, '2024-05-27 16:31:25', '2024-05-28 14:09:12', 18, 2, '0', 'P'),
(130, 31, 1, 1, 'HHYA76XH', '', '', 2, 'gdf', 'gfdgd', 'dfgfdg', 584.00, 584.00, '2024-05-27 16:44:38', '2024-05-28 09:15:11', 22, 1, '0', 'P'),
(132, 31, 1, 1, 'WQG72S8J', 'Joven Bataller', 'Oas, Albay', 1, 'ella', 'dasda', 'asdad', 2164.00, 2164.00, '2024-05-27 18:34:04', '2024-05-28 06:48:45', 21, 1, '5', 'P'),
(133, 31, 1, 1, 'WQG72S8J', 'Joven Bataller', 'Oas, Albay', 1, 'ella', 'dasda', 'asdad', 2164.00, 2164.00, '2024-05-27 18:34:07', '2024-05-28 07:25:53', 19, 1, '5', 'P'),
(134, 31, 1, 1, 'WQG72S8J', 'Joven Bataller', 'Oas, Albay', 1, 'ella', 'dasda', 'asdad', 2164.00, 2164.00, '2024-05-27 18:34:10', '2024-05-28 06:47:15', 14, 1, '5', 'P'),
(135, 31, 1, 1, 'OIWJV12Q', 'Cameron Diaz', 'Apud, Libon, Albay', 2, 'esess', 'fsdfsd', 'sdfsdf', 485.00, 485.00, '2024-05-27 18:34:13', '2024-05-28 09:14:15', 14, 1, '5', 'P'),
(136, 31, 1, 1, 'H6WQJNGQ', 'Joven Bataller', 'Oas, Albay', 1, 'rewr', 'fgsdg', 'sfgsg', 1590.00, 1590.00, '2024-05-27 18:34:17', '2024-05-28 08:56:05', 3, 2, '5', 'P'),
(137, 29, 1, 1, 'HYUVJL4W', 'Joven Bataller', 'Oas, Albay', 1, 'rewr', 'ewrwer', 'ewrwr', 56050.00, 56050.00, '2024-05-28 07:23:40', '2024-05-28 07:25:50', 46, 2, '5', 'P'),
(138, 31, 1, 1, 'H6WQJNGQ', 'Joven Bataller', 'Oas, Albay', 1, 'rewr', 'fgsdg', 'sfgsg', 1590.00, 1590.00, '2024-05-28 08:54:30', '2024-05-28 09:03:31', 45, 1, '0', 'P'),
(139, 31, 1, 1, 'BC6HW3DR', 'Joven Bataller', 'Oas, Albay', 1, 'eses', 'ese', 'dssfsdf', 28050.00, 28050.00, '2024-05-28 09:03:54', '2024-05-28 09:04:38', 46, 1, '5', 'P'),
(140, 31, 1, 1, 'G68IL0SX', 'Joven Bataller', 'Oas, Albay', 1, 'dvsv', 'dvzv', 'dzvzv', 850.00, 850.00, '2024-05-28 09:16:22', '2024-05-28 09:16:56', 45, 1, '5', 'P'),
(142, 31, 1, 1, 'GX8KP8EZ', '', '', 1, 'sfsf', 'sdfsf', 'dsfsf', 30050.00, 30050.00, '2024-05-28 14:12:33', '2024-05-28 17:48:26', 46, 1, '5', 'P'),
(143, 31, 1, 1, '3XIXD7E3', '', '', 1, 'sdad', 'sadad', 'sadad', 209.00, 209.00, '2024-05-29 12:08:17', '2024-05-29 15:07:45', 46, 1, '0', 'P'),
(144, 31, 1, 1, '3XIXD7E3', '', '', 1, 'sdad', 'sadad', 'sadad', 209.00, 209.00, '2024-05-29 12:10:24', '2024-05-29 15:07:45', 46, 1, '0', 'P'),
(145, 31, 1, 1, '3XIXD7E3', '', '', 1, 'sdad', 'sadad', 'sadad', 209.00, 209.00, '2024-05-29 12:16:59', '2024-05-29 15:07:45', 25, 1, '0', 'P'),
(146, 31, 1, 1, '3XIXD7E3', '', '', 1, 'sdad', 'sadad', 'sadad', 209.00, 209.00, '2024-05-29 12:49:03', '2024-05-29 15:07:45', 23, 1, '0', 'P'),
(147, 31, 1, 1, '6TSADAI1', '', '', 1, 'fdgfdg', 'dfgdg', 'gfdgdg', 300.00, 300.00, '2024-05-29 12:53:24', '2024-05-30 02:19:58', 18, 1, '5', 'P'),
(150, 31, 1, 1, 'CZVWBHOG', 'joven', 'apud', 2, 'dsfsdf', 'sdfsf', 'dfsdfs', 2175.00, 2175.00, '2024-05-29 14:57:53', '2024-05-29 15:09:05', 48, 1, '5', 'P'),
(151, 31, 1, 1, 'CZVWBHOG', 'joven', 'apud', 2, 'dsfsdf', 'sdfsf', 'dfsdfs', 2175.00, 2175.00, '2024-05-29 14:58:05', '2024-05-29 15:09:02', 4, 1, '5', 'P'),
(152, 31, 1, 1, '011VPENL', '', '', 1, 'fghgfh', 'fghgfh', 'fghfh', 2350.00, 2350.00, '2024-05-29 15:05:57', '2024-05-29 15:27:12', 3, 1, '5', 'P'),
(153, 31, 1, 1, '011VPENL', '', '', 1, 'fghgfh', 'fghgfh', 'fghfh', 2350.00, 2350.00, '2024-05-29 15:06:01', '2024-05-29 15:27:17', 14, 1, '5', 'P'),
(154, 31, 1, 1, '011VPENL', '', '', 1, 'fghgfh', 'fghgfh', 'fghfh', 2350.00, 2350.00, '2024-05-29 15:06:06', '2024-05-29 15:27:20', 15, 1, '5', 'P'),
(155, 31, 1, 1, '0D1PY7QN', '', '', 1, 'dfss', 'sdfs', 'sdfsf', 30420.00, 30420.00, '2024-05-29 15:32:32', '2024-05-29 15:33:40', 46, 1, '5', 'P'),
(156, 31, 1, 1, '0D1PY7QN', '', '', 1, 'dfss', 'sdfs', 'sdfsf', 30420.00, 30420.00, '2024-05-29 15:32:35', '2024-05-29 15:33:43', 25, 1, '5', 'P'),
(157, 31, 1, 1, 'P46SWV2O', '', '', 1, 'sadd', 'asda', 'asdad', 1169.00, 1169.00, '2024-05-29 15:39:50', '2024-05-29 15:40:40', 25, 1, '5', 'P'),
(158, 31, 1, 1, 'P46SWV2O', '', '', 1, 'sadd', 'asda', 'asdad', 1169.00, 1169.00, '2024-05-29 15:39:53', '2024-05-29 15:40:40', 24, 1, '5', 'P'),
(159, 31, 1, 1, '1SW4O3IV', '', '', 1, 'eawea', 'eafaf', 'eafaf', 30420.00, 30420.00, '2024-05-30 01:27:32', '2024-05-30 02:00:05', 46, 1, '0', 'P'),
(160, 31, 1, 1, '1SW4O3IV', '', '', 1, 'eawea', 'eafaf', 'eafaf', 30420.00, 30420.00, '2024-05-30 01:27:36', '2024-05-30 02:00:05', 25, 1, '0', 'P'),
(161, 31, 1, 1, '28BMIXTH', '', '', 1, 'xcz', 'zxcz', 'xzcxzc', 1659.00, 1659.00, '2024-05-30 02:16:14', '2024-05-30 02:19:57', 22, 1, '5', 'P'),
(162, 31, 1, 1, '28BMIXTH', '', '', 1, 'xcz', 'zxcz', 'xzcxzc', 1659.00, 1659.00, '2024-05-30 02:16:20', '2024-05-30 02:19:57', 16, 1, '5', 'P'),
(163, 31, 1, 1, '5589HC96', '', '', 2, 'bcvb', 'cvbcv', 'vcbcb', 3055.00, 3055.00, '2024-05-30 02:16:23', '2024-05-30 02:32:30', 15, 1, '4', 'P'),
(164, 31, 1, 1, '5589HC96', '', '', 2, 'bcvb', 'cvbcv', 'vcbcb', 3055.00, 3055.00, '2024-05-30 02:16:29', '2024-05-30 02:32:30', 15, 1, '4', 'P'),
(165, 31, 1, 1, 'FWZV2V9N', '', '', 1, 'dsZd', 'sdz c', 'dczc', 579.00, 579.00, '2024-05-30 02:19:19', '2024-05-30 02:19:54', 22, 1, '3', 'P'),
(166, 31, 1, 1, 'Y7T4AWM7', '', '', 1, 'dfgd', 'dfgdg', 'dfgd', 30050.00, 30050.00, '2024-05-30 03:23:57', '2024-05-30 03:24:11', 46, 1, '2', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `payment_method_desc`) VALUES
(1, 'GCASH');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_ref_number` varchar(255) NOT NULL,
  `rev_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `item_id`, `user_id`, `rating`, `review_text`, `created_at`, `order_ref_number`, `rev_img`) VALUES
(20, 45, 31, 5, 'dfsdf', '2024-05-27 05:41:13', '29W0YBU7', NULL),
(21, 45, 31, 5, 'nice, good item', '2024-05-27 05:43:48', 'XSF16U53', NULL),
(22, 45, 31, 1, 'shit', '2024-05-27 05:54:38', '86X9671F', NULL),
(23, 21, 31, 4, 'pwede na sir', '2024-05-28 02:49:22', 'WQG72S8J', NULL),
(24, 3, 31, 3, 'dsadsa', '2024-05-28 03:01:40', 'H6WQJNGQ', '../img/f41ef91e17f2ac5dfe13bb5b4b1223f5 (1).jpg'),
(25, 46, 31, 4, 'hmmmmshahjshajhsjh hajdhjabsjdfa', '2024-05-28 03:05:47', 'BC6HW3DR', '../uploads/18595510_1434200656602333_4952857753910495050_o.jpg'),
(26, 14, 31, 5, 'matibay sya, pwede ipampukpok', '2024-05-28 03:15:52', 'OIWJV12Q', '../uploads/Screenshot 2024-05-13 233448.png'),
(27, 45, 31, 4, 'matibay namn po your honor', '2024-05-28 03:18:48', 'G68IL0SX', '../uploads/1537542_786162678072804_8430942415280620631_o.jpg'),
(28, 46, 31, 5, 'nice', '2024-05-28 11:49:00', 'GX8KP8EZ', '../uploads/445948121_413881764945461_9073975926692061545_n.jpg'),
(29, 48, 31, 4, 'ayoss bossing', '2024-05-29 09:10:46', 'CZVWBHOG', '../uploads/10669075_823746194314452_8565061906951086651_o.jpg'),
(30, 3, 31, 4, 'sakto lang', '2024-05-29 09:30:43', '011VPENL', '../uploads/10506663_760338613988544_7348800843115676162_o.jpg'),
(31, 46, 31, 3, 'sulit sirrrrsss', '2024-05-29 09:34:35', '0D1PY7QN', '../uploads/10498460_760332907322448_8166495306206181224_o.jpg'),
(32, 25, 31, 3, 'sulit sirrrrsss', '2024-05-29 09:34:35', '0D1PY7QN', '../uploads/10498460_760332907322448_8166495306206181224_o.jpg'),
(33, 25, 31, 5, 'sulit', '2024-05-29 10:20:45', 'P46SWV2O', '../uploads/1487722_760813500607722_7509112768345565117_o.jpg'),
(34, 24, 31, 5, 'sulit', '2024-05-29 10:20:45', 'P46SWV2O', '../uploads/1487722_760813500607722_7509112768345565117_o.jpg'),
(35, 22, 31, 5, 'the bestt', '2024-05-29 21:30:06', '28BMIXTH', '../uploads/10649025_793127570709648_6637439553441735894_o.jpg'),
(36, 16, 31, 5, 'the bestt', '2024-05-29 21:30:06', '28BMIXTH', '../uploads/10649025_793127570709648_6637439553441735894_o.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE `shippers` (
  `shipper_id` int(11) NOT NULL,
  `shipping_company` varchar(255) NOT NULL,
  `shipping_method_desc` text DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`shipper_id`, `shipping_company`, `shipping_method_desc`, `shipping_cost`) VALUES
(1, 'Flash Express', 'Best in service', 50.00),
(2, 'J&T', 'fast and trusted', 55.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `user_cat` varchar(255) NOT NULL COMMENT 'U -User/Client\r\nA -Admin/Seller\r\n',
  `uname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `address`, `user_cat`, `uname`, `password`, `date_added`, `user_img`) VALUES
(1, 'Naomi Do\r\n', 'Polangui, Albay', 'U', 'DoNaomi', 'gty6X&q', '2024-02-24 10:34:57', NULL),
(3, 'Vinni Pladen\r\n', 'Oas, Albay', 'U', 'PladenVinni', 'KrN85pj\r\n', '2024-02-24 10:34:57', NULL),
(4, 'Mirabella Walsh', 'Libon, Albay', 'U', 'WalshMirabella', 'cCs8uJ7', '2024-02-24 10:34:57', NULL),
(5, 'Drusilla Elvin', 'Ligao, Albay', 'U', 'ElvinDrusilla', 'GkuRGba', '2024-02-24 10:34:57', NULL),
(7, 'Paulo Hefner', 'Oas, Albay', 'U', 'HefnerPaulo', '$2VY4ZV', '2024-02-24 10:34:57', NULL),
(8, 'Alick Neaves', 'Polangui, Albay', 'U', 'NeavesAlick ', 'tz2nXkX', '2024-02-24 10:34:57', NULL),
(9, 'Raymond Wissby', 'Libon, Albay', 'U', 'WissbyRaymond', '6dxFCuS', '2024-03-06 06:52:50', NULL),
(10, 'Colan Gartan', 'Oas, Albay', 'U', 'GartanColan', 'Rd6DMbb', '2024-02-24 10:34:57', NULL),
(11, 'Hart Harbinson', 'Ligao, Albay', 'U', 'HarbinsonHart', 'CYcUT@H', '2024-02-24 10:34:57', NULL),
(12, 'Sam Natte', 'Polangui, Albay', 'U', 'NatteSam ', 'BtN2rAP', '2024-02-24 10:34:57', NULL),
(13, 'Gilberta Wallage', 'Oas, Albay', 'U', 'WallageGilberta ', 'SEKjk5M', '2024-03-06 06:52:50', NULL),
(14, 'Bondon Farren', 'Legazpi, Albay', 'U', 'FarrenBondon ', 'zmJc5mh', '2024-03-06 06:52:51', NULL),
(15, 'Kelly Warrack', 'Guinobatan, Albay', 'U', 'WarrackKelly ', 'Ya6seHX', '2024-02-24 10:34:57', NULL),
(16, 'Rozina Yesson', 'Legazpi, Albay', 'U', 'YessonRozina ', 'uCUddZc', '2024-02-24 10:34:57', NULL),
(17, 'Neysa Elsmere', 'Camalig, Albay', 'U', 'ElsmereNeysa ', 'JZnxT!7', '2024-02-24 10:34:57', NULL),
(18, 'Paloma Montacute', 'Guinobatan, Albay', 'U', 'MontacutePaloma ', 'tjrJ5g8', '2024-02-24 10:34:57', NULL),
(19, 'Erena Duplan', 'Pioduran, Albay', 'U', 'DuplanErena ', 'aXhyXGr', '2024-02-24 10:34:57', NULL),
(20, 'Levon Torrans', 'Polangui, Albay', 'U', 'TorransLevon ', 'K8cekDe', '2024-02-24 10:34:57', NULL),
(24, 'Carlo Aquino', 'sa', 'U', 'sa', '1', '2024-05-10 14:53:43', NULL),
(26, 'Harley Gepila', 'Oas, Albay', 'A', 'Harley', '1234', '2024-05-10 15:09:49', NULL),
(27, 'Joven Serdan Bataller', 'Apud, Libon, Albay', 'A', 'wakamonooo', '1234', '2024-05-10 15:11:15', NULL),
(28, 'Jhonmel Bobis', 'Polangui, Albay', 'U', 'mel', '1234', '2024-05-11 03:10:43', NULL),
(29, 'Thomas Shelby', 'Oas', 'U', 'thommy', '1234', '2024-05-28 13:40:32', 'shuichi.png'),
(30, 'ella', 'Libon', 'U', 'elle', '1234', '2024-05-20 07:10:44', NULL),
(31, 'Joven Bataller', 'Apud, Libon, Albay', 'U', 'jovs', '2121', '2024-05-29 15:07:33', 'download.jpg'),
(33, 'Cameron Diaz', 'Apud, Libon, Albay', 'U', 'cam', '12345', '2024-05-28 11:33:39', 'Blue Welcome to School Library Banner.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shipper_id` (`shipper_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`shipper_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
  MODIFY `shipper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`shipper_id`) REFERENCES `shippers` (`shipper_id`),
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
