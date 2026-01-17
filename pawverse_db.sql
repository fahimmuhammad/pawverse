-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2026 at 12:04 PM
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
-- Database: `pawverse_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(78, 1, 'Cleared all activity logs', 'IP: 127.0.0.1', '2025-10-25 00:45:19'),
(79, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 00:45:25'),
(80, 9, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 00:45:32'),
(81, 9, 'Message marked read', 'Msg#9 — Query about online training — set is_read=1', '2025-10-25 00:52:43'),
(82, 9, 'Changed message status', 'Message #9 marked unread', '2025-10-25 01:10:03'),
(83, 9, 'Changed message status', 'Message #9 marked read', '2025-10-25 01:10:04'),
(84, 9, 'Deleted message', 'Message #8 deleted', '2025-10-25 01:10:14'),
(85, 9, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 01:13:49'),
(86, 9, 'Changed message status', 'Message #7 marked read', '2025-10-25 01:14:00'),
(87, 9, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 01:15:50'),
(88, 9, 'Changed message status', 'Message #9 marked unread', '2025-10-25 01:18:02'),
(89, 9, 'Changed message status', 'Message #7 marked unread', '2025-10-25 01:18:03'),
(90, 9, 'Changed message status', 'Message #10 marked read', '2025-10-25 01:18:11'),
(91, 9, 'Changed message status', 'Message #6 marked read', '2025-10-25 01:18:12'),
(92, 9, 'Changed message status', 'Message #6 marked unread', '2025-10-25 01:19:30'),
(93, 9, 'Cleared all messages', 'All messages removed', '2025-10-25 01:19:38'),
(94, 9, 'Cleared all messages', 'All messages removed', '2025-10-25 01:19:45'),
(95, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 20:55:59'),
(96, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:00:23'),
(97, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:00:35'),
(98, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:06:06'),
(99, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:30:42'),
(100, 9, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:39:49'),
(101, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:42:02'),
(102, 9, 'Appointment status updated', 'Appointment #1 -> Confirmed', '2025-10-25 21:46:16'),
(103, 8, 'User logged in', 'IP: 127.0.0.1', '2025-10-25 21:55:48'),
(104, 9, 'Updated appointment status', 'Appointment #1 → Pending', '2025-10-25 22:00:08'),
(105, 9, 'Rejected reschedule request', 'Appointment #1', '2025-10-25 22:25:03'),
(106, 9, 'Updated appointment status', 'Appointment #1 → Confirmed', '2025-10-25 22:25:53'),
(107, 9, 'User logged in', 'IP: 127.0.0.1', '2025-11-04 09:38:07'),
(108, 9, 'User status changed', 'User ID: 8 set to inactive, IP: 127.0.0.1', '2025-11-04 09:38:28'),
(109, 9, 'User status changed', 'User ID: 8 set to active, IP: 127.0.0.1', '2025-11-04 09:38:29'),
(110, 9, 'User status changed', 'User ID: 2 set to inactive, IP: 127.0.0.1', '2025-11-04 09:38:35'),
(111, 9, 'User status changed', 'User ID: 2 set to active, IP: 127.0.0.1', '2025-11-04 09:38:37'),
(112, 9, 'Changed message status', 'Message #112 marked read', '2025-11-04 09:39:43'),
(113, 9, 'Changed message status', 'Message #112 marked unread', '2025-11-04 09:39:44'),
(114, 9, 'User logged in', 'IP: 127.0.0.1', '2025-11-04 09:40:26'),
(115, 8, 'User logged in', 'IP: 127.0.0.1', '2025-11-04 09:40:35'),
(116, 1, 'User logged in', 'IP: 127.0.0.1', '2025-12-10 19:56:19'),
(117, 8, 'User logged in', 'IP: 127.0.0.1', '2025-12-10 19:57:31'),
(118, 1, 'User logged in', 'IP: 127.0.0.1', '2025-12-10 19:57:46'),
(119, 1, 'User status changed', 'User ID: 8 set to inactive, IP: 127.0.0.1', '2025-12-10 19:57:51'),
(120, 1, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:31:40'),
(121, 1, 'User status changed', 'User ID: 8 set to active, IP: 127.0.0.1', '2026-01-01 19:31:45'),
(122, 8, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:32:08'),
(123, 9, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:34:21'),
(124, 10, 'New user registered', 'User created via registration form', '2026-01-01 19:35:00'),
(125, 10, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:35:55'),
(126, 10, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:36:40'),
(127, 9, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 19:36:56'),
(128, 9, 'Updated appointment status', 'Appointment #3 → Confirmed', '2026-01-01 19:37:04'),
(129, 9, 'Updated appointment status', 'Appointment #2 → Confirmed', '2026-01-01 19:37:06'),
(130, 1, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 20:00:56'),
(131, 9, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 20:01:41'),
(132, 10, 'User logged in', 'IP: 127.0.0.1', '2026-01-01 20:01:49'),
(133, 10, 'User logged in', 'IP: 127.0.0.1', '2026-01-13 19:26:55'),
(134, 9, 'User logged in', 'IP: 127.0.0.1', '2026-01-13 19:27:22'),
(135, 9, 'User status changed', 'User ID: 10 set to inactive, IP: 127.0.0.1', '2026-01-13 19:28:08'),
(136, 1, 'User logged in', 'IP: 127.0.0.1', '2026-01-13 19:28:24'),
(137, 1, 'Changed message status', 'Message #112 marked read', '2026-01-13 19:28:45'),
(138, 1, 'Changed message status', 'Message #112 marked unread', '2026-01-13 19:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `pet_type` varchar(50) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `requested_date` date DEFAULT NULL,
  `requested_time` time DEFAULT NULL,
  `request_status` enum('None','Pending','Approved','Rejected') DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `vet_id`, `pet_name`, `pet_type`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`, `updated_at`, `requested_date`, `requested_time`, `request_status`) VALUES
