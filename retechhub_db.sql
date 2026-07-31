-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2026 at 05:04 PM
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
-- Database: `retechhub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_images`
--

CREATE TABLE `booking_images` (
  `image_id` int(11) NOT NULL,
  `booking_type` varchar(20) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_images`
--

INSERT INTO `booking_images` (`image_id`, `booking_type`, `booking_id`, `image_path`, `created_at`) VALUES
(1, 'sell', 18, 'uploads/sell/1782813281_9302_card_after_training (21).png', '2026-06-30 09:54:41'),
(2, 'sell', 18, 'uploads/sell/1782813281_2196_card_after_training (19).png', '2026-06-30 09:54:41'),
(3, 'repair', 17, 'uploads/repair/1782813915_8178_card_after_training (22).png', '2026-06-30 10:05:15'),
(4, 'repair', 17, 'uploads/repair/1782813915_7828_card_after_training (16).png', '2026-06-30 10:05:15'),
(5, 'repair', 17, 'uploads/repair/1782813915_4918_card_after_training (19).png', '2026-06-30 10:05:15'),
(6, 'repair', 18, 'uploads/repair/1782873450_2714_lowzhejun pratical1.png', '2026-07-01 02:37:30'),
(7, 'repair', 18, 'uploads/repair/1782873450_8556_Screenshot 2024-09-11 120653.png', '2026-07-01 02:37:30'),
(8, 'repair', 18, 'uploads/repair/1782873450_5592_Screenshot 2025-05-05 171224.png', '2026-07-01 02:37:30'),
(9, 'repair', 18, 'uploads/repair/1782873450_2752_Screenshot 2025-05-08 151229.png', '2026-07-01 02:37:30'),
(10, 'sell', 19, 'uploads/sell/1782873940_8379_Screenshot 2024-09-11 120653.png', '2026-07-01 02:45:41'),
(11, 'sell', 19, 'uploads/sell/1782873941_8167_Screenshot 2025-05-05 221806.png', '2026-07-01 02:45:41'),
(12, 'sell', 19, 'uploads/sell/1782873941_5750_Screenshot 2025-05-08 135603.png', '2026-07-01 02:45:41'),
(13, 'sell', 19, 'uploads/sell/1782873941_5962_Screenshot 2025-05-08 151229.png', '2026-07-01 02:45:41'),
(14, 'repair', 19, 'uploads/repair/1782875136_5554_card_after_training (5).png', '2026-07-01 03:05:36'),
(15, 'repair', 19, 'uploads/repair/1782875136_3795_card_after_training (10).png', '2026-07-01 03:05:36'),
(16, 'repair', 19, 'uploads/repair/1782875136_8398_card_after_training (1).png', '2026-07-01 03:05:36'),
(17, 'repair', 20, 'uploads/repair/1783231002_4943_card_after_training (21).png', '2026-07-05 05:56:42'),
(18, 'repair', 20, 'uploads/repair/1783231002_8384_card_after_training (10).png', '2026-07-05 05:56:42'),
(19, 'sell', 21, 'uploads/sell/1783231491_6658_card_after_training (19).png', '2026-07-05 06:04:51'),
(20, 'sell', 21, 'uploads/sell/1783231491_6729_card_after_training (4).png', '2026-07-05 06:04:51'),
(21, 'sell', 22, 'uploads/sell/1785226229_2988_1783703965_images (22).jpg', '2026-07-28 08:10:29'),
(22, 'repair', 21, 'uploads/repair/1785226280_2462_1783703965_images (22).jpg', '2026-07-28 08:11:20'),
(23, 'repair', 22, 'uploads/repair/1785228984_4608_card_after_training.png', '2026-07-28 08:56:24'),
(24, 'repair', 22, 'uploads/repair/1785228984_7561_card_after_training (18).png', '2026-07-28 08:56:24'),
(25, 'repair', 23, 'uploads/repair/1785229230_6050_card_after_training (21).png', '2026-07-28 09:00:30'),
(26, 'sell', 25, 'uploads/sell/1785339736_1248_card_after_training (19).png', '2026-07-29 15:42:16'),
(27, 'repair', 24, 'uploads/repair/1785339774_6948_card_after_training (21).png', '2026-07-29 15:42:54'),
(28, 'repair', 25, 'uploads/repair/1785339808_4131_card_after_training (20).png', '2026-07-29 15:43:28'),
(29, 'repair', 26, 'uploads/repair/1785392368_3664_card_after_training (21).png', '2026-07-30 06:19:28'),
(30, 'repair', 27, 'uploads/repair/1785394189_2243_card_after_training (20).png', '2026-07-30 06:49:49'),
(31, 'sell', 26, 'uploads/sell/1785425795_4907_1783703965_images (22).jpg', '2026-07-30 15:36:35'),
(32, 'repair', 28, 'uploads/repair/1785425840_8797_1783703965_images (22).jpg', '2026-07-30 15:37:20'),
(33, 'repair', 29, 'uploads/repair/1785432929_2460_1783703965_images (22).jpg', '2026-07-30 17:35:29'),
(34, 'repair', 30, 'uploads/repair/1785432962_7242_card_after_training (8).png', '2026-07-30 17:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(42, 9, 28, 1, '2026-07-10 03:30:02'),
(43, 9, 31, 1, '2026-07-10 03:30:03'),
(44, 10, 32, 1, '2026-07-10 03:30:24'),
(45, 10, 34, 1, '2026-07-10 03:30:26'),
(46, 10, 30, 1, '2026-07-10 03:30:28'),
(62, 5, 34, 1, '2026-07-30 15:57:26'),
(63, 5, 32, 1, '2026-07-30 15:57:26'),
(64, 5, 28, 1, '2026-07-30 15:57:26');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `created_at`, `is_read`, `image`) VALUES
(1, 5, 1, 'adqw', '2026-06-09 09:33:21', 1, NULL),
(2, 1, 5, 'a\'d\'w\'d\'q\'w\'s\'d\'q\'w', '2026-06-09 14:47:05', 1, NULL),
(3, 1, 5, 'lol', '2026-06-09 14:48:57', 1, NULL),
(4, 5, 6, 'sdfghjk', '2026-06-09 14:50:35', 1, NULL),
(5, 5, 1, 'dfghjklkjhgfdsdfghj', '2026-06-10 01:49:34', 1, NULL),
(6, 5, 1, 'sdfghjk,l.;/', '2026-06-10 01:49:39', 1, NULL),
(7, 5, 1, 'ghjkljhgghjkl;', '2026-06-10 01:49:47', 1, NULL),
(8, 5, 6, 'sdfghjkl;\'', '2026-06-10 01:50:15', 1, NULL),
(9, 5, 6, 'ergjk', '2026-06-10 01:50:20', 1, NULL),
(10, 1, 5, 'dfghjkl', '2026-06-10 03:11:43', 1, NULL),
(11, 1, 5, 'sdfghj', '2026-06-10 16:41:40', 1, NULL),
(12, 1, 5, 'asdfghj', '2026-06-10 16:41:56', 1, NULL),
(13, 1, 5, 'sdfghjk', '2026-06-10 16:45:50', 1, NULL),
(14, 1, 5, 'd\'f\'g\'h\'j', '2026-06-10 16:49:22', 1, NULL),
(15, 1, 5, 'f\'f\'f\'f', '2026-06-10 16:49:35', 1, NULL),
(16, 1, 5, 'f\'f\'f\'f\'f\'f', '2026-06-10 16:49:38', 1, NULL),
(17, 7, 5, 's\'d\'f\'g\'h\'j', '2026-06-10 17:09:50', 1, NULL),
(18, 7, 5, 's\'d\'f\'g\'h', '2026-06-10 17:10:06', 1, NULL),
(19, 1, 5, 's\'d\'f\'g\'h', '2026-06-10 17:25:24', 1, NULL),
(20, 5, 1, 'asdfghjk', '2026-06-10 17:38:26', 1, NULL),
(21, 1, 5, 'asdfg', '2026-06-11 05:32:28', 1, NULL),
(22, 1, 5, 'sdf', '2026-06-11 05:32:34', 1, NULL),
(23, 1, 5, 'sdf', '2026-06-11 05:32:39', 1, NULL),
(24, 5, 6, 's\'d\'f\'g', '2026-06-11 08:06:47', 1, NULL),
(25, 5, 6, 's\'d\'f\'g\'h\'j\'k\'l', '2026-06-11 08:07:09', 1, NULL),
(26, 1, 6, 's\'d\'f\'g', '2026-06-11 08:19:46', 1, NULL),
(27, 6, 1, 'd\'d\'d\'d\'d', '2026-06-11 08:28:18', 1, NULL),
(28, 6, 1, 's\'s\'s\'s\'s', '2026-06-11 08:28:24', 1, NULL),
(29, 6, 1, 'ssss', '2026-06-11 08:31:42', 1, NULL),
(30, 1, 6, 'sssss', '2026-06-11 08:55:20', 1, NULL),
(31, 1, 6, 's\'s\'s\'s', '2026-06-11 08:56:03', 1, NULL),
(32, 1, 6, 's\'s\'s\'s', '2026-06-11 09:05:22', 1, NULL),
(33, 1, 5, 's\'s\'s\'s\'s', '2026-06-11 09:08:43', 1, NULL),
(34, 1, 6, 'q\'q\'q\'q\'q', '2026-06-11 09:09:24', 1, NULL),
(35, 6, 5, 'ssssss', '2026-06-11 09:10:56', 1, NULL),
(36, 1, 5, 's\'s\'s', '2026-06-11 09:13:08', 1, NULL),
(37, 1, 5, 'q\'q\'q\'q\'q', '2026-06-11 09:13:21', 1, NULL),
(38, 1, 5, 's\'s\'s\'s', '2026-06-11 09:13:28', 1, NULL),
(39, 1, 5, 'a\'a\'a', '2026-06-11 09:16:23', 1, NULL),
(40, 6, 5, 'sss', '2026-06-11 09:53:47', 1, NULL),
(41, 5, 6, 'sssss', '2026-06-11 14:28:48', 1, NULL),
(42, 5, 1, 's\'s\'s\'s\'s', '2026-06-11 14:28:52', 1, NULL),
(43, 5, 1, 'ssss', '2026-06-11 14:29:01', 1, NULL),
(44, 5, 6, 's\'s\'s\'s', '2026-06-11 14:29:13', 1, NULL),
(45, 5, 1, 'ssssss', '2026-06-11 14:29:30', 1, NULL),
(46, 5, 6, 's\'s\'s\'s\'s', '2026-06-11 14:29:53', 1, NULL),
(47, 6, 5, 's\'s\'s\'s\'s\'s', '2026-06-11 14:30:00', 1, NULL),
(48, 6, 5, 's\'s\'s\'s\'s\'s', '2026-06-11 14:30:10', 1, NULL),
(49, 6, 5, 's\'s\'s\'s', '2026-06-11 14:30:16', 1, NULL),
(50, 6, 1, 'sdfghjkl;', '2026-06-12 01:26:00', 1, NULL),
(51, 1, 6, 'sdfgnm', '2026-06-12 01:26:14', 1, NULL),
(52, 1, 5, 'd\'d\'d\'d\'d\'d', '2026-06-12 08:15:52', 1, NULL),
(53, 6, 1, 'd\'d\'d\'d\'d\'d\'d', '2026-06-12 08:15:58', 1, NULL),
(54, 6, 5, 'd\'d\'d\'d\'d\'d', '2026-06-12 08:16:13', 1, NULL),
(55, 1, 5, 'd\'d\'d\'d\'d', '2026-06-12 08:16:25', 1, NULL),
(56, 1, 5, 's\'s\'s\'s\'s', '2026-06-12 08:16:36', 1, NULL),
(57, 1, 5, 'd\'d\'d\'d', '2026-06-12 08:25:23', 1, NULL),
(58, 1, 5, 'd\'d\'d\'d\'d', '2026-06-12 08:25:32', 1, NULL),
(59, 6, 5, 's\'s\'s\'s', '2026-06-12 08:25:43', 1, NULL),
(60, 1, 6, 's\'s\'s\'s', '2026-06-12 08:26:02', 1, NULL),
(61, 1, 6, 'sssss', '2026-06-12 14:40:55', 1, NULL),
(62, 5, 1, 's\'d\'f\'g\'h', '2026-06-12 14:59:45', 1, NULL),
(63, 5, 6, 's\'d\'f\'g\'h\'j\'k', '2026-06-12 14:59:55', 1, NULL),
(64, 1, 6, 's\'d\'f\'g\'h\'j', '2026-06-12 15:00:00', 1, NULL),
(65, 5, 6, 's\'s\'s\'s\'s\'s', '2026-06-12 15:00:15', 1, NULL),
(66, 5, 6, 's\'s\'s\'s\'s\'s', '2026-06-12 15:00:25', 1, NULL),
(67, 1, 6, 's\'s\'s\'s\'s', '2026-06-12 15:02:17', 1, NULL),
(68, 5, 6, 's\'s\'s\'s\'s\'s', '2026-06-12 15:02:22', 1, NULL),
(69, 5, 6, 's\'s\'s\'s\'s', '2026-06-12 15:02:36', 1, NULL),
(70, 5, 6, 'sssss', '2026-06-12 15:03:45', 1, NULL),
(71, 1, 6, 's\'s\'s\'s', '2026-06-12 15:04:54', 1, NULL),
(72, 1, 6, 's\'s\'s', '2026-06-12 15:05:14', 1, NULL),
(73, 5, 1, 's\'s\'s', '2026-06-12 15:05:39', 1, NULL),
(74, 6, 1, 's\'s\'s\'s', '2026-06-12 15:05:49', 1, NULL),
(75, 5, 1, 's\'s\'s\'s\'s', '2026-06-12 15:20:03', 1, NULL),
(76, 5, 1, 's\'s', '2026-06-14 16:22:52', 1, ''),
(77, 5, 1, 'sss', '2026-06-14 17:00:05', 1, ''),
(78, 5, 1, '', '2026-06-14 17:04:36', 1, 'uploads/messages/1781456676_4511_1780850413_iphone14pro.jpg'),
(79, 5, 1, '', '2026-06-14 17:59:25', 1, 'uploads/messages/1781459965_6583_1780852309_1780850413_iphone14pro.jpg'),
(80, 1, 5, '', '2026-06-15 06:11:21', 1, 'uploads/messages/1781503881_8177_1780850413_iphone14pro.jpg'),
(81, 5, 1, '', '2026-06-15 06:24:12', 1, 'uploads/messages/1781504652_6532_1781157120_mzk.jpg'),
(82, 5, 6, '', '2026-06-15 06:24:37', 1, 'uploads/messages/1781504677_5785_1781157120_mzk.jpg'),
(83, 6, 1, '', '2026-06-15 06:34:49', 1, 'uploads/messages/1781505289_6989_mzk.jpg'),
(84, 5, 1, '✅', '2026-06-15 06:54:52', 1, ''),
(85, 5, 1, '🙏', '2026-06-15 07:04:53', 1, ''),
(86, 5, 6, '✅', '2026-06-15 07:05:04', 1, ''),
(87, 6, 1, '✅', '2026-06-15 07:06:11', 1, ''),
(88, 6, 1, '😎', '2026-06-15 07:06:13', 1, ''),
(89, 6, 1, '👍👍👍👍', '2026-06-15 07:06:16', 1, ''),
(90, 6, 1, '✅✅✅', '2026-06-15 07:06:54', 1, ''),
(91, 5, 6, '✅✅✅✅', '2026-06-15 07:07:03', 1, ''),
(92, 1, 5, '✅✅✅', '2026-06-15 07:12:26', 1, ''),
(93, 1, 6, '✅✅✅✅', '2026-06-15 07:12:32', 1, ''),
(94, 5, 1, '', '2026-06-23 08:43:01', 1, 'uploads/messages/1782204181_6463_Mind_Map.jpeg'),
(95, 5, 1, '', '2026-06-23 08:43:01', 1, 'uploads/messages/1782204181_5126_card_after_training (4).png'),
(96, 1, 15, '', '2026-07-01 02:20:53', 1, 'uploads/messages/1782872453_5578_card_after_training (18).png'),
(97, 1, 15, '', '2026-07-01 02:20:53', 1, 'uploads/messages/1782872453_6004_card_after_training (17).png'),
(98, 1, 15, '✅', '2026-07-01 02:21:09', 1, ''),
(99, 15, 1, 'hello', '2026-07-01 02:23:52', 1, ''),
(100, 15, 1, 'byebye', '2026-07-01 02:23:59', 1, ''),
(101, 15, 1, 'kamsha', '2026-07-01 02:24:05', 1, ''),
(102, 1, 15, 'wertyuiop[', '2026-07-01 02:24:13', 1, ''),
(103, 15, 1, '👍', '2026-07-01 02:24:15', 1, ''),
(104, 5, 1, 'hi', '2026-07-28 08:12:23', 1, ''),
(105, 5, 1, '😂', '2026-07-28 08:12:28', 1, ''),
(106, 5, 1, '', '2026-07-28 08:12:38', 1, 'uploads/messages/1785226358_6027_1783703965_images (22).jpg'),
(107, 1, 5, 'sss', '2026-07-28 08:16:01', 1, ''),
(108, 1, 5, '🙏', '2026-07-28 08:16:04', 1, ''),
(109, 1, 5, '', '2026-07-28 08:16:11', 1, 'uploads/messages/1785226571_5246_card_after_training (22).png'),
(110, 1, 5, 'dfgh', '2026-07-28 08:45:56', 1, ''),
(111, 1, 5, '😎', '2026-07-28 08:46:00', 1, ''),
(112, 1, 5, 'ssssss', '2026-07-28 08:46:50', 1, ''),
(113, 1, 5, '😎', '2026-07-28 08:46:52', 1, ''),
(114, 1, 5, '', '2026-07-28 08:46:58', 1, 'uploads/messages/1785228418_6635_card_after_training (22).png'),
(115, 6, 1, 'sss👍', '2026-07-28 09:02:15', 1, ''),
(116, 6, 1, '', '2026-07-28 09:02:20', 1, 'uploads/messages/1785229340_7875_card_after_training (19).png'),
(117, 1, 5, 'adqw', '2026-07-30 17:17:09', 0, ''),
(118, 1, 5, '😎', '2026-07-30 17:17:13', 0, ''),
(119, 1, 5, '', '2026-07-30 17:17:24', 0, 'uploads/messages/1785431844_2914_1783703965_images (22).jpg'),
(120, 6, 1, 'sss😎', '2026-07-30 17:37:53', 0, ''),
(121, 6, 1, '', '2026-07-30 17:37:58', 0, 'uploads/messages/1785433078_4715_1783703965_images (22).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(190, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-06-23 08:43:01'),
(191, 5, 'Sell Booking Updated', 'Your sell booking #SB2 status was updated to Completed by admin.', 'sell', 1, '2026-06-24 02:30:57'),
(192, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-06-30 09:02:52'),
(193, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-06-30 09:06:11'),
(194, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-06-30 09:54:41'),
(195, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-06-30 10:05:15'),
(196, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD10.', 'order', 1, '2026-06-30 10:15:42'),
(197, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Shipped.', 'order', 1, '2026-06-30 10:16:17'),
(198, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Completed.', 'order', 1, '2026-06-30 10:16:20'),
(199, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Processing.', 'order', 1, '2026-06-30 10:16:58'),
(200, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Cancelled.', 'order', 1, '2026-06-30 10:17:08'),
(201, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Completed.', 'order', 1, '2026-06-30 10:17:14'),
(202, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Pending.', 'order', 1, '2026-06-30 10:17:43'),
(203, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Completed.', 'order', 1, '2026-06-30 10:17:49'),
(204, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Pending.', 'order', 1, '2026-06-30 10:22:03'),
(205, 12, 'Repair Status Updated', 'Your repair booking #RB15 status was updated to Pending by admin.', 'repair', 0, '2026-06-30 10:38:22'),
(206, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Completed.', 'order', 1, '2026-06-30 10:47:05'),
(207, 5, 'Order Status Updated', 'Your order #ORD10 status has been updated to Pending.', 'order', 1, '2026-06-30 10:47:12'),
(208, 1, 'New User Registered', 'A new user has registered: LowBrain.', 'user', 1, '2026-07-01 02:18:41'),
(209, 15, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:20:53'),
(210, 15, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:21:09'),
(211, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:23:52'),
(212, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:23:59'),
(213, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:24:05'),
(214, 15, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:24:13'),
(215, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-01 02:24:15'),
(216, 1, 'New Order Placed', 'User #15  LowBrain placed a new order #ORD11.', 'order', 1, '2026-07-01 02:28:24'),
(217, 15, 'Order Status Updated', 'Your order #ORD11 status has been updated to Processing.', 'order', 1, '2026-07-01 02:28:50'),
(218, 15, 'Order Status Updated', 'Your order #ORD11 status has been updated to Completed.', 'order', 1, '2026-07-01 02:29:08'),
(219, 15, 'Order Status Updated', 'Your order #ORD11 status has been updated to Cancelled.', 'order', 1, '2026-07-01 02:29:26'),
(220, 15, 'Order Status Updated', 'Your order #ORD11 status has been updated to Shipped.', 'order', 1, '2026-07-01 02:29:51'),
(221, 1, 'New Order Placed', 'User #15  LowBrain placed a new order #ORD12.', 'order', 1, '2026-07-01 02:34:17'),
(222, 1, 'Order Cancelled by User', 'User #15 LowBrain cancelled order #ORD11. Reason: Prefer not to answer', 'order', 1, '2026-07-01 02:35:26'),
(223, 15, 'Order Status Updated', 'Your order #ORD12 status has been updated to Completed.', 'order', 1, '2026-07-01 02:36:24'),
(224, 1, 'New Repair Booking', 'User #15 LowBrain submitted a new repair booking.', 'booking', 1, '2026-07-01 02:37:30'),
(225, 15, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB18.', 'repair', 1, '2026-07-01 02:40:36'),
(226, 1, 'New Sell Request', 'User #15 submitted a new sell request.', 'booking', 1, '2026-07-01 02:45:41'),
(227, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-01 03:05:36'),
(228, 5, 'Technician Assigned', 'Technician technician1 has been assigned to your repair booking.', 'repair', 1, '2026-07-01 03:41:12'),
(229, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Cancelled by admin.', 'sell', 0, '2026-07-01 03:42:48'),
(230, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Cancelled by admin.', 'sell', 0, '2026-07-01 03:43:13'),
(231, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Pending by admin.', 'sell', 0, '2026-07-01 03:43:14'),
(232, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Cancelled by admin.', 'sell', 0, '2026-07-01 03:43:17'),
(233, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Cancelled by admin.', 'sell', 0, '2026-07-01 03:43:18'),
(234, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Pending by admin.', 'sell', 0, '2026-07-01 03:43:19'),
(235, 5, 'Sell Booking Updated', 'Your sell booking #SB4 status was updated to Rejected by admin.', 'sell', 1, '2026-07-01 03:43:31'),
(236, 5, 'Sell Booking Updated', 'Your sell booking #SB5 status was updated to Rejected by admin.', 'sell', 1, '2026-07-01 03:44:33'),
(237, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Rejected by admin.', 'sell', 0, '2026-07-01 03:45:58'),
(238, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Assigned by admin.', 'repair', 1, '2026-07-03 08:02:32'),
(239, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Assigned by admin.', 'repair', 1, '2026-07-03 08:15:10'),
(240, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Assigned by admin.', 'repair', 1, '2026-07-03 08:15:11'),
(241, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Assigned by admin.', 'repair', 1, '2026-07-03 08:15:13'),
(242, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:49:34'),
(243, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:49:37'),
(244, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:49:47'),
(245, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:50:52'),
(246, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:50:56'),
(247, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:50:57'),
(248, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:50:57'),
(249, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:03'),
(250, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:03'),
(251, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:04'),
(252, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:04'),
(253, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:04'),
(254, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:05'),
(255, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:16'),
(256, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:18'),
(257, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:18'),
(258, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Assigned by admin.', 'repair', 0, '2026-07-03 08:51:19'),
(259, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Workshop Repair by admin.', 'repair', 0, '2026-07-03 08:52:39'),
(260, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Workshop Repair by admin.', 'repair', 1, '2026-07-03 08:54:30'),
(261, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to In Progress by admin.', 'repair', 1, '2026-07-03 08:56:36'),
(262, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Workshop Repair by admin.', 'repair', 1, '2026-07-03 08:56:38'),
(263, 5, 'Repair Status Updated', 'Your repair booking #RB13 status was updated to Assigned.', 'repair', 1, '2026-07-03 14:55:28'),
(264, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB13 to Assigned.', 'repair', 1, '2026-07-03 14:55:28'),
(265, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 15:19:30'),
(266, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 15:19:32'),
(267, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to In Progress.', 'repair', 1, '2026-07-03 15:19:34'),
(268, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Assigned.', 'repair', 1, '2026-07-03 15:19:39'),
(269, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 15:19:41'),
(270, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Cancelled.', 'repair', 1, '2026-07-03 15:20:05'),
(271, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 15:20:09'),
(272, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to In Progress.', 'repair', 0, '2026-07-03 16:08:40'),
(273, 5, 'Repair Status Updated', 'Your repair booking #RB13 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 16:08:42'),
(274, 12, 'Technician Assigned', 'Technician amia has been assigned to your repair booking.', 'repair', 0, '2026-07-03 16:12:54'),
(275, 12, 'Technician Assigned', 'Technician amia has been assigned to your repair booking.', 'repair', 0, '2026-07-03 16:13:51'),
(276, 12, 'Technician Assigned', 'Technician amia has been assigned to your repair booking.', 'repair', 0, '2026-07-03 16:34:34'),
(277, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB15.', 'repair', 1, '2026-07-03 16:34:34'),
(278, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking.', 'repair', 1, '2026-07-03 16:35:44'),
(279, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB1by admin.', 'repair', 1, '2026-07-03 16:35:44'),
(280, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking.', 'repair', 1, '2026-07-03 16:35:58'),
(281, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB1 by admin.', 'repair', 1, '2026-07-03 16:35:58'),
(282, 5, 'Technician Assigned', 'Technician technician1 has been assigned to your repair booking.', 'repair', 1, '2026-07-03 16:36:40'),
(283, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB13 by admin.', 'repair', 1, '2026-07-03 16:36:40'),
(284, 5, 'Technician Updated', 'The technician for your repair booking #RB1 has been changed to technician1.', 'repair', 1, '2026-07-03 16:55:27'),
(285, 6, 'Repair Reassigned', 'Repair booking #RB1 has been reassigned to another technician.', 'repair', 1, '2026-07-03 16:55:27'),
(286, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB1.', 'repair', 1, '2026-07-03 16:55:27'),
(287, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to In Progress.', 'repair', 0, '2026-07-03 16:56:31'),
(288, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to Workshop Repair.', 'repair', 0, '2026-07-03 16:56:34'),
(289, 12, 'Repair Status Updated', 'Your repair booking #RB15 status has been updated to Workshop Repair.', 'repair', 0, '2026-07-03 16:56:46'),
(290, 5, 'Repair Status Updated', 'Your repair booking #RB11 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-03 16:57:01'),
(291, 5, 'Sell Booking Updated', 'Your sell booking #SB8 status was updated to Approved by admin.', 'sell', 1, '2026-07-03 17:57:55'),
(292, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD13.', 'order', 1, '2026-07-04 08:33:23'),
(293, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD13. Reason: Changed my mind', 'order', 1, '2026-07-04 08:34:25'),
(294, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD14.', 'order', 1, '2026-07-04 08:48:07'),
(295, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD15.', 'order', 1, '2026-07-04 08:57:35'),
(296, 5, 'Repair Status Updated', 'Your repair booking #RB17 status was updated to Pending by admin.', 'repair', 1, '2026-07-04 12:18:20'),
(297, 5, 'Repair Status Updated', 'Your repair booking #RB17 status was updated to Completed by admin.', 'repair', 1, '2026-07-04 12:18:24'),
(298, 5, 'Sell Booking Updated', 'Your sell booking #SB18 status was updated to Completed by admin.', 'sell', 1, '2026-07-04 12:18:43'),
(299, 5, 'Sell Booking Updated', 'Your sell booking #SB18 status was updated to Completed by admin.', 'sell', 1, '2026-07-04 12:19:12'),
(300, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-05 05:56:42'),
(301, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-05 06:04:14'),
(302, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-05 06:04:51'),
(303, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to In Progress.', 'repair', 0, '2026-07-12 14:15:59'),
(304, 12, 'Repair Status Updated', 'Your repair booking #RB15 status has been updated to In Progress.', 'repair', 0, '2026-07-12 14:16:01'),
(305, 5, 'Repair Status Updated', 'Your repair booking #RB11 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-12 14:16:09'),
(306, 5, 'Repair Status Updated', 'Your repair booking #RB11 status has been updated to Completed.', 'repair', 1, '2026-07-12 14:16:12'),
(307, 5, 'Repair Status Updated', 'Your repair booking #RB10 status has been updated to Assigned.', 'repair', 1, '2026-07-12 14:16:27'),
(308, 12, 'Repair Status Updated', 'Your repair booking #RB16 status was updated to Assigned.', 'repair', 0, '2026-07-18 10:10:35'),
(309, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB16 to Assigned.', 'repair', 1, '2026-07-18 10:10:35'),
(310, 12, 'Repair Status Updated', 'Your repair booking #RB16 status was updated to Assigned.', 'repair', 0, '2026-07-18 10:10:38'),
(311, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB16 to Assigned.', 'repair', 1, '2026-07-18 10:10:38'),
(312, 5, 'Repair Status Updated', 'Your repair booking #RB10 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-18 10:10:57'),
(313, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to Assigned.', 'repair', 0, '2026-07-18 13:21:49'),
(314, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to In Progress.', 'repair', 0, '2026-07-18 13:21:51'),
(315, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to Completed.', 'repair', 0, '2026-07-18 13:21:54'),
(316, 12, 'Repair Status Updated', 'Your repair booking #RB16 status has been updated to Cancelled.', 'repair', 0, '2026-07-18 13:21:57'),
(317, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Workshop Repair.', 'repair', 0, '2026-07-18 13:22:54'),
(318, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB18 to Workshop Repair.', 'repair', 1, '2026-07-18 13:22:54'),
(319, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to Workshop Repair.', 'repair', 0, '2026-07-18 13:24:26'),
(320, 15, 'Repair Status Updated', 'Your repair booking #RB18 status has been updated to Completed.', 'repair', 0, '2026-07-18 13:25:13'),
(321, 12, 'Repair Status Updated', 'Your repair booking #RB16 status was updated to Workshop Repair.', 'repair', 0, '2026-07-18 13:25:21'),
(322, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB16 to Workshop Repair.', 'repair', 1, '2026-07-18 13:25:21'),
(323, 1, 'New User Registered', 'A new user has registered: user5.', 'user', 1, '2026-07-22 05:40:46'),
(324, 1, 'New User Registered', 'A new user has registered: user5.', 'user', 1, '2026-07-22 06:06:41'),
(325, 12, 'Repair Status Updated', 'Your repair booking #RB16 status has been updated to Cancelled.', 'repair', 0, '2026-07-23 09:27:10'),
(326, 1, 'New User Registered', 'A new user has registered: test user.', 'user', 1, '2026-07-28 07:49:23'),
(327, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD16.', 'order', 1, '2026-07-28 08:08:17'),
(328, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD16. Reason: Prefer not to answer', 'order', 1, '2026-07-28 08:08:45'),
(329, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-28 08:10:29'),
(330, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-28 08:11:20'),
(331, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:12:23'),
(332, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:12:28'),
(333, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:12:38'),
(334, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:16:01'),
(335, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:16:04'),
(336, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:16:11'),
(337, 1, 'New User Registered', 'A new user has registered: demo.', 'user', 1, '2026-07-28 08:20:40'),
(338, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Shipped.', 'order', 1, '2026-07-28 08:21:47'),
(339, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Processing.', 'order', 1, '2026-07-28 08:21:49'),
(340, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-28 08:21:51'),
(341, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Processing.', 'order', 1, '2026-07-28 08:27:51'),
(342, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Shipped.', 'order', 1, '2026-07-28 08:28:02'),
(343, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-28 08:28:23'),
(344, 1, 'New User Registered', 'A new user has registered: test user.', 'user', 1, '2026-07-28 08:31:20'),
(345, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Processing.', 'order', 1, '2026-07-28 08:32:21'),
(346, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Shipped.', 'order', 1, '2026-07-28 08:32:27'),
(347, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Completed.', 'order', 1, '2026-07-28 08:32:32'),
(348, 5, 'Technician Updated', 'The technician for your repair booking #RB1 has been changed to technician2.', 'repair', 1, '2026-07-28 08:37:49'),
(349, 7, 'Repair Reassigned', 'Repair booking #RB1 has been reassigned to another technician.', 'repair', 0, '2026-07-28 08:37:49'),
(350, 8, 'New Repair Assigned', 'You have been assigned repair booking #RB1.', 'repair', 0, '2026-07-28 08:37:49'),
(351, 5, 'Sell Booking Updated', 'Your sell booking #SB2 status was updated to Approved by admin.', 'sell', 1, '2026-07-28 08:38:02'),
(352, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking #RB20.', 'repair', 1, '2026-07-28 08:38:10'),
(353, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB20.', 'repair', 1, '2026-07-28 08:38:10'),
(354, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-28 08:39:06'),
(355, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Processing.', 'order', 1, '2026-07-28 08:39:56'),
(356, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-28 08:40:01'),
(357, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Shipped.', 'order', 1, '2026-07-28 08:40:06'),
(358, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Completed.', 'order', 1, '2026-07-28 08:40:13'),
(359, 5, 'Repair Status Updated', 'Your repair booking #RB10 status was updated to In Progress by admin.', 'repair', 1, '2026-07-28 08:42:28'),
(360, 5, 'Technician Updated', 'The technician for your repair booking #RB1 has been changed to amia.', 'repair', 1, '2026-07-28 08:43:01'),
(361, 8, 'Repair Reassigned', 'Repair booking #RB1 has been reassigned to another technician.', 'repair', 0, '2026-07-28 08:43:01'),
(362, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB1.', 'repair', 1, '2026-07-28 08:43:01'),
(363, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking #RB1.', 'repair', 1, '2026-07-28 08:43:01'),
(364, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB1.', 'repair', 1, '2026-07-28 08:43:01'),
(365, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to In Progress by admin.', 'repair', 1, '2026-07-28 08:43:03'),
(366, 5, 'Technician Updated', 'The technician for your repair booking #RB1 has been changed to technician1.', 'repair', 1, '2026-07-28 08:43:06'),
(367, 6, 'Repair Reassigned', 'Repair booking #RB1 has been reassigned to another technician.', 'repair', 1, '2026-07-28 08:43:06'),
(368, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB1.', 'repair', 0, '2026-07-28 08:43:06'),
(369, 5, 'Technician Updated', 'The technician for your repair booking #RB20 has been changed to technician1.', 'repair', 1, '2026-07-28 08:43:19'),
(370, 6, 'Repair Reassigned', 'Repair booking #RB20 has been reassigned to another technician.', 'repair', 1, '2026-07-28 08:43:19'),
(371, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB20.', 'repair', 0, '2026-07-28 08:43:19'),
(372, 5, 'Repair Status Updated', 'Your repair booking #RB20 status was updated to Workshop Repair by admin.', 'repair', 1, '2026-07-28 08:43:22'),
(373, 5, 'Sell Booking Updated', 'Your sell booking #SB2 status was updated to Completed by admin.', 'sell', 1, '2026-07-28 08:43:35'),
(374, 5, 'Sell Booking Updated', 'Your sell booking #SB2 status was updated to Pending by admin.', 'sell', 1, '2026-07-28 08:43:37'),
(375, 5, 'Sell Booking Updated', 'Your sell booking #SB2 status was updated to Rejected by admin.', 'sell', 1, '2026-07-28 08:43:39'),
(376, 12, 'Technician Updated', 'The technician for your repair booking #RB15 has been changed to technician1.', 'repair', 0, '2026-07-28 08:44:35'),
(377, 6, 'Repair Reassigned', 'Repair booking #RB15 has been reassigned to another technician.', 'repair', 1, '2026-07-28 08:44:35'),
(378, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB15.', 'repair', 0, '2026-07-28 08:44:35'),
(379, 12, 'Technician Updated', 'The technician for your repair booking #RB15 has been changed to technician2.', 'repair', 0, '2026-07-28 08:44:47'),
(380, 7, 'Repair Reassigned', 'Repair booking #RB15 has been reassigned to another technician.', 'repair', 0, '2026-07-28 08:44:47'),
(381, 8, 'New Repair Assigned', 'You have been assigned repair booking #RB15.', 'repair', 0, '2026-07-28 08:44:47'),
(382, 12, 'Repair Status Updated', 'Your repair booking #RB15 status was updated to Completed by admin.', 'repair', 0, '2026-07-28 08:44:55'),
(383, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:45:56'),
(384, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:46:00'),
(385, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:46:50'),
(386, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:46:52'),
(387, 5, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 08:46:58'),
(388, 12, 'Repair Status Updated', 'Your repair booking #RB16 status has been updated to Completed.', 'repair', 0, '2026-07-28 08:48:43'),
(389, 5, 'Repair Status Updated', 'Your repair booking #RB10 status has been updated to Assigned.', 'repair', 1, '2026-07-28 08:48:48'),
(390, 5, 'Repair Status Updated', 'Your repair booking #RB10 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-28 08:48:51'),
(391, 5, 'Repair Status Updated', 'Your repair booking #RB10 status was updated to Workshop Repair.', 'repair', 1, '2026-07-28 08:48:57'),
(392, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB10 to Workshop Repair.', 'repair', 1, '2026-07-28 08:48:57'),
(393, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB21.', 'repair', 1, '2026-07-28 08:49:21'),
(394, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB19.', 'repair', 1, '2026-07-28 08:55:11'),
(395, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-28 08:56:24'),
(396, 5, 'Repair Status Updated', 'Your repair booking #RB21 status was updated to In Progress.', 'repair', 1, '2026-07-28 08:57:29'),
(397, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB21 to In Progress.', 'repair', 1, '2026-07-28 08:57:29'),
(398, 5, 'Repair Status Updated', 'Your repair booking #RB21 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-28 08:57:34'),
(399, 5, 'Repair Status Updated', 'Your repair booking #RB21 status has been updated to Completed.', 'repair', 1, '2026-07-28 08:57:43'),
(400, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB22.', 'repair', 1, '2026-07-28 08:57:53'),
(401, 5, 'Repair Status Updated', 'Your repair booking #RB21 status was updated to Workshop Repair.', 'repair', 1, '2026-07-28 08:58:11'),
(402, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB21 to Workshop Repair.', 'repair', 1, '2026-07-28 08:58:11'),
(403, 5, 'Repair Status Updated', 'Your repair booking #RB21 status has been updated to Cancelled.', 'repair', 1, '2026-07-28 08:58:23'),
(404, 5, 'Repair Status Updated', 'Your repair booking #RB22 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-28 08:59:28'),
(405, 5, 'Repair Status Updated', 'Your repair booking #RB22 status has been updated to Completed.', 'repair', 1, '2026-07-28 08:59:38'),
(406, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to In Progress.', 'repair', 1, '2026-07-28 08:59:52'),
(407, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB22 to In Progress.', 'repair', 1, '2026-07-28 08:59:52'),
(408, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-28 09:00:30'),
(409, 5, 'Repair Status Updated', 'Your repair booking #RB22 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-28 09:01:06'),
(410, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Completed.', 'repair', 1, '2026-07-28 09:01:11'),
(411, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB22 to Completed.', 'repair', 1, '2026-07-28 09:01:11'),
(412, 5, 'Repair Status Updated', 'Your repair booking #RB19 status has been updated to Completed.', 'repair', 1, '2026-07-28 09:01:18'),
(413, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB23.', 'repair', 1, '2026-07-28 09:01:27'),
(414, 5, 'Repair Status Updated', 'Your repair booking #RB21 status was updated to In Progress.', 'repair', 1, '2026-07-28 09:01:41'),
(415, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB21 to In Progress.', 'repair', 1, '2026-07-28 09:01:41'),
(416, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 09:02:15'),
(417, 1, 'New Message', 'You received a new message.', 'message', 1, '2026-07-28 09:02:20'),
(418, 5, 'Repair Status Updated', 'Your repair booking #RB23 status has been updated to Completed.', 'repair', 1, '2026-07-28 09:23:31'),
(419, 5, 'Repair Status Updated', 'Your repair booking #RB23 status was updated to Cancelled.', 'repair', 1, '2026-07-28 09:23:43'),
(420, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB23 to Cancelled.', 'repair', 1, '2026-07-28 09:23:43'),
(421, 5, 'Repair Status Updated', 'Your repair booking #RB23 status was updated to Workshop Repair.', 'repair', 1, '2026-07-28 09:27:47'),
(422, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB23 to Workshop Repair.', 'repair', 1, '2026-07-28 09:27:47'),
(423, 5, 'Repair Status Updated', 'Your repair booking #RB23 status has been updated to Cancelled.', 'repair', 1, '2026-07-28 09:27:54'),
(424, 1, 'New User Registered', 'A new user has registered: test user.', 'user', 1, '2026-07-29 12:53:33'),
(425, 1, 'New User Registered', 'A new user has registered: test user.', 'user', 1, '2026-07-29 12:58:16'),
(426, 1, 'New User Registered', 'A new user has registered: test user.', 'user', 1, '2026-07-29 12:59:59'),
(427, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-29 13:24:07'),
(428, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD16. Reason: Prefer not to answer', 'order', 1, '2026-07-29 13:29:42'),
(429, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-29 13:29:56'),
(430, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD16. Reason: Changed my mind', 'order', 1, '2026-07-29 13:30:06'),
(431, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-29 13:30:32'),
(432, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD16. Reason: Ordered by mistake', 'order', 1, '2026-07-29 13:31:41'),
(433, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-29 13:32:08'),
(434, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD16. Reason: Changed my mind', 'order', 1, '2026-07-29 13:36:01'),
(435, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Pending.', 'order', 1, '2026-07-29 13:36:11'),
(436, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD17.', 'order', 1, '2026-07-29 13:41:35'),
(437, 1, 'New Order Placed', 'User #5  wert placed a new order #ORD18.', 'order', 1, '2026-07-29 13:43:38'),
(438, 1, 'Order Cancelled by User', 'User #5 wert cancelled order #ORD18. Reason: Prefer not to answer', 'order', 1, '2026-07-29 13:44:02'),
(439, 5, 'Technician Updated', 'The technician for your repair booking #RB21 has been changed to technician1.', 'repair', 1, '2026-07-29 14:58:32'),
(440, 6, 'Repair Reassigned', 'Repair booking #RB21 has been reassigned to another technician.', 'repair', 1, '2026-07-29 14:58:32'),
(441, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB21.', 'repair', 0, '2026-07-29 14:58:32'),
(442, 5, 'Repair Status Updated', 'Your repair booking #RB21 status was updated to In Progress by admin.', 'repair', 1, '2026-07-29 14:58:35'),
(443, 5, 'Technician Updated', 'The technician for your repair booking #RB21 has been changed to amia.', 'repair', 1, '2026-07-29 14:58:40'),
(444, 7, 'Repair Reassigned', 'Repair booking #RB21 has been reassigned to another technician.', 'repair', 0, '2026-07-29 14:58:40'),
(445, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB21.', 'repair', 1, '2026-07-29 14:58:40'),
(446, 5, 'Repair Status Updated', 'Your repair booking #RB21 status was updated to In Progress by admin.', 'repair', 1, '2026-07-29 14:58:41'),
(447, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-29 15:30:48'),
(448, 5, 'Repair Status Updated', 'Your repair booking #RB1 status was updated to Cancelled by admin.', 'repair', 1, '2026-07-29 15:32:58'),
(449, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-29 15:33:53'),
(450, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-29 15:42:16'),
(451, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-29 15:42:54'),
(452, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-29 15:43:28'),
(453, 5, 'Repair Status Updated', 'Your repair booking #RB21 status has been updated to Workshop Repair.', 'repair', 1, '2026-07-29 15:50:08'),
(454, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Cancelled.', 'order', 1, '2026-07-29 15:51:26'),
(455, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Cancelled.', 'order', 1, '2026-07-29 15:51:26'),
(456, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Processing.', 'order', 1, '2026-07-29 15:51:29'),
(457, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Shipped.', 'order', 1, '2026-07-29 16:53:11'),
(458, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB25.', 'repair', 0, '2026-07-29 17:08:58'),
(459, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Workshop Repair.', 'repair', 0, '2026-07-29 17:09:11'),
(460, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB18 to Workshop Repair.', 'repair', 1, '2026-07-29 17:09:11'),
(461, 15, 'Repair Status Updated', 'Your repair booking #RB18 status was updated to Completed.', 'repair', 0, '2026-07-29 17:09:15'),
(462, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB18 to Completed.', 'repair', 1, '2026-07-29 17:09:15'),
(463, 5, 'Repair Status Updated', 'Your repair booking #RB25 status has been updated to In Progress.', 'repair', 0, '2026-07-30 05:38:16'),
(464, 5, 'Repair Status Updated', 'Your repair booking #RB23 status was updated to Assigned by admin.', 'repair', 0, '2026-07-30 05:40:20'),
(465, 5, 'Technician Updated', 'The technician for your repair booking #RB25 has been changed to technician1.', 'repair', 0, '2026-07-30 05:40:31'),
(466, 6, 'Repair Reassigned', 'Repair booking #RB25 has been reassigned to another technician.', 'repair', 1, '2026-07-30 05:40:31'),
(467, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB25.', 'repair', 0, '2026-07-30 05:40:31'),
(468, 5, 'Technician Updated', 'The technician for your repair booking #RB25 has been changed to amia.', 'repair', 0, '2026-07-30 05:40:52'),
(469, 7, 'Repair Reassigned', 'Repair booking #RB25 has been reassigned to another technician.', 'repair', 0, '2026-07-30 05:40:52'),
(470, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB25.', 'repair', 1, '2026-07-30 05:40:52'),
(471, 5, 'Repair Status Updated', 'Your repair booking #RB25 status was updated to In Progress by admin.', 'repair', 0, '2026-07-30 05:40:54'),
(472, 5, 'Sell Booking Updated', 'Your sell booking #SB25 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 05:41:11'),
(473, 5, 'Sell Booking Updated', 'Your sell booking #SB25 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 05:41:13'),
(474, 5, 'Sell Booking Updated', 'Your sell booking #SB25 status was updated to Completed by admin.', 'sell', 0, '2026-07-30 05:41:15'),
(475, 5, 'Sell Booking Updated', 'Your sell booking #SB25 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 05:41:17'),
(476, 5, 'Technician Updated', 'The technician for your repair booking #RB25 has been changed to technician1.', 'repair', 0, '2026-07-30 05:49:51'),
(477, 6, 'Repair Reassigned', 'Repair booking #RB25 has been reassigned to another technician.', 'repair', 1, '2026-07-30 05:49:51'),
(478, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB25.', 'repair', 0, '2026-07-30 05:49:51'),
(479, 5, 'Technician Updated', 'The technician for your repair booking #RB25 has been changed to amia.', 'repair', 0, '2026-07-30 05:49:55'),
(480, 7, 'Repair Reassigned', 'Repair booking #RB25 has been reassigned to another technician.', 'repair', 0, '2026-07-30 05:49:55'),
(481, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB25.', 'repair', 1, '2026-07-30 05:49:55'),
(482, 5, 'Repair Status Updated', 'Your repair booking #RB25 status was updated to In Progress by admin.', 'repair', 0, '2026-07-30 05:49:58'),
(483, 5, 'Sell Booking Updated', 'Your sell booking #SB25 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 05:50:05'),
(484, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Cancelled by admin.', 'repair', 0, '2026-07-30 05:50:14'),
(485, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Completed by admin.', 'repair', 0, '2026-07-30 05:50:18'),
(486, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Completed by admin.', 'repair', 0, '2026-07-30 05:50:18'),
(487, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Completed by admin.', 'sell', 0, '2026-07-30 05:50:25'),
(488, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking #RB25.', 'repair', 0, '2026-07-30 05:50:46'),
(489, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB25.', 'repair', 1, '2026-07-30 05:50:46'),
(490, 5, 'Technician Assigned', 'Technician technician1 has been assigned to your repair booking #RB13.', 'repair', 0, '2026-07-30 05:50:53'),
(491, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB13.', 'repair', 0, '2026-07-30 05:50:53'),
(492, 5, 'Repair Status Updated', 'Your repair booking #RB25 status has been updated to Assigned.', 'repair', 0, '2026-07-30 05:51:27'),
(493, 5, 'Repair Status Updated', 'Your repair booking #RB25 status has been updated to Assigned.', 'repair', 0, '2026-07-30 05:59:39'),
(494, 5, 'Repair Status Updated', 'Your repair booking #RB25 status was updated to Assigned.', 'repair', 0, '2026-07-30 05:59:45'),
(495, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB25 to Assigned.', 'repair', 1, '2026-07-30 05:59:45'),
(496, 5, 'Repair Status Updated', 'Your repair booking #RB25 status was updated to Assigned.', 'repair', 0, '2026-07-30 06:09:39'),
(497, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB25 to Assigned.', 'repair', 1, '2026-07-30 06:09:39'),
(498, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB24.', 'repair', 0, '2026-07-30 06:15:35'),
(499, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-30 06:19:28'),
(500, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB26.', 'repair', 0, '2026-07-30 06:48:05'),
(501, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-30 06:49:49'),
(502, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB27.', 'repair', 0, '2026-07-30 07:00:57'),
(503, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Workshop Repair.', 'repair', 0, '2026-07-30 07:01:24'),
(504, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB22 to Workshop Repair.', 'repair', 1, '2026-07-30 07:01:24'),
(505, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Completed.', 'repair', 0, '2026-07-30 07:01:28'),
(506, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB22 to Completed.', 'repair', 1, '2026-07-30 07:01:28'),
(507, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to Assigned.', 'repair', 0, '2026-07-30 07:02:57'),
(508, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB27 to Assigned.', 'repair', 1, '2026-07-30 07:02:57'),
(509, 15, 'Sell Booking Updated', 'Your sell booking #SB19 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 11:48:20'),
(510, 1, 'New Sell Request', 'User #5 submitted a new sell request.', 'booking', 1, '2026-07-30 15:36:35'),
(511, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 1, '2026-07-30 15:37:20'),
(512, 1, 'New User Registered', 'A new user has registered: demo.', 'user', 1, '2026-07-30 15:45:28'),
(513, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Cancelled.', 'order', 0, '2026-07-30 15:56:16'),
(514, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Completed.', 'order', 0, '2026-07-30 15:56:23'),
(515, 5, 'Order Status Updated', 'Your order #ORD16 status has been updated to Shipped.', 'order', 0, '2026-07-30 15:56:30'),
(516, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Pending.', 'order', 0, '2026-07-30 15:57:03'),
(517, 5, 'Order Status Updated', 'Your order #ORD18 status has been updated to Processing.', 'order', 0, '2026-07-30 15:58:47'),
(518, 5, 'Technician Assigned', 'Technician amia has been assigned to your repair booking #RB28.', 'repair', 0, '2026-07-30 17:13:51'),
(519, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB28.', 'repair', 1, '2026-07-30 17:13:51'),
(520, 5, 'Repair Status Updated', 'Your repair booking #RB28 status was updated to In Progress by admin.', 'repair', 0, '2026-07-30 17:13:55'),
(521, 5, 'Technician Updated', 'The technician for your repair booking #RB28 has been changed to technician1.', 'repair', 0, '2026-07-30 17:14:52'),
(522, 6, 'Repair Reassigned', 'Repair booking #RB28 has been reassigned to another technician.', 'repair', 1, '2026-07-30 17:14:52'),
(523, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB28.', 'repair', 0, '2026-07-30 17:14:52'),
(524, 5, 'Repair Status Updated', 'Your repair booking #RB28 status was updated to In Progress by admin.', 'repair', 0, '2026-07-30 17:14:56'),
(525, 5, 'Repair Status Updated', 'Your repair booking #RB28 status was updated to Workshop Repair by admin.', 'repair', 0, '2026-07-30 17:14:59'),
(526, 5, 'Sell Booking Updated', 'Your sell booking #SB26 status was updated to Approved by admin.', 'sell', 0, '2026-07-30 17:15:21'),
(527, 5, 'Sell Booking Updated', 'Your sell booking #SB26 status was updated to Completed by admin.', 'sell', 0, '2026-07-30 17:15:25'),
(528, 5, 'Technician Updated', 'The technician for your repair booking #RB22 has been changed to technician1.', 'repair', 0, '2026-07-30 17:15:37'),
(529, 6, 'Repair Reassigned', 'Repair booking #RB22 has been reassigned to another technician.', 'repair', 1, '2026-07-30 17:15:37'),
(530, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB22.', 'repair', 0, '2026-07-30 17:15:37'),
(531, 5, 'Technician Updated', 'The technician for your repair booking #RB22 has been changed to amia.', 'repair', 0, '2026-07-30 17:15:41'),
(532, 7, 'Repair Reassigned', 'Repair booking #RB22 has been reassigned to another technician.', 'repair', 0, '2026-07-30 17:15:41'),
(533, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB22.', 'repair', 1, '2026-07-30 17:15:41'),
(534, 5, 'Repair Status Updated', 'Your repair booking #RB22 status was updated to Completed by admin.', 'repair', 0, '2026-07-30 17:15:45'),
(535, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to Pending by admin.', 'repair', 0, '2026-07-30 17:16:14'),
(536, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to Assigned by admin.', 'repair', 0, '2026-07-30 17:16:30'),
(537, 5, 'Technician Updated', 'The technician for your repair booking #RB27 has been changed to technician1.', 'repair', 0, '2026-07-30 17:16:51'),
(538, 6, 'Repair Reassigned', 'Repair booking #RB27 has been reassigned to another technician.', 'repair', 1, '2026-07-30 17:16:51'),
(539, 7, 'New Repair Assigned', 'You have been assigned repair booking #RB27.', 'repair', 0, '2026-07-30 17:16:51'),
(540, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to In Progress by admin.', 'repair', 0, '2026-07-30 17:16:55'),
(541, 5, 'Technician Updated', 'The technician for your repair booking #RB27 has been changed to amia.', 'repair', 0, '2026-07-30 17:16:59'),
(542, 7, 'Repair Reassigned', 'Repair booking #RB27 has been reassigned to another technician.', 'repair', 0, '2026-07-30 17:16:59'),
(543, 6, 'New Repair Assigned', 'You have been assigned repair booking #RB27.', 'repair', 1, '2026-07-30 17:16:59'),
(544, 5, 'New Message', 'You received a new message.', 'message', 0, '2026-07-30 17:17:09'),
(545, 5, 'New Message', 'You received a new message.', 'message', 0, '2026-07-30 17:17:13'),
(546, 5, 'New Message', 'You received a new message.', 'message', 0, '2026-07-30 17:17:24'),
(547, 5, 'Repair Status Updated', 'Your repair booking #RB27 status has been updated to Assigned.', 'repair', 0, '2026-07-30 17:34:25'),
(548, 5, 'Repair Status Updated', 'Your repair booking #RB27 status has been updated to In Progress.', 'repair', 0, '2026-07-30 17:34:28'),
(549, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to Completed.', 'repair', 0, '2026-07-30 17:34:32'),
(550, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB27 to Completed.', 'repair', 0, '2026-07-30 17:34:32'),
(551, 5, 'Repair Status Updated', 'Your repair booking #RB27 status was updated to Assigned.', 'repair', 0, '2026-07-30 17:34:37'),
(552, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB27 to Assigned.', 'repair', 0, '2026-07-30 17:34:37'),
(553, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 0, '2026-07-30 17:35:29'),
(554, 1, 'New Repair Booking', 'User #5 wert submitted a new repair booking.', 'booking', 0, '2026-07-30 17:36:02'),
(555, 5, 'Repair Status Updated', 'Your repair booking #RB27 status has been updated to In Progress.', 'repair', 0, '2026-07-30 17:37:12'),
(556, 5, 'Repair Status Updated', 'Your repair booking #RB27 status has been updated to Workshop Repair.', 'repair', 0, '2026-07-30 17:37:15'),
(557, 5, 'Repair Status Updated', 'Your repair booking #RB27 status has been updated to Completed.', 'repair', 0, '2026-07-30 17:37:18'),
(558, 5, 'Repair Status Updated', 'Your repair booking #RB26 status was updated to In Progress.', 'repair', 0, '2026-07-30 17:37:28'),
(559, 1, 'Technician Updated Repair Status', 'Technician amia updated repair booking #RB26 to In Progress.', 'repair', 0, '2026-07-30 17:37:28'),
(560, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB30.', 'repair', 0, '2026-07-30 17:37:36'),
(561, 5, 'Technician Accepted Your Repair', 'Technician amia has accepted your repair booking #RB29.', 'repair', 0, '2026-07-30 17:37:44'),
(562, 1, 'New Message', 'You received a new message.', 'message', 0, '2026-07-30 17:37:53'),
(563, 1, 'New Message', 'You received a new message.', 'message', 0, '2026-07-30 17:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cancel_reason` varchar(255) DEFAULT NULL,
  `delivery_method` varchar(50) DEFAULT 'Standard Delivery'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `full_name`, `phone`, `address`, `payment_method`, `subtotal`, `delivery_fee`, `total_amount`, `status`, `created_at`, `cancel_reason`, `delivery_method`) VALUES