(2, 8, 2, 'Tomas', 'Cat', '2026-01-05 00:00:00', '18:00:00', 'Confirmed', '', '2026-01-01 19:33:00', '2026-01-01 20:05:29', NULL, NULL, 'None'),
(3, 10, 3, 'Tyson', 'Dog', '2026-01-06 00:00:00', '11:35:00', 'Confirmed', '', '2026-01-01 19:35:48', '2026-01-01 19:37:04', NULL, NULL, 'None'),
(4, 10, 2, 'Tom', 'Cat', '2026-01-07 00:00:00', '12:30:00', 'Pending', '', '2026-01-01 20:04:24', '2026-01-01 20:04:24', NULL, NULL, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `description`, `created_at`) VALUES
(1, 'Dogs', 'assets/images/Dog_Shop_by_category.png', 'Everything your dog needs — toys, food, and care essentials.', '2025-10-25 20:55:43'),
(2, 'Cats', 'assets/images/Cat_Shop_by_category.png', 'Premium products for cats: from cozy beds to tasty treats.', '2025-10-25 20:55:43'),
(3, 'Birds', 'assets/images/Bird_Shop_by_category.png', 'Cages, food, and supplies for your feathered friends.', '2025-10-25 20:55:43'),
(4, 'Fish', 'assets/images/Fish_Shop_by_category.png', 'Aquarium essentials and nutrition for your aquatic pets.', '2025-10-25 20:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender_email` varchar(191) DEFAULT NULL,
  `sender_name` varchar(191) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `sender_email`, `sender_name`, `subject`, `message`, `is_read`, `status`, `created_at`) VALUES
(112, NULL, 'fahim1996.df@gmail.com', 'MD. Fahim Munshi', 'Demo Mail', 'This is a test mail', 0, 'unread', '2025-11-04 09:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 8, 'Your appointment (ID #1) status changed to Confirmed.', 0, '2025-10-25 21:46:16'),
(2, 8, 'Your appointment #1 status updated to Pending.', 0, '2025-10-25 22:00:08'),
(3, 8, 'Your reschedule request for appointment #1 has been rejected.', 0, '2025-10-25 22:25:03'),
(4, 8, 'Your appointment #1 status updated to Confirmed.', 0, '2025-10-25 22:25:53'),
(5, 10, 'Your appointment #3 status updated to Confirmed.', 0, '2026-01-01 19:37:04'),
(6, 8, 'Your appointment #2 status updated to Confirmed.', 0, '2026-01-01 19:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 59.99, '', '2025-10-23 04:15:00'),
(2, 2, 120.50, '', '2025-10-22 08:30:00'),
(3, 3, 34.75, '', '2025-10-21 12:10:00'),
(4, 4, 78.25, 'cancelled', '2025-10-20 03:45:00'),
(5, 1, 199.99, '', '2025-10-19 14:05:00'),
(6, 2, 89.00, '', '2025-10-24 05:00:00'),
(7, 3, 15.99, '', '2025-10-25 03:30:00'),
(8, 4, 299.49, 'pending', '2025-10-25 07:15:00'),
(9, 1, 65.75, 'processing', '2025-10-25 10:45:00'),
(10, 2, 45.99, '', '2025-10-24 11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` enum('dog','cat','bird','fish') NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(1, 'Premium Dog Food', 'dog', 'Nutritious meal for dogs with essential vitamins.', 24.99, 100, 'assets/images/dogfood.png', '2025-10-23 21:35:33'),
(2, 'Cat Scratching Post', 'cat', 'Durable scratching post to keep cats entertained.', 18.50, 80, 'assets/images/catpost.png', '2025-10-23 21:35:33'),
(3, 'Bird Feeder Deluxe', 'bird', 'Hanging feeder ideal for garden birds.', 15.00, 60, 'assets/images/birdfeeder.png', '2025-10-23 21:35:33'),
(4, 'Aquarium Filter Pro', 'fish', 'High-efficiency water filter for aquariums.', 29.90, 50, 'assets/images/fishfilter.png', '2025-10-23 21:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','vet','admin') DEFAULT 'customer',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Admin', 'admin@pawverse.com', '$2y$10$5zng8gYc/hsKIKbJpkKaC.cF4b0CPQ/wx44g/Dqxjb7d0A7SgbPxS', 'admin', 'active', '2025-10-23 21:35:33'),
(2, 'Dr. Lina Chowdhury', 'lina@pawverse.com', '$2y$10$abcdabcdabcdabcdabcdabCdABCDABCDABCDABCDabcdabcdab', 'vet', 'active', '2025-10-23 21:35:33'),
(3, 'Dr. Rahim Ahmed', 'rahim@pawverse.com', '$2y$10$abcdabcdabcdabcdabcdabCdABCDABCDABCDABCDabcdabcdab', 'vet', 'active', '2025-10-23 21:35:33'),
(4, 'Dr. Sara Khan', 'sara@pawverse.com', '$2y$10$abcdabcdabcdabcdabcdabCdABCDABCDABCDABCDabcdabcdab', 'vet', 'active', '2025-10-23 21:35:33'),
(7, 'MD. Fahim Munshi', 'fahim1996.df@gmail.com', '$2y$10$tJUwRtRFFQGmAG2tMq6tAu1kTJhkz4/4454O6e7ADbm2hHRQs3/Ha', 'customer', 'active', '2025-10-23 22:27:19'),
(8, 'Siam Wahid', 'siam@gmail.com', '$2y$10$UyO4VDqjPkNljc/dCpQuNeEX1CdLZ.clB1kWbvPunnJH742r7Dqdy', 'customer', 'active', '2025-10-23 22:37:13'),
(9, 'fahim', 'fahim@gmail.com', '$2y$10$q8113aKCKV0L0Njh0iIMquhVdp7TcfCIA.NW.09q5UBw/b/AunsIO', 'admin', 'active', '2025-10-24 23:17:15'),
(10, 'Fahim', 'fahim@yahoo.com', '$2y$10$klviqGRL5sOMl22dfenAE.0fviGfQgpXsfd/oF6FymSStJeuGQj4m', 'customer', 'inactive', '2026-01-01 19:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `veterinarians`
--

CREATE TABLE `veterinarians` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'vet1.png',
  `fee` decimal(6,2) DEFAULT 0.00,
  `rating` varchar(10) DEFAULT '5.0★',
  `experience_years` int(11) DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `veterinarians`
--

INSERT INTO `veterinarians` (`id`, `name`, `user_id`, `specialization`, `description`, `image`, `fee`, `rating`, `experience_years`, `phone`) VALUES
(1, 'Dr. Lina Chowdhury', 2, 'Small Animals', 'Experienced in surgery & internal medicine.', 'vet1.png', 18.00, '4.9★', 5, '+8801777000001'),
(2, 'Dr. Rahim Ahmed', 3, 'Dermatology', 'Specialized in skin & allergy care.', 'vet2.png', 20.00, '4.8★', 8, '+8801777000002'),
(3, 'Dr. Sara Khan', 4, 'Avian Specialist', 'Expert in avian care and nutrition.', 'vet3.png', 22.00, '4.7★', 4, '+8801777000003');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `veterinarians`
--
ALTER TABLE `veterinarians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `veterinarians`
--
ALTER TABLE `veterinarians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`vet_id`) REFERENCES `veterinarians` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `veterinarians`
--
ALTER TABLE `veterinarians`
  ADD CONSTRAINT `veterinarians_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