(10, 5, 'test10', '0', 'test10', 'Online Banking', 8396.00, 0.00, 8396.00, 'Pending', '2026-06-30 10:15:42', NULL, 'Self Pickup'),
(11, 15, 'Lowbrain', '1234567890', 'qwertyuiop[12345678asdfghjkl;', 'Online Banking', 6398.00, 8.00, 6406.00, 'Cancelled', '2026-07-01 02:28:24', 'Prefer not to answer', 'Standard Delivery'),
(12, 15, 'asdfghj', '1234567890', 'qwerghzsdfgwer13456789\r\n', 'Credit Card', 19794.00, 0.00, 19794.00, 'Completed', '2026-07-01 02:34:17', NULL, 'Self Pickup'),
(13, 5, '676767', '2147483647', '67', 'Credit Card', 5299.00, 0.00, 5299.00, 'Cancelled', '2026-07-04 08:33:23', 'Changed my mind', 'Self Pickup'),
(14, 5, '676767', '2147483647', 'qwertyuiop', 'Online Banking', 2699.00, 0.00, 2699.00, 'Pending', '2026-07-04 08:48:07', NULL, 'Self Pickup'),
(15, 5, '676767', '6767676767', '6767', 'Online Banking', 2699.00, 0.00, 2699.00, 'Pending', '2026-07-04 08:57:35', NULL, 'Self Pickup'),
(16, 5, 'wertyui', '0198765432', 'demo', 'Cash On Delivery', 19094.00, 8.00, 19102.00, 'Cancelled', '2026-07-28 08:08:17', NULL, 'Standard Delivery'),
(17, 5, 'test', '12344234567', 'sdfghjk', 'Cash On Delivery', 19094.00, 8.00, 19102.00, 'Cancelled', '2026-07-29 13:41:35', NULL, 'Standard Delivery'),
(18, 5, 'test', '1234567890', 'demo', 'Cash On Delivery', 19094.00, 8.00, 19102.00, 'Cancelled', '2026-07-29 13:43:38', NULL, 'Standard Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(14, 10, 25, 1, 2699.00),
(15, 10, 20, 1, 2499.00),
(16, 10, 30, 1, 1799.00),
(17, 10, 31, 1, 1399.00),
(18, 11, 33, 1, 4599.00),
(19, 11, 30, 1, 1799.00),
(20, 12, 34, 1, 2099.00),
(21, 12, 30, 1, 1799.00),
(22, 12, 31, 1, 1399.00),
(23, 12, 33, 2, 4599.00),
(24, 12, 1, 1, 5299.00),
(25, 13, 1, 1, 5299.00),
(26, 14, 25, 1, 2699.00),
(27, 15, 25, 1, 2699.00),
(28, 16, 34, 1, 2099.00),
(29, 16, 32, 1, 399.00),
(30, 16, 28, 1, 4999.00),
(31, 16, 23, 1, 2999.00),
(32, 16, 14, 1, 3999.00),
(33, 16, 33, 1, 4599.00),
(34, 17, 34, 1, 2099.00),
(35, 17, 32, 1, 399.00),
(36, 17, 28, 1, 4999.00),
(37, 17, 23, 1, 2999.00),
(38, 17, 14, 1, 3999.00),
(39, 17, 33, 1, 4599.00),
(40, 18, 34, 1, 2099.00),
(41, 18, 32, 1, 399.00),
(42, 18, 28, 1, 4999.00),
(43, 18, 23, 1, 2999.00),
(44, 18, 14, 1, 3999.00),
(45, 18, 33, 1, 4599.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `condition_type` enum('Like New','Good','Fair','Used') DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `badge` varchar(20) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `storage` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `battery_health` varchar(100) DEFAULT NULL,
  `sim` varchar(100) DEFAULT NULL,
  `network` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `brand`, `condition_type`, `price`, `location`, `description`, `stock`, `created_at`, `badge`, `status`, `storage`, `color`, `battery_health`, `sim`, `network`) VALUES
(1, 'iPhone 15 Pro Max 512GB', 'Smartphone', 'Apple', 'Like New', 5299.00, 'Johor Bahru', 'Excellent condition with full accessories included.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '512GB', 'Titanium Black', '98%', 'Dual SIM', '5G / 4G'),
(2, 'Samsung Galaxy S24 Ultra', 'Smartphone', 'Samsung', 'Good', 4699.00, 'Kuala Lumpur', 'Minor scratches but fully functional.', 1, '2026-06-16 07:33:06', '-10%', 'published', '256GB', 'Titanium Gray', '92%', 'Dual SIM', '5G'),
(3, 'ASUS ROG Strix G16', 'Laptop', 'ASUS', 'Like New', 6899.00, 'Penang', 'High performance gaming laptop.', 1, '2026-06-16 07:33:06', 'HOT', 'published', '1TB SSD', 'Eclipse Gray', 'Excellent', 'N/A', 'WiFi 6'),
(4, 'MacBook Air M2', 'Laptop', 'Apple', 'Good', 4199.00, 'Selangor', 'Lightweight and powerful for students.', 1, '2026-06-16 07:33:06', '', 'published', '512GB SSD', 'Silver', 'Excellent', 'N/A', 'WiFi 6'),
(5, 'PlayStation 5 Disc Edition', 'Gaming', 'Sony', 'Like New', 2299.00, 'Johor', 'Smooth gaming experience with controller included.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '825GB', 'White', 'Excellent', 'N/A', 'WiFi'),
(6, 'Nintendo Switch OLED', 'Gaming', 'Nintendo', 'Good', 1399.00, 'Melaka', 'Portable gaming console with OLED display.', 1, '2026-06-16 07:33:06', '-5%', 'published', '64GB', 'White', 'Good', 'N/A', 'WiFi'),
(7, 'iPad Pro M2 11-inch', 'Tablet', 'Apple', 'Like New', 3599.00, 'Kuala Lumpur', 'Perfect for drawing and productivity.', 1, '2026-06-16 07:33:06', 'HOT', 'published', '256GB', 'Space Gray', '97%', 'eSIM', '5G / WiFi'),
(8, 'Huawei MatePad Pro', 'Tablet', 'Huawei', 'Fair', 1599.00, 'Penang', 'Visible wear but works properly.', 1, '2026-06-16 07:33:06', '-18%', 'published', '128GB', 'Black', '85%', 'Single SIM', 'WiFi'),
(9, 'Dell XPS 15', 'Laptop', 'Dell', 'Good', 3899.00, 'Johor Bahru', 'Premium ultrabook with OLED display.', 1, '2026-06-16 07:33:06', '', 'published', '1TB SSD', 'Silver', 'Excellent', 'N/A', 'WiFi 6'),
(10, 'Lenovo Legion 5', 'Laptop', 'Lenovo', 'Used', 3199.00, 'Ipoh', 'Gaming laptop with RTX graphics.', 1, '2026-06-16 07:33:06', '', 'published', '512GB SSD', 'Black', '80%', 'N/A', 'WiFi 6'),
(11, 'AirPods Pro 2', 'Accessories', 'Apple', 'Like New', 899.00, 'Klang', 'Noise cancelling wireless earbuds.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '256GB', 'White', '95%', 'N/A', 'Bluetooth 5.3'),
(12, 'Sony WH-1000XM5', 'Accessories', 'Sony', 'Good', 1199.00, 'Johor', 'Premium wireless headphones.', 1, '2026-06-16 07:33:06', 'HOT', 'published', 'N/A', 'Black', 'Excellent', 'N/A', 'Bluetooth 5.2'),
(13, 'MSI Katana GF66', 'Laptop', 'MSI', 'Fair', 2799.00, 'Selangor', 'Gaming laptop with decent performance.', 1, '2026-06-16 07:33:06', '-15%', 'published', '512GB SSD', 'Black', '78%', 'N/A', 'WiFi 6'),
(14, 'Google Pixel 8 Pro', 'Smartphone', 'Google', 'Like New', 3999.00, 'Kuala Lumpur', 'Clean Android experience.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '256GB', 'Blue', '96%', 'Dual SIM', '5G'),
(15, 'Apple Watch Series 9', 'Accessories', 'Apple', 'Good', 1499.00, 'Johor', 'Fitness tracking and notifications.', 1, '2026-06-16 07:33:06', '', 'published', '64GB', 'Midnight', '93%', 'eSIM', 'Bluetooth / WiFi'),
(16, 'Samsung Galaxy Tab S9', 'Tablet', 'Samsung', 'Good', 2599.00, 'Penang', 'Powerful Android tablet.', 1, '2026-06-16 07:33:06', '-8%', 'published', '256GB', 'Gray', '91%', 'Single SIM', '5G'),
(17, 'Razer Blade 15', 'Laptop', 'Razer', 'Like New', 7299.00, 'Kuala Lumpur', 'Premium gaming laptop.', 1, '2026-06-16 07:33:06', 'HOT', 'published', '1TB SSD', 'Matte Black', 'Excellent', 'N/A', 'WiFi 6'),
(18, 'Steam Deck', 'Gaming', 'Valve', 'Good', 1999.00, 'Johor', 'Portable PC gaming device.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '512GB', 'Black', '89%', 'N/A', 'WiFi'),
(19, 'Canon EOS M50', 'Camera', 'Canon', 'Fair', 1899.00, 'Melaka', 'Mirrorless camera for photography beginners.', 1, '2026-06-16 07:33:06', '-12%', 'published', '128GB SD', 'Black', '82%', 'N/A', 'WiFi / Bluetooth'),
(20, 'DJI Mini 3', 'Camera', 'DJI', 'Like New', 2499.00, 'Kuala Lumpur', 'Compact drone with 4K camera.', 1, '2026-06-16 07:33:06', '', 'published', '128GB', 'Gray', '97%', 'N/A', 'WiFi'),
(21, 'Bose QuietComfort 45', 'Accessories', 'Bose', 'Like New', 1099.00, 'Penang', 'Comfortable noise cancelling headphones.', 1, '2026-06-16 07:33:06', 'NEW', 'published', 'N/A', 'White', 'Excellent', 'N/A', 'Bluetooth'),
(22, 'Acer Nitro 5', 'Laptop', 'Acer', 'Used', 2399.00, 'Johor Bahru', 'Affordable gaming laptop.', 1, '2026-06-16 07:33:06', '', 'published', '512GB SSD', 'Red Black', '75%', 'N/A', 'WiFi 5'),
(23, 'OnePlus 12', 'Smartphone', 'OnePlus', 'Good', 2999.00, 'Selangor', 'Fast and smooth Android flagship.', 1, '2026-06-16 07:33:06', 'HOT', 'published', '256GB', 'Green', '94%', 'Dual SIM', '5G'),
(24, 'Meta Quest 3', 'Gaming', 'Meta', 'Like New', 2299.00, 'Kuala Lumpur', 'Immersive VR gaming experience.', 1, '2026-06-16 07:33:06', 'NEW', 'published', '128GB', 'White', '98%', 'N/A', 'WiFi 6'),
(25, 'Nothing Phone 2', 'Smartphone', 'Nothing', 'Like New', 2699.00, 'Johor Bahru', 'Transparent design with smooth performance.', 1, '2026-06-16 07:42:47', 'NEW', 'published', '256GB', 'White', '97%', 'Dual SIM', '5G'),
(26, 'HP Victus 15', 'Laptop', 'HP', 'Good', 3299.00, 'Kuala Lumpur', 'Gaming laptop suitable for students.', 1, '2026-06-16 07:42:47', '-7%', 'published', '512GB SSD', 'Mica Silver', '88%', 'N/A', 'WiFi 6'),
(27, 'Logitech G Pro X', 'Accessories', 'Logitech', 'Good', 499.00, 'Penang', 'Professional gaming headset.', 1, '2026-06-16 07:42:47', 'HOT', 'published', 'N/A', 'Black', 'Excellent', 'N/A', 'Bluetooth'),
(28, 'Apple iMac 24-inch', 'Laptop', 'Apple', 'Like New', 4999.00, 'Selangor', 'Beautiful all-in-one desktop.', 1, '2026-06-16 07:42:47', 'NEW', 'published', '512GB SSD', 'Blue', '99%', 'N/A', 'WiFi 6'),
(29, 'Samsung Odyssey G7', 'Laptop', 'Samsung', 'Good', 1899.00, 'Johor', '240Hz curved gaming monitor.', 1, '2026-06-16 07:42:47', '', 'published', 'N/A', 'Black', 'Excellent', 'N/A', 'HDMI / DisplayPort'),
(30, 'GoPro Hero 12', 'Camera', 'GoPro', 'Like New', 1799.00, 'Melaka', 'Action camera with stabilization.', 1, '2026-06-16 07:42:47', 'NEW', 'published', '128GB', 'Black', '96%', 'N/A', 'WiFi / Bluetooth'),
(31, 'Xiaomi Pad 6', 'Tablet', 'Xiaomi', 'Good', 1399.00, 'Kuala Lumpur', 'Affordable Android tablet.', 1, '2026-06-16 07:42:47', '-5%', 'published', '128GB', 'Gray', '90%', 'Single SIM', 'WiFi'),
(32, 'Corsair K70 RGB', 'Accessories', 'Corsair', 'Used', 399.00, 'Ipoh', 'Mechanical gaming keyboard.', 1, '2026-06-16 07:42:47', '', 'published', 'N/A', 'Black', '78%', 'N/A', 'USB'),
(33, 'Surface Laptop 5', 'Laptop', 'Microsoft', 'Like New', 4599.00, 'Penang', 'Slim laptop for productivity.', 1, '2026-06-16 07:42:47', 'HOT', 'published', '512GB SSD', 'Platinum', '95%', 'N/A', 'WiFi 6'),
(34, 'Insta360 X3', 'Camera', 'Insta360', 'Fair', 2099.00, 'Johor Bahru', '360-degree action camera.', 3, '2026-06-16 07:42:47', '', 'published', '128GB', 'Black', '92%', 'N/A', 'WiFi / Bluetooth');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_path`) VALUES
(13, 34, 'assets/images/products/1783698581_images.jpg'),
(14, 34, 'assets/images/products/1783698593_images (1).jpg'),
(16, 34, 'assets/images/products/1783698614_images (3).jpg'),
(17, 34, 'assets/images/products/1783698625_images (2).jpg'),
(19, 34, 'assets/images/products/1783698756_1783698602_images (4).jpg'),
(20, 33, 'assets/images/products/1783698891_images.jpg'),
(21, 33, 'assets/images/products/1783698900_images (2).jpg'),
(22, 33, 'assets/images/products/1783698907_images (1).jpg'),
(23, 33, 'assets/images/products/1783698914_images (3).jpg'),
(24, 32, 'assets/images/products/1783699017_images (4).jpg'),
(25, 32, 'assets/images/products/1783699024_images (5).jpg'),
(26, 32, 'assets/images/products/1783699030_images (6).jpg'),
(27, 31, 'assets/images/products/1783699154_images (8).jpg'),
(28, 31, 'assets/images/products/1783699167_images (9).jpg'),
(29, 31, 'assets/images/products/1783699221_images (11).jpg'),
(30, 31, 'assets/images/products/1783699233_images (10).jpg'),
(31, 30, 'assets/images/products/1783699410_images.jpg'),
(32, 30, 'assets/images/products/1783699417_images (4).jpg'),
(33, 30, 'assets/images/products/1783699423_images (1).jpg'),
(34, 30, 'assets/images/products/1783699437_images (3).jpg'),
(35, 30, 'assets/images/products/1783699445_images (2).jpg'),
(36, 29, 'assets/images/products/1783699530_images (5).jpg'),
(37, 29, 'assets/images/products/1783699535_images (6).jpg'),
(38, 28, 'assets/images/products/1783699645_images (7).jpg'),
(39, 28, 'assets/images/products/1783699657_images (8).jpg'),
(40, 28, 'assets/images/products/1783699663_images (9).jpg'),
(41, 28, 'assets/images/products/1783699667_images (10).jpg'),
(42, 27, 'assets/images/products/1783701896_images (11).jpg'),
(43, 27, 'assets/images/products/1783701896_images (12).jpg'),
(44, 27, 'assets/images/products/1783701896_images (13).jpg'),
(45, 26, 'assets/images/products/1783702042_images (14).jpg'),
(46, 26, 'assets/images/products/1783702042_images (15).jpg'),
(47, 26, 'assets/images/products/1783702042_images (17).jpg'),
(48, 25, 'assets/images/products/1783702252_images (2).jpg'),
(49, 25, 'assets/images/products/1783702265_images (1).jpg'),
(50, 25, 'assets/images/products/1783702265_images (3).jpg'),
(51, 25, 'assets/images/products/1783702270_images.jpg'),
(52, 25, 'assets/images/products/1783702297_images (4).jpg'),
(53, 24, 'assets/images/products/1783702441_images (5).jpg'),
(54, 24, 'assets/images/products/1783702457_images (6).jpg'),
(55, 24, 'assets/images/products/1783702457_images (7).jpg'),
(56, 24, 'assets/images/products/1783702457_images (8).jpg'),
(57, 23, 'assets/images/products/1783702560_images (9).jpg'),
(58, 23, 'assets/images/products/1783702610_images (10).jpg'),
(59, 23, 'assets/images/products/1783702610_images (12).jpg'),
(60, 23, 'assets/images/products/1783702610_images (14).jpg'),
(61, 23, 'assets/images/products/1783702610_images (13).jpg'),
(62, 22, 'assets/images/products/1783702691_images (15).jpg'),
(63, 22, 'assets/images/products/1783702691_images (16).jpg'),
(64, 22, 'assets/images/products/1783702691_images (17).jpg'),
(65, 22, 'assets/images/products/1783702691_images (18).jpg'),
(66, 22, 'assets/images/products/1783702691_images (19).jpg'),
(67, 21, 'assets/images/products/1783703965_images (20).jpg'),
(68, 21, 'assets/images/products/1783703965_images (21).jpg'),
(69, 21, 'assets/images/products/1783703965_images (22).jpg'),
(70, 21, 'assets/images/products/1783703965_images (23).jpg'),
(71, 21, 'assets/images/products/1783703965_images (24).jpg'),
(72, 20, 'assets/images/products/1783704054_images (25).jpg'),
(73, 20, 'assets/images/products/1783704054_images (26).jpg'),
(74, 20, 'assets/images/products/1783704054_images (27).jpg'),
(75, 20, 'assets/images/products/1783704054_images (28).jpg'),
(76, 20, 'assets/images/products/1783704054_images (29).jpg'),
(77, 19, 'assets/images/products/1783704154_images (30).jpg'),
(78, 19, 'assets/images/products/1783704154_images (31).jpg'),
(79, 19, 'assets/images/products/1783704154_images (32).jpg'),
(80, 19, 'assets/images/products/1783704154_images (33).jpg'),
(81, 18, 'assets/images/products/1783704792_images (34).jpg'),
(82, 18, 'assets/images/products/1783704792_images (35).jpg'),
(83, 18, 'assets/images/products/1783704792_images (37).jpg'),
(84, 18, 'assets/images/products/1783704800_images (36).jpg'),
(85, 17, 'assets/images/products/1783705197_images (38).jpg'),
(86, 17, 'assets/images/products/1783705197_images (39).jpg'),
(87, 17, 'assets/images/products/1783705197_images (40).jpg'),
(88, 16, 'assets/images/products/1783705290_images (41).jpg'),
(89, 16, 'assets/images/products/1783705290_images (42).jpg'),
(90, 16, 'assets/images/products/1783705290_images (43).jpg'),
(91, 16, 'assets/images/products/1783705290_images (44).jpg'),
(92, 15, 'assets/images/products/1783705374_images (45).jpg'),
(93, 15, 'assets/images/products/1783705374_images (46).jpg'),
(94, 15, 'assets/images/products/1783705391_images (47).jpg'),
(95, 14, 'assets/images/products/1783705577_images (48).jpg'),
(96, 14, 'assets/images/products/1783705577_images (49).jpg'),
(97, 14, 'assets/images/products/1783705590_images (50).jpg'),
(98, 13, 'assets/images/products/1783705688_images (51).jpg'),
(99, 13, 'assets/images/products/1783705688_images (52).jpg'),
(100, 13, 'assets/images/products/1783705688_images (53).jpg'),
(101, 13, 'assets/images/products/1783705688_images (54).jpg'),
(102, 12, 'assets/images/products/1783705792_images (55).jpg'),
(103, 12, 'assets/images/products/1783705792_images (56).jpg'),
(104, 12, 'assets/images/products/1783705792_images (58).jpg'),
(105, 12, 'assets/images/products/1783705799_images (57).jpg'),
(106, 11, 'assets/images/products/1783705954_images (59).jpg'),
(107, 11, 'assets/images/products/1783705954_images (61).jpg'),
(109, 11, 'assets/images/products/1783705980_images (60).jpg'),
(110, 11, 'assets/images/products/1783705980_images (62).jpg'),
(111, 11, 'assets/images/products/1783705980_images (63).jpg'),
(112, 10, 'assets/images/products/1783706083_images (64).jpg'),
(113, 10, 'assets/images/products/1783706083_images (65).jpg'),
(114, 10, 'assets/images/products/1783706083_images (66).jpg'),
(115, 10, 'assets/images/products/1783706083_images (67).jpg'),
(116, 9, 'assets/images/products/1783706151_images (68).jpg'),
(117, 9, 'assets/images/products/1783706151_images (69).jpg'),
(118, 9, 'assets/images/products/1783706151_images (70).jpg'),
(119, 9, 'assets/images/products/1783706151_images (71).jpg'),
(120, 8, 'assets/images/products/1783706246_images (72).jpg'),
(121, 8, 'assets/images/products/1783706246_images (73).jpg'),
(122, 8, 'assets/images/products/1783706246_images (74).jpg'),
(123, 7, 'assets/images/products/1783706352_images (75).jpg'),
(124, 7, 'assets/images/products/1783706352_images (76).jpg'),
(125, 7, 'assets/images/products/1783706352_images (77).jpg'),
(126, 7, 'assets/images/products/1783706352_images (78).jpg'),
(128, 7, 'assets/images/products/1783706352_images (80).jpg'),
(129, 7, 'assets/images/products/1783706367_images (79).jpg'),
(130, 6, 'assets/images/products/1783706504_images.jpg'),
(131, 6, 'assets/images/products/1783706522_images (3).jpg'),
(132, 6, 'assets/images/products/1783706527_images (1).jpg'),
(133, 6, 'assets/images/products/1783706542_images (2).jpg'),
(134, 6, 'assets/images/products/1783706542_images (4).jpg'),
(135, 5, 'assets/images/products/1783706624_images (7).jpg'),
(136, 5, 'assets/images/products/1783706633_images (5).jpg'),
(137, 5, 'assets/images/products/1783706644_images (8).jpg'),
(138, 5, 'assets/images/products/1783706644_images (9).jpg'),
(139, 4, 'assets/images/products/1783706718_images (10).jpg'),
(140, 4, 'assets/images/products/1783706733_images (11).jpg'),
(141, 4, 'assets/images/products/1783706733_images (12).jpg'),
(142, 3, 'assets/images/products/1783706896_images (13).jpg'),
(143, 3, 'assets/images/products/1783706896_images (14).jpg'),
(144, 3, 'assets/images/products/1783706896_images (15).jpg'),
(145, 3, 'assets/images/products/1783706896_images (16).jpg'),
(146, 3, 'assets/images/products/1783706896_images (17).jpg'),
(147, 3, 'assets/images/products/1783706896_images (18).jpg'),
(148, 2, 'assets/images/products/1783706987_images (19).jpg'),
(149, 2, 'assets/images/products/1783706987_images (20).jpg'),
(150, 1, 'assets/images/products/1783707072_images (21).jpg'),
(151, 1, 'assets/images/products/1783707072_images (22).jpg'),
(152, 1, 'assets/images/products/1783707072_images (23).jpg'),
(153, 1, 'assets/images/products/1783707072_images (24).jpg'),
(154, 1, 'assets/images/products/1783707072_images (25).jpg'),
(155, 1, 'assets/images/products/1783707072_images (26).jpg'),
(160, 71, 'assets/images/products/1785227796_card_after_training (18).png'),
(161, 71, 'assets/images/products/1785227796_card_after_training.png');

-- --------------------------------------------------------

--
-- Table structure for table `register_otp`
--

CREATE TABLE `register_otp` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repair_bookings`
--

CREATE TABLE `repair_bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `technician_id` int(11) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `repair_type` varchar(100) DEFAULT NULL,
  `issue_description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `status` enum('Pending','Assigned','In Progress','Workshop Repair','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_bookings`
--

INSERT INTO `repair_bookings` (`booking_id`, `user_id`, `technician_id`, `device_type`, `repair_type`, `issue_description`, `address`, `preferred_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 7, 'Smartphone', NULL, 'dadcxcaasdsxs', 'aszxcdsfwdfscvsddsfd', '2026-06-17', 'Cancelled', '2026-06-07 18:04:30', '2026-07-29 15:32:58'),
(6, 5, 6, 'Laptop', 'Battery Replacement', 'sfgsdvxz', 'ddcvfcdxvd', '2026-06-10', 'Completed', '2026-06-09 04:54:29', '2026-07-03 16:07:14'),
(7, 5, 6, 'Tablet', 'Hardware Repair', '6767676767677677676767676767', '676766767777', '2026-06-23', 'Completed', '2026-06-09 06:34:56', '2026-07-03 16:07:14'),
(8, 5, NULL, 'Accessories', 'Battery Replacement', 'qwertyuiop[]', '676766767777', '2026-06-10', 'Completed', '2026-06-09 06:48:55', '2026-05-04 16:07:14'),
(9, 5, 6, 'Camera', 'Screen Repair', '6767676767676767', '676767676767', '2026-06-03', 'Completed', '2026-06-09 06:57:45', '2026-07-03 16:07:14'),
(10, 5, 6, 'Smartphone', 'Water Damage', 'test', 'test', '2026-06-10', 'Workshop Repair', '2026-06-11 05:20:09', '2026-07-28 08:48:51'),
(11, 5, 6, 'Accessories', 'Screen Repair', 'test1', 'test1', '2026-06-11', 'Completed', '2026-06-11 06:04:33', '2026-07-12 14:16:12'),
(12, 5, 6, 'Tablet', 'Water Damage', 'test2', 'test2', '2026-06-09', 'Completed', '2026-06-11 07:06:23', '2026-07-03 16:07:14'),
(13, 5, 7, 'Tablet', 'Software Issue', 'test3', 'test3', '2026-06-20', 'Assigned', '2026-06-11 08:06:13', '2026-07-03 16:36:40'),
(14, 12, NULL, 'Smartphone', 'Battery Replacement', 'test5', 'test5', '2026-06-16', 'Completed', '2026-06-11 11:17:01', '2026-07-03 16:07:14'),
(15, 12, 8, 'Smartphone', 'Screen Repair', 'test5', 'test5', '2026-06-17', 'Completed', '2026-06-11 11:19:01', '2026-07-28 08:44:55'),
(16, 12, 6, 'Smartphone', 'Screen Repair', 'test6', 'test6', '2026-06-19', 'Completed', '2026-06-11 11:43:03', '2026-07-28 08:48:43'),
(17, 5, NULL, 'Tablet', 'Screen Repair', 'test9', 'test9', '2026-07-03', 'Completed', '2026-06-30 10:05:15', '2026-07-04 12:18:24'),
(18, 15, 6, 'Smartphone', 'Hardware Repair', 'alibaba', 'sdfghjkwertyui', '2026-06-29', 'Completed', '2026-06-01 02:37:30', '2026-07-29 17:09:15'),
(19, 5, 6, 'Laptop', 'Screen Repair', 'test11', 'test11', '2026-07-16', 'Completed', '2026-07-01 03:05:36', '2026-07-28 09:01:18'),
(20, 5, 7, 'Tablet', 'Other', '666666666666666', 'wdefsddsdfs', '2026-07-06', 'Workshop Repair', '2026-07-05 05:56:42', '2026-07-28 08:43:22'),
(21, 5, 6, 'Laptop', 'Battery Replacement', 'demo', 'demo', '2026-07-30', 'Workshop Repair', '2026-07-28 08:11:20', '2026-07-29 15:50:08'),
(22, 5, 6, 'Smartphone', '', 'test1', 'test1', '2026-07-30', 'Completed', '2026-07-28 08:56:24', '2026-07-30 17:15:45'),
(23, 5, 6, 'Tablet', 'Water Damage', 'test', 'test', '2026-07-31', 'Assigned', '2026-07-28 09:00:30', '2026-07-30 05:40:20'),
(24, 5, 6, 'Laptop', 'Battery Replacement', 'test', 'test', '2026-07-31', 'Assigned', '2026-07-29 15:42:54', '2026-07-30 06:15:35'),
(25, 5, 6, 'Laptop', 'Battery Replacement', 'test', 'test', '2026-07-31', 'Assigned', '2026-07-29 15:43:28', '2026-07-30 05:50:46'),
(26, 5, 6, 'Tablet', 'Water Damage', '499684', 'test2', '2026-07-31', 'In Progress', '2026-07-30 06:19:28', '2026-07-30 17:37:28'),
(27, 5, 6, 'Accessories', 'Software Issue', '809687', 'test5', '2026-07-23', 'Completed', '2026-07-30 06:49:49', '2026-07-30 17:37:18'),
(28, 5, 7, 'Smartphone', 'Screen Repair', 'demo', 'demo', '2026-07-31', 'Workshop Repair', '2026-07-30 15:37:20', '2026-07-30 17:14:59'),
(29, 5, 6, 'Laptop', 'Water Damage', 'demo', 'demo', '2026-08-01', 'Assigned', '2026-07-30 17:35:29', '2026-07-30 17:37:44'),
(30, 5, 6, 'Gaming', 'Hardware Repair', 'demo', 'demo', '2026-08-02', 'Assigned', '2026-07-30 17:36:02', '2026-07-30 17:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `sell_requests`
--

CREATE TABLE `sell_requests` (
  `sell_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `condition_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sell_requests`
--

INSERT INTO `sell_requests` (`sell_id`, `user_id`, `device_type`, `brand`, `model`, `condition_type`, `description`, `address`, `preferred_date`, `status`, `created_at`) VALUES
(2, 5, 'Smartphone', 'Apple', 'iPhone 17 Pro 128GB', 'Like New', 'fsdffsefsdfsdf', 'wdefsddsdfs', '2026-06-11', 'Rejected', '2026-06-09 04:53:24'),
(3, 5, 'Laptop', 'Apple', 'iPhone 14 Pro 128GB', 'Like New', 'asdfghjk', 'ddcvfcdxvd', '2026-06-09', 'Completed', '2026-06-09 06:34:16'),
(4, 5, 'Gaming', 'Apple', 'asfsd', 'Like New', 'qwertyuiop[', 'qwertyuiop[', '2026-06-10', 'Rejected', '2026-06-09 06:48:33'),
(5, 5, 'Camera', 'Apple', '67676767', 'Like New', '76767676767676', '6767676767667', '2026-06-06', 'Rejected', '2026-06-09 06:56:33'),
(6, 5, 'Laptop', 'sdfghjkl;', '57575', 'Used', '5757575757575', '57575757575', '2026-06-12', 'Pending', '2026-06-10 01:52:06'),
(7, 5, 'Smartphone', 'test1', 'test1', 'Like New', 'test1', 'test1', '2026-06-11', 'Pending', '2026-06-11 06:03:53'),
(8, 5, 'Laptop', 'test4', 'test4', 'Used', 'test4', 'test4', '2026-06-19', 'Approved', '2026-06-11 10:29:18'),
(9, 12, 'Laptop', 'test5', 'test5', 'Like New', 'test5', 'test5', '2026-06-11', 'Pending', '2026-06-11 11:16:41'),
(10, 12, 'Accessories', 'test5', 'test5', 'Like New', 'test5', 'test5', '2026-06-11', 'Pending', '2026-06-11 11:18:25'),
(11, 12, 'Smartphone', 'test5', 'test5', 'Like New', 'test5', 'test5', '2026-06-11', 'Pending', '2026-06-11 11:30:14'),
(12, 12, 'Smartphone', 'test5', 'test5', 'Like New', 'test5', 'test5', '2026-06-11', 'Pending', '2026-06-11 11:30:57'),
(13, 12, 'Accessories', 'test5', 'test5', 'Like New', 'test5', 'test5', '2026-06-12', 'Pending', '2026-06-11 11:31:09'),
(14, 12, 'Smartphone', 'test6', 'test6', 'Used', 'test6', 'test6', '2026-06-11', 'Pending', '2026-06-11 11:38:19'),
(15, 12, 'Tablet', 'test6', 'test6', 'Fair', 'test6', 'test6', '2026-06-11', 'Pending', '2026-06-11 14:57:56'),
(16, 5, 'Gaming', 'test5', 'test5', 'Good', 'test5', 'test5', '2026-07-01', 'Pending', '2026-06-30 09:02:52'),
(17, 5, 'Tablet', 'test7', 'test7', 'Like New', 'test7', 'test7', '2026-07-02', 'Pending', '2026-06-30 09:06:11'),
(18, 5, 'Smartphone', 'test8', 'test8', 'Fair', 'test8', 'test8', '2026-07-11', 'Completed', '2026-06-30 09:54:40'),
(19, 15, 'Smartphone', 'samsung', 'S24', 'Like New', '会尽快理查德归还借款后排空间和拜纳姆，的风格和健康食物都发噶话尽快八块腹肌你收到v', 'ADFGNJKJHGFDSDTFYGUJGHF', '2026-07-02', 'Approved', '2026-07-01 02:45:40'),
(20, 5, 'Smartphone', '6767676767', 'iPhone 14 Pro 128GB', 'Like New', 'we\'r\'t\'y', 'wdefsddsdfs', '2026-07-25', 'Pending', '2026-07-05 06:04:14'),
(21, 5, 'Laptop', 'Apple', 'asfsd', 'Fair', 's\'s\'s', 'qwertyuiop[', '2026-07-24', 'Pending', '2026-07-05 06:04:51'),
(22, 5, 'Smartphone', 'demo', 'demo', 'Good', 'demo', 'demo', '2026-07-30', 'Pending', '2026-07-28 08:10:29'),
(23, 5, 'Laptop', 'test', 'test', 'Like New', 'test', 'test', '2026-07-31', 'Pending', '2026-07-29 15:30:48'),
(24, 5, 'Laptop', 'test', 'test', 'Like New', 'test', 'test', '2026-07-31', 'Pending', '2026-07-29 15:33:53'),
(25, 5, 'Tablet', 'test', 'test', 'Like New', 'test', 'test', '2026-07-30', 'Approved', '2026-07-29 15:42:16'),
(26, 5, 'Smartphone', 'demo', 'demo', 'Like New', 'demo', 'demo', '2026-07-31', 'Completed', '2026-07-30 15:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','technician') DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `otp_verified` tinyint(1) DEFAULT 0,
  `login_otp` varchar(6) DEFAULT NULL,
  `login_otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `password`, `role`, `profile_image`, `created_at`, `otp_code`, `otp_expiry`, `otp_verified`, `login_otp`, `login_otp_expiry`) VALUES
(1, 'admin', 'ferpwz@hi2.in', '012-3456789', '$2y$10$.tKGFOMCE1nXSfO8jphVKOJpfLJNsVc8pdpRW5bETZxa0hLqxFeXq', 'admin', 'uploads/profile/1780745773_mzk.jpg', '2026-06-04 04:08:39', NULL, NULL, 0, NULL, NULL),
(5, 'wert', 'onszhlyu@hi2.in', '0102345678', '$2y$10$Z0EXeKop9JrzjK99shJcmuhZC6h63npBp4YGbm55YsByNkyEaq./q', 'user', 'uploads/profile/1785226475_card_after_training (18).png', '2026-06-04 08:25:39', '327741', '2026-07-21 18:27:34', 0, NULL, NULL),
(6, 'amia', 'yiomct@hi2.in', '019-8765432', '$2y$10$Me45SSpP92X4j3QnS7x3aeFfr3dyVhlixZkhYIrH3C8TrzYCifuaC', 'technician', 'uploads/profile/1780934170_mzk1.jpg.png', '2026-06-08 14:59:44', NULL, NULL, 0, NULL, NULL),
(7, 'technician1', 'dpzgozl@hi2.in', '013-2456789', '$2y$10$m0qfSk.YxbCUrE0ZJhINBucC0iDxqOKr6vr9H6qE/g2oXOw9OFI2K', 'technician', NULL, '2026-06-10 15:20:52', NULL, NULL, 0, '525254', '2026-07-22 09:57:44'),
(8, 'technician2', 'leiyaazd@hi2.in', '014-2356789', '$2y$10$fUrRrQJfvWFgdk7GT4Eps.ITNSyqVlivt4ydAkToN/brHkYFAH8l6', 'technician', NULL, '2026-06-10 15:22:45', NULL, NULL, 0, NULL, NULL),
(9, 'user1', 'u4565306@gmail.com', '018-7654321', '$2y$10$oV.2jYobhqPJgrrpBwNMa.iuGyB1RDJGPDODJjq5fnJk4NFlZTR82', 'user', NULL, '2026-06-10 15:27:49', NULL, NULL, 0, NULL, NULL),
(10, 'user2', 'myffiz@hi2.in', '0179865432', '$2y$10$Nu/BNA756ySkL9gcB2WhMeup2iJ8oD4WLvc2v0kdQExG4K9mFy0Oq', 'user', NULL, '2026-06-10 15:28:19', NULL, NULL, 0, NULL, NULL),
(11, 'user3', 'sjdhef@hi2.in', '016-9875432', '$2y$10$R8BhZN10nl3vq63w47qBrOBoV28WFyNr/yhlhm4ed/AG2kV8sujRK', 'user', NULL, '2026-06-12 02:09:28', NULL, NULL, 0, NULL, NULL),
(12, 'user4', 'yuofep@hi2.in', '015-9876432', '$2y$10$25xV9lBc.pfC.Uo3Ebr0LupWOnTHnQ2nDGmvw5hljTzA1AVsztxuO', 'user', NULL, '2026-06-12 02:10:02', NULL, NULL, 0, NULL, NULL),
(17, 'user5', 'lgyrszj@hi2.in', '0176543210', '$2y$10$3v9k9FJhauiWmDb86xPSqej.1lmIfuu4H79KIicG..GkI3Ur5vHgW', 'user', NULL, '2026-07-22 06:06:41', NULL, NULL, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_images`
--
ALTER TABLE `booking_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `register_otp`
--
ALTER TABLE `register_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repair_bookings`
--
ALTER TABLE `repair_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `sell_requests`
--
ALTER TABLE `sell_requests`
  ADD PRIMARY KEY (`sell_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_images`
--
ALTER TABLE `booking_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=564;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `register_otp`
--
ALTER TABLE `register_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repair_bookings`
--
ALTER TABLE `repair_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `sell_requests`
--
ALTER TABLE `sell_requests`
  MODIFY `sell_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
